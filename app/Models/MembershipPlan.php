<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = ['name', 'duration_days', 'price', 'description', 'is_active'];
    protected $casts    = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function subscriptions() { return $this->hasMany(Subscription::class, 'plan_id'); }
}
