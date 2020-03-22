<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLivestockDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livestock_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nfc_uuid');
            $table->integer('temperature');
            $table->integer('humidity');
            $table->integer('pulse_rate');
            $table->string('loc_latitude');
            $table->string('loc_longitude');
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
        Schema::dropIfExists('livestock_datas');
    }
}
