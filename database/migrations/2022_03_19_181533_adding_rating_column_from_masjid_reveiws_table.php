<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingRatingColumnFromMasjidReveiwsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masjid_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('rating_id')->after('user_id');
            $table->foreign('rating_id')->references('id')->on('ratings')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masjid_reviews', function (Blueprint $table) {
            //
        });
    }
}
