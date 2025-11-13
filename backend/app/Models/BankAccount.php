<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'account_holder_name', 'bank_name', 'account_number',
        'routing_number', 'swift_code', 'iban', 'bic', 'account_type',
        'currency', 'country', 'is_verified', 'verification_method',
        'verified_at', 'micro_deposit_1', 'micro_deposit_2',
        'verification_attempts', 'verification_expires_at',
        'is_default', 'is_active', 'notes',
    ];

    protected $hidden = ['micro_deposit_1', 'micro_deposit_2'];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'verification_expires_at' => 'datetime',
        'micro_deposit_1' => 'decimal:2',
        'micro_deposit_2' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getMaskedAccountNumberAttribute()
    {
        $number = $this->account_number;
        $length = strlen($number);
        return $length <= 4 ? str_repeat('*', $length) : str_repeat('*', $length - 4) . substr($number, -4);
    }
}
