<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code', 'title', 'description', 'type', 'discount_value',
        'discount_percentage', 'min_transfer_amount', 'max_discount',
        'usage_limit', 'usage_count', 'per_user_limit', 'valid_from',
        'valid_until', 'applicable_countries', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'min_transfer_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'applicable_countries' => 'array',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function usages() { return $this->hasMany(PromotionUsage::class); }

    public function isValid()
    {
        $now = now();
        return $this->is_active &&
               (!$this->valid_from || $now->gte($this->valid_from)) &&
               (!$this->valid_until || $now->lte($this->valid_until)) &&
               (!$this->usage_limit || $this->usage_count < $this->usage_limit);
    }

    public function calculateDiscount($amount)
    {
        $discount = match($this->type) {
            'fixed' => $this->discount_value,
            'percentage' => $amount * ($this->discount_percentage / 100),
            'free_transfer' => $amount, // Full transfer amount
            default => 0,
        };

        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return round($discount, 2);
    }
}

