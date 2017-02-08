<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class VerifyLogin extends MY_Controller {

    var $cuenta = "", $password = "";

    var $respuesta = array('mensaje' => "", 'alert'=>'' );

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
            $this->buscarUsuario();      
        }
     }

     private function getDataSession($datos){
        $sess_array = array();

        foreach($datos as $row)
        {
            $rol=isset($row->rol) ? $row->rol : 7;
            $sess_array = array(
              'cuentausuario' => $row->cuentausuario,
              'pnombre' => $row->pnombre,
              'rol'=>$rol,
              'idpersona'=> $row->curp
           );   
        }

        $sess_array['token']= md5("correcto" . $this->cuenta . rand(1,10) );
        
        return $sess_array;  
     }

    private function validarIntentos($intentos_fecha){
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

    private function buscarUsuario(){  
      
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
        $intentosvalidos=$this->validarIntentos($intentos_fecha);   
       
        if($intentosvalidos){
          $this->incrementarIntentoLogueo($cuenta,'cuentausuario','usuariobiblio',$result);
        }
       
        $datos=$result ? $result['datos'] : [];
        $acccesotipo='accesousuarios';
      
      }else if($this->Persona_model->findadmin($cuenta)||$this->User->email($cuenta)){
        
        $result=$this->User->usuariode($cuenta, $password);

        $intentos_fecha=$this->User->get_intentos_fecha($cuenta,'empleados','cuenta');
        $intentosvalidos=$this->validarIntentos($intentos_fecha);   
        
        if($intentosvalidos){
          $this->incrementarIntentoLogueo($cuenta,'cuenta','empleados',$result);
        }
        
        $datos=$result?$result:[];
        $acccesotipo='acccesoempl';  

      }
      
      if($intentosvalidos){
          $this->usuarioCorrecto($result,$datos,$acccesotipo);
      }else{
          $this->respuesta['alert'] = 'warning';
          $this->respuesta['mensaje'] = 'Se ha denegado el inicio de sesión por 4 intentos fallidos.Intenta de nuevo mas tarde';  
      }
    }

   private function incrementarIntentoLogueo($cuenta,$campo,$tabla,$result){
      if(!$result){
        $this->User->actualizarIntento($cuenta,1,$campo,$tabla);
      }else{
        $this->User->actualizarIntento($cuenta,0,$campo,$tabla);
      }
   }

   private function usuarioCorrecto($acceso,$datos,$acccesotipo){
      if($acceso){
          $this->verificarPermisosAcceso($datos,$acccesotipo);
      }else{
          $this->respuesta['alert'] = 'error';
          $this->respuesta['mensaje'] = 'Datos Incorrectos';        
      }
   }
   //el usuario se logueo correctamente , esta funcion verifica si hay permisos de acceso al sistema
   private function verificarPermisosAcceso($datos,$acccesotipo){
      
      $sess_array = $this->getDataSession($datos);

      $bloqueo=$sess_array['rol']!=7 ? false : $this->isBloqueado($sess_array['cuentausuario']);

      if($this->Biblioteca_model->getpermisoBiblio($acccesotipo)||$sess_array['rol']==1||$sess_array['rol']==8){    
        if(!$bloqueo){
          $this->session->set_userdata('logged_in', $sess_array);
          $this->respuesta['alert'] = 'success';
          $this->respuesta['mensaje'] = 'Se esta redirigiendo a la pagina principal..';
          $this->respuesta['token'] = $sess_array['token'];
        }else{
          $this->respuesta['alert'] = 'error';
          $this->respuesta['mensaje'] = 'Tu cuenta ha sido bloqueada';
        }
      }else{
        $this->respuesta['alert'] = 'error';
        $this->respuesta['mensaje'] = 'Servicio temporalmente no disponible.';
      } 
   }
   
   private function isBloqueado($cuenta){
      return $this->User->userbloqueado($cuenta);
   }

   function dirigira($dir){
      redirect($dir, 'refresh');
   }
   

  function loginandroid(){
    $data = json_decode(file_get_contents("php://input"));
    
    if(isset($data->user->email)&&isset($data->user->pass)){

        $this->cuenta = $data->user->email;
        $this->password = $data->user->pass;
        $this->buscarUsuario();
       
    }else{
      $this->respuesta['alert']='error';
      $this->respuesta['mensaje']="Ingrese cuenta y password";        
    }

    echo json_encode($this->getRespuesta());
  }

  private function getRespuesta(){
    return $this->respuesta;
  }

}
?>