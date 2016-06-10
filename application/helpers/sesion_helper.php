<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
if(!function_exists('logout'))
{
	function logout(){
  		$ci =& get_instance();
    	$ci->session->unset_userdata('logged_in');
    	session_destroy();
    	redirect(base_url(), 'refresh');
  	}	
}
if(!function_exists('validarsesion'))
{
	function validarsesion(){
		$ci =& get_instance();
		return $ci->session->userdata('logged_in');
  	}	
}
if(!function_exists('get_rol_sesion'))
{
  function get_rol_sesion(){
        return validarsesion()['rol'];
  }
}
if(!function_exists('get_cuenta_sesion'))
{
  function get_cuenta_sesion(){
      return validarsesion()['cuentausuario'];   
  }
}
if(!function_exists('get_id_persona_en_sesion'))
{
  function get_id_persona_en_sesion(){
        return validarsesion()['idpersona']; 
  }
}
if(!function_exists('get__tipo_user'))
{
  function get__tipo_user(){
      $rol =get_rol_sesion();
      if(isset($rol)){
        if($rol=='1'){
            $userbi="empleado";
         }elseif($rol=='3'){
            $userbi="userbi"; 
        }
        return $userbi;
      }else{
        redirect('home');
      }
  }
}
if(!function_exists('get_nombre_persona_en_session'))
{
  function get_nombre_persona_en_session(){
    return validarsesion()['pnombre'];  
  }
}

