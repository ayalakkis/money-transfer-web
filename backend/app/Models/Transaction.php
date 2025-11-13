<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'transaction_number', 'user_id', 'send_amount', 'send_currency',
        'receive_amount', 'receive_currency', 'exchange_rate', 'rate_locked_at',
        'transfer_fee', 'service_fee', 'total_fee', 'total_amount',
        'payment_method_id', 'bank_account_id', 'payment_type',
        'beneficiary_id', 'beneficiary_name', 'destination_country',
        'payout_method', 'payout_location_id', 'agent_id',
        'transfer_service_id', 'transfer_speed', 'estimated_arrival',
        'status', 'stripe_payment_intent_id', 'stripe_charge_id',
        'paypal_transaction_id', 'tracking_number', 'purpose', 'description',
        'failure_reason', 'cancellation_reason', 'requires_kyc', 'is_suspicious',
        'risk_level', 'agent_commission', 'payment_confirmed_at',
        'processing_started_at', 'sent_at', 'completed_at', 'cancelled_at',
    ];

    protected $casts = [
        'send_amount' => 'decimal:2',
        'receive_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'transfer_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'agent_commission' => 'decimal:2',
        'requires_kyc' => 'boolean',
        'is_suspicious' => 'boolean',
        'rate_locked_at' => 'datetime',
        'payment_confirmed_at' => 'datetime',
        'processing_started_at' => 'datetime',
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user() { return $this->belongsTo(User::class); }
    public function paymentMethod() { return $this->belongsTo(PaymentMethod::class); }
    public function bankAccount() { return $this->belongsTo(BankAccount::class); }
    public function beneficiary() { return $this->belongsTo(Beneficiary::class); }
    public function agent() { return $this->belongsTo(Agent::class); }
    public function transferService() { return $this->belongsTo(TransferService::class); }
    public function statusHistory() { return $this->hasMany(TransactionStatusHistory::class); }

    // Scopes
    public function scopeCompleted($query) { return $query->where('status', 'completed'); }
    public function scopePending($query) { return $query->whereIn('status', ['pending', 'payment_processing', 'processing']); }
    
    // Helpers
    public function isSuccessful() { return $this->status === 'completed'; }
    public function isPending() { return in_array($this->status, ['pending', 'payment_processing', 'processing']); }
}
