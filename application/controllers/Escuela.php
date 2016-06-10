<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Escuela extends MY_Controller {
	function __construct() {
        parent::__construct(); 
       $this->load->model('Escuela_model','',TRUE);
    }
    public function editar(){
        if(get_rol_sesion()==3){
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data->nombre)&&isset($data->id)){    
                if(strlen($data->nombre)>0&&strlen($data->id)>0){
                        $remp=array("<",">");
                        $escuela['nombre']=str_replace($remp,"",$data->nombre);
                        $editado=$this->Escuela_model->editar($escuela,$data->id);
                        if($editado){
                            $arr['mensaje']="Actualización exitosa";
                        }else{
                            $arr['mensaje']="Error al actualizar. Intente de nuevo";
                        }
                        
                }else{
                    $arr['mensaje']="Falta Nombre";   
                }
                echo json_encode($arr);
            } 
        }
    }
    public function eliminar(){
        if(get_rol_sesion()==3){
            $arr=array();
            $data = json_decode(file_get_contents("php://input"));   
                  if(isset($data->idesc)){           
                        $eliminado=$this->Escuela_model->eliminar($data->idesc);
                        if($eliminado){
                            $arr['mensaje']="La escuela  ha sido eliminada"; 
                        }else{
                            $arr['mensaje']="No se pudo eliminar";
                        }
                   }else{
                    $arr['mensaje']="Faltan datos"; 
                   }
            }
            echo json_encode($arr);    
    }
    public function agregar(){
        if(get_rol_sesion()==3){
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data->nombre)){    
                if(strlen($data->nombre)>0){
                        $remp=array("<",">");
                        $nombre=str_replace($remp,"",$data->nombre);
                        $agregado=$this->Escuela_model->agregar($nombre);
                        if($agregado){
                            $arr['mensaje']="Se agregó correctamente";
                        }else{
                            $arr['mensaje']="Error al agregar. Intente de nuevo";
                        }
                        
                }else{
                    $arr['mensaje']="Falta Nombre";   
                }
                echo json_encode($arr);
            } 
        } 
    }
}
    
?>