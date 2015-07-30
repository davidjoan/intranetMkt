<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookAccounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        Schema::create('file_formats', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code',10)->unique();
            $table->string('name',100);
            $table->string('file',200)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });


        Schema::create('book_accounts', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code',10)->unique();
            $table->string('name',100);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_types', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('book_account_id',false, true)->nullable();
            $table->string('code',10)->unique();
            $table->string('name',100);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('book_account_id')->references('id')->on('book_accounts');
        });


        Schema::create('expense_type_file_format', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->integer('expense_type_id')->unsigned();
            $table->integer('file_format_id')->unsigned();

            $table->foreign('expense_type_id')->references('id')->on('expense_types')->onDelete('cascade');
            $table->foreign('file_format_id')->references('id')->on('file_formats')->onDelete('cascade');
        });

        Schema::create('expenses', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('expense_type_id',false, true)->nullable();
            $table->integer('user_id',false, true)->nullable();
            $table->integer('division_id',false, true)->nullable();
            $table->dateTime('application_date')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('code',20)->unique();
            $table->string('name',100);
            $table->string('description')->nullable();
            $table->string('place')->nullable();
            $table->boolean('approval_1')->default(false);
            $table->boolean('approval_2')->default(false);
            $table->boolean('approval_3')->default(false);
            $table->boolean('approval_4')->default(false);
            $table->boolean('approval_5')->default(false);
            $table->boolean('approval_6')->default(false);
            $table->boolean('approval_7')->default(false);
            $table->boolean('approval_8')->default(false);
            $table->boolean('approval_9')->default(false);
            $table->decimal('total_amount',12,2)->default(0.0)->nullable();
            $table->decimal('estimated_amount',12,2)->default(0.0)->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('expense_type_id')->references('id')->on('expense_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('division_id')->references('id')->on('divisions');
        });

        Schema::create('expense_amounts', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('expense_id')->unsigned();
            $table->integer('cost_center_id')->unsigned();
            $table->decimal('percent',12,2)->default(0.0)->nullable();
            $table->timestamps();

            $table->unique(array('expense_id','cost_center_id'));

            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('cascade');
        });

        Schema::create('buy_orders', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('file_format_id',false, true)->nullable();
            $table->string('cost_center',200)->nullable();
            $table->string('book_account',200)->nullable();
            $table->integer('expense_id',false, true)->nullable();
            $table->string('code',20)->unique();
            $table->dateTime('delivery_date')->nullable();
            $table->boolean('inventory')->default(false);
            $table->boolean('active')->default(false);
            $table->boolean('expenditure')->default(false);
            $table->integer('quantity');
            $table->decimal('price_unit',12,2)->default(0.0)->nullable();
            $table->decimal('estimated_value',12,2)->default(0.0)->nullable();
            $table->string('description',500)->nullable();
            $table->string('destination',500)->nullable();
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
        Schema::drop('expenses');
        Schema::drop('expense_type_file_format');
        Schema::drop('file_formats');
        Schema::drop('book_accounts');
        Schema::drop('expense_types');
        Schema::drop('buy_orders');
	}

}
