<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'bill_category_id',
        'bill_month',
        'amount',
        'previous_due',
        'total_amount',
        'status',
        'due_date',
        'paid_date',
        'payment_method',
        'transaction_reference',
        'notes',
        'created_by',
        'tenant_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'previous_due' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    // Relationships
    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function billCategory()
    {
        return $this->belongsTo(BillCategory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tenant()
    {
        return $this->hasOneThrough(Tenant::class, Flat::class, 'id', 'flat_id', 'flat_id', 'id');
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'unpaid')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeForMonth($query, $month)
    {
        return $query->where('bill_month', $month);
    }

    public function scopeForFlat($query, $flatId)
    {
        return $query->where('flat_id', $flatId);
    }

    // Helper methods
    public function markAsPaid($paymentMethod = null, $transactionReference = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_method' => $paymentMethod,
            'transaction_reference' => $transactionReference,
        ]);
    }

    public function isOverdue()
    {
        return $this->status === 'unpaid' && $this->due_date < now();
    }

    public function getFormattedBillMonthAttribute()
    {
        return \Carbon\Carbon::createFromFormat('Y-m', $this->bill_month)->format('F Y');
    }

    // Automatically calculate total amount when saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($bill) {
            $bill->total_amount = $bill->amount + $bill->previous_due;
            
            // Mark as overdue if past due date and unpaid
            if ($bill->status === 'unpaid' && $bill->due_date < now()) {
                $bill->status = 'overdue';
            }
        });
    }
}
