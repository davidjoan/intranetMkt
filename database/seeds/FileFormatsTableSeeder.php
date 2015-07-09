

<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12/03/15
 * Time: 15:19
 */

class FileFormatsTableSeeder extends \Illuminate\Database\Seeder {

    public function run(){

        DB::table('file_formats')->delete();

        \IntranetMkt\Models\FileFormat::create(array('code' => 'F01' , 'name' => 'Formato Nro 1' ,'description' => ''));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F02' , 'name' => 'Formato Nro 2' ,'description' => 'F02: MOTIVO DE EVENTO'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F03' , 'name' => 'Formato Nro 3' ,'description' => 'F03: CARTA DE INVITACION'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F04' , 'name' => 'Formato Nro 4' ,'description' => 'F04: SOLICITUD DE PATROCINIO'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F05' , 'name' => 'Formato Nro 5' ,'description' => 'F05: PLANTILLA DE CONTROL TRIMESTRAL'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F06' , 'name' => 'Formato Nro 6' ,'description' => 'F06: ACUERDO/CONTRATO DE SERVICIOS PROFESIONALES'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F07' , 'name' => 'Formato Nro 7' ,'description' => ''));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F08' , 'name' => 'Formato Nro 8' ,'description' => 'F08: CUESTIONARIO DE DEBIDA DILIGENCIA'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F09' , 'name' => 'Formato Nro 9' ,'description' => 'F09: SOLICITUD DE ORDEN DE COMPRA'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F10' , 'name' => 'Formato Nro 10','description' => 'F10: SOLICITUD DE CONTRIBUCIONES/DONACIONES'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F11' , 'name' => 'Formato Nro 11','description' => 'F11: SOLICITUD DE ORDEN DE COMPRA'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F12' , 'name' => 'Formato Nro 12','description' => 'F12: SOLICITUD DE ORDEN DE COMPRA'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F13' , 'name' => 'Formato Nro 13','description' => 'F13: SOLICITUD DE ORDEN DE COMPRA'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F14' , 'name' => 'Formato Nro 14','description' => 'F14: SOLICITUD DE ORDEN DE COMPRA'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F15' , 'name' => 'Formato Nro 15','description' => 'F15: SOLICITUD DE ACTIVIDADES PROMOCIONALES'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F16' , 'name' => 'Formato Nro 16','description' => 'F16: SOLICITUD DE ACTIVIDADES PROMOCIONALES'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F17' , 'name' => 'Formato Nro 17','description' => 'F17: SOLICITUD DE ACTIVIDADES PROMOCIONALES'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F18' , 'name' => 'Formato Nro 18','description' => 'F18: SOLICITUD DE ATENCIONES'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F19' , 'name' => 'Formato Nro 19','description' => 'F19: SOLICITUD DE ATENCIONES'));
        \IntranetMkt\Models\FileFormat::create(array('code' => 'F20' , 'name' => 'Formato Nro 20','description' => ''));


    }

}