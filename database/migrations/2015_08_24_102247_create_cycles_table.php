<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyclesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cycles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code',10);
			$table->string('name',100);
			$table->string('month',3);
			$table->string('year',4);
			$table->date('start')->nullable();
			$table->date('end')->nullable();
			$table->string('description',100)->nullable();
			$table->boolean('active')->default(false);
			//$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cycles');
	}

}
