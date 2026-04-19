<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('member.dashboard');
        }
        return view('member.auth.login');
    }

    /**
     * Members log in with their phone number + password.
     * Phone is on the `members` table; we join via member_credentials.
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Find the member by phone
        $member = \App\Models\Member::where('phone', $request->phone)
            ->where('status', 'active')
            ->first();

        if (! $member || ! $member->credentials) {
            return back()->withInput()->withErrors([
                'phone' => 'No active account found for that phone number.',
            ]);
        }

        $credential = $member->credentials;

        if (! Hash::check($request->password, $credential->password)) {
            return back()->withInput()->withErrors([
                'phone' => 'Incorrect password.',
            ]);
        }

        Auth::guard('member')->login($credential, $request->boolean('remember'));
        $credential->update(['last_login' => now()]);
        $request->session()->regenerate();

        // Force password change on first login
        if ($credential->must_change_pw) {
            return redirect()->route('member.change-password');
        }

        return redirect()->route('member.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('member.login');
    }

    public function showChangePassword()
    {
        return view('member.auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var MemberCredential $credential */
        $credential = Auth::guard('member')->user();
        $credential->update([
            'password'      => Hash::make($request->password),
            'must_change_pw'=> false,
        ]);

        return redirect()->route('member.dashboard')->with('success', 'Password updated successfully.');
    }
}
