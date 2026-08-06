<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'logo',
        'favicon',
        'address',
        'city',
        'state',
        'country',
        'gst_number',
        'subscription_plan_id',
        'status',
        'timezone',
        'currency',
        'trial_ends_at',
        'subscription_ends_at'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}