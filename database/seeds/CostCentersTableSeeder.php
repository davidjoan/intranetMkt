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
                $division = DB::table('divisions')->where('name', '=', $result->division)->first();

                \IntranetMkt\Models\CostCenter::create(array(

                    'code' => strtoupper($result->code),
                    'name' => strtoupper($result->name),
                    'responsible' => strtoupper($result->responsible),
                    'manager' => strtoupper($result->manager),
                    'division_id' => $division->id,
                    'cost_center_type' => strtoupper($result->cost_center_type)
                ));
            }
        });
    }
}