<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 //we need to call PHP's session object to access it through CI
class Home extends MY_Controller {

 function __construct()
 { 
   parent::__construct();

      $this->load->model('Libro_model','',TRUE);
      $this->load->model('Grupo_model','',TRUE);
      $this->load->model('Persona_model','',TRUE);
      $this->load->model('Prestamo_model','',TRUE);
      $this->load->model('Registro_model','',TRUE);
      $this->load->model('Reserva_model','',TRUE);
      $this->load->model('Empleado_model','',TRUE);
      $this->load->model('User','',TRUE);
      $modelo= new Reserva_model;
      $modelo->eliminar_reservas_vencidas();
      if(validarsesion()){

      }else{
        redirect('welcome_message','refresh');
      }
  }
  //variable usada para estadisticas 
var $periodo=array('dia'=>'',
                    'mes'=>'',
                    'anio'=>'');
/*  @params  
  verifica que exista la sesion 
  cargara la vista correspondiente al rol de la persona que inicio sesion
  en caso de no tener rol carga pagina de erro
*/
 function index()
 {
    $usuario=array(1 =>'admin/inicial',
                    2 =>'empleados/inicialprocesos',3=>'empleados/inicialprestamos',
                    4=>'evento/nuevo',5=>'estadistica/prestamointer',
                    6=>'estadistica/visitasregistro',7=>'usuarios/inicial',
                    8=>'admin/inicio',
                    9=>'evento/actividad');
      $rol=get_rol_sesion();
      if($rol<11){
        $pagina=(isset($usuario[$rol])) ? $usuario[$rol] :'../Error'; 
        $this->cargarpage($pagina);
      }else{
        redirect();
      }
 }
 /*   @params area:tipo de empleado a postrar
              cont:para paginacion 
              muestra pagina de error,si se ingresa un area no encontrada en dicho arreglo    
 */
function empleados($area="analistas",$cont=0){
  $areas=["analistas","prestador","recepcion","encargadosala","difusores"];
  $accesso=false;
  foreach ($areas as $a) {
    $accesso=$accesso||(strcmp($area,$a)==0);
  }
  if($accesso){ 
    $empleados=new Empleado_model;
    $ruta="home/empleados/".$area;
    $pagina='empleados/mostrar';
    $this->recibe_datos($pagina,$empleados,$cont,$ruta,$area);  
  }else{
    $this->error_home();
  }
}
function respaldar(){
  ini_set('memory_limit','512M');
  set_time_limit(1000);
  $session=validarsesion();
  if(isset($session)){
      if(validarsesion()['rol']==8){
        $this->load->dbutil();
        $name='biblioteca'.date('YmdHms');
        $prefs = array('format'=>'zip',
                        'filename'=>$name.'.sql',
                        
                      );
        $copia_de_seguridad =&$this->dbutil->backup($prefs);
        $this->load->helper('file');
        write_file(base_url().'csv/copia_de_seguridad.sql',$copia_de_seguridad);
        $this->load->helper('download');
        force_download('copia_de_seguridad.gz', $copia_de_seguridad); 
      }
  }else{
    redirect('home');
  } 
}
/* @params tipo hora mes año
    si inicio sesion y el rol es el de director
    mostrara los datos en funcion del parametro recibidos
    se configuraran los datos antes de llamar a la funcion
    que ejecutara dicho procesos
*/
   function estadisticas($tipo){
        $data = json_decode(file_get_contents("php://input"));        
        if(validarsesion()['rol']==1){
          $formato='Y';
          $arr_data=array();
          $modelo=new Registro_model;
          $edades=array('niños' => 0,'jovenes' =>0,'adultos' =>0);
          if(strcmp($tipo,"hora")==0){
            if(isset($data)){
              if(strcmp($data->datos,"")==0){
                $dia=date('d');$mes=date('m');$anio=date('Y');
              }else{
                list($dia, $mes, $anio)=explode("/",$data->datos);
              }
            }
            $rango=range(7, 21);
            $this->periodo['dia']=$dia;
            $this->periodo['mes']=$mes; 
            $this->periodo['anio']=$anio;
            $fecha=$anio."-".$mes."-".$dia;
            $periodo="hora";
            $formato="Y-m-d";
            $date=DateTime::createFromFormat('Y-m-d',$fecha);
            $date=$date->format("Y-m-d");
          }elseif (strcmp($tipo,"mes")==0) {
            list($mes, $anio)=explode("/",$data->datos);
            $this->periodo['mes']=$mes;
            $this->periodo['anio']=$anio;
            $diastoal=date("d",mktime(0,0,0,$mes+1,0,$anio));
            $rango=range(1,$diastoal);
            $fecha=$anio."-".$mes;
            $periodo="dia";
            $formato="Y-m";
            $date=DateTime::createFromFormat('Y-m',$fecha);
            $date=$date->format("Y-m");
          }elseif (strcmp($tipo,"anio")==0){
            $this->periodo['anio']=$data->datos;
            $date=DateTime::createFromFormat('Y',$this->periodo['anio']);
            $date=$date->format("Y");
            $periodo="mes";
            $formato="Y";
            $rango=range(1,12);
          }
          $config=array('arrdata'=>$arr_data,'modelo'=>$modelo,'edades'=>$edades,
                          'tipo'=>$periodo,'areglorango'=>$rango,'formatodate'=>$formato,'periodo'=>$this->periodo,'date'=>$date);
          $arr_data=$this->estadisitcasvisitas($config);
          echo json_encode($arr_data);
        }else{
          redirect('home','refresh');
        }
 }
  /*  @params pagina contenido del layouadmin a mostrar
      datos del modelo que se mostrarar , contador para paginacion 
      contenido vista que se muestra dentro del panel 
      en la pagina de configuracion (perfil)
  */
  function cargarpage($pagina,$datosmodelo='',$cont=0,$contenido='datos'){
      if(strcmp($pagina,"admin/inicial")==0){
          $data['totaluser']=$this->contarModelo(new Persona_model);
          $data['totalejemplares']=$this->contarModelo(new Libro_model);
      }elseif(strcmp($pagina,"empleados/inicialprestamos")==0){
        $data['totalprestamos']=$this->Prestamo_model->contar_prestamos_activos();//activos
        $data['totalretrasos']=$this->Prestamo_model->contar_prestamos_activos(true);//retrasos
        $data['totalreservas']=$this->Reserva_model->contar_reservas();//reservas
      }elseif (strcmp($pagina,"empleados/inicialprocesos")==0) {
        $data['totalejemplares']=$this->contarModelo(new Libro_model);
      }
      $data['cuentaempleado'] = get_cuenta_sesion();
      $data['idpersona'] = get_id_persona_en_sesion();
      $data['pnombre'] = get_nombre_persona_en_session();
      $data['rol'] = get_rol_sesion();
      $data['content']=$pagina;
      $data['contenido']=$contenido;
      $data['datos']=$datosmodelo;
      $data['cont']=$cont; 
      if($this->permitiracceso($pagina)){
          $this->load->view('layouts/layoutadmin', $data); //llamada a la vista general
      }else{
         $this->sinacceso();
      }
 }
 
