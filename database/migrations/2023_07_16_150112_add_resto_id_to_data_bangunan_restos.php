<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestoIdToDataBangunanRestos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_bangunan_restos', function (Blueprint $table) {
            $table->unsignedBigInteger('resto_id')->after('id'); // Add the resto_id column
            $table->foreign('resto_id')->references('id')->on('restorans'); // Add the foreign key constraint
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
            $table->dropForeign(['resto_id']);
            $table->dropColumn('resto_id');
        });
    }
}
