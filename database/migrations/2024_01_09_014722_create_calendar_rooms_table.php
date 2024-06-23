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
        Schema::create('calendar_rooms', function (Blueprint $table) {
            $table->date('calendar_room_date');
            $table->unsignedInteger('room_id');
            
            $table->foreign('room_id')->references('room_id')->on('rooms');
            $table->primary(['calendar_room_date', 'room_id']);
            $table->unique(['calendar_room_date', 'room_id']);
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
        Schema::dropIfExists('calendar_rooms');
    }
};
