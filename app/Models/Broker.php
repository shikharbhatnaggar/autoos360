<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToTenant;

class Broker extends Model
{
    use BelongsToTenant;
    protected $fillable = ['name', 'mobile', 'address'];

    public function purchases(): HasMany
    {
        return $this->hasMany(VehiclePurchase::class);
    }
}
