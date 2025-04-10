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
        Schema::table('order_lines', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['order_id'])->on('orders')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['product_id'])->on('products')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_lines', function (Blueprint $table) {
            $table->dropForeign('order_lines_order_id_foreign');
            $table->dropForeign('order_lines_product_id_foreign');
        });
    }
};
