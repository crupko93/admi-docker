<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_announcements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id',false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('announcement_id',false);
            $table->foreign('announcement_id')->references('id')->on('announcements');

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
        Schema::dropIfExists('user_announcements');
    }
}
