@extends('layouts.app')
@section('title', 'Add Member')
@section('page-title', 'Add Member')

@section('content')

<div style="max-width:680px;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('members.index') }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
            <i class="bi bi-arrow-left"></i> Back to Members
        </a>
    </div>

    <div class="ft-card">
        <h5 style="margin-bottom:24px;font-size:1.05rem;">New Member Details</h5>

        @if($errors->any())
            <div class="ft-alert ft-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul style="margin:0;padding-left:14px;">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('members.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-12 col-md-7">
                    <label class="ft-label">Full Name *</label>
                    <input class="ft-input" name="name" value="{{ old('name') }}" required placeholder="e.g. Alice Wanjiku">
                </div>
                <div class="col-12 col-md-5">
                    <label class="ft-label">Gender</label>
                    <select class="ft-input" name="gender" style="padding:9px 13px;">
                        <option value="">Select…</option>
                        <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Phone Number *</label>
                    <input class="ft-input" name="phone" value="{{ old('phone') }}" required placeholder="07XXXXXXXX">
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Email Address</label>
                    <input class="ft-input" name="email" type="email" value="{{ old('email') }}" placeholder="optional">
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Join Date *</label>
                    <input class="ft-input" name="join_date" type="date" value="{{ old('join_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Assigned Trainer</label>
                    <select class="ft-input" name="trainer_id" style="padding:9px 13px;">
                        <option value="">None</option>
                        @foreach($trainers as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                {{ $trainer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Status *</label>
                    <select class="ft-input" name="status" style="padding:9px 13px;" required>
                        <option value="active"    {{ old('status', 'active') === 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="inactive"  {{ old('status') === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Date of Birth</label>
                    <input class="ft-input" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}">
                </div>
                <div class="col-12">
                    <label class="ft-label">Address</label>
                    <input class="ft-input" name="address" value="{{ old('address') }}" placeholder="e.g. Westlands, Nairobi">
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Emergency Contact Name</label>
                    <input class="ft-input" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Emergency Contact Phone</label>
                    <input class="ft-input" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">
                </div>
                <div class="col-12">
                    <label class="ft-label">Notes</label>
                    <textarea class="ft-input" name="notes" rows="3" placeholder="Any additional notes…" style="resize:vertical;">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="margin-top:24px;display:flex;gap:10px;">
                <button type="submit" class="btn-ft-primary"><i class="bi bi-check-lg"></i> Create Member</button>
                <a href="{{ route('members.index') }}" class="btn-ft-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
