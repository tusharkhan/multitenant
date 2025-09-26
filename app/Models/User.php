<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tenant_id',
        'phone',
        'address',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function buildings()
    {
        return $this->hasMany(Building::class, 'owner_id');
    }

    public function createdBills()
    {
        return $this->hasMany(Bill::class, 'created_by');
    }

    public function assignedTenants()
    {
        return $this->hasMany(Tenant::class, 'assigned_by');
    }

    public function tenants()
    {
        // Return a query builder for tenants in this house owner's buildings
        return Tenant::whereHas('flat.building', function($query) {
            $query->where('owner_id', $this->id);
        });
    }

    // Scopes
    public function scopeHouseOwners($query)
    {
        return $query->where('role', 'house_owner');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHouseOwner()
    {
        return $this->role === 'house_owner';
    }
}
