<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $service) {}

    public function index(Request $request)
    {
        $query = Attendance::with('member')->orderByDesc('check_in_time');

        if ($search = $request->get('search')) {
            $query->whereHas('member', fn($q) => $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%"));
        }

        if ($date = $request->get('date')) {
            $query->whereDate('check_in_time', $date);
        }

        $records = $query->paginate(30)->withQueryString();
        $todayCount = $this->service->todayCount();

        return view('attendance.index', compact('records', 'todayCount'));
    }

    public function checkin()
    {
        return view('attendance.checkin');
    }

    public function store(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $member = Member::where('member_number', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (! $member) {
            return back()->withErrors(['identifier' => 'Member not found.']);
        }

        try {
            $this->service->checkIn($member);
            return back()->with('success', "✓ {$member->name} checked in successfully.");
        } catch (\RuntimeException $e) {
            return back()->withErrors(['identifier' => $e->getMessage()]);
        }
    }
}
