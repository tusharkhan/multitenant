<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'id_proof_type',
        'id_proof_number',
        'flat_id',
        'assigned_by',
        'move_in_date',
        'move_out_date',
        'security_deposit',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'move_in_date' => 'date',
            'move_out_date' => 'date',
            'security_deposit' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function bills()
    {
        return $this->hasManyThrough(Bill::class, Flat::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('flat_id');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('flat_id');
    }

    // Helper methods
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getTenancyDurationAttribute()
    {
        if (!$this->move_in_date) return null;
        
        $endDate = $this->move_out_date ?: now();
        return $this->move_in_date->diffInMonths($endDate);
    }

    public function getCurrentDuesAttribute()
    {
        if (!$this->flat_id) return 0;
        return $this->flat->total_dues;
    }
}
