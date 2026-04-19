@extends('trainer-portal.layouts.app')
@section('title', 'Log Session')
@section('page-title', 'Log Workout Session')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('trainer.member.show', $member) }}" style="font-size:.85rem;color:var(--t-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to {{ $member->name }}
    </a>
</div>

<div style="max-width:800px;">
    <div class="t-card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
            <div style="width:44px;height:44px;border-radius:50%;background:var(--t-accent);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0;">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:600;font-size:1rem;">{{ $member->name }}</div>
                <div style="font-size:.75rem;color:var(--t-muted);">{{ $member->member_number }} · Logging new workout session</div>
            </div>
        </div>

        @if($errors->any())
            <div class="t-alert t-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul style="margin:0;padding-left:14px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('trainer.session.store', $member) }}" id="session-form">
            @csrf

            {{-- Session meta --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <label class="t-label">Date *</label>
                    <input class="t-input" name="session_date" type="date" value="{{ old('session_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-12 col-md-4">
                    <label class="t-label">Session Type</label>
                    <select class="t-input" name="session_type" style="padding:9px 12px;">
                        <option value="">Select…</option>
                        @foreach(['Strength','Cardio','HIIT','Yoga','Flexibility','Circuit','Sports Specific','Recovery','Other'] as $type)
                            <option value="{{ $type }}" {{ old('session_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="t-label">Duration (minutes)</label>
                    <input class="t-input" name="duration_mins" type="number" min="1" max="300"
                           value="{{ old('duration_mins') }}" placeholder="e.g. 60">
                </div>
                <div class="col-12">
                    <label class="t-label">Overall Session Notes</label>
                    <textarea class="t-input" name="overall_notes" rows="2"
                              placeholder="How did the session go? Progress notes, cues, observations…"
                              style="resize:vertical;">{{ old('overall_notes') }}</textarea>
                </div>
            </div>

            {{-- Exercise table --}}
            <div style="margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;">
                <h5 style="font-size:1rem;margin:0;">Exercises</h5>
                <button type="button" id="add-row" class="t-btn-outline" style="font-size:.8rem;padding:6px 12px;">
                    <i class="bi bi-plus-lg"></i> Add Exercise
                </button>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.85rem;" id="exercises-table">
                    <thead>
                        <tr style="background:var(--t-bg);">
                            <th style="padding:8px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--t-muted);white-space:nowrap;">#</th>
                            <th style="padding:8px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--t-muted);">Exercise</th>
                            <th style="padding:8px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--t-muted);">Sets</th>
                            <th style="padding:8px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--t-muted);">Reps</th>
                            <th style="padding:8px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--t-muted);">kg</th>
                            <th style="padding:8px 10px;text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--t-muted);">Notes</th>
                            <th style="padding:8px 6px;"></th>
                        </tr>
                    </thead>
                    <tbody id="exercises-body">
                        <tr class="ex-row" data-index="0">
                            <td style="padding:6px 10px;color:var(--t-muted);font-size:.8rem;vertical-align:middle;">1</td>
                            <td style="padding:6px 6px;"><input class="t-input" name="exercises[0][name]" placeholder="e.g. Bench Press" required style="min-width:140px;"></td>
                            <td style="padding:6px 6px;"><input class="t-input" name="exercises[0][sets]" type="number" min="1" placeholder="4" style="width:60px;"></td>
                            <td style="padding:6px 6px;"><input class="t-input" name="exercises[0][reps]" type="number" min="1" placeholder="8" style="width:60px;"></td>
                            <td style="padding:6px 6px;"><input class="t-input" name="exercises[0][weight_kg]" type="number" step="0.5" min="0" placeholder="60" style="width:70px;"></td>
                            <td style="padding:6px 6px;"><input class="t-input" name="exercises[0][notes]" placeholder="Optional note" style="min-width:120px;"></td>
                            <td style="padding:6px 4px;"><button type="button" class="remove-row" style="background:none;border:none;color:#e94560;font-size:1rem;cursor:pointer;padding:4px;"><i class="bi bi-x-circle"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="margin-top:24px;display:flex;gap:10px;">
                <button type="submit" class="t-btn-primary"><i class="bi bi-check-lg"></i> Save Session</button>
                <a href="{{ route('trainer.member.show', $member) }}" class="t-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let rowIndex = 1;
const tbody = document.getElementById('exercises-body');

document.getElementById('add-row').addEventListener('click', function () {
    const row = document.createElement('tr');
    row.className = 'ex-row';
    row.dataset.index = rowIndex;
    row.innerHTML = `
        <td style="padding:6px 10px;color:var(--t-muted);font-size:.8rem;vertical-align:middle;">${rowIndex + 1}</td>
        <td style="padding:6px 6px;"><input class="t-input" name="exercises[${rowIndex}][name]" placeholder="e.g. Squat" required style="min-width:140px;"></td>
        <td style="padding:6px 6px;"><input class="t-input" name="exercises[${rowIndex}][sets]" type="number" min="1" placeholder="4" style="width:60px;"></td>
        <td style="padding:6px 6px;"><input class="t-input" name="exercises[${rowIndex}][reps]" type="number" min="1" placeholder="8" style="width:60px;"></td>
        <td style="padding:6px 6px;"><input class="t-input" name="exercises[${rowIndex}][weight_kg]" type="number" step="0.5" min="0" placeholder="80" style="width:70px;"></td>
        <td style="padding:6px 6px;"><input class="t-input" name="exercises[${rowIndex}][notes]" placeholder="Optional note" style="min-width:120px;"></td>
        <td style="padding:6px 4px;"><button type="button" class="remove-row" style="background:none;border:none;color:#e94560;font-size:1rem;cursor:pointer;padding:4px;"><i class="bi bi-x-circle"></i></button></td>
    `;
    tbody.appendChild(row);
    rowIndex++;
    updateRowNumbers();
});

tbody.addEventListener('click', function (e) {
    const btn = e.target.closest('.remove-row');
    if (btn && tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
        updateRowNumbers();
    }
});

function updateRowNumbers() {
    tbody.querySelectorAll('tr').forEach((row, i) => {
        row.querySelector('td:first-child').textContent = i + 1;
    });
}
</script>
@endpush

@endsection
