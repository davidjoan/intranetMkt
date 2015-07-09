<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Entertainments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('entertainments', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('file_format_id',false, true)->nullable();
            $table->integer('expense_id',false, true)->nullable();
            $table->string('consultor',200)->nullable();
            $table->string('entertainment_type',20)->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('place',200)->nullable();
            $table->integer('qty_doctors',false, true)->nullable();
            $table->decimal('estimated_value',12,2)->default(0.0)->nullable();
            $table->string('description',500)->nullable();
            $table->string('status',200)->nullable();
            $table->timestamps();

            $table->foreign('file_format_id')->references('id')->on('file_formats');
            $table->foreign('expense_id')->references('id')->on('expenses');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('entertainments');
	}

}