 /* params  panel:vista que se mostrara dentro del panel encotnrados en view/admin/(vista)
    llama a la funcion cargarpage indicandole la vista a mostrar dentor del layout admin
    los datos del modelo, en este caso datos del usario logueado
    contador 0 y panel recibido desde la url defautl:datos
 */
 function configurar($panel='datos'){
      $empleado = $this->Persona_model->findadmin(get_cuenta_sesion());
      $usuario=$this->Persona_model->findcuentapanel(get_cuenta_sesion());
      $pagina='admin/config_admin';
      if($empleado){
          $this->cargarpage($pagina,$empleado,0,$panel);
      }else if($usuario){
          $this->cargarpage($pagina,$usuario,0,$panel);
      }else{
          $this->error_home();
      }
 }
 /*   @params tabla area de empleado
      id:identifiador de usuario
 */
  function ver($tabla="analistas",$id=0){
        $empleado = $this->Empleado_model->findempleado($tabla,$id);
        if($empleado){
          $pagina='empleados/showempleados';
          $this->cargarpage($pagina,$empleado,0);
        }else{
          $this->error_home();
        }      
  }
  //carga vista de codigos de barras
 function barcode(){
  $pagina="Pdf/barcode";
  $this->load->model('Barcode_model','',TRUE);
  $codigos = $this->Barcode_model->get_codigos("g"); 
  $this->cargarpage($pagina,$codigos);
 }
 //elimina o vacio los codigos de barras
 function vaciarcb($dato=""){
  $this->load->model('Barcode_model','',TRUE);
  (strlen($dato)==0) ? $this->Barcode_model->vaciar("g") : $this->Barcode_model->eliminar($dato,"g");
  redirect('home/ejemplares/codes','refresh');
 }
 //carga vista para agregqar fechas a una actividad ya creada
function agregarfechas($idactividad){
  $this->load->model('Actividad_model','',TRUE);
  $actividad = $this->Actividad_model->find_by_id($idactividad); 
  $pagina='evento/actividades';
  $this->cargarpage($pagina,$actividad);
}
//  Carga vista para mostra una determinada actividad  y poder registrar las visitas
  function findevbyid($idactividad){  
    $this->load->model('Actividad_model','',TRUE);  
    $evento = $this->Actividad_model->findbyid($idactividad);
    $pagina=($evento) ? 'evento/show' : '../Error';
    $this->cargarpage($pagina,$evento);  
  }
/* carga vista para mostrar datos de un usuario
  define a la pagina a la que ira
  y carga la vista mediante la funcion cargarpage
*/
  function findembyid($idem){
    $user=get__tipo_user();
    $usuario = $this->Persona_model->findbyid($idem,$user);
    $pagina=($usuario) ? 'empleados/show' : '../Error';
    $this->cargarpage($pagina,$usuario);  
  }
  /*params modelo datos del modelo
    retorna la cantidad de datos obtenidos del modelo
    */
 private function contarModelo($modelo){         
        $totaluser=$modelo->contar();
        if($totaluser){
          foreach ($totaluser as $row) {
            $cantidad=$row->total;
          }
        }
        return $cantidad; 
 }
 //devuelve un arreglo de las preferencias del usaurio
 function preferencias($cuenta,$tipo){
  $arr_data=array();
  $clasifica=new User;
        $datosclas=$clasifica->preferenciasusuario($cuenta,$tipo);        
        if($datosclas){ 
              $i=0;  
            foreach ($datosclas as $row ) {
                $arr_data[$i]['clasificacion']=$row->Clasificacion;
                $arr_data[$i]['total']=$row->total;
                $i++;
              }
          }
        echo json_encode($arr_data); 
 }
 //permite el acceso 
 private function acceso($rol){
      if(get_rol_sesion()==$rol){
        return true;
      }else{
         $this->dirigir_a('home','refresh');
      }
 }
 private function dirigir_a($sitio,$accion){
    redirect($sitio,$accion);
 }
 function estadisticasprestamo_por_nadqui(){
    $pres=new Prestamo_model;
    $data = json_decode(file_get_contents("php://input"));
    $arr_data=[];
    if(isset($data)){  
      $resultados=$pres->construir_consulta_para_graficas_procesos($data->nadqui);
      $arregloadebvolver=[];
      foreach (range(2012,date('Y')) as $key => $value) {
        $arregloadebvolver[$value]=array('I' =>0 , 'E'=>0);
      }
      if($resultados){
        foreach ($resultados as $row) {
            $arregloadebvolver[$row->anio][$row->tipo]=$row->total;
        }
      }  
      $arr_data['datos']=$arregloadebvolver;
      echo json_encode($arr_data);
    }     
 }
 /* params configuracion del arreglo
    creara un arreglo de tipo
    "formato"=>edades('ninos'=>0,'jovenes'=>0,'adultos'=>0) 
    en funcion de la configuracion recibida
    returna el arreglo obtenido
 */
private function get_arreglo($config){
    extract($config);
  if(strcmp($tipo,"hora")==0){
    $date=$date." ";
   foreach ($areglorango as $valor ) {
        $arr_data[$date.$valor] = $edades;
    }
  }elseif((strcmp($tipo, "dia")==0)||(strcmp($tipo,"mes")==0)){
      foreach ($areglorango as $valor ) {
      $separador=($valor<10) ? "-0" :"-";
      $arr_data[$date.$separador.$valor]=$edades;
      }
  }
  return $arr_data;  
 }
 // obtiene la llave en formato 'Y-m-d' de acuerdo al tipo
 private function get_llave($tipo,$filadia,$filahora){
  $llave="";
  if(strcmp($tipo,"hora")==0){
   $llave=$filadia." ".$filahora;
  }elseif (strcmp($tipo,"dia")==0) {
    $llave=$filadia;
  }elseif (strcmp($tipo,"mes")==0) {
    if($filadia<10){
        $filadia='0'.$filadia;
    }
    $llave=$filahora."-".$filadia;
  }
  return $llave;      
 }
/*
  @params configuracion datos,tipo,periodo,rango formato 
  crear un arreglo a partir de la configuracion recibida
  y lo llena con los datos obtenidos de la consulta
*/
 private function estadisitcasvisitas($config){
  extract($config);
  $datosclas=$modelo->showvisitas($tipo,$periodo); 
  $arr_data=$this->get_arreglo($config);    
        if($datosclas){    
            foreach($datosclas as $row ){
              $llave=$this->get_llave($tipo,$row->dia,$row->hora);
              if(strcmp($row->edad,"hd")==0){
                $arr_data[$llave]['niños']=$arr_data[$llave]['niños']+$row->total;
              }elseif(strcmp($row->edad,"et")==0){
                $arr_data[$llave]['jovenes']=$arr_data[$llave]['jovenes']+$row->total;
              }elseif(strcmp($row->edad,"md")==0){
                $arr_data[$llave]['adultos']=$arr_data[$llave]['adultos']+$row->total;
              }
              }
          }
        return $arr_data; 
 }
 /* @params contador para paginar 
    function recibe_datos() para paginar
 */
function grupos($cont=0){ 
    $grupo=new Grupo_model;
    $ruta="grupos";
    $pagina='grupo/mostrargrupos';
    $this->recibe_datos($pagina,$grupo,$cont,$ruta);
 }
 /* @params contador para paginar 
    function recibe_datos() para paginar
 */
function escuelas($cont=0){
    $this->load->model('Escuela_model','',TRUE); 
    $escuela=new Escuela_model;
    $ruta="escuelas";
    $pagina='escuela/mostrarescuelas';
    $this->recibe_datos($pagina,$escuela,$cont,$ruta);
}
/* @params contador para paginar 
    function recibe_datos() para paginar
 */
function mostrarusuarios($cont=0){
   $ruta="empleados";
    $persona=new Persona_model;
    $rol =get_rol_sesion(); 
    if($rol==1){
      $ruta="empleados";
    }elseif ($rol==3) {
      $ruta="usuarios";
    }
    $pagina='empleados/mostrarempleados';
    $this->recibe_datos($pagina,$persona,$cont,$ruta);
 }
/* @params pagina:vista que se cargara
          modelo:datos del modelo
          cont:para paginacion
          ruta:para configuracion de paginacion (metodo de la clase padre)
          area: para buscar solo los empleados de dicha area
*/
 public function recibe_datos($pagina="",$modelo,$cont=0,$ruta,$arwhere=[]){
        $resultados=$modelo->show($cont,"",$arwhere);
        $total=$modelo->show($cont,"contar",$arwhere,$arwhere);
        $config=$this->get_configuracion_paginacion(
                    base_url().'home/'.$ruta,
                    $total,
                    '3');
        $this->pagination->initialize($config);
        $pagina=($cont>$total) ? "../Error" :$pagina;
        $datos= $resultados ? $resultados : array();
        $this->cargarpage($pagina,$datos,$cont);
  }
  /* @params cuenta
      carga la vista mostrarempleados con solo el empelado que tiene la cuenta recibida
  */
 function mostarunusuario($cuenta){
    $this->load->model('User','',TRUE); 
    $datos=$this->User->mostrarporcuenta($cuenta);
    $pagina='empleados/mostrarempleados';  
    $this->cargarpage($pagina,$datos,$cont=0);
 }
 
