<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class ExpensesTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('expenses')->delete();

        \IntranetMkt\Models\Expense::create(
            array(
                'expense_type_id' =>  1,
                'cycle_id' => 1,
                'user_id' => 1,
                'division_id' => 1,
                'application_date' => '2015-06-20 12:00:00',
                'code' => '2015000001',
                'name' => 'Congreso Mediciana General Lima 2015',
                'description' => 'Congreso Mediciana General Lima 2015 aspuciado por Lafrancol',
                'approval_1' => 0,
                'approval_2' => 0,
                'approval_3' => 0,
                'approval_4' => 0,
                'approval_5' => 0,
                'approval_6' => 0,
                'approval_7' => 0,
                'approval_8' => 0,
                'approval_9' => 0,
                'total_amount' => 1200.3,
                'active' => 1
                ));


        \IntranetMkt\Models\Expense::create(
            array(
                'expense_type_id' =>  1,
                'cycle_id' => 1,
                'user_id' => 1,
                'division_id' => 1,
                'application_date' => '2015-06-20 12:00:00',
                'code' => '2015000002',
                'name' => 'Congreso otorrino Lima 2015',
                'description' => 'Congreso Mediciana General Lima 2015 aspuciado por Lafrancol',
                'approval_1' => 0,
                'approval_2' => 0,
                'approval_3' => 0,
                'approval_4' => 0,
                'approval_5' => 0,
                'approval_6' => 0,
                'approval_7' => 0,
                'approval_8' => 0,
                'approval_9' => 0,
                'total_amount' => 1200.3,
                'active' => 1
            ));

        \IntranetMkt\Models\Expense::create(
            array(
                'expense_type_id' =>  1,
                'cycle_id' => 1,
                'user_id' => 1,
                'division_id' => 1,
                'application_date' => '2015-08-20 12:00:00',
                'code' => '2015000003',
                'name' => 'Congreso Cardio Lima 2015',
                'description' => 'Congreso Cardio Lima 2015 aspuciado por Lafrancol',
                'approval_1' => 0,
                'approval_2' => 0,
                'approval_3' => 0,
                'approval_4' => 0,
                'approval_5' => 0,
                'approval_6' => 0,
                'approval_7' => 0,
                'approval_8' => 0,
                'approval_9' => 0,
                'total_amount' => 1100.3,
                'active' => 1
            ));

        \IntranetMkt\Models\Expense::create(
            array(
                'expense_type_id' =>  1,
                'cycle_id' => 1,
                'user_id' => 1,
                'division_id' => 1,
                'application_date' => '2015-07-20 12:00:00',
                'code' => '2015000004',
                'name' => 'Campaña de Salud',
                'description' => 'Campaña de Salud Mediderm',
                'approval_1' => 0,
                'approval_2' => 0,
                'approval_3' => 0,
                'approval_4' => 0,
                'approval_5' => 0,
                'approval_6' => 0,
                'approval_7' => 0,
                'approval_8' => 0,
                'approval_9' => 0,
                'total_amount' => 200.3,
                'active' => 1
            ));




    }

}