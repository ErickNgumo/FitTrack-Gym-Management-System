@extends('layouts.app')
@section('title', 'Record Payment')
@section('page-title', 'Record Payment')

@section('content')
<div style="max-width:620px;">
    <div style="margin-bottom:16px;">
        <a href="{{ route('payments.index') }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
            <i class="bi bi-arrow-left"></i> Back to Payments
        </a>
    </div>
    <div class="ft-card">
        <h5 style="margin-bottom:24px;font-size:1.05rem;">Record New Payment</h5>

        @if($errors->any())
            <div class="ft-alert ft-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul style="margin:0;padding-left:14px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            @if(request('subscription_id'))
                <input type="hidden" name="subscription_id" value="{{ request('subscription_id') }}">
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <label class="ft-label">Member *</label>
                    @if($member)
                        <input type="hidden" name="member_id" value="{{ $member->id }}">
                        <div class="ft-input" style="background:#f8f9fc;cursor:not-allowed;">
                            {{ $member->name }} — {{ $member->member_number }}
                        </div>
                    @else
                        <select class="ft-input" name="member_id" style="padding:9px 13px;" required>
                            <option value="">Select member…</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }} ({{ $m->member_number }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Amount (KES) *</label>
                    <input class="ft-input" name="amount" type="number" step="0.01" min="1"
                           value="{{ old('amount') }}" required placeholder="e.g. 2500">
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Payment Date *</label>
                    <input class="ft-input" name="payment_date" type="date"
                           value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Payment Method *</label>
                    <select class="ft-input" name="payment_method" style="padding:9px 13px;" required id="method-select">
                        <option value="cash"          {{ old('payment_method') === 'cash'          ? 'selected' : '' }}>Cash</option>
                        <option value="mpesa"         {{ old('payment_method') === 'mpesa'         ? 'selected' : '' }}>M-Pesa</option>
                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="card"          {{ old('payment_method') === 'card'          ? 'selected' : '' }}>Card</option>
                        <option value="other"         {{ old('payment_method') === 'other'         ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-12 col-md-6" id="mpesa-ref-field" style="{{ old('payment_method','') === 'mpesa' ? '' : 'display:none;' }}">
                    <label class="ft-label">M-Pesa Reference</label>
                    <input class="ft-input" name="mpesa_ref" value="{{ old('mpesa_ref') }}" placeholder="e.g. QAB1234XYZ">
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Coverage Start</label>
                    <input class="ft-input" name="coverage_start" type="date" value="{{ old('coverage_start') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Coverage End</label>
                    <input class="ft-input" name="coverage_end" type="date" value="{{ old('coverage_end') }}">
                </div>
                <div class="col-12">
                    <label class="ft-label">Notes</label>
                    <textarea class="ft-input" name="notes" rows="2" style="resize:vertical;">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="margin-top:24px;display:flex;gap:10px;">
                <button type="submit" class="btn-ft-primary"><i class="bi bi-check-lg"></i> Save & View Receipt</button>
                <a href="{{ route('payments.index') }}" class="btn-ft-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('method-select').addEventListener('change', function() {
        document.getElementById('mpesa-ref-field').style.display = this.value === 'mpesa' ? '' : 'none';
    });
</script>
@endpush

@endsection
