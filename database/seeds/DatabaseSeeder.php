<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        ini_set('memory_limit','10024M');

        Model::unguard();


        $this->call('CostCentersTableSeeder');

        $this->command->info('Cost Centers Table Seeded!');

        $this->call('BookAccountsTableSeeder');

        $this->command->info('Book Accounts Table Seeded!');

        $this->call('FileFormatsTableSeeder');

        $this->command->info('File Formats Table Seeded!');


        $this->call('ExpenseTypesTableSeeder');

        $this->command->info('Expense Types Table Seeded!');


        $this->call('RolesTableSeeder');

        $this->command->info('Roles Table Seeded!');

        $this->call('DivisionsTableSeeder');

        $this->command->info('Divisions Table Seeded!');

        $this->call('UsersTableSeeder');

        $this->command->info('Users Table Seeded!');

        $this->call('ExpensesTableSeeder');

        $this->command->info('Expenses Table Seeded!');

        $this->call('BuyOrdersTableSeeder');

        $this->command->info('Buy Orders Table Seeded!');





    }

}
