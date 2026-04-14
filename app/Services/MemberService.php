<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Str;

class MemberService
{
    /**
     * Create a new member and generate their member number.
     */
    public function create(array $data): Member
    {
        $data['member_number'] = $this->generateMemberNumber();
        $data['created_by']    = auth()->id();

        return Member::create($data);
    }

    /**
     * Update member details.
     */
    public function update(Member $member, array $data): Member
    {
        $member->update($data);
        return $member->fresh();
    }

    /**
     * Soft-deactivate a member (does not delete).
     */
    public function deactivate(Member $member): void
    {
        $member->update(['status' => 'inactive']);
    }

    /**
     * Re-activate a previously inactive member.
     */
    public function activate(Member $member): void
    {
        $member->update(['status' => 'active']);
    }

    /**
     * Generate sequential member number: FT-000001.
     */
    private function generateMemberNumber(): string
    {
        $last = Member::orderByDesc('id')->first();
        $next = $last ? ((int) ltrim(substr($last->member_number, 3), '0')) + 1 : 1;
        return 'FT-' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Members whose subscription expires within $days days.
     */
    public function expiringWithinDays(int $days = 7): \Illuminate\Database\Eloquent\Collection
    {
        return Member::whereHas('subscriptions', function ($q) use ($days) {
            $q->where('status', 'active')
              ->whereBetween('end_date', [now()->toDateString(), now()->addDays($days)->toDateString()]);
        })->with(['subscriptions' => function ($q) {
            $q->where('status', 'active')->with('plan');
        }])->get();
    }
}
