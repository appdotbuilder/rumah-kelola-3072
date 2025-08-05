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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('block_number')->comment('Block/Unit number');
            $table->text('address')->comment('House address');
            $table->string('house_type')->comment('Type of house');
            $table->decimal('land_area', 8, 2)->comment('Land area in square meters');
            $table->decimal('building_area', 8, 2)->comment('Building area in square meters');
            $table->enum('status', ['available', 'sold', 'reserved', 'maintenance'])
                ->default('available')
                ->comment('House status');
            $table->string('owner_name')->nullable()->comment('Owner/resident name');
            $table->string('owner_phone')->nullable()->comment('Owner/resident phone');
            $table->date('handover_date')->nullable()->comment('Date of house handover');
            $table->decimal('selling_price', 15, 2)->nullable()->comment('House selling price');
            $table->integer('bedrooms')->comment('Number of bedrooms');
            $table->integer('bathrooms')->comment('Number of bathrooms');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('block_number');
            $table->index('status');
            $table->index('house_type');
            $table->index(['status', 'house_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};