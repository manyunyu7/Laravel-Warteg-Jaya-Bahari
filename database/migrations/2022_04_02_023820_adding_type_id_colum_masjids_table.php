<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingTypeIdColumMasjidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masjids', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->after('name');
            $table->foreign('type_id')->references('id')->on('masjid_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masjids', function (Blueprint $table) {
            //
        });
    }
}
