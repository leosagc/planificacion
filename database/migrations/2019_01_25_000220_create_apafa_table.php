<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApafaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apafa', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('period')->nullable();
            $table->integer('number')->nullable();
            $table->string('folder')->nullable();
            $table->string('binder')->nullable();
            $table->integer('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('school');
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
        Schema::dropIfExists('apafa');
    }
}
