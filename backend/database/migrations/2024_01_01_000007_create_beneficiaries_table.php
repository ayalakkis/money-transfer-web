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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            
            // Beneficiary Details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country', 2);
            
            // Bank Details (for bank deposit)
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('iban')->nullable();
            $table->string('branch_name')->nullable();
            
            // Mobile Wallet (for mobile money)
            $table->string('mobile_wallet_provider')->nullable();
            $table->string('mobile_wallet_number')->nullable();
            
            // Preferred Payout Method
            $table->enum('preferred_payout', ['bank_deposit', 'cash_pickup', 'mobile_wallet', 'home_delivery'])->default('bank_deposit');
            $table->string('currency', 3);
            
            // Relationship & Notes
            $table->string('relationship')->nullable(); // family, friend, business
            $table->text('notes')->nullable();
            
            // Verification
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_favorite')->default(false);
            
            // Usage Stats
            $table->integer('transfer_count')->default(0);
            $table->timestamp('last_transfer_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};

