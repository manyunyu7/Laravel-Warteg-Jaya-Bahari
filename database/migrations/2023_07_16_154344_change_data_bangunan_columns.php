<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataBangunanColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_bangunan_restos', function (Blueprint $table) {
            $table->renameColumn('lebar_bangunan', 'panjang_bangunan');
            $table->renameColumn('lebar_tanah', 'panjang_tanah');
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
            $table->renameColumn('panjang_bangunan', 'lebar_bangunan');
            $table->renameColumn('panjang_tanah', 'lebar_tanah');
        });
    }
}
