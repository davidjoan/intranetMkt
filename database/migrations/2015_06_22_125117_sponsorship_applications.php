<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SponsorshipApplications extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('sponsorship_applications', function(Blueprint $table)
        {

            //common in format 2 and format 4
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('file_format_id',false, true)->nullable();
            $table->integer('expense_id',false, true)->nullable();
            $table->boolean('is_national')->default(true);

            //for format 2
            $table->string('justification',200)->nullable();
            $table->string('event_type',200)->nullable();
            $table->string('products',500)->nullable();
            $table->string('location',500)->nullable();
            $table->string('place',500)->nullable();
            $table->integer('quantity_participant')->nullable();
            $table->integer('quantity_doctors')->nullable();
            $table->integer('quantity_employees')->nullable();
            $table->string('event_directed_to',500)->nullable();

            $table->boolean('is_seller')->default(false);
            $table->string('decision_is_in',1)->nullable();

            $table->dateTime('event_date')->nullable();
            $table->dateTime('request_date')->nullable();

            $table->string('member_of_social_medicine_id',100)->nullable();
            $table->string('academic_responsibility',100)->nullable();

            $table->boolean('inscription')->default(false);
            $table->boolean('hotel')->default(false);
            $table->boolean('transport')->default(false);
            $table->boolean('food')->default(false);
            $table->boolean('he_was_sponsored')->default(false);

            $table->string('name_last_event',200)->nullable();
            $table->string('location_last_event',200)->nullable();


            $table->boolean('question_1')->default(false);
            $table->boolean('question_2')->default(false);
            $table->boolean('question_3')->default(false);
            $table->boolean('question_4')->default(false);
            $table->boolean('question_5')->default(false);
            $table->boolean('question_6')->default(false);
            $table->boolean('question_7')->default(false);
            $table->boolean('question_8')->default(false);
            $table->boolean('question_9')->default(false);

            $table->decimal('expense_1',12,2)->default(0.0)->nullable();
            $table->decimal('expense_2',12,2)->default(0.0)->nullable();
            $table->decimal('expense_3',12,2)->default(0.0)->nullable();
            $table->decimal('expense_4',12,2)->default(0.0)->nullable();
            $table->decimal('expense_5',12,2)->default(0.0)->nullable();
            $table->decimal('expense_6',12,2)->default(0.0)->nullable();
            $table->decimal('expense_7',12,2)->default(0.0)->nullable();
            $table->decimal('expense_8',12,2)->default(0.0)->nullable();
            $table->decimal('expense_9',12,2)->default(0.0)->nullable();
            $table->decimal('expense_10',12,2)->default(0.0)->nullable();
            $table->decimal('expense_11',12,2)->default(0.0)->nullable();
            $table->decimal('expense_12',12,2)->default(0.0)->nullable();

            $table->date('start')->nullable();
            $table->date('end')->nullable();

            $table->date('fly_start')->nullable();
            $table->date('fly_end')->nullable();
            $table->string('ticket_fly_start',200)->nullable();
            $table->string('ticket_fly_end',200)->nullable();

            $table->string('hotel_name',200)->nullable();
            $table->string('hotel_address',200)->nullable();
            $table->string('hotel_location',200)->nullable();
            $table->string('hotel_description',200)->nullable();

            $table->string('description',500)->nullable();
            //$table->timestamps();

            $table->foreign('file_format_id')->references('id')->on('file_formats');
            $table->foreign('expense_id')->references('id')->on('expenses');

        });

        Schema::create('assistants', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('sponsorship_application_id',false, true)->nullable();
            $table->integer('expense_id',false, true)->nullable();
            $table->string('name',100)->nullable();
            $table->string('role',100)->nullable();
            $table->string('specialty',100)->nullable();
            $table->boolean('is_government_employee')->default(false);
            $table->string('hospital',200)->nullable();
            $table->string('city_of_residence',200)->nullable();
            $table->string('phone',200)->nullable();
            $table->string('email',200)->nullable();

            $table->string('description',500)->nullable();
            //$table->timestamps();

            $table->foreign('sponsorship_application_id')->references('id')->on('sponsorship_applications');
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
        Schema::drop('assistants');
        Schema::drop('sponsorship_applications');
	}

}
