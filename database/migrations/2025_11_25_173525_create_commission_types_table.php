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
        Schema::create('commission_types', function (Blueprint $table) {
            $table->id();

            // Commission Type
            $table->enum('type', ['subscription_only', 'order_only', 'both'])->default('both');

            // Order Commission Percentage
            $table->decimal('order_commission_percentage', 5, 2)->nullable()->default(0); // 0-100

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_types');
    }
};
