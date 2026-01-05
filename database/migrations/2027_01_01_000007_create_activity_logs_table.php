<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['owner', 'warehouse_manager', 'shop_manager', 'salesperson'])->default('salesperson')->after('email');
            $table->foreignId('location_id')->nullable()->after('role')->constrained('locations')->onDelete('set null');
            $table->boolean('is_active')->default(true)->after('location_id');
            $table->string('phone', 20)->nullable()->after('is_active');
            
            $table->index('role');
            $table->index('location_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn(['role', 'location_id', 'is_active', 'phone']);
        });
    }
};
