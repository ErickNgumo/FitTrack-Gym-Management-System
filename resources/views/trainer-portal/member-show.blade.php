@extends('trainer-portal.layouts.app')
@section('title', $member->name)
@section('page-title', $member->name)

@section('content')

<div style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
    <a href="{{ route('trainer.members') }}" style="font-size:.85rem;color:var(--t-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to Members
    </a>
    <a href="{{ route('trainer.session.create', $member) }}" class="t-btn-primary">
        <i class="bi bi-plus-lg"></i> Log Workout Session
    </a>
</div>

<div class="row g-3">

    {{-- Left: profile card --}}
    <div class="col-12 col-xl-4">
        <div class="t-card" style="text-align:center;padding:28px;">
            <div style="width:68px;height:68px;border-radius:50%;background:var(--t-accent);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.5rem;color:#fff;font-weight:700;margin:0 auto 14px;">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <h5 style="margin-bottom:3px;font-size:1.05rem;">{{ $member->name }}</h5>
            <div style="font-size:.78rem;font-family:monospace;color:var(--t-muted);margin-bottom:10px;">{{ $member->member_number }}</div>

            @if($activeSub)
                <div style="background:#dcfce7;color:#15803d;border-radius:10px;padding:10px 14px;font-size:.82rem;margin-bottom:16px;text-align:left;">
                    <strong>✓ Active Membership</strong><br>
                    {{ $activeSub->plan->name }}<br>
                    Expires {{ $activeSub->end_date->format('d M Y') }} ({{ $activeSub->end_date->diffInDays(now()) }} days)
                </div>
            @else
                <div style="background:#fee2e2;color:#dc2626;border-radius:10px;padding:10px 14px;font-size:.82rem;margin-bottom:16px;text-align:left;">
                    ⚠ No active membership
                </div>
            @endif

            <div style="font-size:.85rem;text-align:left;display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Phone</span><strong>{{ $member->phone }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Joined</span><strong>{{ $member->join_date->format('d M Y') }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Sessions</span><strong>{{ $sessions->total() }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">This month</span>
                    <strong>{{ $member->attendance()->whereMonth('check_in_time', now()->month)->count() }} visits</strong>
                </div>
            </div>
        </div>

        {{-- Recent attendance --}}
        <div class="t-card mt-3">
            <h5 style="font-size:.9rem;margin-bottom:12px;">Recent Check-Ins</h5>
            @forelse($attendance as $att)
            <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:7px 0;border-bottom:1px solid var(--t-border);">
                <span>{{ \Carbon\Carbon::parse($att->check_in_time)->format('D, d M') }}</span>
                <span style="color:var(--t-muted);">{{ \Carbon\Carbon::parse($att->check_in_time)->format('g:i A') }}</span>
            </div>
            @empty
            <p style="color:var(--t-muted);font-size:.82rem;text-align:center;padding:12px 0;margin:0;">No check-ins yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Right: sessions + notes --}}
    <div class="col-12 col-xl-8">

        {{-- Workout sessions --}}
        <div class="t-card mb-3">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h5 style="font-size:1rem;margin:0;">Workout Sessions</h5>
                <a href="{{ route('trainer.session.create', $member) }}" class="t-btn-outline" style="font-size:.78rem;padding:5px 12px;">
                    <i class="bi bi-plus-lg"></i> New Session
                </a>
            </div>
            @forelse($sessions as $s)
            <a href="{{ route('trainer.session.show', $s) }}" style="text-decoration:none;color:inherit;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--t-border);" onmouseover="this.style.background='#f0faf5'" onmouseout="this.style.background=''">
                    <div>
                        <div style="font-weight:500;font-size:.9rem;">{{ $s->session_type ?? 'Workout Session' }}</div>
                        <div style="font-size:.75rem;color:var(--t-muted);">
                            {{ $s->session_date->format('D, d M Y') }}
                            @if($s->duration_mins) · {{ $s->duration_mins }} mins @endif
                            · {{ $s->exercises->count() }} exercises
                        </div>
                    </div>
                    <div style="text-align:right;">
                        @if($s->member_feedback)
                            <span class="t-badge t-badge-green" style="font-size:.65rem;">Feedback ✓</span>
                        @endif
                        <div style="font-size:.8rem;color:var(--t-accent);margin-top:4px;">View →</div>
                    </div>
                </div>
            </a>
            @empty
            <p style="color:var(--t-muted);font-size:.875rem;text-align:center;padding:24px 0;margin:0;">No sessions logged yet.</p>
            @endforelse

            @if($sessions->hasPages())
            <div style="margin-top:12px;">{{ $sessions->links() }}</div>
            @endif
        </div>

        {{-- Quick notes --}}
        <div class="t-card">
            <h5 style="font-size:1rem;margin-bottom:16px;">Quick Notes</h5>

            {{-- Add note form --}}
            <form method="POST" action="{{ route('trainer.note.store', $member) }}" style="margin-bottom:20px;">
                @csrf
                <textarea class="t-input" name="note" rows="2" placeholder="Quick note about {{ $member->name }}…" style="resize:vertical;margin-bottom:8px;" required></textarea>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                    <label style="display:flex;align-items:center;gap:7px;font-size:.82rem;color:var(--t-muted);cursor:pointer;">
                        <input type="checkbox" name="is_private" value="1" style="accent-color:var(--t-accent2);">
                        <span>Private (member can't see this)</span>
                    </label>
                    <button type="submit" class="t-btn-primary" style="padding:7px 14px;font-size:.82rem;">
                        <i class="bi bi-sticky"></i> Save Note
                    </button>
                </div>
            </form>

            {{-- Existing notes --}}
            @forelse($notes as $note)
            <div style="border:1px solid {{ $note->is_private ? '#fecaca' : 'var(--t-border)' }};border-radius:10px;padding:12px 14px;margin-bottom:10px;background:{{ $note->is_private ? '#fff5f5' : '#f9fafb' }};">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
                    <p style="font-size:.875rem;margin:0;line-height:1.6;flex:1;">{{ $note->note }}</p>
                    <form method="POST" action="{{ route('trainer.note.destroy', $note) }}" onsubmit="return confirm('Delete this note?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:#94a3b8;font-size:.8rem;cursor:pointer;padding:0;" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
                <div style="margin-top:8px;display:flex;align-items:center;gap:8px;font-size:.72rem;color:var(--t-muted);">
                    <span>{{ $note->created_at->format('d M Y, g:i A') }}</span>
                    @if($note->is_private)
                        <span style="background:#fee2e2;color:#dc2626;padding:1px 7px;border-radius:8px;font-weight:600;">🔒 Private</span>
                    @else
                        <span style="background:#dcfce7;color:#15803d;padding:1px 7px;border-radius:8px;font-weight:600;">👁 Visible to member</span>
                    @endif
                </div>
            </div>
            @empty
            <p style="color:var(--t-muted);font-size:.875rem;text-align:center;padding:16px 0;margin:0;">No notes yet.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
