<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
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

    // Default categories
    public static function getDefaultCategories()
    {
        return [
            'Electricity',
            'Gas bill',
            'Water bill',
            'Utility Charges',
        ];
    }
}
