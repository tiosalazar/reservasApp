<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres',45);
            $table->string('apellidos',45);
            $table->string('celular',20)->nullable();
            $table->string('fijo',20)->nullable();
            $table->string('genero',1)->nullable();
            $table->string('email',105)->unique();
            $table->string('api_token', 60)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->integer('roles_id')->unsigned()->default(4);
            $table->integer('pais_id')->unsigned()->default(1);
            $table->integer('ciudad_id')->unsigned()->nullable();
            $table->tinyInteger('notifications_push')->default(1);
            $table->tinyInteger('notifications_email')->default(1);
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
        Schema::drop('users');
    }
}
