@extends('layouts.app')
@section('title', 'Portal Access – Trainers')
@section('page-title', 'Portal Access Management')

@section('content')

{{-- Tab nav --}}
<div style="display:flex;gap:8px;margin-bottom:22px;border-bottom:2px solid var(--ft-border);padding-bottom:0;">
    <a href="{{ route('portal.members') }}"
       style="padding:10px 18px;font-size:.875rem;font-weight:500;text-decoration:none;color:var(--ft-muted);border-bottom:2px solid transparent;margin-bottom:-2px;">
        Members
    </a>
    <a href="{{ route('portal.trainers') }}"
       style="padding:10px 18px;font-size:.875rem;font-weight:600;text-decoration:none;border-bottom:2px solid var(--ft-accent);color:var(--ft-accent);margin-bottom:-2px;">
        Trainers
    </a>
</div>

<div style="margin-bottom:18px;">
    <h4 style="margin:0;font-size:1.1rem;">Trainer Portal Access</h4>
    <p style="margin:4px 0 0;font-size:.82rem;color:var(--ft-muted);">
        Grant trainers login access at
        <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:.78rem;">/trainer/login</code>.
        Trainers log in with their <strong>email address</strong>.
    </p>
</div>

@if(session('success'))
    <div class="ft-alert ft-alert-success"><i class="bi bi-check-circle-fill"></i> {!! session('success') !!}</div>
@endif

<div class="ft-card" style="padding:0;overflow:hidden;">
    <table class="ft-table">
        <thead>
            <tr>
                <th>Trainer</th>
                <th>Email (login ID)</th>
                <th>Speciality</th>
                <th>Portal Access</th>
                <th>Last Login</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainers as $trainer)
            <tr>
                <td>
                    <strong style="font-size:.9rem;">{{ $trainer->name }}</strong>
                    <div style="font-size:.72rem;color:var(--ft-muted);">{{ ucfirst($trainer->status) }}</div>
                </td>
                <td style="font-size:.875rem;">
                    @if($trainer->email)
                        {{ $trainer->email }}
                    @else
                        <span style="color:#dc2626;font-size:.78rem;"><i class="bi bi-exclamation-triangle"></i> No email set</span>
                    @endif
                </td>
                <td style="font-size:.875rem;color:var(--ft-muted);">{{ $trainer->speciality ?? '—' }}</td>
                <td>
                    @if($trainer->credentials)
                        <span class="ft-badge badge-active"><i class="bi bi-shield-check"></i> Enabled</span>
                        @if($trainer->credentials->must_change_pw)
                            <span class="ft-badge" style="background:#fef3c7;color:#92400e;margin-left:4px;">Temp PW</span>
                        @endif
                    @else
                        <span class="ft-badge badge-inactive">No Access</span>
                    @endif
                </td>
                <td style="font-size:.8rem;color:var(--ft-muted);">
                    {{ $trainer->credentials?->last_login?->format('d M Y, g:i A') ?? '—' }}
                </td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                        @if(!$trainer->credentials)
                            <form method="POST" action="{{ route('portal.trainers.grant', $trainer) }}">
                                @csrf
                                <button type="submit" class="btn-ft-primary" style="padding:5px 12px;font-size:.78rem;"
                                        {{ !$trainer->email ? 'disabled title=No email address set' : '' }}>
                                    <i class="bi bi-unlock"></i> Grant Access
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('portal.trainers.reset', $trainer) }}" onsubmit="return confirm('Reset password for {{ $trainer->name }}?')">
                                @csrf
                                <button type="submit" class="btn-ft-secondary" style="padding:5px 12px;font-size:.78rem;">
                                    <i class="bi bi-key"></i> Reset PW
                                </button>
                            </form>
                            <form method="POST" action="{{ route('portal.trainers.revoke', $trainer) }}" onsubmit="return confirm('Revoke portal access for {{ $trainer->name }}?')">
                                @csrf
                                <button type="submit" class="btn-ft-secondary" style="padding:5px 12px;font-size:.78rem;color:#dc2626;border-color:#fecaca;">
                                    <i class="bi bi-slash-circle"></i> Revoke
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:var(--ft-muted);">No trainers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($trainers->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--ft-border);">{{ $trainers->links() }}</div>
    @endif
</div>

<div style="margin-top:18px;background:#f0fff4;border:1px solid #bbf7d0;border-radius:12px;padding:16px 20px;font-size:.82rem;color:#374151;">
    <strong>Trainer login requirements:</strong>
    <ul style="margin:8px 0 0;padding-left:18px;line-height:1.8;">
        <li>Trainer must have an <strong>email address</strong> on their profile (used as login ID).</li>
        <li>Trainer portal shows <strong>only their assigned members</strong> — they cannot see other trainers' clients.</li>
        <li>Trainers can log workout sessions, add notes, and view member attendance.</li>
        <li>Trainer notes marked <strong>Private</strong> are hidden from the member portal.</li>
    </ul>
</div>

@endsection
