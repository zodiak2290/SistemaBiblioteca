<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 //we need to call PHP's session object to access it through CI
class Restaurador  extends MY_Controller {
 function index()
    {   
      $data['content']="loginrestore";
      $this->load->view('layouts/layoutprincipal',$data);
    }
    function error(){
      $data['content']="errordatabase";
      $this->load->view('layouts/layoutprincipal',$data); 
    }
    function restore($token=""){ 
      $cuenta = $this->input->post('cuentausuario');
      $password = $this->input->post('password_d');
      $sesion=$this->session->userdata('logged_in');
      if((isset($cuenta)&&isset($password))){
        if((strcmp(sha1(md5($cuenta)),$this->config->item('user'))==0
                  &&strcmp(sha1(md5($password)),$this->config->item('pass'))==0)){
                    $ci =& get_instance();
                    $db_server   = $ci->config->item('dbserver');
                    $db_name     = $ci->config->item('dbname');
                    $db_username = $ci->config->item('dbuser');
                    $db_password = $ci->config->item('dbpass'); 
            $this->pdo = new PDO("mysql:host={$db_server};", "{$db_username}", "{$db_password}");
            $crear_db = $this->pdo->prepare('CREATE DATABASE IF NOT EXISTS '.$db_name.' COLLATE utf8_spanish_ci');               
            $crear_db->execute();
            //si la base de datos ha sudo creada procedemos a a cargar la vista big dump
            if($crear_db):
              $use_db = $this->pdo->prepare('USE '.$db_name.'');             
              $use_db->execute();
            endif;
            //si se ha creado la base de datos y estamos en uso de ella creamos las tablas
            if($use_db):  
                  $token=sha1(date('Y-m-d h:m:s'));
                  $data = array('token'=>$token,'rol'=>"11");
                  $this->session->set_userdata('logged_in',$data);
                  $this->load->view('restauradb');
              endif;
             }else{
              redirect('');
             } 
        }elseif (isset($sesion['token'])) {
            $this->load->view('restauradb');
        }else{
          //mostrar mensaje de error
          redirect('');
        }
    }

    
}
?>