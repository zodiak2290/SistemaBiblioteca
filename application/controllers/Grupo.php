<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Grupo extends CI_Controller {
	function __construct() {
        parent::__construct();
         $this->load->model('Grupo_model','',TRUE);   
    }
	
    
    private function validacion($dato,$tipo){
        //validamos los diversos campos de empleado
        $valido=false;
         if (strcmp($tipo,"nombre")==0) {
            $valido=preg_match('/^[a-zñáéíóú\s]{0,30}$/i',$dato);
        }elseif (strcmp($tipo, "num")==0) {
            $valido=preg_match('/^[\d]{0,9}$/',$dato);
        }elseif (strcmp($tipo, "monto")==0) {
            $valido=is_numeric($dato);
        }
        return ($valido==1) ? $dato : false;         
    }
    function agregar(){
        if($this->session->userdata('logged_in')){
            $session_data = $this->session->userdata('logged_in');
            $data['rol'] = $session_data['rol'];  
            if($data['rol']==3){  
            $data = json_decode(file_get_contents("php://input"));
            $arr_data=array();     
            if(isset($data->nombre)){$nombre=$data->nombre;}else{$nombre="";$arr_data['error']="El nombre del grupo es obligario";}
            if(isset($data->multa)){$multa=$data->multa;}else{$multa=0;}
            if(isset($data->dias)){$dias=$data->dias;}else{$dias=2;} 
            if(isset($data->reno)){$reno=$data->reno;}else{$reno=2;}
            if(isset($data->cant)){$cant=$data->cant;}else{$cant=2;}
            if(isset($data->vigencia)){$vigencia=$data->vigencia;}else{$vigencia=2;}
                $arr_data['nombre']=$this->validarinsertar($nombre);
                $arr_data['multa']=$this->validarinsertar($multa);
                $arr_data['dias']=$this->validarinsertar($dias);
                $arr_data['reno']=$this->validarinsertar($reno);
                $arr_data['cant']=$this->validarinsertar($cant);
                $arr_data['vi']=$this->validarinsertar($vigencia);


                if($this->validarinsertar($arr_data['nombre'])&&$this->validarinsertar($arr_data['multa'])&&$this->validarinsertar($arr_data['dias'])&&$this->validarinsertar($arr_data['reno'])&&$this->validarinsertar($arr_data['cant'])&&$this->validarinsertar($arr_data['vi'])){
                       $grupo= $this->Grupo_model->crearedit(null,$nombre,$multa,$dias,$reno,$cant,$vigencia); 
                         if($grupo){
                            $arr_data['mensaje']="Agregado correctamente";   
                        }else{
                            $arr_data['mensajes']="El nombre de este grupo ya está en uso";
                        }
                }else{$arr_data['mensajess']="No fue posible agregar el grupo. Intente de nuevo";}

                     echo json_encode($arr_data); 
                }
                redirect('home','refresh');     
        }else{
            redirect('login','refresh');
        }
    }
    private function validarinsertar($var){
        $retorno=false;
        if(is_numeric($var)||(is_numeric($var)&&$var==0)){
            $retorno=true;
        }elseif (is_bool($var)) {
                $retorno=$var;          
        }elseif(is_string($var)&&strlen($var)>0) {
            $retorno=true;
        }
        return $retorno;
    }
}
?>