<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'country',
        'google_id',
        'facebook_id',
        'apple_id',
        'user_type',
        'is_verified',
        'is_active',
        'is_approved',
        'document_type',
        'document_number',
        'kyc_status',
    ];

    /**
     * The attributes that should be hidden.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
        'facebook_id',
        'apple_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'kyc_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    /**
     * Helper Methods
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin' || $this->hasRole('admin');
    }

    public function isAgent()
    {
        return $this->user_type === 'agent' || $this->hasRole('agent');
    }

    public function isUser()
    {
        return $this->user_type === 'user' || $this->hasRole('user');
    }

    public function isKycVerified()
    {
        return $this->kyc_status === 'approved';
    }

    public function canTransfer()
    {
        return $this->is_active && $this->is_verified && $this->is_approved;
    }
}
