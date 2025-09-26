<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'description',
        'owner_id',
        'tenant_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function flats()
    {
        return $this->hasMany(Flat::class);
    }

    public function billCategories()
    {
        return $this->hasMany(BillCategory::class);
    }

    public function bills()
    {
        return $this->hasManyThrough(Bill::class, Flat::class);
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

    public function scopeForOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    // Helper methods
    public function getTotalFlatsAttribute()
    {
        return $this->flats()->count();
    }

    public function getOccupiedFlatsAttribute()
    {
        return $this->flats()->where('is_occupied', true)->count();
    }
}
