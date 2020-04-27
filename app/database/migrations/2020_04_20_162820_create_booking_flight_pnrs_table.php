<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingFlightPnrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_flight_pnrs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_flight_id');
            $table->foreign('booking_flight_id')->references('id')->on('booking_flights');

            $table->string('booking_code', 10);
            $table->string('pnr', 20);
            $table->integer('status');
            $table->string('ticket_code', 200);

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
        Schema::dropIfExists('booking_flight_pnrs');
    }
}
