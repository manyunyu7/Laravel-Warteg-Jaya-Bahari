<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaRukoToDataBangunan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_bangunan_restos', function (Blueprint $table) {
            $table->string("harga_ruko")->default("")->nullable();
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
            // Drop the columns
            $table->dropColumn('harga_ruko');
        });
    }
}
