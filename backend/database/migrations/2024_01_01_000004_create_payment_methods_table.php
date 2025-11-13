<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            
            // Payment Type
            $table->enum('type', ['card', 'ewallet', 'bank_transfer', 'mobile_money']);
            
            // Card Details (for type = 'card')
            $table->string('card_last_4', 4)->nullable();
            $table->string('card_brand', 20)->nullable(); // visa, mastercard, amex
            $table->integer('card_exp_month')->nullable();
            $table->integer('card_exp_year')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('card_country', 2)->nullable();
            
            // E-wallet Details (for type = 'ewallet')
            $table->string('wallet_provider', 50)->nullable(); // paypal, venmo, cashapp
            $table->string('wallet_email')->nullable();
            $table->string('wallet_phone')->nullable();
            
            // Mobile Money (for type = 'mobile_money')
            $table->string('mobile_money_provider', 50)->nullable(); // mpesa, gcash, etc.
            $table->string('mobile_money_number')->nullable();
            $table->string('mobile_money_name')->nullable();
            
            // Stripe/Payment Gateway IDs
            $table->string('stripe_payment_method_id')->nullable()->unique();
            $table->string('stripe_customer_id')->nullable();
            
            // PayPal
            $table->string('paypal_email')->nullable();
            $table->string('paypal_payer_id')->nullable();
            
            // Status
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index('stripe_payment_method_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

