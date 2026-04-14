<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Trainer;
use App\Services\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(private MemberService $service) {}

    public function index(Request $request)
    {
        $query = Member::with('trainer')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $members  = $query->paginate(20)->withQueryString();
        $trainers = Trainer::where('status', 'active')->orderBy('name')->get();

        return view('members.index', compact('members', 'trainers'));
    }

    public function show(Member $member)
    {
        $member->load(['subscriptions.plan', 'payments', 'attendance', 'trainer']);
        return view('members.show', compact('member'));
    }

    public function create()
    {
        $trainers = Trainer::where('status', 'active')->orderBy('name')->get();
        return view('members.create', compact('trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'phone'     => 'required|string|max:20',
            'email'     => 'nullable|email|max:150|unique:members',
            'gender'    => 'nullable|in:male,female,other',
            'join_date' => 'required|date',
            'trainer_id'=> 'nullable|exists:trainers,id',
            'status'    => 'required|in:active,inactive,suspended',
        ]);

        $member = $this->service->create($validated);

        return redirect()
            ->route('members.show', $member)
            ->with('success', "Member {$member->name} created successfully.");
    }

    public function edit(Member $member)
    {
        $trainers = Trainer::where('status', 'active')->orderBy('name')->get();
        return view('members.edit', compact('member', 'trainers'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'phone'     => 'required|string|max:20',
            'email'     => "nullable|email|max:150|unique:members,email,{$member->id}",
            'gender'    => 'nullable|in:male,female,other',
            'join_date' => 'required|date',
            'trainer_id'=> 'nullable|exists:trainers,id',
            'status'    => 'required|in:active,inactive,suspended',
            'notes'     => 'nullable|string',
        ]);

        $this->service->update($member, $validated);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $this->service->deactivate($member);
        return redirect()->route('members.index')->with('success', 'Member deactivated.');
    }
}
