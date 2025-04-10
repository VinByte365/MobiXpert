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
        Schema::create('order_lines', function (Blueprint $table) {
            $table->bigIncrements('order_line_id');
            $table->unsignedBigInteger('order_id')->index('order_lines_order_id_foreign');
            $table->unsignedBigInteger('product_id')->index('order_lines_product_id_foreign');
            $table->integer('quantity');
            $table->decimal('price', 10);
            $table->decimal('subtotal', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
