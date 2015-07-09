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
                $division = DB::table('divisions')->where('name', '=', $result->division)->first();
                $cost_center = DB::table('cost_centers')->where('code', '=', $result->centro_costo)->first();

                $data = \IntranetMkt\User::create(array(
                    'role_id' => $rol->id,
                    'cost_center_id' => ($cost_center)?$cost_center->id:1,
                    'code' => $result->code,
                    'name' => $result->name,
                    'username' => $result->username,
                    'email' => $result->email,
                    'password' => Hash::make('1234')
                ));

                $data->divisions()->attach($division->id);
            }
        });
    }
}