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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('items')->onDelete('cascade');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->string('transaction_method')->nullable();
            $table->integer('rating')->nullable()->comment('Rating from 1 to 5');
            $table->text('review')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
