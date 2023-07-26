<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoYoutubeLinkToRestaurants extends Migration
{
    public function up()
    {
        Schema::table('restorans', function (Blueprint $table) {
            $table->string('video_youtube_link')->nullable()->after('address'); // Adjust 'column_name' to the column where you want the new column to be placed
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
            $table->dropColumn('video_youtube_link');
        });
    }
}
