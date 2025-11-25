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
            $table->id();
            $table->foreignId('transaction_category_id')->constrained()->onDelete('restrict');
            $table->string('reference_number')->unique()->nullable(); // Auto-generated or manual
            $table->string('title');
            $table->text('description')->nullable();

            // Amount
            $table->decimal('amount', 15, 2); // Large amount for financial precision
            $table->string('currency', 3)->default('USD');

            // Transaction Type
            $table->enum('type', ['income', 'expense'])->default('expense');

            // Payment Method
            $table->string('payment_method')->nullable(); // cash, bank_transfer, credit_card, etc.

            // Dates
            $table->date('transaction_date');
            $table->dateTime('due_date')->nullable(); // For future transactions

            // Related Entities (polymorphic)
            $table->string('transactionable_type')->nullable(); // e.g., App\Models\Plan, App\Models\User
            $table->unsignedBigInteger('transactionable_id')->nullable();

            // Status
            $table->enum('status', ['pending', 'completed', 'cancelled', 'failed'])->default('completed');

            // Tax Information (for future tax system)
            $table->decimal('tax_amount', 15, 2)->nullable()->default(0);
            $table->decimal('tax_rate', 5, 2)->nullable()->default(0);
            $table->string('tax_type')->nullable(); // VAT, GST, etc.

            // Metadata
            $table->json('metadata')->nullable(); // Additional data as JSON

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('admins')->onDelete('set null');

            $table->timestamps();

            // Indexes
            $table->index('transaction_date');
            $table->index('type');
            $table->index('status');
            $table->index(['transactionable_type', 'transactionable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
