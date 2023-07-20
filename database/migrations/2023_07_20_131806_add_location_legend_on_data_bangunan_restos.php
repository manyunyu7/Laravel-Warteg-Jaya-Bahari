<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationLegendOnDataBangunanRestos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_bangunan_restos', function (Blueprint $table) {
            $table->string('is_alfamart_100_exist')->nullable();
            $table->string('is_indomaret_100_exist')->nullable();
            $table->string('is_spbu_100_exist')->nullable();
            $table->string('is_univ_100_exist')->nullable();
            $table->string('is_counter_usaha_lain_100_exist')->nullable();
            $table->string('is_masjid_100_exist')->nullable();
            $table->string('is_gereja_100_exist')->nullable();
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
            $table->dropColumn('is_alfamart_100_exist');
            $table->dropColumn('is_indomaret_100_exist');
            $table->dropColumn('is_spbu_100_exist');
            $table->dropColumn('is_univ_100_exist');
            $table->dropColumn('is_counter_usaha_lain_100_exist');
            $table->dropColumn('is_masjid_100_exist');
            $table->dropColumn('resto_id');
            $table->dropColumn('is_gereja_100_exist');
        });
    }
}
