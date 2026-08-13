<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleDocument extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'vehicle_id',
        'tenant_id',
        'section',
        'document_type',
        'document_name',
        'document_no',
        'valid_till',
        'file_path',
        'original_file_name',
        'mime_type',
        'file_size',
    ];

    protected function casts(): array
    {
        return [
            'valid_till' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }

    public const VEHICLE_DOCUMENTS = [
        'registration_certificate' => 'Registration Certificate',
        'puc' => 'PUC',
        'fitness' => 'Fitness',
        'road_tax' => 'Road Tax',
        'permit' => 'Permit',
        'noc' => 'NOC',
        'insurance' => 'Insurance',
    ];

    public const ID_DOCUMENTS = [
        'aadhaar' => 'Aadhaar Card',
        'driving_license' => 'Driving License',
        'passport' => 'Passport',
    ];

}