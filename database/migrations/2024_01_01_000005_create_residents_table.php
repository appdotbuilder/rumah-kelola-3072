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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name')->comment('Resident name');
            $table->string('email')->nullable()->comment('Resident email');
            $table->string('phone')->comment('Resident phone number');
            $table->string('id_number')->nullable()->comment('ID card number');
            $table->enum('relationship', ['owner', 'tenant', 'family_member'])
                ->default('owner')
                ->comment('Relationship to house');
            $table->date('move_in_date')->nullable()->comment('Date moved in');
            $table->date('move_out_date')->nullable()->comment('Date moved out');
            $table->boolean('is_active')->default(true)->comment('Is currently living here');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index('house_id');
            $table->index('relationship');
            $table->index('is_active');
            $table->index(['house_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};