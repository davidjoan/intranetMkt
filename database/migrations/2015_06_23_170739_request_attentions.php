<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequestAttentions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('request_attentions', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('file_format_id',false, true)->nullable();
            $table->integer('expense_id',false, true)->nullable();
            $table->string('promotora',200)->nullable();
            $table->string('description',500)->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('client_code',200)->nullable();
            $table->string('client',200)->nullable();
            $table->decimal('price_unit',12,2)->default(0.0)->nullable();
            $table->integer('quantity');
            $table->decimal('estimated_value',12,2)->default(0.0)->nullable();

            $table->string('reason',500)->nullable();
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
        Schema::drop('request_attentions');
	}

}
