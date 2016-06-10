<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Pdfs extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Biblioteca_model');
        $this->load->model('Persona_model');
        $this->load->model('User');
          $this->load->library('Pdf');
          $this->load->database('default');
    }
    function generar_bar_codes(){
        if($this->session->userdata('logged_in'))
       {
            $session_data = $this->session->userdata('logged_in');
            $rol = $session_data['rol'];
            if($rol==2){
                $this->load->model('Barcode_model','',TRUE);
                $arreglo=$this->Barcode_model->get_codigos("g");
                if($arreglo){
                    $this->barcode($arreglo);
                }else{
                     $this->barcode([]);           
                }
            }
        }   
    }
    private function barcode($arreglocodigos){          
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->AddPage();
        if(count($arreglocodigos)==0){
            $html = <<<EOD
            <div align="center"><h1>No hemos encontrado nada en este lugar.</h1></div>
            <div align="center"><h1>No se han agregado ejempalres.</h1></div>
            <div align="center"><h1>Dirijase al apartado de ejempalres, agrege los códigos que necesite,y vuelva a esta URL.</h1></div>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        }
        $pdf->SetY(30);
        $pdf->SetFont('helvetica', '', 10);
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => 'C',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 8
        );
$contbar=1;
    foreach ($arreglocodigos as $codigo){
        
     
        $x = $pdf->GetX();
            $y = $pdf->GetY();
        $codigos=7; $codig=strlen($codigo->dato);
        $ceros=$codigos-$codig;
            $cont=0;$cadena="0";
            while ($cont<$ceros-1){
                $cadena.="0";
                $cont++;
            }
        if($contbar%3==0){    
            $pdf->write1DBarcode($cadena.$codigo->dato, 'C39','', $y-10.5, 60, 18, 0.4, $style, 'M');
            $pdf->SetXY($x,$y);
            $pdf->Cell(60,20, '', 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
            $pdf->Ln(); 
        }else{
            $pdf->write1DBarcode($cadena.$codigo->dato, 'C39','', $y-10.5, 60, 18, 0.4, $style, 'M');
            $pdf->SetXY($x,$y);
            $pdf->Cell(60,20, '', 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
           
        }
          $contbar=$contbar+1;;
                   
     }

      
     
        $this->mostrarpdf($pdf,"barcode.pdf","I");
    }
    private function get_valmes($value){
            $arrmeses= array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>"Abril",
                            5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",
                            9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre");
            return $arrmeses[$value];
    }
    //variblae que guarda el mes
    var $m="";
    public function reportar(){
        if($this->session->userdata('logged_in'))
       {
            $session_data = $this->session->userdata('logged_in');
            $rol = $session_data['rol'];
            if($rol==1){
                $mes=$this->input->post('mes');
                $this->m=$mes;
                if(isset($mes)){
                    if($mes>0&&$mes<13){
                        $namemes=$this->get_valmes($mes);
                    }
                }else{
                    $namemes=$this->get_valmes(intval(date('m')));
                }
                $anio=$this->input->post('anio');
                $this->get_usuariosatendidos($anio,$namemes);
                
            }
        }    
    }
var $totaladultos=0;
var $totaljovenes=0;var $totaldomicilio=0;var $totalnuevos=0;
var $totalninos=0;var $totalinfantil=0;var $totalconsulta=0;
var $actividadesadultos=0;var $sumaasitenciaactadu=0;
var $actividadesjovenes=0;var $sumaasitenciaactjov=0;
var $actividadesninos=0;var $sumaasitenciaactnin=0;
var $actividadeste=0;
var $tesumaasitenciaactnin=0;
var $tesumaasitenciaactjov=0;
var $tesumaasitenciaactadu=0;
    private function get_usuariosatendidos($anio,$namemes){
        $this->load->model('Reporte_model');
        $this->set_adultos($this->Reporte_model->usuariosatendidos($this->m,$anio,"md"));
        $this->set_jovenes($this->Reporte_model->usuariosatendidos($this->m,$anio,"et"));
        $this->set_ninos($this->Reporte_model->usuariosatendidos($this->m,$anio,"hd"));
        $this->set_infantil($this->Reporte_model->prestamos($this->m,$anio,"I","I"));
        $this->set_consulta($this->Reporte_model->prestamos($this->m,$anio,"I","C"));
        $this->set_domicilio($this->Reporte_model->prestamos($this->m,$anio,"E",""));
        $this->set_nuevosusuarios($this->Reporte_model->usuarionuevos($this->m,$anio));
        //reportando actividades
        $this->set_actividades_adultos($this->Reporte_model->actividades($this->m,$anio,"md","contar"));
        $this->set_actividades_ninos($this->Reporte_model->actividades($this->m,$anio,"hd","contar"));
        $this->set_actividades_jovenes($this->Reporte_model->actividades($this->m,$anio,"et","contar"));
        $this->set_actividades_td($this->Reporte_model->actividadeste($this->m,$anio,"contar"));
        //asistencias 
        $this->set_totalasistentesac_adul($this->Reporte_model->actividades($this->m,$anio,"md","sumar"));
        $this->set_totalasistentesac_jov($this->Reporte_model->actividades($this->m,$anio,"et","sumar"));
        $this->set_totalasistentesac_nin($this->Reporte_model->actividades($this->m,$anio,"hd","sumar"));

        $this->set_totalasistentes_tea($this->Reporte_model->actividadeste($this->m,$anio,"sumar","adultos"));
        $this->set_totalasistentes_tej($this->Reporte_model->actividadeste($this->m,$anio,"sumar","jovenes"));
        $this->set_totalasistentes_ten($this->Reporte_model->actividadeste($this->m,$anio,"sumar","ninos"));
        $this->generar($namemes);
    }
    private function set_totalasistentes_ten($asistencias){
        $this->tesumaasitenciaactnin=$asistencias;
    }
    private function get_totalasistentes_ten(){
        return $this->tesumaasitenciaactnin;
    }
    private function set_totalasistentes_tej($asistencias){
        $this->tesumaasitenciaactjov=$asistencias;
    }
    private function get_totalasistentes_tej(){
        return $this->tesumaasitenciaactjov;
    }
    private function set_totalasistentes_tea($asistencias){
        $this->tesumaasitenciaactadu=$asistencias;
    }
    private function get_totalasistentes_tea(){
        return $this->tesumaasitenciaactadu;
    }
    private function set_totalasistentesac_nin($asistencias){
        $this->sumaasitenciaactnin=$asistencias;
    }
    private function get_totalasistentes_nin(){
        return $this->sumaasitenciaactnin;
    }
    private function set_totalasistentesac_jov($asistencias){
        $this->sumaasitenciaactjov=$asistencias;
    }
    private function get_totalasisjov(){
        return $this->sumaasitenciaactjov;
    }
    private function set_totalasistentesac_adul($asisadul){
        $this->sumaasitenciaactadu=$asisadul;
    }
    private function get_totalasisadult(){
        return $this->sumaasitenciaactadu;
    }
    private function set_actividades_td($te){
        $this->actividadeste=$te;
    }
    private function get_actividades_te(){
        return $this->actividadeste;
    }
    private function set_actividades_jovenes($acjovenes){
        $this->actividadesjovenes=$acjovenes;
    }
    private function get_actividades_jovenes(){
        return $this->actividadesjovenes;
    }
    private function set_actividades_ninos($acninos){
        $this->actividadesninos=$acninos;
    }
    private function get_actividades_ninos(){
        return $this->actividadesninos;
    }
    private function set_actividades_adultos($acadultos){
        $this->actividadesadultos=$acadultos;
    }
    private function get_actividades_adultos(){
        return $this->actividadesadultos;
    }
     private function set_nuevosusuarios($nuevos){
        $this->totalnuevos=$nuevos;
    }
    private function get_nuevos(){
        return  $this->totalnuevos;
    }
     private function set_domicilio($domicilio){
        $this->totaldomicilio=$domicilio;
    }
    private function get_domicilio(){
        return  $this->totaldomicilio;
    }
    private function set_infantil($infantil){
        $this->totalinfantil=$infantil;
    }
    private function get_infantil(){
        return  $this->totalinfantil;
    }
    private function set_consulta($consulta){
        $this->totalconsulta=$consulta;
    }
    private function get_consulta(){
        return  $this->totalconsulta;
    }
    private function set_adultos($adultos){
        $this->totaladultos=$adultos;
    }
    private function get_adultos(){
        return  $this->totaladultos;
    }
    private function set_jovenes($jovenes){
        $this->totaljovenes=$jovenes;
    }
    private function get_jovenes(){
        return  $this->totaljovenes;
    }
    private function set_ninos($ninos){
        $this->totalninos=$ninos;
    }
    private function get_ninos(){
        return  $this->totalninos;
    }

    private function generar($mes){
        $pdf = new Pdf('P', 'mm', 'A0', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('BPCO');
        $pdf->SetTitle('Estadistica mesual');
        $pdf->SetSubject('');
        $pdf->SetKeywords('TCPDF, PDF, MENSUAL, ESTADISTICA, guide');
        $tit = 'ESTADÍSTICA MENSUAL';
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        $pdf->SetHeaderData(PDF_HEADER_LOGO, 40,"       ".$tit." ", "", array(0,0,0), array(255, 255, 255));
        //$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
        
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->setHeaderFont(Array("times", '', 20));
       // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(10);
       // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
//relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
 
// ---------------------------------------------------------
// establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);
 
// Establecer el tipo de letra
 
//Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
// Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont('freemono', '', 20, '', true);
 
// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
        $pdf->AddPage();
 
//fijar efecto de sombra en el texto
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0, 'depth_h' => 0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Establecemos el contenido para imprimir
        $datos = $this->Biblioteca_model->getBiblio();
        //preparamos y maquetamos el contenido a crear
        $html = '';
               foreach ($datos as $fila) 
        {
            $nombre = $fila->namebiblio;
            $idbiblio = $fila->idbiblio;
            $loc = $fila->localidad;
            $mun = $fila->municipio;
            $est = $fila->estado;
            $enc = $fila->encargado;
        }

$adultos=$this->get_adultos();
$jovenes=$this->get_jovenes();
$ninos=$this->get_ninos();
$consulta=$this->get_consulta();
$infantil=$this->get_infantil();
$domicilio=$this->get_domicilio();
$nuevos=$this->get_nuevos();
$te=$this->get_actividades_te();
$acad=$this->get_actividades_adultos()+$te;
$acni=$this->get_actividades_ninos()+$te;
$acjv=$this->get_actividades_jovenes()+$te;

//actividades para todas las edades
$totaltea=$this->get_totalasistentes_tea();
$totaltej=$this->get_totalasistentes_tej();
$totalten=$this->get_totalasistentes_ten();

$asisadu=$this->get_totalasisadult()+$totaltea;
$asjov=$this->get_totalasisjov()+$totaltej;
$asnin=$this->get_totalasistentes_nin()+$totalten;
$html.='';

$nombre=utf8_encode(ucwords(strtolower(utf8_decode($nombre))));

$loc=utf8_encode(ucwords(strtolower(utf8_decode($loc))));

$mun=utf8_encode(ucwords(strtolower(utf8_decode($mun))));

$est=utf8_encode(ucwords(strtolower(utf8_decode($est))));
$enc=utf8_encode(ucwords(strtolower(utf8_decode($enc))));
// Imprimimos el texto con writeHTMLCell()
$dia=date("Y-m-d");
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 $tbl = <<<EOD
 <div style="border: 2px solid black; ">
<table cellspacing="5" cellpadding="6" >
    <tr>
        <td colspan="3"style="font-size:10px;">BIBLIOTECA: <span>$nombre </span></td>
        <td colspan="2" style="font-size:12px;">N° COLECCIÓN: <span>$idbiblio</span></td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:12px;">LOCALIDAD: <span>$loc</span></td>
        <td colspan="2" style="font-size:12px;">MUNICIPIO: <span>$mun</span></td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:12px;">ESTADO: $est</td>
        <td colspan="3" style="font-size:12px;">ENCARGADO: $enc</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:12px;">MES QUE REPORTA: $mes</td>
        <td colspan="3" style="font-size:12px;">FECHA DE ELABORACIÓN: $dia</td>
    </tr>     
</table>
</div>

<div style="border: 2px solid black; ">
<table cellspacing="3" cellpadding="5" >

    <tr>
        <td colspan="4"style="font-size:14px;"><h5 >LIBROS UTILIZADOS DENTRO DE LA BIBLIOTECA</h5></td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:12px;">COLECCIÓN GENERAL Y DE CONSULTA </td>
        <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >$consulta</span> </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:12px;">COLECCIÓN INFANTIL </td>
        <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >$infantil</span> </td>
    </tr>
    <tr>
        <td colspan="4"style="font-size:14px;"><h5 >SERVICIO DE PRÉSTAMO A DOMICILIO</h5></td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:12px;">USUARIOS REGISTRADOS</td>
        <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >$nuevos</span> </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:12px;">LIBROS PRESTADOS A DOMICILIO </td>
        <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >$domicilio</span> </td>
    </tr>
    <tr>
        <td colspan="4"style="font-size:14px;"><h5 >FOMENTO A LA LECTURA</h5></td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:12px;">ACTIVIDADES PARA ADULTOS  $acad</td>
         <td colspan="1" style="font-size:12px;">ASISTENTES ADULTOS</td>
         <td colspan="1" style="font-size:12px;">  $asisadu</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:12px;">ACTIVIDADES PARA JÓVENES  $acjv</td>
         <td colspan="1" style="font-size:12px;">ASISTENTES JÓVENES</td>
         <td colspan="1" style="font-size:12px;">  $asjov</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:12px;">ACTIVIDADES PARA NIÑOS   &nbsp;&nbsp;$acni</td>
         <td colspan="1" style="font-size:12px;">ASISTENTES NIÑOS</td>
         <td colspan="1" style="font-size:12px;">  $asnin</td>
    </tr>      
</table>
</div>
<BR />
<style>
    
    h5{
        text-align: center;
        text-decoration: underline;
    }
</style>
EOD;
///<span style="font-size:12px;">OBSERVACIONES: _________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________</span>

$pdf->writeHTML($tbl, true, false, false, false, '');
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("estmensual.pdf");
        ob_end_clean();
        $pdf->Output($nombre_archivo, 'I');
    }

    private function mostrarpdf($pdf,$nombre,$formato){
        $pdf->Output($nombre,$formato);
    }
    //crear reporte de usuario
    public function generarreporte(){
        $cuenta = $this->input->post('cuenta');
         $pdf = new Pdf('P', 'mm', 'A0', true, 'UTF-8', false);
         if(isset($cuenta)){
                            $tit = 'Reporte de usuario';
                    // datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
                            $pdf->SetHeaderData('', 40,"       ".$tit." ", "", array(0,0,0), array(255, 255, 255));
                            //$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
                    // datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
                            $pdf->setHeaderFont(Array("times", '', 20));
                           // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                    // se pueden modificar en el archivo tcpdf_config.php de libraries/config
                            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                    // se pueden modificar en el archivo tcpdf_config.php de libraries/config
                            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                            $pdf->SetHeaderMargin(10);
                           // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                    // se pueden modificar en el archivo tcpdf_config.php de libraries/config
                            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                    //relación utilizada para ajustar la conversión de los píxeles
                            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                    // ---------------------------------------------------------
                    // establecer el modo de fuente por defecto
                            $pdf->setFontSubsetting(true);
                    // Establecer el tipo de letra
                    //Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
                    // Helvetica para reducir el tamaño del archivo.
                            $pdf->SetFont('freemono', '', 20, '', true);
                    // Añadir una página
                    // Este método tiene varias opciones, consulta la documentación para más información.
                            $pdf->AddPage();
                    //fijar efecto de sombra en el texto
                            $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0, 'depth_h' => 0, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
                    // Establecemos el contenido para imprimir
                            $usuario= $this->Persona_model->findcuenta($cuenta);
                            //preparamos y maquetamos el contenido a crear
                            $html = '';
                            
                            foreach ($usuario as $fila) 
                            {
                                $nombre =$fila->apPat." ".$fila->apMat." ".$fila->pnombre;
                                $email = $fila->email;
                                $registrado = $fila->created_at;
                                $fecha=$fila->fechaNaci;
                            }
                                 $time = strtotime($fecha);
                                $fecha = date("Y-m-d", $time);
                                $edad=(int)(((((strtotime(date('Y-m-d'))-strtotime($fecha))/365)/24)/60)/60);

                        $nombre=ucwords(strtolower($nombre));
                        $datosprestamo=$this->User->get_datos_prestamos_usuarui($cuenta,"E");
                        $totalprestmosex=count($datosprestamo);
                        $datosprestamoin=$this->User->get_datos_prestamos_usuarui($cuenta,"I");
                        $totalprestmosin=count($datosprestamoin);
                        $multas_generadas=$this->User->total_multa_saldo($cuenta);
                            foreach ($multas_generadas as $fila) 
                            {
                                $total_multas =$fila->total;
                                $monto_mutas = $fila->monto;
                            }  
                        $multas_pendientes=$this->User->total_multa_pendientes_saldo($cuenta);
                        $total_multas_pend=0;
                        if($multas_pendientes){
                            foreach ($multas_pendientes as $fila) 
                            {
                                $total_multas_pend =$fila->total;
                                $monto_mutas_pen = $fila->monto;
                            }
                        } 
                          $preferencias=$this->User->preferenciasusuario($cuenta,"E"); 

                          $cadena="";
                          foreach ($preferencias as $value) {
                                   $cadena.='<tr>
                                            <td colspan="3" style="font-size:12px;">'.$value->Clasificacion.'</td>
                                            <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >'.$value->total.'</span> </td>
                                        </tr>';                  
                            }
                            $preferenciasin=$this->User->preferenciasusuario($cuenta,"I"); 
                          $cadenain="";
                          foreach ($preferenciasin as $value) {
                                   $cadenain.='<tr>
                                            <td colspan="3" style="font-size:12px;">'.$value->Clasificacion.'</td>
                                            <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >'.$value->total.'</span> </td>
                                        </tr>';                  
                            }   
                             
                    $html.='';
                    // Imprimimos el texto con writeHTMLCell()
                           

                        $html = '<div style="border: 2px solid black; ">
                    <table cellspacing="5" cellpadding="6" border="1" >
                        <tr>
                            <td colspan="3"style="font-size:10px;">Nombre: <span>'.$nombre.' </span></td>
                            <td colspan="2" style="font-size:12px;">Cuenta: <span>'.$cuenta.'</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size:12px;">Email: <span>'.$email.'</span></td>
                            <td colspan="2" style="font-size:12px;">Fecha de registro: <span>'.$registrado.'</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size:12px;">Edad: '.$edad.' años</td>
                        </tr>  
                    </table>
                    </div>

                    <div style="border: 2px solid black; ">
                    <table cellspacing="3" cellpadding="5" border="1" class="table table-condensed table-bordered table-striped" >
                        <tr >
                            <td colspan="4"style="font-size:14px;"><h5 >Préstamos</h5></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size:12px;">Internos </td>
                            <td colspan="1"  style="text-decoration:underline" style="font-size:12px;" align="center"><span >'.$totalprestmosin.'</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size:12px;">Externos </td>
                            <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >'.$totalprestmosex.'</span></td>
                        </tr>
                        <tr>
                            <td colspan="4"style="font-size:14px;"><h5 >Multas</h5></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size:12px;">Multas generadas </td>
                            <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >'.$total_multas.'</span> </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size:12px;">Monto de multas </td>
                            <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >'.$monto_mutas.'</span> </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-size:12px;">Multas pendientes </td>
                            <td colspan="1" style="text-decoration:underline" style="font-size:12px;" align="center"><span >'.$total_multas_pend.'</span> </td>
                        </tr>    
                        <tr>
                            <td colspan="4"style="font-size:14px;"><h5 >Preferencias de usuario</h5></td>
                        </tr>
                        <tr>
                            <td colspan="4"style="font-size:14px;"><h5 >Externos</h5></td>
                        </tr>
                        '.$cadena.'
                        <tr>
                            <td colspan="4"style="font-size:14px;"><h5 >Internos</h5></td>
                        </tr>
                        '.$cadenain.'     
                    </table>
                    </div>
                    <BR />
                    <style>
                        h5{
                            text-align: center;
                            text-decoration: underline;
                        }


                    </style>';

                    // output the HTML content
                    $pdf->writeHTML($html, true, false, true, false, '');     
                    $files = glob('./pdf/*'); // get all file names
                                
                                //if(count($files)>6){ 
                                    foreach($files as $file){ // iterate files
                                      if(is_file($file))
                                        unlink($file); // delete file
                                    }
                                /* }else{   
                                       if(file_exists('./pdf/reporte'.$cuenta.'.pdf')){
                                            return unlink('./pdf/reporte'.$cuenta.'.pdf');
                                        }
                                     }*/
                    return $pdf->Output(getcwd().'/pdf/reporte'.$cuenta.'.pdf', 'F');                         
        }else{
            echo "No se envió cuenta de usuario";
        }                               

    }
}

