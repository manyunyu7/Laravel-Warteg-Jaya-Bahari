<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataBangunanRestosTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_bangunan_restos', function (Blueprint $table) {
            $table->string("jumlah_lantai")->default("")->nullable();
            $table->string("parkir_motor")->default("")->nullable();
            $table->string("parkir_mobil")->default("")->nullable();
            $table->string("harga_sewa")->default("")->nullable();
            $table->string("bisa_dimajukan")->default("")->nullable();
            $table->string("saluran_air")->default("")->nullable();
            $table->string("5_menit_mobil")->default("")->nullable();
            $table->string("5_menit_motor")->default("")->nullable();
            $table->string("5_menit_truk")->default("")->nullable();
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
            $table->dropColumn('jumlah_lantai');
            $table->dropColumn('parkir_motor');
            $table->dropColumn('parkir_mobil');
            $table->dropColumn('harga_sewa');
            $table->dropColumn('bisa_dimajukan');
            $table->dropColumn('saluran_air');
            $table->dropColumn('5_menit_mobil');
            $table->dropColumn('5_menit_motor');
            $table->dropColumn('5_menit_truk');
        });
    }
}
