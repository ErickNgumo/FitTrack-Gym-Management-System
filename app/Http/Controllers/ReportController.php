<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reports) {}

    public function index()
    {
        return view('reports.index');
    }

    public function revenue(Request $request)
    {
        $months  = (int) $request->get('months', 6);
        $revenue = $this->reports->revenueByMonth($months);
        return view('reports.revenue', compact('revenue', 'months'));
    }

    public function members()
    {
        $summary    = $this->reports->memberStatusSummary();
        $expiring   = $this->reports->expiredMemberships();
        return view('reports.members', compact('summary', 'expiring'));
    }

    public function attendance(Request $request)
    {
        $days       = (int) $request->get('days', 30);
        $attendance = $this->reports->dailyAttendance($days);
        return view('reports.attendance', compact('attendance', 'days'));
    }
}
