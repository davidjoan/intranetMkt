<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expense_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_format_id',false, true)->nullable();
			$table->integer('expense_id',false, true)->nullable();
			$table->string('filename');
			$table->string('mime');
			$table->string('original_filename');
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
		Schema::drop('expense_details');
	}

}
