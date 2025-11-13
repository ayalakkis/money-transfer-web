<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'registration_number',
        'tax_id',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'working_hours',
        'is_open_now',
        'commission_rate',
        'daily_limit',
        'transaction_limit',
        'cash_in',
        'cash_out',
        'money_transfer',
        'supported_currencies',
        'total_transactions',
        'total_volume',
        'rating',
        'review_count',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'supported_currencies' => 'array',
        'is_open_now' => 'boolean',
        'cash_in' => 'boolean',
        'cash_out' => 'boolean',
        'money_transfer' => 'boolean',
        'commission_rate' => 'decimal:2',
        'daily_limit' => 'decimal:2',
        'transaction_limit' => 'decimal:2',
        'total_volume' => 'decimal:2',
        'rating' => 'decimal:2',
        'approved_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'agent_id');
    }

    public function payoutLocations()
    {
        return $this->hasMany(PayoutLocation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope for active agents
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for agents near location
     */
    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 10)
    {
        return $query->selectRaw("
            *, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radiusKm)
        ->orderBy('distance');
    }
}

