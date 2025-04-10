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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->unsignedBigInteger('brand_id')->nullable()->index('products_brand_id_foreign');
            $table->unsignedBigInteger('price_range_id')->nullable()->index('products_price_range_id_foreign');
            $table->softDeletes();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10);
            $table->string('image_path');
            $table->integer('stock_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
