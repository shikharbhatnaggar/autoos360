<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id', 'vehicle_id', 'name', 'mobile', 'email', 
        'vehicle_url', 'message', 'status', 'final_closing_rate', 'calculated_commission'
    ];

    /**
     * Link up to the parent Multi-Tenant Dealership profile.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Link directly to the specific vehicle asset being inspected.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
