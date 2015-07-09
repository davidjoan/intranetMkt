

<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class BookAccountsTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('book_accounts')->delete();

        \IntranetMkt\Models\BookAccount::create(array('code' => '6371010' , 'name' => 'Material Promocional - impreso'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371020' , 'name' => 'Gastos de Promoción'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371026' , 'name' => 'Capacitación médica'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6373008' , 'name' => 'Atenciones a clientes y/o diversos'));

    }

}