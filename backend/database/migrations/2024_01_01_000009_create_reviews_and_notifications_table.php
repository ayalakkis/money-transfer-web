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
        // Reviews & Ratings
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('transaction_id')->nullable();
            $table->uuid('agent_id')->nullable();
            $table->integer('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->enum('review_type', ['service', 'agent', 'app'])->default('service');
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            
            $table->index(['agent_id', 'is_approved']);
            $table->index('rating');
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('type'); // transaction_update, kyc_approved, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data
            $table->uuid('related_id')->nullable(); // Transaction ID, etc.
            $table->string('related_type')->nullable(); // Transaction, Agent, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });

        // Promotions & Offers
        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique(); // SAVE10, FIRSTTRANSFER
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'free_transfer']); 
            $table->decimal('discount_value', 10, 2)->nullable(); // 10.00 for $10 off
            $table->decimal('discount_percentage', 5, 2)->nullable(); // 10.00 for 10% off
            $table->decimal('min_transfer_amount', 15, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // Total uses allowed
            $table->integer('usage_count')->default(0);
            $table->integer('per_user_limit')->default(1);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->json('applicable_countries')->nullable(); // ["US", "UK"]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('code');
            $table->index(['valid_from', 'valid_until']);
            $table->index('is_active');
        });

        // User Promotion Usage Tracking
        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('promotion_id');
            $table->uuid('user_id');
            $table->uuid('transaction_id')->nullable();
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
            
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            
            $table->index(['user_id', 'promotion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('reviews');
    }
};

