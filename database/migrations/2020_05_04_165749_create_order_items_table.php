<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('img_src')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('order_id');
            $table->integer('price');
            $table->integer('quantity')->default(1);
            $table->integer('total');
            $table->string('sku')->nullable();
            $table->smallInteger('is_inventory')->default(1);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::dropIfExists('order_items');
    }
};
