<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class VehicleExpense extends Model
{
    use BelongsToTenant;

    protected $fillable = ['vehicle_id', 'category', 'label', 'amount', 'percentage'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'percentage' => 'decimal:2',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public const CATEGORIES = [
        'engine' => 'Engine',
        'denting_painting' => 'Denting / Painting',
        'accessories' => 'Accessories',
        'tyre' => 'Tyre',
        'other' => 'Other',
    ];
}
