<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiary extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'phone', 'address',
        'city', 'country', 'bank_name', 'account_number', 'routing_number',
        'swift_code', 'iban', 'branch_name', 'mobile_wallet_provider',
        'mobile_wallet_number', 'preferred_payout', 'currency', 'relationship',
        'notes', 'is_verified', 'verified_at', 'is_active', 'is_favorite',
        'transfer_count', 'last_transfer_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'is_favorite' => 'boolean',
        'verified_at' => 'datetime',
        'last_transfer_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getMaskedAccountNumberAttribute()
    {
        if (!$this->account_number) return null;
        $number = $this->account_number;
        $length = strlen($number);
        return $length <= 4 ? str_repeat('*', $length) : str_repeat('*', $length - 4) . substr($number, -4);
    }
}
