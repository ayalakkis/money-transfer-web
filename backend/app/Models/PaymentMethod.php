<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'type', 'card_last_4', 'card_brand', 'card_exp_month',
        'card_exp_year', 'card_holder_name', 'card_country', 'wallet_provider',
        'wallet_email', 'wallet_phone', 'mobile_money_provider',
        'mobile_money_number', 'mobile_money_name', 'stripe_payment_method_id',
        'stripe_customer_id', 'paypal_email', 'paypal_payer_id',
        'is_default', 'is_active', 'is_verified',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getDisplayNameAttribute()
    {
        return match($this->type) {
            'card' => ucfirst($this->card_brand) . " ending in " . $this->card_last_4,
            'ewallet' => ucfirst($this->wallet_provider) . " (" . ($this->wallet_email ?? $this->wallet_phone) . ")",
            'mobile_money' => $this->mobile_money_provider . " - " . $this->mobile_money_number,
            default => 'Payment Method',
        };
    }

    public function isExpired()
    {
        if ($this->type !== 'card') return false;
        $now = now();
        return $this->card_exp_year < $now->year || 
               ($this->card_exp_year == $now->year && $this->card_exp_month < $now->month);
    }
}
