<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBengkelOnDataBangunanRestos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_bangunan_restos', function (Blueprint $table) {
            $table->string('is_bengkel_100_exist')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_bangunan_restos', function (Blueprint $table) {
            $table->dropColumn('is_bengkel_100_exist');
        });
    }
}
