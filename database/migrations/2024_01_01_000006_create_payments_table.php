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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->onDelete('cascade');
            $table->string('payment_type')->comment('Type of payment (maintenance, utility, etc.)');
            $table->decimal('amount', 15, 2)->comment('Payment amount');
            $table->date('due_date')->comment('Payment due date');
            $table->date('paid_date')->nullable()->comment('Date payment was made');
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])
                ->default('pending')
                ->comment('Payment status');
            $table->string('receipt_number')->nullable()->comment('Receipt number');
            $table->text('description')->nullable()->comment('Payment description');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('house_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('payment_type');
            $table->index(['house_id', 'status']);
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};