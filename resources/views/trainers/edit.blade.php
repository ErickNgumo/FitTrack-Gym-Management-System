@extends('layouts.app')
@section('title', 'Edit Trainer')
@section('page-title', 'Edit Trainer')

@section('content')
<div style="max-width:560px;">
    <div style="margin-bottom:16px;">
        <a href="{{ route('trainers.index') }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
            <i class="bi bi-arrow-left"></i> Back to Trainers
        </a>
    </div>
    <div class="ft-card">
        <h5 style="margin-bottom:24px;">Edit Trainer</h5>
        @if($errors->any())
            <div class="ft-alert ft-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul style="margin:0;padding-left:14px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('trainers.update', $trainer) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="ft-label">Full Name *</label>
                    <input class="ft-input" name="name" value="{{ old('name', $trainer->name) }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Phone *</label>
                    <input class="ft-input" name="phone" value="{{ old('phone', $trainer->phone) }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Email</label>
                    <input class="ft-input" name="email" type="email" value="{{ old('email', $trainer->email) }}">
                </div>
                <div class="col-12">
                    <label class="ft-label">Speciality</label>
                    <input class="ft-input" name="speciality" value="{{ old('speciality', $trainer->speciality) }}">
                </div>
                <div class="col-12">
                    <label class="ft-label">Bio</label>
                    <textarea class="ft-input" name="bio" rows="3" style="resize:vertical;">{{ old('bio', $trainer->bio) }}</textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Status *</label>
                    <select class="ft-input" name="status" style="padding:9px 13px;" required>
                        <option value="active"   {{ old('status', $trainer->status) === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $trainer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div style="margin-top:24px;display:flex;gap:10px;">
                <button type="submit" class="btn-ft-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
                <a href="{{ route('trainers.index') }}" class="btn-ft-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
