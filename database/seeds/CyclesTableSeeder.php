<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class CyclesTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('cycles')->delete();

        $years = array(2015,2016,2017,2018,2019);
        $months = array('ENE' => '01', 'FEB' => '02','MAR' => '03','ABR' => '04', 'MAY' => '05',
                   'JUN' => '06', 'JUL' => '07','AGO' => '08', 'SEP' => '09', 'OCT' => '10',
                   'NOV' => '11','DIC' => '12');

        foreach($years as $year){

            foreach($months as $key => $month){

                \IntranetMkt\Models\Cycle::create(array(
                    'code' => $year.$month,
                    'name' => 'Ciclo '.$year.$month,
                    'month' => $key,
                    'year' => $year
                ));
            }
        }
    }

}