<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Dashboard KPI summary.
     */
    public function dashboardKpis(): array
    {
        return [
            'active_members'       => Member::where('status', 'active')->count(),
            'today_checkins'       => Attendance::whereDate('check_in_time', today())->count(),
            'monthly_revenue'      => (float) Payment::whereMonth('payment_date', now()->month)
                                        ->whereYear('payment_date', now()->year)->sum('amount'),
            'expiring_soon'        => Subscription::where('status', 'active')
                                        ->whereBetween('end_date', [today()->toDateString(), today()->addDays(7)->toDateString()])
                                        ->count(),
            'inactive_members'     => Member::where('status', 'inactive')->count(),
            'total_members'        => Member::count(),
        ];
    }

    /**
     * Revenue by month for the last N months.
     */
    public function revenueByMonth(int $months = 6): array
    {
        $rows = Payment::selectRaw("DATE_FORMAT(payment_date,'%b %Y') AS label, SUM(amount) AS total")
            ->where('payment_date', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->groupBy('label', DB::raw("DATE_FORMAT(payment_date,'%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(payment_date,'%Y-%m')"))
            ->get();

        return [
            'labels' => $rows->pluck('label')->toArray(),
            'data'   => $rows->pluck('total')->map(fn($v) => (float)$v)->toArray(),
        ];
    }

    /**
     * Active vs inactive member counts for doughnut chart.
     */
    public function memberStatusSummary(): array
    {
        return Member::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Attendance per day for the last N days.
     */
    public function dailyAttendance(int $days = 30): array
    {
        $rows = Attendance::selectRaw("DATE(check_in_time) AS day, COUNT(*) AS visits")
            ->where('check_in_time', '>=', now()->subDays($days))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return [
            'labels' => $rows->pluck('day')->toArray(),
            'data'   => $rows->pluck('visits')->toArray(),
        ];
    }

    /**
     * Expired memberships list.
     */
    public function expiredMemberships()
    {
        return Subscription::with(['member', 'plan'])
            ->where('status', 'expired')
            ->orderByDesc('end_date')
            ->paginate(20);
    }
}
