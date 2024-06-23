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
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('hotel_id');
            $table->text('hotel_name');
            $table->integer('hotel_star');
            $table->double('hotel_rate_point', 3, 1)->default(0);
            $table->integer('hotel_number_rate')->default(0);
            $table->text('hotel_address');
            $table->double('hotel_old_price', 10, 2)->nullable();
            $table->double('hotel_new_price', 10, 2);
            $table->string('hotel_thumbnail', 500);
            $table->text('hotel_description');
            $table->boolean('hotel_status')->default(0);

            $table->text('hotel_area');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->unsignedInteger('child_area_id');
            $table->foreign('child_area_id')->references('child_area_id')->on('child_areas');
            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('users');

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
        Schema::dropIfExists('hotels');
    }
};
