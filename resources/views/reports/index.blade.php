@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')

<div class="row g-3">
    <div class="col-12 col-md-6 col-xl-3">
        <a href="{{ route('reports.revenue') }}" style="text-decoration:none;">
            <div class="ft-card" style="text-align:center;padding:32px 24px;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow=''">
                <div style="font-size:2.2rem;margin-bottom:12px;">💰</div>
                <h5 style="font-size:1rem;margin-bottom:6px;">Revenue Report</h5>
                <p style="font-size:.8rem;color:var(--ft-muted);margin:0;">Monthly revenue breakdown and payment method analysis</p>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <a href="{{ route('reports.members') }}" style="text-decoration:none;">
            <div class="ft-card" style="text-align:center;padding:32px 24px;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow=''">
                <div style="font-size:2.2rem;margin-bottom:12px;">👥</div>
                <h5 style="font-size:1rem;margin-bottom:6px;">Member Report</h5>
                <p style="font-size:.8rem;color:var(--ft-muted);margin:0;">Active/inactive members, expired subscriptions</p>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <a href="{{ route('reports.attendance') }}" style="text-decoration:none;">
            <div class="ft-card" style="text-align:center;padding:32px 24px;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow=''">
                <div style="font-size:2.2rem;margin-bottom:12px;">📅</div>
                <h5 style="font-size:1rem;margin-bottom:6px;">Attendance Report</h5>
                <p style="font-size:.8rem;color:var(--ft-muted);margin:0;">Daily check-in trends and frequency analysis</p>
            </div>
        </a>
    </div>
</div>

@endsection
