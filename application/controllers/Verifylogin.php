<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class VerifyLogin extends MY_Controller {

    var $cuenta = "", $password = "";

    function __construct()
    {
        parent::__construct();
        $this->load->model('User','',TRUE);
        $this->load->model('Biblioteca_model','',TRUE);
        $this->load->model('Persona_model','',TRUE);
    }
    
    function index()
    {
        //Validacion
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cuenta','Cuenta','required');
        $this->form_validation->set_rules('password','Password','required');
         
        $this->cuenta = $this->input->post('cuenta');
        $this->password =  $this->input->post('password');

        if($this->form_validation->run() == FALSE)
        {
            //Si no valida el formulario de acceso redirige a pagina  principal
            redirect('login','refresh');
            $this->form_validation->set_message('Credenciales', 'Ingresar datos');
            $this->dirigira('login');
        }
        else
        {
            //Verifica que user y pass sean correctos
            $this->check_database();      
        }
     }

     private function usuario_logueado($datos){
        $sess_array = array();
        foreach($datos as $row)
        {
            $rol=isset($row->rol)?$row->rol:7;
            $sess_array = array(
              'cuentausuario' => $row->cuentausuario,
              'pnombre' => $row->pnombre,
              'rol'=>$rol,
              'idpersona'=> $row->curp
           );   
        }
        $sess_array['token']= $this->input->post('token');
        return $sess_array;  
     }

    private function validar_Intentos($intentos_fecha){
      $num_intentos=0;
      $fecha_ultimo_intento=date("Y-m-d H:i:s");
      $continuar=false;

      if($intentos_fecha){
        foreach ($intentos_fecha as $row) {
            $num_intentos=$row->intentos;
            $fecha_ultimo_intento=$row->fechaintento;
        }
      }  
      $to = new DateTime();
      
      $res = $to->format('Y-m-d H:i:s');
      $minutos = ceil((strtotime($res) - strtotime($fecha_ultimo_intento)) / 60);
      if($num_intentos<4||$minutos>15){
          $continuar=true;
      }
      
      return $continuar;
    }

    function check_database(){  
      $result=false;
      $bloqueo=false;
      $intentos_fecha=false;
      $intentosvalidos=true;
      $datos=[];
      $acccesotipo="";

      $cuenta = $this->cuenta;
      $password = $this->password;
    

      $esUsuariob =  $this->User->is_userbiblio($cuenta);
      $cuentaNumerica = is_numeric($cuenta);

      if( ( $esUsuariob && $cuentaNumerica ) || $this->Persona_model->findaemail($cuenta)){
       
        $result = $this->User->is_usuariob($cuenta, $password);
        $intentos_fecha=$this->User->get_intentos_fecha($cuenta,'usuariobiblio','cuentausuario');
        $intentosvalidos=$this->validar_Intentos($intentos_fecha);   
       
        if($intentosvalidos){
          $this->incrementar_Intento_Logueo($cuenta,'cuentausuario','usuariobiblio',$result);
        }
       
        $datos=$result ? $result['datos'] : [];
        $acccesotipo='accesousuarios';
      
      }else if($this->Persona_model->findadmin($cuenta)||$this->User->email($cuenta)){
        
        $result=$this->User->usuariode($cuenta, $password);

        $intentos_fecha=$this->User->get_intentos_fecha($cuenta,'empleados','cuenta');
        $intentosvalidos=$this->validar_Intentos($intentos_fecha);   
        
        if($intentosvalidos){
          $this->incrementar_Intento_Logueo($cuenta,'cuenta','empleados',$result);
        }
        
        $datos=$result?$result:[];
        $acccesotipo='acccesoempl';  

      }
      
      if($intentosvalidos){
        $this->usuario_Correcto($result,$datos,$acccesotipo);
      }else{
        $this->session->set_flashdata('correcto','Se ha denegado el inicio de sesión por 4 intentos fallidos.Intenta de nuevo mas tarde');
        $this->dirigira('login');   
      }
    }

   private function incrementar_Intento_Logueo($cuenta,$campo,$tabla,$result){
      if(!$result){
        $this->User->actualizarIntento($cuenta,1,$campo,$tabla);
      }else{
        $this->User->actualizarIntento($cuenta,0,$campo,$tabla);
      }
   }

   private function usuario_Correcto($acceso,$datos,$acccesotipo){
      if($acceso){
        $this->verificar_Permisos_Acceso($datos,$acccesotipo);
      }else{
        $this->session->set_flashdata('correcto', 'Datos Incorrectos');
        $this->dirigira('login');        
      }
   }
   //el usuario se logueo correctamente , esta funcion verifica si hay permisos de acceso al sistema
   private function verificar_Permisos_Acceso($datos,$acccesotipo){
      $sess_array = $this->usuario_logueado($datos);
      $bloqueo=$sess_array['rol']!=7?false:$this->is_bloqueado($sess_array['cuentausuario']);
      if($this->Biblioteca_model->getpermisoBiblio($acccesotipo)||$sess_array['rol']==1||$sess_array['rol']==8){    
        if(!$bloqueo){
          $this->session->set_userdata('logged_in', $sess_array);
          $this->dirigira('home');
        }else{
          $this->dirigira('permisos');
        }
      }else{
        $this->dirigira('nodisponible');
      } 
   }
   private function is_bloqueado($cuenta){
      return $this->User->userbloqueado($cuenta);
   }
   function dirigira($dir){
      redirect($dir, 'refresh');
   }
   function loginandroid(){

//login prigin
    /*
    if (isset($_SERVER['HTTP_ORIGIN'])) {  
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");  
        header('Access-Control-Allow-Credentials: true');  
        header('Access-Control-Max-Age: 86400');   
    }  
      
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {  
      
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))  
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  
      
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))  
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");  
    } */ 
//
      $data = json_decode(file_get_contents("php://input"));
      if(isset($data->user->email)&&isset($data->user->pass)){
          $this->cuenta = $data->user->email;
          $this->password = $data->user->pass;
          $this->check_database();
          /*$resulty = $this->User->usuariode($cuenta, $password);
          if($resulty){
              $arr['usuario']=$resulty;
              $arr['mensaje']="Acceso exitoso";
              $arr['rol']=2;
              $arr['login']=true;
          }elseif(!$this->is_bloqueado($cuenta)){
            if($this->Biblioteca_model->getpermisoBiblio('accesousuarios')){ 
               $result = $this->User->loginandroi($cuenta, $password);
               $arr=[];
               if($result){
                  $arr['usuario']=$result;
                  $arr['mensaje']="Acceso exitoso";
                  $arr['login']=true;
                  $arr['rol']=1;
               }else{
                  $arr['usuario']='';
                  $arr['mensaje']="Cuenta o contraseña incorrectos";
                  $arr['login']=false;
               }
              }else{
                $arr['usuario']='';
                  $arr['mensaje']="Servicio no disponible";
                  $arr['login']=false;
              } 
           }else{
            $arr['usuario']='';
                $arr['mensaje']="Tu cuenta ha sido bloqueada";
                $arr['login']=false;
           }*/
           }else{
            $arr['login']=false;
            $arr['mensaje']="Ingrese cuenta y password";
            $arr['usuario']='';
           }
       echo json_encode($arr);
   }

}
?>