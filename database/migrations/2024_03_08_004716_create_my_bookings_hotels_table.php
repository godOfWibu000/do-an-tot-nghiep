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
        Schema::create('my_bookings_hotels', function (Blueprint $table) {
            $table->unsignedInteger('my_booking_id');
            $table->foreign('my_booking_id')->references('my_booking_id')->on('my_bookings');
            $table->unsignedInteger('rooms_hotels_id');
            $table->foreign('rooms_hotels_id')->references('rooms_hotels_id')->on('rooms_hotels');
            $table->integer('my_bookings_hotel_rooms_number');

            $table->primary(['my_booking_id', 'rooms_hotels_id']);
            $table->unique(['my_booking_id', 'rooms_hotels_id']);
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
        Schema::dropIfExists('my_bookings_hotels');
    }
};
