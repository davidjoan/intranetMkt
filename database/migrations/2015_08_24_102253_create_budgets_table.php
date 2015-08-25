<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budgets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('division_id',false, true)->nullable();
			$table->integer('cost_center_id',false, true)->nullable();
			$table->integer('book_account_id',false, true)->nullable();
			$table->integer('cycle_id',false, true)->nullable();
			$table->integer('user_id',false, true)->nullable();
			$table->decimal('amount',12,2)->default(0.0)->nullable();
			$table->timestamps();

			$table->foreign('division_id')->references('id')->on('divisions');
			$table->foreign('cost_center_id')->references('id')->on('cost_centers');
			$table->foreign('book_account_id')->references('id')->on('book_accounts');
			$table->foreign('cycle_id')->references('id')->on('cycles');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('budgets');
	}

}
