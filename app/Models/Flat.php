<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_number',
        'building_id',
        'owner_name',
        'owner_phone',
        'owner_email',
        'owner_address',
        'carpet_area',
        'bedrooms',
        'bathrooms',
        'notes',
        'tenant_id',
        'is_occupied',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_occupied' => 'boolean',
            'is_active' => 'boolean',
            'carpet_area' => 'decimal:2',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
        ];
    }

    // Relationships
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function currentTenant()
    {
        return $this->hasOne(Tenant::class)->where('is_active', true);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOccupied($query)
    {
        return $query->where('is_occupied', true);
    }

    public function scopeVacant($query)
    {
        return $query->where('is_occupied', false);
    }

    public function scopeForBuilding($query, $buildingId)
    {
        return $query->where('building_id', $buildingId);
    }

    // Helper methods
    public function getUnpaidBillsAttribute()
    {
        return $this->bills()->where('status', 'unpaid')->get();
    }

    public function getTotalDuesAttribute()
    {
        return $this->bills()->where('status', 'unpaid')->sum('total_amount');
    }

    public function getFullAddressAttribute()
    {
        return "Flat {$this->flat_number}, {$this->building->name}, {$this->building->address}";
    }
}
