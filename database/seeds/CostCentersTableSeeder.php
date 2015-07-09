<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class CostCentersTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('cost_centers')->delete();

        Excel::load('/database/seeds/data/cost_centers.csv', function($reader) {

            $results = $reader->get();

            foreach($results as $result)
            {
                \IntranetMkt\Models\CostCenter::create(array(

                    'code' => $result->code,
                    'name' => $result->name
                ));
            }
        });
    }
}