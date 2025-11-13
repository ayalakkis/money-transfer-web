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
        // Transfer Services / Corridors (Country to Country)
        Schema::create('transfer_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // Service name
            $table->string('from_country', 2); // ISO code
            $table->string('to_country', 2);
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            
            // Payout Methods Available
            $table->boolean('bank_deposit')->default(true);
            $table->boolean('cash_pickup')->default(false);
            $table->boolean('mobile_wallet')->default(false);
            $table->boolean('home_delivery')->default(false);
            
            // Transfer Speed
            $table->enum('speed', ['instant', 'same_day', 'next_day', '2-3_days', '3-5_days'])->default('same_day');
            $table->string('estimated_time')->nullable(); // "Within 30 minutes", "1-2 hours"
            
            // Fees Structure
            $table->decimal('fixed_fee', 10, 2)->default(0.00);
            $table->decimal('percentage_fee', 5, 4)->default(0.00); // 2.5% = 0.0250
            $table->decimal('min_fee', 10, 2)->default(0.00);
            $table->decimal('max_fee', 10, 2)->nullable();
            
            // Limits
            $table->decimal('min_amount', 15, 2)->default(1.00);
            $table->decimal('max_amount', 15, 2)->default(10000.00);
            
            // Promotion
            $table->boolean('is_promoted')->default(false);
            $table->decimal('promotional_rate', 5, 4)->nullable();
            $table->timestamp('promotion_valid_until')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->integer('popularity_score')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['from_country', 'to_country']);
            $table->index(['from_currency', 'to_currency']);
            $table->index('is_active');
            $table->index('speed');
        });

        // Payout Locations (for cash pickup)
        Schema::create('payout_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('agent_id')->nullable(); // Link to agent if agent location
            $table->string('name');
            $table->string('provider'); // Western Union, MoneyGram, Agent name
            $table->string('address');
            $table->string('city');
            $table->string('country', 2);
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('phone')->nullable();
            $table->json('working_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
            $table->index(['latitude', 'longitude']);
            $table->index(['country', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_locations');
        Schema::dropIfExists('transfer_services');
    }
};

