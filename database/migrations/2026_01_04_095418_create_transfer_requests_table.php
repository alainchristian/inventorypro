<?php

// FILE: database/migrations/2024_01_01_000010_create_transfer_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create parent transfer requests table
        Schema::create('transfer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 20)->unique(); // REQ-2024-00001
            $table->foreignId('from_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('to_location_id')->constrained('locations')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'in_transit', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('requested_at')->useCurrent();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('request_number');
            $table->index('requested_at');
        });

        // Modify transfers table to link to parent request
        Schema::table('transfers', function (Blueprint $table) {
            $table->foreignId('transfer_request_id')->nullable()->after('id')->constrained('transfer_requests')->onDelete('cascade');
            $table->index('transfer_request_id');
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['transfer_request_id']);
            $table->dropColumn('transfer_request_id');
        });
        
        Schema::dropIfExists('transfer_requests');
    }
};