@extends('layouts.app')
@section('title', 'Portal Access – Members')
@section('page-title', 'Portal Access Management')

@section('content')

{{-- Tab nav --}}
<div style="display:flex;gap:8px;margin-bottom:22px;border-bottom:2px solid var(--ft-border);padding-bottom:0;">
    <a href="{{ route('portal.members') }}"
       style="padding:10px 18px;font-size:.875rem;font-weight:600;text-decoration:none;border-bottom:2px solid var(--ft-accent);color:var(--ft-accent);margin-bottom:-2px;">
        Members
    </a>
    <a href="{{ route('portal.trainers') }}"
       style="padding:10px 18px;font-size:.875rem;font-weight:500;text-decoration:none;color:var(--ft-muted);border-bottom:2px solid transparent;margin-bottom:-2px;">
        Trainers
    </a>
</div>

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:18px;flex-wrap:wrap;gap:12px;">
    <div>
        <h4 style="margin:0;font-size:1.1rem;">Member Portal Access</h4>
        <p style="margin:4px 0 0;font-size:.82rem;color:var(--ft-muted);">
            Grant members login access to the self-service portal at
            <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:.78rem;">/member/login</code>
        </p>
    </div>
</div>

@if(session('success'))
    <div class="ft-alert ft-alert-success"><i class="bi bi-check-circle-fill"></i> {!! session('success') !!}</div>
@endif

<div class="ft-card" style="padding:0;overflow:hidden;">
    <table class="ft-table">
        <thead>
            <tr>
                <th>Member</th>
                <th>Phone (login ID)</th>
                <th>Status</th>
                <th>Portal Access</th>
                <th>Last Login</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
            <tr>
                <td>
                    <a href="{{ route('members.show', $member) }}" style="font-weight:500;text-decoration:none;color:var(--ft-text);">{{ $member->name }}</a>
                    <div style="font-size:.72rem;color:var(--ft-muted);font-family:monospace;">{{ $member->member_number }}</div>
                </td>
                <td style="font-size:.875rem;font-family:monospace;">{{ $member->phone }}</td>
                <td>
                    <span class="ft-badge badge-{{ $member->status }}">{{ ucfirst($member->status) }}</span>
                </td>
                <td>
                    @if($member->credentials)
                        <span class="ft-badge badge-active">
                            <i class="bi bi-shield-check"></i> Enabled
                        </span>
                        @if($member->credentials->must_change_pw)
                            <span class="ft-badge" style="background:#fef3c7;color:#92400e;margin-left:4px;">Temp PW</span>
                        @endif
                    @else
                        <span class="ft-badge badge-inactive">No Access</span>
                    @endif
                </td>
                <td style="font-size:.8rem;color:var(--ft-muted);">
                    {{ $member->credentials?->last_login?->format('d M Y, g:i A') ?? '—' }}
                </td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                        @if(!$member->credentials)
                            <form method="POST" action="{{ route('portal.members.grant', $member) }}">
                                @csrf
                                <button type="submit" class="btn-ft-primary" style="padding:5px 12px;font-size:.78rem;">
                                    <i class="bi bi-unlock"></i> Grant Access
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('portal.members.reset', $member) }}" onsubmit="return confirm('Reset password for {{ $member->name }}?')">
                                @csrf
                                <button type="submit" class="btn-ft-secondary" style="padding:5px 12px;font-size:.78rem;">
                                    <i class="bi bi-key"></i> Reset PW
                                </button>
                            </form>
                            <form method="POST" action="{{ route('portal.members.revoke', $member) }}" onsubmit="return confirm('Revoke portal access for {{ $member->name }}?')">
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
                <td colspan="6" style="text-align:center;padding:40px;color:var(--ft-muted);">No members found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($members->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--ft-border);">{{ $members->links() }}</div>
    @endif
</div>

{{-- Info box --}}
<div style="margin-top:18px;background:#f0f4ff;border:1px solid #c7d7ff;border-radius:12px;padding:16px 20px;font-size:.82rem;color:#374151;">
    <strong>How it works:</strong>
    <ol style="margin:8px 0 0;padding-left:18px;line-height:1.8;">
        <li>Click <strong>Grant Access</strong> — a temporary password is generated and shown here.</li>
        <li>Share the temp password with the member verbally or via SMS.</li>
        <li>Member logs in at <code>/member/login</code> using their <strong>phone number</strong> + temp password.</li>
        <li>They're prompted to set a personal password on first login.</li>
    </ol>
</div>

@endsection
