<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRentalDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_rental_drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_rental_id');
            $table->foreign('booking_rental_id')->references('id')->on('booking_rentals');

            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_birth');
            $table->string('country', 2);

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
        Schema::dropIfExists('booking_rental_drivers');
    }
}
