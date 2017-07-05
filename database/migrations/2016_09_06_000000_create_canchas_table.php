<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanchasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canchas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50);
            $table->string('direccion',110);
            $table->string('telefono',50)->nullable();
            $table->string('celular',50);
            $table->string('imagen',255)->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('zona_id')->unsigned();
            $table->softDeletes(); // <-- This will add a deleted_at field
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
        Schema::drop('canchas');
    }
}
