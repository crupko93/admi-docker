<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRentalCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_rental_cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_rental_id');
            $table->foreign('booking_rental_id')->references('id')->on('booking_rentals');

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
        Schema::dropIfExists('booking_rental_cars');
    }
}
