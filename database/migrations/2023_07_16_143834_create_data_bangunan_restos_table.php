<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataBangunanRestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_bangunan_restos', function (Blueprint $table) {
            $table->id();
            $table->string("luas_bangunan")->default("0")->nullable();
            $table->string("lebar_bangunan")->default("0")->nullable();
            $table->string("luas_tanah")->default("0")->nullable();
            $table->string("lebar_tanah")->default("0")->nullable();
            $table->string("peruntukan_bangunan")->default("")->nullable();
            $table->string("jenis_sertifikat")->default("")->nullable();
            $table->string("masa_berlaku_sertifikat")->default("")->nullable();
            $table->string("ijin_domisili")->default("")->nullable();
            $table->string("is_bukti_pbb_available")->default("")->nullable();
            $table->string("bukti_pbb_tahun")->default("")->nullable();
            $table->string("bukti_pbb_photo_path")->default("")->nullable();
            $table->string("is_sewa")->default("")->nullable();
            $table->string("jangka_sewa")->default("")->nullable();
            $table->string("nama_pemilik_sertifikat")->default("")->nullable();
            $table->string("jenis_pemilik_sertifikat")->default("")->nullable();
            $table->string("kondisi_bangunan")->default("")->nullable();
            $table->string("fasilitas_listrik_watt")->default("")->nullable();
            $table->string("fasilitas_telp")->default("")->nullable();
            $table->string("fasilitas_air")->default("")->nullable();
            $table->string("jenis_jalan")->default("")->nullable();
            $table->string("rencana_pelebaran")->default("")->nullable();
            $table->string("penggantian_jenis_wilayah")->default("")->nullable();
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
        Schema::dropIfExists('data_bangunan_restos');
    }
}
