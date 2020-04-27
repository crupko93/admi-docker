<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRentalAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_rental_additional_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_rental_id');
            $table->foreign('booking_rental_id')->references('id')->on('booking_rentals');

            $table->string('type');
            $table->integer('status');
            $table->decimal('price');
            $table->string('currency', 3);

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
        Schema::dropIfExists('booking_rental_additional_services');
    }
}
