<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller { 
//arreglo de roles y paginas a las que puede acceder
// 1 direccion,2 procesos,3 prestamo,4 difusio,5 sala ,6 recepcion , 7 usuario ,8 admin 
// 9 todos
var $empleados=array('1'=>array(
                        'reportes/reportes','admin/config_admin','admin/inicial'
                          ), 
                      '2'=>array(
                          'empleados/inicialprocesos','libro/mostrarlib',"Pdf/barcode","libro/ejemplares",'fichas/actualizarficha','admin/config_admin'
                          ,'empleados/inicial','libro/libros','libro/buscaretiquetas','libro/cargaProm','libro/bajas'
                          ),
                      '3'=>array(
                          'grupo/mostrargrupos','escuela/mostrarescuelas','empleados/mostrarempleados','empleados/agregar','prestamo/reservas',
                          'empleados','empleados/show','admin/config_admin','empleados/inicial','prestamo/prestamo','prestamo/enprestamo'
                          ,'prestamo/prestamovencido','empleados/menuusuarios','empleados/inicialprestamos'
                          ),
                      '4'=>array(
                          'evento/mostrareventos','evento/nuevo','evento/actividad','evento/nuevaacti','libro/novedades'
                          ,'admin/config_admin','empleados/inicial','evento/actividadsinfech','evento/actividades','evento/show'
                          ),
                      '5'=>array(
                          'admin/config_admin','estadistica/prestamointer','admin/inicial'
                          ),
                      '6'=>array(
                          'admin/config_admin','estadistica/visitasregistro','admin/inicial'
                          ),
                      '7'=>array(
                        'admin/config_admin','usuarios/inicial','usuarios/reservar'
                          ),
                      '8'=>array(
                          'admin/inicio','admin/config_admin','empleados/mostrar','empleados/showempleados','empleados/agregarempl'
                          ),

                      '9'=>array(
                          'evento/actividad','admin/config_admin','evento/show'));
var $paginaerro="../Error";
 function __construct()
 {
   parent::__construct();
   $this->load->helper(array('sesion_helper','url'));
   $this->load->library('pagination');
   $this->load->library('session');
 }
  function logout(){
    logout();
  }
  function permitiracceso($irapagina){
    $accesso=false;
    $rol=get_rol_sesion();
    $paginas=$this->empleados[$rol];
    if(in_array($irapagina,$paginas)){
        $accesso=true;
    }else{
      $accesso=false;
    }
    if(strcmp($irapagina,'../Error')==0){
      $accesso=true;
    }else if(strcmp($irapagina,'../Permisos')==0){
      $accesso=true;
    }
    return $accesso;
  }  
  // metodo para eliminar imagenes utilizado por controladores admin y actividad
    function borrarimagen($img){
        if(file_exists("./images/".$img)){
            return unlink("./images/".$img);
        }else{
            return true;
        }
        $thum=explode(".", $img);
        $img=$thum[0]."_thumb.".$thum[1];
        if(file_exists("./images/thumbs/".$img)){
          unlink("./images/thumbs/".$img);
        }
    }
    //metodo para verificar que la extension del archivo es la correcta, utilizado por los controladores admin y actividad
    //recibe como parametros las extensiones permitidas
    function extecorrecta($nombre,$arregloextension=array()){
        return array_search($nombre,$arregloextension,true);  
    }
    /*  @párams baseurl:url sobre la que se llevara a cabo la paginacion
        total:total de registro a paginar
        urlsegment: segmento de la url donde se paginara 
        retorna la configuracion de la paginacion
    */
    function get_configuracion_paginacion($baseurl,$total,$urlsegment){
      $config['per_page'] = '10';
      $config['cur_tag_open'] = '<li><a href="#">';
      $config['cur_tag_close'] = '</a></li>';
      $config['num_tag_open'] = '<li>';
      $config['num_tag_close'] = '</li>';
      $config['last_link'] = "<button class='btn btn-default btn-xs'>Último</button>";
      $config['first_link'] = "<button class='btn btn-default btn-xs'>Primero</button>";
      $config['next_link'] = '&raquo;';
      $config['next_tag_open'] = '<li>';
      $config['next_tag_close'] = '</li>';
      $config['prev_link'] = '&laquo;';
      $config['prev_tag_open'] = '<li>';
      $config['prev_tag_close'] = '</li>';
      $config['base_url']=$baseurl;
      $config['total_rows']=$total;
      $config['uri_segment'] = $urlsegment; 
      return $config;
    }
}