<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class VehicleSale extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'vehicle_id', 'purchaser_name', 'purchaser_address', 'purchaser_mobile',
        'reference_medium', 'sale_date', 'sale_rate', 'commission', 'net_rate', 'profit_loss',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'sale_rate' => 'decimal:2',
            'commission' => 'decimal:2',
            'net_rate' => 'decimal:2',
            'profit_loss' => 'decimal:2',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Net Rate = Sale Rate - Commission (as per the memo).
     */
    public function calculateNetRate(): float
    {
        return round((float) $this->sale_rate - (float) $this->commission, 2);
    }
}
