<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class DivisionsTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('divisions')->delete();

        \IntranetMkt\Models\Division::create(array('code' => 'LF' , 'name' => 'Eticos LF'));
        \IntranetMkt\Models\Division::create(array('code' => 'NEU', 'name' => 'Neumobiotics'));
        \IntranetMkt\Models\Division::create(array('code' => 'GYN', 'name' => 'Gynopharm'));
        \IntranetMkt\Models\Division::create(array('code' => 'CAR', 'name' => 'Cardiopharm'));
        \IntranetMkt\Models\Division::create(array('code' => 'DRU', 'name' => 'Drugtech'));
        \IntranetMkt\Models\Division::create(array('code' => 'MED', 'name' => 'Mediderm'));
        \IntranetMkt\Models\Division::create(array('code' => 'LAF', 'name' => 'Lafrancol'));
        \IntranetMkt\Models\Division::create(array('code' => 'K2' , 'name' => 'K2'));
        \IntranetMkt\Models\Division::create(array('code' => 'GEN', 'name' => 'Genericos'));
        \IntranetMkt\Models\Division::create(array('code' => 'BIO', 'name' => 'Biomedical'));
        \IntranetMkt\Models\Division::create(array('code' => 'LIA', 'name' => 'LIA'));
        \IntranetMkt\Models\Division::create(array('code' => 'COR', 'name' => 'Corporación'));



    }

}