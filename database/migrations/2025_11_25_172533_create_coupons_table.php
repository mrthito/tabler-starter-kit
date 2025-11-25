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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            // Discount Type
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // Discount value (percentage or fixed amount)

            // Usage Limits
            $table->integer('usage_limit')->nullable(); // Total usage limit (null = unlimited)
            $table->integer('used_count')->default(0); // Track how many times used
            $table->integer('usage_limit_per_user')->nullable(); // Per user limit (null = unlimited)

            // Amount Limits
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum order amount
            $table->decimal('maximum_discount', 10, 2)->nullable(); // Maximum discount for percentage type

            // Validity
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();

            // Applicability
            $table->enum('applicable_to', ['all', 'plans', 'specific_plans'])->default('all');
            $table->json('plan_ids')->nullable(); // For specific_plans type

            // Status
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
