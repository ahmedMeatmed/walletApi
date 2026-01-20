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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['charge', 'transfer', 'service_purchase']);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->decimal('amount', 15, 2);
            $table->foreignId('reference_id')->nullable(); 
            $table->string('reference_type')->nullable();  
            $table->timestamps();

            $table->index(['from_user_id', 'to_user_id']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
