<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->unsignedBigInteger('address_id',false)->nullable();
            $table->foreign('address_id')->references('id')->on('addresses');

            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->boolean('is_company');
            $table->string('company_name', 200);
            $table->string('vat', 200);
            $table->string('email', 200);
            $table->string('phone', 200);

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
        Schema::dropIfExists('booking_billings');
    }
}
