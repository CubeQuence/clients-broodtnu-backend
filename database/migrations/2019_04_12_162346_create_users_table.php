<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('active')->default(false); // Will implement email verification later.

            $table->string('authentication_method')->default('email'); // Will implement social login later.
            $table->string('email')->unique();
            $table->string('password')/*->nullable()*/;

            $table->string('address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}
