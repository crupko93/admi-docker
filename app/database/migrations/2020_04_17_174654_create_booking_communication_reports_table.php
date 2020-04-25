<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingCommunicationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_communication_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_communication_id');
            $table->foreign('booking_communication_id')->references('id')->on('booking_communications');
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
        Schema::dropIfExists('booking_communication_reports');
    }
}
