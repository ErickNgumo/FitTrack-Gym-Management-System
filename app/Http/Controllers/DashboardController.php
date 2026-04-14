<?php

namespace App\Http\Controllers;

use App\Services\ReportService;

class DashboardController extends Controller
{
    public function __construct(private ReportService $reports) {}

    public function index()
    {
        $kpis     = $this->reports->dashboardKpis();
        $revenue  = $this->reports->revenueByMonth(6);
        $members  = $this->reports->memberStatusSummary();
        $attendance = $this->reports->dailyAttendance(14);

        return view('dashboard.index', compact('kpis', 'revenue', 'members', 'attendance'));
    }
}
