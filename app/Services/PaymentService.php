<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    /**
     * Record a payment, optionally linked to a subscription.
     */
    public function record(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $data['receipt_number'] = Payment::generateReceiptNumber();
            $data['recorded_by']    = auth()->id();

            return Payment::create($data);
        });
    }

    /**
     * Monthly revenue totals.
     *
     * @return \Illuminate\Support\Collection  [year_month => total]
     */
    public function monthlyRevenue(int $months = 12): \Illuminate\Support\Collection
    {
        return Payment::selectRaw("DATE_FORMAT(payment_date,'%Y-%m') AS month, SUM(amount) AS total")
            ->where('payment_date', '>=', now()->subMonths($months))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
    }

    /**
     * Revenue for the current calendar month.
     */
    public function currentMonthRevenue(): float
    {
        return (float) Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
    }
}
