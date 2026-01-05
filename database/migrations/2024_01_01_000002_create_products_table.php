<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // BOX001, BOX002, etc.
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->integer('units_per_box');
            $table->decimal('purchase_price', 10, 2)->nullable(); // Hidden from shop managers
            $table->decimal('selling_price', 10, 2);
            $table->integer('min_stock_level')->default(5);
            $table->string('supplier_name', 100)->nullable();
            $table->string('supplier_phone', 20)->nullable();
            $table->string('barcode', 100)->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_active');
            $table->index('barcode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
