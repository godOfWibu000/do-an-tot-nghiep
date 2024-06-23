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
        Schema::create('my_bookings', function (Blueprint $table) {
            $table->increments('my_booking_id');
            $table->double('my_booking_pay');
            $table->text('my_booking_status')->default('Chờ xác nhận');
            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('users');
            $table->unsignedInteger('hotel_id');
            $table->foreign('hotel_id')->references('hotel_id')->on('hotels');
            $table->date('my_booking_date_success')->nullable();
            $table->date('my_booking_checkin');
            $table->date('my_booking_checkout');
            $table->integer('my_booking_day');
            
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
        Schema::dropIfExists('my_bookings');
    }
};
