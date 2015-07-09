<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class BuyOrdersTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('buy_orders')->delete();

        \IntranetMkt\Models\BuyOrder::create(
            array(
                'file_format_id' =>  1,
                'expense_id' => 1,
                'code' => '2015000001',
                'delivery_date' => '2015-06-10',
                'inventory' => 1,
                'active' => 0,
                'expenditure' => 0,
                'quantity' => 100,
                'price_unit' => 122.50,
                'estimated_value' => 122.50*100,
                'description' => 'Amloc 5mg Tabletas recubiertas x 2 MM',
                'destination' => 'Av. Cesar Vallejo 565 Lince'

                ));


        \IntranetMkt\Models\BuyOrder::create(
            array(
                'file_format_id' =>  1,
                'expense_id' => 1,
                'code' => '2015000002',
                'delivery_date' => '2015-06-10',
                'inventory' => 1,
                'active' => 0,
                'expenditure' => 0,
                'quantity' => 100,
                'price_unit' => 122.50,
                'estimated_value' => 122.50*100,
                'description' => 'CARDIOPLUS AM 50/5 40MG',
                'destination' => 'Av. Cesar Vallejo 565 Lince'

            ));


        \IntranetMkt\Models\BuyOrder::create(
            array(
                'file_format_id' =>  1,
                'expense_id' => 1,
                'code' => '2015000003',
                'delivery_date' => '2015-06-10',
                'inventory' => 1,
                'active' => 0,
                'expenditure' => 0,
                'quantity' => 100,
                'price_unit' => 122.50,
                'estimated_value' => 122.50*100,
                'description' => 'EZATOR 10Mg/20mg Tableta Rec. x 2MM',
                'destination' => 'Av. Cesar Vallejo 565 Lince'

            ));


        \IntranetMkt\Models\BuyOrder::create(
            array(
                'file_format_id' =>  1,
                'expense_id' => 1,
                'code' => '2015000004',
                'delivery_date' => '2015-06-10',
                'inventory' => 1,
                'active' => 0,
                'expenditure' => 0,
                'quantity' => 100,
                'price_unit' => 122.50,
                'estimated_value' => 122.50*100,
                'description' => 'EZATOR 10Mg/20mg Tableta Rec. x 2MM',
                'destination' => 'Av. Cesar Vallejo 565 Lince'

            ));


    }

}