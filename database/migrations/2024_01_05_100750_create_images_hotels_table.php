<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images_hotels', function (Blueprint $table) {
            $table->increments('images_hotel_id');
            $table->string('images_hotel_file_name', 500);
            $table->unsignedInteger('hotel_id');
            $table->foreign('hotel_id')->references('hotel_id')->on('hotels');

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
        Schema::dropIfExists('images_hotels');
    }
};
