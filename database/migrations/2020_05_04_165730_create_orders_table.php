<?php

use App\Enums\OrderSource;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('total')->default(0);
            $table->integer('balance')->default(0);
            $table->boolean('refunded')->default(false);
            $table->enum('source', array_map(fn ($status) => $status->value, OrderSource::cases()))->default(OrderSource::ONLINE);
            $table->unsignedBigInteger('order_id')->nullable(); //woocommerce
            $table->unsignedBigInteger('agentx_order_id')->nullable(); //agentx
            $table->string('agent_id')->nullable(); //AgentX id ie. user_id
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('orders');
    }
};
