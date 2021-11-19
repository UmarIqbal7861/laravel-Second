<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriends extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->id('f_id');
            $table->bigInteger('user1')->unsigned();
            $table->bigInteger('user2')->unsigned();
            $table->timestamps();
            $table->foreign('user1')->references('U_id')->on('users');
            $table->foreign('user2')->references('U_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
}
