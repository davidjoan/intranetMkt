

<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class ExpenseTypesTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('expense_types')->delete();

        $book_accoount_a_id = \IntranetMkt\Models\BookAccount::where('code','=', '6371010')->first()->id;
        $book_accoount_b_id = \IntranetMkt\Models\BookAccount::where('code','=', '6371020')->first()->id;
        $book_accoount_c_id = \IntranetMkt\Models\BookAccount::where('code','=', '6371026')->first()->id;
        $book_accoount_d_id = \IntranetMkt\Models\BookAccount::where('code','=', '6373008')->first()->id;

        $file_format_1_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F01')->first()->id;
        $file_format_2_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F02')->first()->id;
        $file_format_3_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F03')->first()->id;
        $file_format_4_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F04')->first()->id;
        $file_format_5_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F05')->first()->id;
        $file_format_6_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F06')->first()->id;
        $file_format_7_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F07')->first()->id;
        $file_format_8_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F08')->first()->id;
        $file_format_9_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F09')->first()->id;
        $file_format_10_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F10')->first()->id;
        $file_format_11_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F11')->first()->id;
        $file_format_12_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F12')->first()->id;
        $file_format_13_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F13')->first()->id;
        $file_format_14_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F14')->first()->id;
        $file_format_15_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F15')->first()->id;
        $file_format_16_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F16')->first()->id;
        $file_format_17_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F17')->first()->id;
        $file_format_18_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F18')->first()->id;
        $file_format_19_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F19')->first()->id;
        $file_format_20_id = \IntranetMkt\Models\FileFormat::where('code','=', 'F20')->first()->id;


        $expenseType1 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_a_id,'code' => '63710101' , 'name' => 'Materiales promocionales impresos'));
        $expenseType2 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_a_id,'code' => '63710102' , 'name' => 'Suministro de recordatorios de la marca'));
        $expenseType3 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_a_id,'code' => '63710103' , 'name' => 'Artículos de utilidad médica'));
        $expenseType4 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_a_id,'code' => '63710104' , 'name' => 'Cortesías culturales'));
        $expenseType5 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_b_id,'code' => '63710205' , 'name' => 'Comidas y refrigerios'));
        $expenseType6 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_b_id,'code' => '63710201' , 'name' => 'Entretenimiento'));
        $expenseType7 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_b_id,'code' => '63710202' , 'name' => 'Recorridos de planta y visitas al sitio'));
        $expenseType8 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_b_id,'code' => '63710203' , 'name' => 'Interacción con pacientes y organizaciones de pacientes'));
        $expenseType9 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_b_id,'code' => '63710204' , 'name' => 'Campañas Medicas'));
        $expenseType10 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_c_id,'code' => '63710261' , 'name' => 'Viajes y alojamientos razonables'));
        $expenseType11 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_c_id,'code' => '63710262' , 'name' => 'Acuerdos de servicios profesionales'));
        $expenseType12 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_c_id,'code' => '63710263' , 'name' => 'Patrocinios: Apoyo de asistencia a conferencias educativas'));
        $expenseType13 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_c_id,'code' => '63710264' , 'name' => 'Subvenciones educativas o científicas'));
        $expenseType14 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_c_id,'code' => '63710265' , 'name' => 'Reuniones de capacitación y educativas sobre productos organizadas por Abbott'));
        $expenseType15 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_c_id,'code' => '63710266' , 'name' => 'Contribuciones caritativas'));
        $expenseType16 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_d_id,'code' => '63730081' , 'name' => 'Compra de Electrodomesticos'));
        $expenseType17 = \IntranetMkt\Models\ExpenseType::create(array('book_account_id' => $book_accoount_d_id,'code' => '63730082' , 'name' => 'Compra de Vales'));

        $expenseType1->file_formats()->attach($file_format_11_id);
        $expenseType2->file_formats()->attach($file_format_12_id);
        $expenseType3->file_formats()->attach($file_format_13_id);
        $expenseType4->file_formats()->attach($file_format_14_id);
        $expenseType5->file_formats()->attach($file_format_15_id);
        $expenseType6->file_formats()->attach($file_format_16_id);

        $expenseType7->file_formats()->attach($file_format_17_id);
        $expenseType8->file_formats()->attach($file_format_16_id);

        $expenseType9->file_formats()->attach($file_format_17_id);
        $expenseType10->file_formats()->attach($file_format_4_id);
        $expenseType11->file_formats()->attach($file_format_6_id);
        $expenseType11->file_formats()->attach($file_format_9_id);

        $expenseType12->file_formats()->attach($file_format_4_id);
        $expenseType12->file_formats()->attach($file_format_3_id);
        $expenseType13->file_formats()->attach($file_format_2_id);
        $expenseType14->file_formats()->attach($file_format_9_id);

        $expenseType15->file_formats()->attach($file_format_17_id);
        $expenseType16->file_formats()->attach($file_format_18_id);
        $expenseType17->file_formats()->attach($file_format_19_id);


    }

}