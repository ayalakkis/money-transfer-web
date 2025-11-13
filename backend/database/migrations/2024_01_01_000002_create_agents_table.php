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
        Schema::create('agents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            
            // Business Information
            $table->string('business_name');
            $table->string('business_type')->nullable(); // retail, exchange_office, etc.
            $table->string('registration_number')->nullable();
            $table->string('tax_id')->nullable();
            
            // Location Details
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country', 2);
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Working Hours
            $table->json('working_hours')->nullable(); // {"monday": "09:00-18:00", ...}
            $table->boolean('is_open_now')->default(true);
            
            // Commission & Limits
            $table->decimal('commission_rate', 5, 2)->default(1.50); // Percentage
            $table->decimal('daily_limit', 15, 2)->default(10000.00);
            $table->decimal('transaction_limit', 15, 2)->default(5000.00);
            
            // Services Offered
            $table->boolean('cash_in')->default(true);
            $table->boolean('cash_out')->default(true);
            $table->boolean('money_transfer')->default(true);
            $table->json('supported_currencies')->nullable(); // ["USD", "EUR", "GBP"]
            
            // Stats
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_volume', 15, 2)->default(0.00);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('review_count')->default(0);
            
            // Status
            $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->uuid('approved_by')->nullable(); // Admin who approved
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['latitude', 'longitude']); // For map queries
            $table->index(['country', 'city']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};

