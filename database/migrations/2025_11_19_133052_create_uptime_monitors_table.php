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
        Schema::create('uptime_monitors', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->string('service_url');
            $table->enum('status', ['operational', 'degraded', 'down'])->default('operational');
            $table->integer('response_time')->nullable()->comment('Response time in milliseconds');
            $table->text('error_message')->nullable();
            $table->timestamp('checked_at');
            $table->timestamps();

            $table->index(['service_name', 'checked_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uptime_monitors');
    }
};
