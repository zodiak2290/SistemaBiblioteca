<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Novedades extends CI_Controller {
	function __construct() {
        parent::__construct();
       $this->load->library('form_validation'); 
       $this->load->model('Libro_model','',TRUE);
    }
	function index(){	
       $this->cargar_vista();
    }
    private function sesion(){
       return $this->session->userdata('logged_in');
    }
    private function get_rol(){
        $ses=$this->sesion();
        return $ses['rol'];
    }
    function editar(){
        $sesion=$this->sesion();
        if($sesion){
            $rol=$this->get_rol();
                if($rol==4){
                    $arr_data=[];
                    $data = json_decode(file_get_contents("php://input"));
                    if(isset($data->nadqui)){
                        if($data->nadqui&&$data->valor&&$data->idb){
                            $noved=array();
                            $novedad['descripcion']=$data->valor;
                            $exito=$this->Libro_model->editarnove($novedad,$data->idb);
                            if($exito){
                                $arr_data['mensaje']="Actualización correcta";
                                $arr_data['exito']=true;
                            }else{
                                $arr_data['mensaje']="Error al actualizar";
                                $arr_data['exito']=false;
                            }
                           echo json_encode($arr_data);
                        }
                    }else{
                        redirect('home');
                    }    
            }
        }
    }
    function eliminar(){
        $sesion=$this->sesion();
        if($sesion){
            $rol=$this->get_rol();
                if($rol==4){
                $arr_data=[];
                $data = json_decode(file_get_contents("php://input"));  
                if(isset($data->nadqui)){
                    if($data->nadqui&&$data->idb){
                        $novedad=$this->Libro_model->nadquinove($data->nadqui);
                        if($novedad){
                            $imagen="";
                            foreach ($novedad as $row) {
                                $imagen=$row->imagen;
                            }
                            $this->borarimagen($imagen);
                            $exito=$this->Libro_model->eliminarnove($data->idb);
                            if($exito){
                                $arr_data['mensaje']="Eliminada";
                                $arr_data['exito']=true;
                            }else{
                                $arr_data['mensaje']="Error al eliminar";
                                $arr_data['exito']=false;
                            }
                        }    
                       echo json_encode($arr_data);
                    }
                }else{
                    echo "No se recibieron datos para eliminar";
                }        
            }else{
            redirect('home');
        }
        }else{
            redirect('home');
        }
    }
    private function borarimagen($img){
        unlink("./images/novedades/".$img);
    }
    function novedades(){
        echo json_encode($this->get_lista_novedades());
    }
    private function get_lista_novedades($tipo=''){
        $sesion=$this->sesion();
        if($sesion){
             $rol=$this->get_rol();
              if($rol==4){
                    $noved=strcmp($tipo,"lista")==0 ?$this->Libro_model->librosreciente():$this->Libro_model->novedades();
                    if($noved){
                        return $noved;
                    }
                }
        }else{
            redirect('home','refresh');
        }
    }
    function lista(){
        echo json_encode($this->get_lista_novedades('lista'));
    }
    function nueva(){
        $sesion=$this->sesion();
        if($sesion){
            $rol=$this->get_rol();
                if($rol==4){
                $arr_data=array();
                $mesnaje="";
                $titulo=$this->input->post('titulo');
                $imagen="";
                $subir="";
                $desc=$this->input->post('desc');
                $nadqui=$this->input->post('nadqui');
                if(isset($titulo)&&isset($desc)&&isset($nadqui)){
                       if(strcmp($titulo,"undefined")!=0&&strcmp($desc,"undefined")!=0){
                            $novedadestotal=$this->Libro_model->novedadestotal();
                            if($novedadestotal<16){
                                if(!$this->Libro_model->nadquinove($nadqui)){
                                       $imgin=$this->cargarimagen();
                                       if(strcmp($imgin,"Imagen agregada")==0){ 
                                       $subir=$this->Libro_model->novedadsubir($titulo,$this->namimg,$desc,$nadqui); 
                                       if($subir){
                                        $arr_data['total']=$novedadestotal;
                                            $mesnaje="Registrada";
                                            $imagen=$this->namimg;                              
                                       }
                                       }else{
                                        $mesnaje=$imgin;
                                       }
                                    }else{
                                        $mesnaje="Este ejemplar ya está en la lista de novedades";    
                                    }
                             }else{
                                $mesnaje= "Límite de novedades alcanzado";
                             }       
                            }else{
                                $mesnaje= "Faltan campos";
                            } 
                }else{
                    $mesnaje="Faltan campos";
                }
                $arr_data['mensaje']=$mesnaje;$arr_data['imagen']=$imagen;$arr_data['id']=$subir;
                echo json_encode($arr_data);
            }
        }
    }


    /*-------------------------------------------------*/

    function nuevas(){
        $sesion=$this->sesion();
        if($sesion){
            $rol=$this->get_rol();
                if($rol==4){
                $arr_data=array();
                $mesnaje="";
                $titulo=$this->input->post('titulo');
                $imagen="";
                $subir="";
                $desc=$this->input->post('desc');
                $nadqui=$this->input->post('nadqui');
                if(isset($titulo)&&isset($desc)&&isset($nadqui)){
                       if(strcmp($titulo,"undefined")!=0&&strcmp($desc,"undefined")!=0){
                            $novedadestotal=$this->Libro_model->novedadestotal();
                            if($novedadestotal<16){
                                if(!$this->Libro_model->nadquinove($nadqui)){
                                       $imgin=$this->cargarimagen();
                                       if(strcmp($imgin,"Imagen agregada")==0){ 
                                       $subir=$this->Libro_model->novedadsubir($titulo,$this->namimg,$desc,$nadqui); 
                                       if($subir){
                                        $arr_data['total']=$novedadestotal;
                                            $mesnaje="Registrada";
                                            $imagen=$this->namimg;                              
                                       }
                                       }else{
                                        $mesnaje=$imgin;
                                       }
                                    }else{
                                        $mesnaje="Este ejemplar ya está en la lista de novedades";    
                                    }
                             }else{
                                $mesnaje= "Límite de novedades alcanzado";
                             }       
                            }else{
                                $mesnaje= "Faltan campos";
                            } 
                }else{
                    $mesnaje="Faltan campos";
                }
                $arr_data['mensaje']=$mesnaje;$arr_data['imagen']=$imagen;$arr_data['id']=$subir;
                echo json_encode($arr_data);
            }
        }
    }
    /*-------------------------------------------------*/
    function editarimg(){
        $sesion=$this->sesion();
        if($sesion){
            $rol=$this->get_rol();
                if($rol==4){
                $arr_data=array();
                $mesnaje="";
                $imagen="";
                $subir="";
                $ida=$this->input->post('ida');
                if(isset($ida)){
                    $novedad=$this->Libro_model->nadquinoveid($ida);
                        if($novedad){
                            $imagen=$this->obtenernombreimagen($novedad);
                               $imgin=$this->cargarimagen();
                               if(strcmp($imgin,"Imagen agregada")==0){ 
                                $this->borarimagen($imagen);
                                $noved=array();
                                $novedad['imagen']=$this->namimg;
                                $subir=$this->Libro_model->editarnove($novedad,$data->ida);
                               //$subir=$this->Libro_model->novedadsubir($titulo,$this->namimg,$desc,$nadqui); 
                               if($subir){
                                    $mesnaje="Actualizada";
                                    $imagen=$this->namimg;                              
                               }
                               }else{
                                $mesnaje=$imgin;
                               }
                            }else{
                                $mesnaje="No existe. Verifique sus datos";    
                            } 
                }else{
                    $mesnaje="Faltan campos";
                }
                $arr_data['mensaje']=$mesnaje;$arr_data['imagen']=$imagen;$arr_data['id']=$subir;
                echo json_encode($arr_data);
            }
        }   
    }
    private function obtenernombreimagen($novedad){
        $imagen="";
        foreach ($novedad as $row) {
            $imagen=$row->imagen;
        }
        return $imagen;
    }
    private function extecorrecta($nombre){
            return array_search($nombre, array('jpg' => 'image/jpeg','png' => 'image/png','gif' => 'image/gif',),true);  
    }
     private function cargarimagen(){
        //verificamos que eista la imagen
        if(isset($_FILES["file"]["name"])){
            //tamaño de la imagen
            if($_FILES['file']['size']<1000000){  
                if($this->extecorrecta($_FILES['file']['type'])){
                    $file=date("YmdHis").$_FILES["file"]["name"];
                    $this->namimg=$file;
                    if(!is_dir("./images/novedades/")){
                        mkdir("./images/novedades/",0777);
                    }
                    if($file && move_uploaded_file($_FILES["file"]["tmp_name"], "./images/novedades/".$file)){
                        return "Imagen agregada";
                    }
                }else{
                    return "Formato de imagen incorrecto, intente con JPG,PNG,GIF";
                }    
              }else{ 
              return "Imagen demasiado pesada";
              } 
           }else{
            return "Falta imagen";
           } 
    }  
}
?>