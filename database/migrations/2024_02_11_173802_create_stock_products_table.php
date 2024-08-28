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
        Schema::create('stock_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('category_original_name')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->integer('price');
            $table->integer('price_approved')->default(0);
            $table->unsignedBigInteger('price_approved_by')->nullable();
            $table->string('initial_stock_taker')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->string('city_name')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->integer('stock_quantity')->default(1);
            $table->string('sku')->unique()->nullable();
            $table->datetime('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->constrained();
            $table->foreign('uploaded_by')->references('id')->on('users')->constrained();
            $table->foreign('price_approved_by')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_products', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropForeign(['location_id']);
            $table->dropForeign(['price_approved_by']);
        });
        Schema::dropIfExists('stock_products');
    }
};
