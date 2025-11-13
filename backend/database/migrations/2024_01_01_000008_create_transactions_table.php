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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaction_number')->unique(); // MT-2024-001234
            $table->uuid('user_id'); // Sender
            
            // Amount Details
            $table->decimal('send_amount', 15, 2); // Amount sender pays
            $table->string('send_currency', 3);
            $table->decimal('receive_amount', 15, 2); // Amount recipient gets
            $table->string('receive_currency', 3);
            
            // Exchange Rate
            $table->decimal('exchange_rate', 12, 6);
            $table->timestamp('rate_locked_at')->nullable();
            
            // Fees
            $table->decimal('transfer_fee', 10, 2)->default(0.00);
            $table->decimal('service_fee', 10, 2)->default(0.00);
            $table->decimal('total_fee', 10, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2); // send_amount + total_fee
            
            // Payment Source
            $table->uuid('payment_method_id')->nullable();
            $table->uuid('bank_account_id')->nullable();
            $table->enum('payment_type', ['card', 'bank', 'ewallet', 'cash'])->nullable();
            
            // Destination
            $table->uuid('beneficiary_id')->nullable();
            $table->string('beneficiary_name')->nullable(); // Cached name
            $table->string('destination_country', 2);
            
            // Payout Details
            $table->enum('payout_method', ['bank_deposit', 'cash_pickup', 'mobile_wallet', 'home_delivery']);
            $table->uuid('payout_location_id')->nullable(); // For cash pickup
            $table->uuid('agent_id')->nullable(); // Agent processing the payout
            
            // Transfer Service Used
            $table->uuid('transfer_service_id')->nullable();
            $table->enum('transfer_speed', ['instant', 'same_day', 'next_day', '2-3_days', '3-5_days']);
            $table->string('estimated_arrival')->nullable();
            
            // Status Tracking
            $table->enum('status', [
                'pending',           // Created, awaiting payment
                'payment_processing', // Payment being processed
                'payment_received',  // Payment confirmed
                'processing',        // Transfer in progress
                'sent',             // Sent to recipient country
                'ready_for_pickup',  // Ready at agent location
                'completed',         // Successfully delivered
                'failed',           // Failed
                'cancelled',         // Cancelled by user
                'refunded'          // Refunded
            ])->default('pending');
            
            // Payment Gateway References
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->string('stripe_charge_id')->nullable();
            $table->string('paypal_transaction_id')->nullable();
            
            // Tracking & Compliance
            $table->string('tracking_number')->nullable()->unique();
            $table->text('purpose')->nullable(); // Purpose of transfer
            $table->text('description')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Compliance & Security
            $table->boolean('requires_kyc')->default(false);
            $table->boolean('is_suspicious')->default(false);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            
            // Commission (for agents)
            $table->decimal('agent_commission', 10, 2)->nullable();
            
            // Important Timestamps
            $table->timestamp('payment_confirmed_at')->nullable();
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('set null');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('set null');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
            $table->foreign('payout_location_id')->references('id')->on('payout_locations')->onDelete('set null');
            $table->foreign('transfer_service_id')->references('id')->on('transfer_services')->onDelete('set null');
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('created_at');
            $table->index('destination_country');
            $table->index('transaction_number');
            $table->index('tracking_number');
        });

        // Transaction Status History
        Schema::create('transaction_status_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_id');
            $table->string('status', 50);
            $table->text('message')->nullable();
            $table->text('metadata')->nullable(); // JSON data
            $table->uuid('updated_by')->nullable(); // User/Admin who updated
            $table->timestamps();
            
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->index(['transaction_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_status_history');
        Schema::dropIfExists('transactions');
    }
};

