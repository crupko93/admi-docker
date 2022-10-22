<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingFlightPassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_flight_passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_flight_id');
            $table->foreign('booking_flight_id')->references('id')->on('booking_flights');

            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_birth');
            $table->string('gender', 1);
            $table->string('country', 2);
            $table->string('document_type', 30);
            $table->string('number', 50);
            $table->date('date_emitting');
            $table->date('date_expiring');

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
        Schema::dropIfExists('booking_flight_passengers');
    }
}
