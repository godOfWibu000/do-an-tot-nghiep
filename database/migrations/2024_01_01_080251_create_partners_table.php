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
        Schema::create('partners', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->text('name');
            $table->text('address');
            $table->string('phone_number', 20);
            $table->string('logo', 500)->nullable();
            $table->text('company_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone_number', 20)->nullable();
            $table->string('company_email', 255)->nullable();
            $table->string('company_website', 255)->nullable();
            $table->boolean('partner_status')->default(0);
            
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
        Schema::dropIfExists('partners');
    }
};
