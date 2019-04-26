<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->integer('district_id')->unsigned()->nullable();
            $table->foreign('district_id')->references('id')->on('district');
            $table->integer('school_type_id')->unsigned();
            $table->foreign('school_type_id')->references('id')->on('school_type');
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
        Schema::dropIfExists('school');
    }
}
