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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->decimal('yearly_price', 10, 2)->default(0);
            $table->integer('yearly_discount_percentage')->nullable()->default(0); // Discount % for yearly
            $table->string('currency', 3)->default('USD');

            // Trial
            $table->boolean('has_trial')->default(false);
            $table->integer('trial_days')->nullable()->default(0);

            // Content
            $table->text('detail')->nullable(); // Detailed description
            $table->json('features')->nullable(); // Array of features

            // Plan Type
            $table->string('plan_type')->default('paid'); // free, paid, enterprise, custom

            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);

            // Status
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
