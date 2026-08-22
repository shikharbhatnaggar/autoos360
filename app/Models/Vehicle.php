<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\BelongsToTenant;
use App\Models\VehicleDocument;

class Vehicle extends Model
{
    use SoftDeletes;
    use BelongsToTenant;

    protected $fillable = [
        'branch_id', 'sr_no', 'memo_no', 'vehicle_no', 'model', 'body_type', 'vehicle_images', 'status', 'created_by',
        // Vehicle Metadata
        'make_year',
        'km_driven',
        'fuel_type',
        'transmission',
        'ownership',
        'engine_cc',
        'engine_description',
        'engine_power_ps',
        'mileage_claimed',
        'seating_capacity',
        'fuel_tank',
        'colour',
        'insurance_type',
        'insurance_valid_till',
        'registration_no',
        'inspection_highlights',
        'features',
        'is_featured',
        'is_new_arrival',
        'is_commercial',
    ];

    protected function casts(): array
    {
        return [
            'make_year' => 'integer',
            'km_driven' => 'integer',
            'engine_cc' => 'integer',
            'engine_power_ps' => 'decimal:2',
            'mileage_claimed' => 'decimal:2',
            'seating_capacity' => 'integer',
            'fuel_tank' => 'decimal:2',
            'insurance_valid_till' => 'date',
            'inspection_highlights' => 'array',
            'features' => 'array',
            'vehicle_images' => 'array',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function purchase(): HasOne
    {
        return $this->hasOne(VehiclePurchase::class, 'vehicle_id');
    }

    public function sale(): HasOne
    {
        return $this->hasOne(VehicleSale::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }

    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('status', 'in_stock');
    }

    public function scopeSold(Builder $query): Builder
    {
        return $query->where('status', 'sold');
    }

    /**
     * Scope query to the branches the given user is allowed to see.
     * Admins see all branches; everyone else is locked to their own branch.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where('branch_id', $user->branch_id);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }

}

