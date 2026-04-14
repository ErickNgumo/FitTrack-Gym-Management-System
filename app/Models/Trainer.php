<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'speciality', 'bio', 'status'];

    public function members() { return $this->hasMany(Member::class); }
}
