<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_repair', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('repair_id');
            $table->bigInteger('imei');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete('cascade')->onUpdate('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->nullOnDelete('cascade')->onUpdate('cascade');
            $table->foreign('repair_id')->references('id')->on('repairs')->nullOnDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_repair');
    }
};
