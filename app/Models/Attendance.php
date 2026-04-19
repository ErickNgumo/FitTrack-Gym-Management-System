<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance'; // Laravel would guess 'attendances' — override it

    public $timestamps = false;

    protected $fillable = ['member_id', 'check_in_time', 'check_out_time', 'notes', 'recorded_by'];

    protected $casts = [
        'check_in_time'  => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function member()     { return $this->belongsTo(Member::class); }
    public function recordedBy() { return $this->belongsTo(User::class, 'recorded_by'); }
}