<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('cost_centers', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code',10)->unique();
            $table->string('name',100);
            $table->string('description')->nullable();
            $table->timestamps();
        });


        Schema::create('roles', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code',10)->unique();
            $table->string('name',100);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('divisions', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code',20)->nullable();
            $table->string('name',100);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('role_id',false, true);
            $table->integer('cost_center_id',false, true)->nullable();
            $table->integer('supervisor_id',false, true)->nullable();
            $table->string('code',100)->nullable();
            $table->string('name',200);
            $table->string('email',100)->nullable();
            $table->string('phone',100)->nullable();
            $table->string('username',50);
            $table->string('password', 255);
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('supervisor_id')->references('id')->on('users');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers');

        });


        Schema::create('division_user', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->integer('division_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
        });




	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('user_division');
		Schema::drop('users');
        Schema::drop('divisions');
        Schema::drop('roles');
        Schema::drop('cost_centers');
	}

}
