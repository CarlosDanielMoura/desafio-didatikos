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
        Schema::create('product', function (Blueprint $table) {
            $table->id("COD_PRODUCT");
            $table->string("NAME_PRODUCT", 100)->nullable();
            $table->decimal("PRICE", 10, 2)->nullable();
            $table->unsignedBigInteger("BRAND_PRODUCT")->nullable();
            $table->decimal("STOCK", 8, 2)->nullable();
            $table->unsignedBigInteger("CITY")->nullable();
            $table->timestamps();

            $table->foreign('BRAND_PRODUCT')
                ->references('COD_BRAND')
                ->on('brand')
                ->onDelete('RESTRICT');

            $table->foreign('CITY')
                ->references('COD_CITY')
                ->on('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
