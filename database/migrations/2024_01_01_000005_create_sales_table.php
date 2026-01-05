<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('product_id', 50);
            $table->integer('quantity'); // Number of boxes sold
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->foreignId('sold_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('sold_at')->useCurrent();
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('receipt_number', 50)->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->index('location_id');
            $table->index('sold_at');
            $table->index('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
