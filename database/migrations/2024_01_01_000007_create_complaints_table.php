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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users');
            $table->string('title')->comment('Complaint title');
            $table->text('description')->comment('Detailed complaint description');
            $table->enum('category', ['maintenance', 'security', 'facility', 'neighbor', 'other'])
                ->comment('Complaint category');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])
                ->default('medium')
                ->comment('Complaint priority');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'cancelled'])
                ->default('open')
                ->comment('Complaint status');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('response')->nullable()->comment('Response from management');
            $table->date('target_resolution_date')->nullable()->comment('Target date for resolution');
            $table->date('resolved_date')->nullable()->comment('Date complaint was resolved');
            $table->decimal('estimated_cost', 15, 2)->nullable()->comment('Estimated cost for repair/fix');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index('house_id');
            $table->index('status');
            $table->index('category');
            $table->index('priority');
            $table->index('reported_by');
            $table->index('assigned_to');
            $table->index(['status', 'priority']);
            $table->index(['house_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};