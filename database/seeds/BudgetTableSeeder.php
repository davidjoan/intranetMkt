<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class BudgetTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('budgets')->delete();

        Excel::load('/database/seeds/data/budget.csv', function($reader) {

            $results = $reader->get();

            foreach($results as $result)
            {
                $division = DB::table('divisions')->where('name', '=', $result->division)->first();
                $user = DB::table('users')->where('code', '=', $result->responsable)->first();
                $cost_center = DB::table('cost_centers')->where('code', '=', $result->centro_costo)->first();
                $book_account = DB::table('book_accounts')->where('code', '=', $result->cuenta)->first();
                $cycle = DB::table('cycles')->where('month', '=', $result->month)->where('year','=',$result->year)->first();

                \IntranetMkt\Models\Budget::create(array(
                    'division_id' => $division->id,
                    'user_id' => $user->id,
                    'cost_center_id' => $cost_center->id,
                    'book_account_id' => $book_account->id,
                    'cycle_id' => $cycle->id,
                    'amount' => $result->monto
                ));
            }
        });
    }
}