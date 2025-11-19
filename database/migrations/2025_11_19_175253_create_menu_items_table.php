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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->string('title')->comment('Menu item title');
            $table->string('url')->nullable()->comment('Custom URL (used when not linking to a resource)');
            $table->string('route')->nullable()->comment('Route name (alternative to URL)');
            $table->string('icon')->nullable()->comment('Icon class or identifier');
            $table->string('css_class')->nullable()->comment('Additional CSS classes');
            $table->string('target')->default('_self')->comment('Link target (_self, _blank, etc.)');
            $table->integer('order')->default(0)->index()->comment('Display order within parent');
            $table->boolean('status')->default(true)->index()->comment('Whether item is enabled');
            $table->boolean('is_visible')->default(true)->index()->comment('Whether item is visible');

            // Polymorphic relationship for linking to resources
            $table->nullableMorphs('linkable');

            // Permissions and access control
            $table->json('permissions')->nullable()->comment('Required permissions to view this item');
            $table->json('roles')->nullable()->comment('Required roles to view this item');

            // Additional settings
            $table->json('attributes')->nullable()->comment('Additional HTML attributes');
            $table->json('meta')->nullable()->comment('Custom metadata');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
