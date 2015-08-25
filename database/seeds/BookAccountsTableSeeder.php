

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

        \IntranetMkt\Models\BookAccount::create(array('code' => '6371002' , 'name' => 'Medios'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371008' , 'name' => 'Propaganda de Mercaderías'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371010' , 'name' => 'Material Promocional - impreso','active' => true));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371014' , 'name' => 'Promoción y publicidad en autoservicios'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371020' , 'name' => 'Gastos de Promoción' ,'active' => true));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371022' , 'name' => 'Otros gastos de publicidad mkt'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371026' , 'name' => 'Capacitación médica', 'active' => true));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6371028' , 'name' => 'Desmedros MM'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6373002' , 'name' => 'Gastos de representación'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6373004' , 'name' => 'Bonificación x Neosalud'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6373008' , 'name' => 'Atenciones a clientes y/o diversos','active' => true));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6411002' , 'name' => 'Impuesto General a las Ventas'));
        \IntranetMkt\Models\BookAccount::create(array('code' => '6411006' , 'name' => 'IGV'));

    }

}