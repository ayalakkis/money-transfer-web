<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferService extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 'from_country', 'to_country', 'from_currency', 'to_currency',
        'bank_deposit', 'cash_pickup', 'mobile_wallet', 'home_delivery',
        'speed', 'estimated_time', 'fixed_fee', 'percentage_fee', 'min_fee',
        'max_fee', 'min_amount', 'max_amount', 'is_promoted',
        'promotional_rate', 'promotion_valid_until', 'is_active', 'popularity_score',
    ];

    protected $casts = [
        'bank_deposit' => 'boolean',
        'cash_pickup' => 'boolean',
        'mobile_wallet' => 'boolean',
        'home_delivery' => 'boolean',
        'is_promoted' => 'boolean',
        'is_active' => 'boolean',
        'fixed_fee' => 'decimal:2',
        'percentage_fee' => 'decimal:4',
        'min_fee' => 'decimal:2',
        'max_fee' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'promotional_rate' => 'decimal:4',
        'promotion_valid_until' => 'datetime',
    ];

    /**
     * Calculate total fee for an amount
     */
    public function calculateFee($amount)
    {
        $percentageFee = $amount * $this->percentage_fee;
        $totalFee = $this->fixed_fee + $percentageFee;
        
        if ($this->min_fee && $totalFee < $this->min_fee) {
            $totalFee = $this->min_fee;
        }
        
        if ($this->max_fee && $totalFee > $this->max_fee) {
            $totalFee = $this->max_fee;
        }
        
        return round($totalFee, 2);
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for searching
     */
    public function scopeSearch($query, $filters = [])
    {
        if (isset($filters['from_country'])) {
            $query->where('from_country', $filters['from_country']);
        }
        
        if (isset($filters['to_country'])) {
            $query->where('to_country', $filters['to_country']);
        }
        
        if (isset($filters['payout_method'])) {
            $query->where($filters['payout_method'], true);
        }
        
        if (isset($filters['speed'])) {
            $query->where('speed', $filters['speed']);
        }
        
        if (isset($filters['max_fee'])) {
            $query->where('fixed_fee', '<=', $filters['max_fee']);
        }
        
        return $query;
    }
}

