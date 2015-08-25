<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class UsersTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('users')->delete();

        Excel::load('/database/seeds/data/users.csv', function($reader) {

            $results = $reader->get();

            foreach($results as $result)
            {


                $rol = DB::table('roles')->where('name', '=', $result->rol)->first();
                $cost_center = DB::table('cost_centers')->where('code', '=', $result->centro_costo)->first();

                $data = \IntranetMkt\User::create(array(
                    'role_id' => $rol->id,
                    'cost_center_id' => ($cost_center)?$cost_center->id:1,
                    'code' => $result->code,
                    'name' => $result->username,
                    //'username' => $result->username,
                    'email' => $result->email,
                    'password' => Hash::make('1234')
                ));

                if(in_array($rol->id, array(1,2,3,4))){

                    $division_names = explode("-",$result->division);
                    foreach($division_names as $name){

                        $division = DB::table('divisions')->where('name', '=', $name)->first();

                        $data->divisions()->attach($division->id);

                    }



                }else{
                    $divisions = \IntranetMkt\Models\Division::all()->sortBy('name');
                    foreach($divisions as $temp){
                        $data->divisions()->attach($temp->id);
                    }
                }


            }
        });
    }
}