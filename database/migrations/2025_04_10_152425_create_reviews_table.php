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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('order_id');
            $table->integer('rating')->comment('Rating from 1-5');
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};