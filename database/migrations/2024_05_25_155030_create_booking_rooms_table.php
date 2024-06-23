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
        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->unsignedInteger('my_booking_id');
            $table->foreign('my_booking_id')->references('my_booking_id')->on('my_bookings');
            $table->unsignedInteger('room_id');
            $table->foreign('room_id')->references('room_id')->on('rooms');
            $table->boolean('booking_room_status')->default(0);

            $table->primary(['my_booking_id', 'room_id']);
            $table->unique(['my_booking_id', 'room_id']);
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
        Schema::dropIfExists('booking_rooms');
    }
};
