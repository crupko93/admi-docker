<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingFlightMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_flight_metadata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_flight_id');
            $table->foreign('booking_flight_id')->references('id')->on('booking_flights');

            $table->string('type');
            $table->string('value');

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
        Schema::dropIfExists('booking_flight_metadata');
    }
}
