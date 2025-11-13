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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('password')->nullable(); // Nullable for social login
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('country', 2)->nullable(); // ISO country code
            
            // Social Login IDs
            $table->string('google_id')->nullable()->unique();
            $table->string('facebook_id')->nullable()->unique();
            $table->string('apple_id')->nullable()->unique();
            
            // Role will be managed by Spatie Permission
            // But we add a quick reference field
            $table->enum('user_type', ['user', 'agent', 'admin'])->default('user');
            
            // Status & Verification
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(true); // For agents, requires admin approval
            
            // KYC (Know Your Customer)
            $table->string('document_type')->nullable(); // passport, id_card, driving_license
            $table->string('document_number')->nullable();
            $table->string('document_front_url')->nullable();
            $table->string('document_back_url')->nullable();
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('kyc_verified_at')->nullable();
            
            // Timestamps
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('email');
            $table->index('user_type');
            $table->index(['is_active', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
