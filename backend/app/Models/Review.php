<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'transaction_id', 'agent_id', 'rating', 'comment',
        'review_type', 'is_verified_purchase', 'is_approved',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function agent() { return $this->belongsTo(Agent::class); }

    public function scopeApproved($query) { return $query->where('is_approved', true); }
}