  /*  
    vista
    las siguentes funciones solo carga vistas
      se declara la vista a la que se accedera 
      y se llama a la funcion cargar page para mostrar el contenido
      para permitir el acceso al usaurio vaya a core MY_CONTROLLER.php
      y agrege la vista al arreglo correspondiente
  */ 
  //vista 
  function sinacceso(){
    $pagina='../Permisos';
    $this->cargarpage($pagina);
  }
  //vista
  function error_home(){
    $pagina='../Error';
    $this->cargarpage($pagina);
  }
  //vista
  function reserva(){
    $pagina='prestamo/reservas';
    $this->cargarpage($pagina);
  }
  //vista
  function enprestamos(){
    $pagina='prestamo/enprestamo';
    $this->cargarpage($pagina);
  }
  //vista
  function actsf(){
    $pagina='evento/actividadsinfech';
    $this->cargarpage($pagina);
  }
  //vista
  function devolucion(){
     $pagina='prestamo/devolucion';
     $this->cargarpage($pagina);
  }
  //vista
  function pvencidos(){
    $pagina='prestamo/prestamovencido';
    $this->cargarpage($pagina);
  }
  public function descartados()
  {
    $pagina='libro/bajas';
    $this->cargarpage($pagina);
  }
  //vista
  function prestamo(){
    $pagina='prestamo/prestamo';
    $this->cargarpage($pagina);
  }
  //vista
  function eventonuevo()
  {
    $pagina='evento/nuevo';
    $this->cargarpage($pagina);
  }
  //vista
  function agregauser()
  {
    $pagina='empleados/agregar';
    $this->cargarpage($pagina);
  }
  //vista
  function agregaemple()
  {
    $pagina='empleados/agregarempl';
    $this->cargarpage($pagina);
  }
  //vista
  function novedades()
  {
    $pagina='libro/novedades';
    $this->cargarpage($pagina);
  }
  //vista
  function paginaejemplares(){
    $pagina='libro/ejemplares';
    $this->cargarpage($pagina);
  }
  //vista
  function paginafichas(){
    $pagina='libro/libros';
    $this->cargarpage($pagina);
  }
  //vista
  function paginaetiquetas(){
    $pagina='libro/buscaretiquetas';
    $this->cargarpage($pagina);
  }
  //vista
  function mostrarlib(){ 
    $pagina='libro/mostrarlib';
    $this->cargarpage($pagina);
  }
  //vista
  function actividades(){
    $pagina='evento/actividad';
    $this->cargarpage($pagina);
  }
  function cargar_archivo(){
    $pagina='libro/cargaProm';
    $this->cargarpage($pagina);    
  }
  // fin de funciones que cargan vistas
}
?>