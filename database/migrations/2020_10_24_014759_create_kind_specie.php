<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKindSpecie extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kind_specie', function (Blueprint $table) {
            $table->unsignedBigInteger('kind_id');
            $table->unsignedBigInteger('specie_id');
 
            $table->foreign('kind_id')->references('id')->on('kinds');
            $table->foreign('specie_id')->references('id')->on('species');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kind_specie');
    }
}
