<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'receipt_number', 'member_id', 'subscription_id', 'amount',
        'payment_method', 'mpesa_ref', 'payment_date',
        'coverage_start', 'coverage_end', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'payment_date'   => 'date',
        'coverage_start' => 'date',
        'coverage_end'   => 'date',
    ];

    public function member()       { return $this->belongsTo(Member::class); }
    public function subscription() { return $this->belongsTo(Subscription::class); }
    public function recordedBy()   { return $this->belongsTo(User::class, 'recorded_by'); }

    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP-' . now()->format('Ymd') . '-';
        $last   = static::where('receipt_number', 'like', $prefix . '%')
                        ->orderByDesc('id')->first();
        $seq    = $last ? ((int) substr($last->receipt_number, -4)) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
