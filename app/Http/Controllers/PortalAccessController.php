<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberCredential;
use App\Models\Trainer;
use App\Models\TrainerCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Lets admins grant / revoke portal access for members and trainers.
 * Route prefix: /admin/portal-access
 */
class PortalAccessController extends Controller
{
    // ── Member portal access ──────────────────────────────────

    public function memberIndex()
    {
        $members = Member::with('credentials')
            ->orderBy('name')
            ->paginate(30);
        return view('admin.portal.members', compact('members'));
    }

    /** Grant portal access to a member (creates credentials with temp password). */
    public function memberGrant(Request $request, Member $member)
    {
        if ($member->credentials) {
            return back()->withErrors(['msg' => 'Member already has portal access.']);
        }

        $tempPassword = 'fittrack' . rand(1000, 9999);

        MemberCredential::create([
            'member_id'     => $member->id,
            'password'      => Hash::make($tempPassword),
            'must_change_pw'=> true,
        ]);

        return back()->with('success',
            "Portal access granted to {$member->name}. Temp password: <strong>{$tempPassword}</strong>. " .
            "They will be prompted to change it on first login."
        );
    }

    /** Reset a member's portal password. */
    public function memberReset(Member $member)
    {
        abort_unless($member->credentials, 404);

        $tempPassword = 'fittrack' . rand(1000, 9999);
        $member->credentials->update([
            'password'       => Hash::make($tempPassword),
            'must_change_pw' => true,
        ]);

        return back()->with('success',
            "Password reset for {$member->name}. New temp password: <strong>{$tempPassword}</strong>"
        );
    }

    /** Revoke portal access entirely. */
    public function memberRevoke(Member $member)
    {
        $member->credentials?->delete();
        return back()->with('success', "Portal access revoked for {$member->name}.");
    }

    // ── Trainer portal access ─────────────────────────────────

    public function trainerIndex()
    {
        $trainers = Trainer::with('credentials')
            ->orderBy('name')
            ->paginate(30);
        return view('admin.portal.trainers', compact('trainers'));
    }

    public function trainerGrant(Request $request, Trainer $trainer)
    {
        if ($trainer->credentials) {
            return back()->withErrors(['msg' => 'Trainer already has portal access.']);
        }

        if (! $trainer->email) {
            return back()->withErrors(['msg' => 'Trainer needs an email address before granting portal access.']);
        }

        $tempPassword = 'trainer' . rand(1000, 9999);

        TrainerCredential::create([
            'trainer_id'    => $trainer->id,
            'password'      => Hash::make($tempPassword),
            'must_change_pw'=> true,
        ]);

        return back()->with('success',
            "Portal access granted to {$trainer->name}. Temp password: <strong>{$tempPassword}</strong>"
        );
    }

    public function trainerReset(Trainer $trainer)
    {
        abort_unless($trainer->credentials, 404);
        $tempPassword = 'trainer' . rand(1000, 9999);
        $trainer->credentials->update([
            'password'       => Hash::make($tempPassword),
            'must_change_pw' => true,
        ]);
        return back()->with('success',
            "Password reset for {$trainer->name}. New temp password: <strong>{$tempPassword}</strong>"
        );
    }

    public function trainerRevoke(Trainer $trainer)
    {
        $trainer->credentials?->delete();
        return back()->with('success', "Portal access revoked for {$trainer->name}.");
    }
}
