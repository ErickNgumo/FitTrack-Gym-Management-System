<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TrainerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('trainer')->check()) {
            return redirect()->route('trainer.dashboard');
        }
        return view('trainer-portal.auth.login');
    }

    /**
     * Trainers log in with their email + password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $trainer = \App\Models\Trainer::where('email', $request->email)
            ->where('status', 'active')
            ->first();

        if (! $trainer || ! $trainer->credentials) {
            return back()->withInput()->withErrors([
                'email' => 'No active trainer account found for that email.',
            ]);
        }

        $credential = $trainer->credentials;

        if (! Hash::check($request->password, $credential->password)) {
            return back()->withInput()->withErrors(['email' => 'Incorrect password.']);
        }

        Auth::guard('trainer')->login($credential, $request->boolean('remember'));
        $credential->update(['last_login' => now()]);
        $request->session()->regenerate();

        if ($credential->must_change_pw) {
            return redirect()->route('trainer.change-password');
        }

        return redirect()->route('trainer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('trainer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('trainer.login');
    }

    public function showChangePassword()
    {
        return view('trainer-portal.auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);

        /** @var TrainerCredential $credential */
        $credential = Auth::guard('trainer')->user();
        $credential->update([
            'password'       => Hash::make($request->password),
            'must_change_pw' => false,
        ]);

        return redirect()->route('trainer.dashboard')->with('success', 'Password updated.');
    }
}
