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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            
            // Bank Details
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('account_number', 50);
            $table->string('routing_number', 50)->nullable();
            $table->string('swift_code', 20)->nullable();
            $table->string('iban', 50)->nullable();
            $table->string('bic', 20)->nullable();
            
            // Account Type & Currency
            $table->enum('account_type', ['checking', 'savings', 'business'])->default('checking');
            $table->string('currency', 3)->default('USD');
            $table->string('country', 2); // ISO 3166-1 alpha-2
            
            // Verification
            $table->boolean('is_verified')->default(false);
            $table->enum('verification_method', ['micro_deposit', 'instant', 'manual', 'document'])->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // Micro-deposits for verification
            $table->decimal('micro_deposit_1', 10, 2)->nullable();
            $table->decimal('micro_deposit_2', 10, 2)->nullable();
            $table->integer('verification_attempts')->default(0);
            $table->timestamp('verification_expires_at')->nullable();
            
            // Status
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Metadata
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};

