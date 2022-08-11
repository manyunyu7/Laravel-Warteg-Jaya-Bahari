<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('resto_id')->constrained('restorans');
            $table->foreignId('driver_id')->constrained('drivers');
            $table->json('orders');
            $table->string('address');
            $table->longtext('user_sign')->nullable();
            $table->string('lat');
            $table->string('long');
            $table->foreignId('status_id')->constrained('order_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_carts');
    }
}
