<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\BelongsToTenant;

class Vehicle extends Model
{
    use SoftDeletes;
    use BelongsToTenant;

    protected $fillable = [
        'branch_id', 'sr_no', 'memo_no', 'vehicle_no', 'model', 'status', 'created_by',
    ];

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
        return $this->hasOne(VehiclePurchase::class);
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
}
