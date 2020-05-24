<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstraintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraint', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name',50);
            $table->bigInteger('value');
            $table->unsignedBigInteger('pigeon_id');
            $table->foreign('pigeon_id')->references('id')->on('pigeon');
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
        Schema::dropIfExists('constraint');
    }
}
