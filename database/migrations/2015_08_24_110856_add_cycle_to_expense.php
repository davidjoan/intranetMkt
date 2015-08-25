<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCycleToExpense extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('expenses', function(Blueprint $table)
		{
            $table->integer('cycle_id',false, true)->nullable();
            $table->foreign('cycle_id')->references('id')->on('cycles');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('expenses', function(Blueprint $table)
		{
			//
		});
	}

}
