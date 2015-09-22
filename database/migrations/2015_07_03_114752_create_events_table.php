<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('file_format_id',false, true)->nullable();
            $table->integer('expense_id',false, true)->nullable();
            $table->integer('service_type_id',false, true)->nullable();
            $table->string('companion',200)->nullable();
            $table->string('place',500)->nullable();
            $table->string('company',200)->nullable();
            $table->integer('quantity_services')->nullable();
            $table->string('name',500)->nullable();
            $table->string('code',500)->nullable();
            $table->string('description',500)->nullable();

			//$table->timestamps();


            $table->foreign('file_format_id')->references('id')->on('file_formats');
            $table->foreign('expense_id')->references('id')->on('expenses');
            $table->foreign('service_type_id')->references('id')->on('service_types');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}
