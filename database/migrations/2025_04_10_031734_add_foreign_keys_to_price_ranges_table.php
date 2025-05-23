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
        Schema::table('price_ranges', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['product_id'])->on('products')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_ranges', function (Blueprint $table) {
            $table->dropForeign('price_ranges_product_id_foreign');
        });
    }
};
