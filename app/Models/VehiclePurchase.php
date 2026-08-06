<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class VehiclePurchase extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'vehicle_id', 'seller_name', 'seller_address', 'seller_mobile',
        'reference_type', 'broker_id', 'purchase_date', 'purchase_rate',
        'commission', 'expenses_total', 'net_rate',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_rate' => 'decimal:2',
            'commission' => 'decimal:2',
            'expenses_total' => 'decimal:2',
            'net_rate' => 'decimal:2',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    /**
     * Net Rate = Purchase Rate + Commission + Expenses (as per the memo).
     */
    public function calculateNetRate(): float
    {
        return round((float) $this->purchase_rate + (float) $this->commission + (float) $this->expenses_total, 2);
    }
}
