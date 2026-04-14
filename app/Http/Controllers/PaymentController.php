<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService      $paymentService,
        private SubscriptionService $subscriptionService,
    ) {}

    public function index(Request $request)
    {
        $query = Payment::with(['member', 'subscription.plan', 'recordedBy'])->latest('payment_date');

        if ($search = $request->get('search')) {
            $query->whereHas('member', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('receipt_number', 'like', "%{$search}%");
        }

        if ($method = $request->get('method')) {
            $query->where('payment_method', $method);
        }

        $payments = $query->paginate(20)->withQueryString();
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $member = $request->member_id ? Member::findOrFail($request->member_id) : null;
        $members = Member::where('status', 'active')->orderBy('name')->get(['id', 'name', 'member_number']);
        return view('payments.create', compact('member', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'      => 'required|exists:members,id',
            'subscription_id'=> 'nullable|exists:subscriptions,id',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mpesa,bank_transfer,card,other',
            'mpesa_ref'      => 'nullable|string|max:30',
            'payment_date'   => 'required|date',
            'coverage_start' => 'nullable|date',
            'coverage_end'   => 'nullable|date|after_or_equal:coverage_start',
            'notes'          => 'nullable|string',
        ]);

        $payment = $this->paymentService->record($validated);

        return redirect()
            ->route('payments.receipt', $payment)
            ->with('success', 'Payment recorded.');
    }

    /** HTML receipt – printable */
    public function receipt(Payment $payment)
    {
        $payment->load(['member', 'subscription.plan', 'recordedBy']);
        return view('payments.receipt', compact('payment'));
    }
}
