<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestoransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restorans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('type_food_id')->constrained('type_food');
            $table->foreignId('certification_id')->constrained('certifications');
            $table->longText('description');
            $table->longtext('address');
            $table->string('operating_hour');
            $table->string('phone_number');
            $table->string('lat');
            $table->string('long');
            $table->longText('image');
            $table->boolean('is_visible');
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
        Schema::dropIfExists('restorans');
    }
}
