<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('product_id', 50);
            $table->text('description');
            $table->enum('status', ['pending', 'resolved', 'supplier_notified', 'closed'])->default('pending');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('reported_at')->useCurrent();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('replacement_given')->default(false);
            $table->integer('damaged_units')->default(1);
            $table->integer('box_opened')->default(0); // Track which box was opened
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->index('status');
            $table->index('location_id');
            $table->index('reported_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
