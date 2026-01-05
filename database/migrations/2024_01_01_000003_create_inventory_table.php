<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('product_id', 50);
            $table->integer('boxes')->default(0);
            $table->integer('loose_units')->default(0); // From opened boxes
            $table->integer('damaged_units')->default(0); // Track damaged items
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['location_id', 'product_id']);
            
            $table->index('location_id');
            $table->index('product_id');
            $table->index('last_updated');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};