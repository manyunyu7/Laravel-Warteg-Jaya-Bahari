<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestoIdOnOrderHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_histories', function (Blueprint $table) {
            $table->foreignId('resto_id')->after('user_id')->constrained('restorans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_histories', function (Blueprint $table) {
            //
        });
    }
}
