<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelurahanKecamatanToRestorans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restorans', function (Blueprint $table) {
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restorans', function (Blueprint $table) {
            $table->dropColumn('kelurahan');
            $table->dropColumn('kecamatan');
        });
    }
}
