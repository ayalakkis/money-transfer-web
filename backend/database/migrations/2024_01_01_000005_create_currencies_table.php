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
        Schema::create('currencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 3)->unique(); // USD, EUR, GBP
            $table->string('name'); // US Dollar, Euro
            $table->string('symbol', 10); // $, €, £
            $table->string('flag_emoji', 10)->nullable(); // 🇺🇸, 🇪🇺
            $table->integer('decimal_places')->default(2);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('is_popular');
        });

        // Exchange Rates Table
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            $table->decimal('rate', 12, 6); // Exchange rate
            $table->decimal('inverse_rate', 12, 6); // Inverse for optimization
            $table->timestamp('valid_until')->nullable();
            $table->string('source')->default('api'); // api, manual, calculated
            $table->timestamps();
            
            // Composite unique index
            $table->unique(['from_currency', 'to_currency']);
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('currencies');
    }
};

