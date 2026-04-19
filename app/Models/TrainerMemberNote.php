<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerMemberNote extends Model
{
    protected $fillable = ['trainer_id', 'member_id', 'note', 'is_private'];
    protected $casts    = ['is_private' => 'boolean'];

    public function trainer() { return $this->belongsTo(Trainer::class); }
    public function member()  { return $this->belongsTo(Member::class); }
}
