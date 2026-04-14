@extends('layouts.app')
@section('title', 'Trainers')
@section('page-title', 'Trainers')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px;">
    <h4 style="margin:0;font-size:1.15rem;">Trainers</h4>
    <a href="{{ route('trainers.create') }}" class="btn-ft-primary">
        <i class="bi bi-person-plus"></i> Add Trainer
    </a>
</div>

<div class="row g-3">
    @forelse($trainers as $trainer)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="ft-card" style="position:relative;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                <div style="width:48px;height:48px;border-radius:50%;background:var(--ft-accent2);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.1rem;color:#fff;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($trainer->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:.95rem;">{{ $trainer->name }}</div>
                    @if($trainer->speciality)
                        <div style="font-size:.78rem;color:var(--ft-muted);">{{ $trainer->speciality }}</div>
                    @endif
                </div>
                <span class="ft-badge badge-{{ $trainer->status }}" style="margin-left:auto;">{{ ucfirst($trainer->status) }}</span>
            </div>
            <div style="font-size:.85rem;display:flex;flex-direction:column;gap:6px;">
                <div style="display:flex;align-items:center;gap:8px;color:var(--ft-muted);">
                    <i class="bi bi-telephone"></i> {{ $trainer->phone }}
                </div>
                @if($trainer->email)
                <div style="display:flex;align-items:center;gap:8px;color:var(--ft-muted);">
                    <i class="bi bi-envelope"></i> {{ $trainer->email }}
                </div>
                @endif
                <div style="display:flex;align-items:center;gap:8px;color:var(--ft-muted);">
                    <i class="bi bi-people"></i> {{ $trainer->members_count }} members assigned
                </div>
            </div>
            <div style="margin-top:16px;display:flex;gap:8px;">
                <a href="{{ route('trainers.edit', $trainer) }}" class="btn-ft-secondary" style="font-size:.8rem;padding:6px 12px;">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form method="POST" action="{{ route('trainers.destroy', $trainer) }}" onsubmit="return confirm('Deactivate this trainer?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ft-secondary" style="font-size:.8rem;padding:6px 12px;color:#dc2626;border-color:#fecaca;">
                        <i class="bi bi-dash-circle"></i> Deactivate
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="ft-card" style="text-align:center;padding:60px 40px;color:var(--ft-muted);">
            <i class="bi bi-person-badge" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
            No trainers yet. <a href="{{ route('trainers.create') }}" style="color:var(--ft-accent);">Add your first trainer.</a>
        </div>
    </div>
    @endforelse
</div>

@endsection
