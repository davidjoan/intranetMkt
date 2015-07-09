<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class RolesTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('roles')->delete();

        \IntranetMkt\Models\Role::create(array('code' => 'SP', 'name' => 'Supervisor'));
        \IntranetMkt\Models\Role::create(array('code' => 'PM', 'name' => 'Product Manager'));
        \IntranetMkt\Models\Role::create(array('code' => 'AM', 'name' => 'Asistente de Marketing'));
        \IntranetMkt\Models\Role::create(array('code' => 'GD', 'name' => 'Gerentes de División'));
        \IntranetMkt\Models\Role::create(array('code' => 'CG', 'name' => 'Control de Gestión'));
        \IntranetMkt\Models\Role::create(array('code' => 'GG', 'name' => 'Gerente General'));
        \IntranetMkt\Models\Role::create(array('code' => 'AD', 'name' => 'Administrador'));

    }

}