@extends('layouts.app')
@section('title', 'Members')
@section('page-title', 'Members')

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px;">
    <div>
        <h4 style="margin:0;font-size:1.15rem;">All Members</h4>
        <p style="margin:0;font-size:.8rem;color:var(--ft-muted);">{{ $members->total() }} total members</p>
    </div>
    <a href="{{ route('members.create') }}" class="btn-ft-primary">
        <i class="bi bi-person-plus"></i> Add Member
    </a>
</div>

{{-- Filters --}}
<div class="ft-card mb-3">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:200px;">
            <label class="ft-label">Search</label>
            <input class="ft-input" name="search" value="{{ request('search') }}"
                   placeholder="Name, phone, or member number…">
        </div>
        <div style="min-width:140px;">
            <label class="ft-label">Status</label>
            <select class="ft-input" name="status" style="padding:9px 13px;">
                <option value="">All Statuses</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                <option value="inactive"  {{ request('status') === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn-ft-primary">Filter</button>
            <a href="{{ route('members.index') }}" class="btn-ft-secondary" style="margin-left:6px;">Clear</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="ft-card" style="padding:0;overflow:hidden;">
    <table class="ft-table">
        <thead>
            <tr>
                <th>Member #</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Trainer</th>
                <th>Joined</th>
                <th>Subscription</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
            <tr>
                <td><span style="font-family:monospace;font-size:.8rem;color:var(--ft-muted);">{{ $member->member_number }}</span></td>
                <td>
                    <strong style="font-size:.9rem;">{{ $member->name }}</strong>
                    @if($member->email)
                        <div style="font-size:.75rem;color:var(--ft-muted);">{{ $member->email }}</div>
                    @endif
                </td>
                <td style="font-size:.875rem;">{{ $member->phone }}</td>
                <td style="font-size:.875rem;">{{ $member->trainer?->name ?? '—' }}</td>
                <td style="font-size:.875rem;">{{ $member->join_date->format('d M Y') }}</td>
                <td>
                    @if($member->isSubscriptionActive())
                        <span class="ft-badge badge-active">Active</span>
                    @else
                        <span class="ft-badge badge-expired">Expired</span>
                    @endif
                </td>
                <td>
                    <span class="ft-badge badge-{{ $member->status }}">{{ ucfirst($member->status) }}</span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('members.show', $member) }}" class="btn-ft-secondary" style="padding:5px 10px;font-size:.78rem;">View</a>
                        <a href="{{ route('members.edit', $member) }}" class="btn-ft-secondary" style="padding:5px 10px;font-size:.78rem;">Edit</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:var(--ft-muted);">
                    <i class="bi bi-people" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    No members found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($members->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--ft-border);">
        {{ $members->links() }}
    </div>
    @endif
</div>

@endsection
