<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestoranOperatingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restoran_operating_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restorans_id')->constrained('restorans');
            $table->string('day');
            $table->string('hour');
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
        Schema::dropIfExists('restoran_operating_hours');
    }
}
