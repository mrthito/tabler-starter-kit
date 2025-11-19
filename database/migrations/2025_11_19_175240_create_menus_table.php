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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Unique menu identifier/slug');
            $table->string('title')->comment('Display name of the menu');
            $table->string('location')->nullable()->index()->comment('Menu location (header, footer, sidebar, etc.)');
            $table->text('description')->nullable()->comment('Menu description');
            $table->boolean('status')->default(true)->index()->comment('Whether menu is enabled');
            $table->integer('order')->default(0)->index()->comment('Display order');
            $table->json('settings')->nullable()->comment('Additional menu settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
