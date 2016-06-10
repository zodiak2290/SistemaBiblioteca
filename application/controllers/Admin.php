<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends MY_Controller {
    function __construct() {
        parent::__construct();
       $this->load->library('form_validation'); 
       $this->load->model('User','',TRUE);
    }
    function logout(){
        logout();
    }
    function logo(){
        if(validarsesion()['rol']==8){
            $arr_data=array();
            $imagentipo=$this->input->post('img');
            if(isset($imagentipo)){
                if(strcmp($imagentipo,"undefined")!=0){
                        $img="";$dir="";
                        if(isset($imagentipo)){
                            $img=$imagentipo.'.png';
                            $dir='./images/logo/';
                        }
                        if($this->borrarimagen($img)){
                            $arr_data['mensaje']=$this->cargarimagen($dir,$img);
                        }else{
                            $arr_data['mensaje']="Error al cargar la imagen intente de nuevo";
                        }
                    }else{
                        $arr_data['mensaje']="Falta Imagen";
                    }
                    echo json_encode($arr_data);
            }
        }
    }
    private function cargarimagen($dir,$nombre){
        //verificamos que eista la imagen
        if(isset($_FILES["file"]["name"])){
            //tamaño de la imagen
            if($_FILES['file']['size']<1000000){  
                if($this->extecorrecta($_FILES['file']['type'],array('png' => 'image/png'))){
                    $file=$nombre;
                    if(!is_dir($dir)){
                        mkdir($dir,0777);
                    }
                    if($file && move_uploaded_file($_FILES["file"]["tmp_name"], $dir.$file)){
                        return "Imagen agregada";
                    }
                }else{
                    return "Formato de imagen incorrecto";
                }    
              }else{ 
              return "Imagen demasiado pesada";
              } 
           }else{
            return "Falta imagen";
           } 
    }  
    function editar(){
        if(validarsesion()['rol']){
            $persona;
            $data = json_decode(file_get_contents("php://input"));
            $arr=array();
            $arr['correcto']=false;  
                if(strlen($data->dato)>0&&strlen($data->valor)>0){
                   if($this->validar_campos($data->dato,$data->valor)){
                    $emailexiste=false;$emailpersonexiste=false;
                        if(strcmp($data->dato,'email')==0){
                            $this->load->model('Persona_model','',TRUE);
                            $emailexiste=$this->email_existe($data->valor,get_cuenta_sesion());
                            $emailpersonexiste=$this->Persona_model->findaemail($data->valor);
                        }
                        if(!$emailexiste&&!$emailpersonexiste){
                            $usuario[$data->dato]=$data->valor;
                            if(get_rol_sesion()==7){
                                $curp="";
                                $persona=$this->Persona_model->findcuenta(get_cuenta_sesion());
                                if($persona){
                                      foreach ($persona as $row) {
                                        $curp=$row->curp;
                                      }
                                 }     
                                $editado=$this->Persona_model->update($usuario,$curp);
                            }else{
                                $editado=$this->User->editadmin($usuario,get_cuenta_sesion());
                            }
                            if($editado){
                                $arr['mensaje']="Actualización Exitosa";
                                $arr['correcto']=true;
                            }else{
                                $arr['mensaje']="No fue posible actualizar los datos. Intente de nuevo";
                            }
                        }else{
                            $arr['mensaje']="Email no disponible";       
                        }
                   }else{
                     $arr['mensaje']="Formato no válido"; 
                   }
                
                }else{
                    $arr['mensaje']="Faltan Campos";   
                }  
        } else{
            $arr['mensaje']="Inicie sesión";
        }

               echo json_encode($arr); 
    }
        private function validar_campos($dato,$valor){
        if(strcmp($dato,'email')==0){
            return preg_match('/^[^0-9][a-zA-Z0-9\_.-]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $valor);
        }else if(strcmp($dato,'password')==0){
             return preg_match('/^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ_.*]{4,255}$/', $valor);
        }else if(strcmp($dato, "nombre")==0){
            return preg_match('/^[a-zñÑáéíóú_\/\-\s]{0,255}$/i', $valor);
        }else{
            return preg_match('/^[a-zñÑáéíóú\d_\/\-\s]{0,255}$/i', $valor);
        }
    }
    public function email_existe($email,$cuenta){
        $user = $this->User->emailid($email,$cuenta);
        return $user;
    }
    public function editpas(){
        if(validarsesion()['rol']){
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data)&&isset($data->nueva)&&isset($data->actual)&&isset($data->confi)){    
                if(strlen($data->nueva)>0&&strlen($data->actual)>0&&strlen($data->confi)>0){
                   if(strcmp(md5($data->actual),$this->get_pass())==0&&strcmp($data->confi,$data->nueva)==0){
                        if($this->contrasean($data->nueva)){
                            $usuario['password_d']=md5($data->nueva);
                            if(validarsesion()['rol']!=7){
                                $editado=$this->User->editpass($usuario,get_cuenta_sesion());
                            }else{
                                $this->load->model('Persona_model','',TRUE);
                                $editado=$this->Persona_model->editpass($usuario,get_id_persona_en_sesion());
                            }
                            if($editado){
                                $arr['mensaje']="Actualización exitosa"; 
                            }else{
                                $arr['mensaje']="No se pudó actualizar la contraseña. Intente de nuevo";
                            }
                        }else{
                           $arr['mensaje']="Contraseña con caracteres alfanúmericos y - _ . *";  
                        }
                   }else{
                    $arr['mensaje']="La contrasea actual es incorrecta"; 
                   }
                }else{
                    $arr['mensaje']="Falta contraseña de confirmación";   
                }
             
            } 
            echo json_encode($arr);    
        }
                 
    }
    private function get_pass(){
        $cuenta=validarsesion()['cuentausuario'];
        return (validarsesion()['rol']!=7) ?  $this->User->get_pass_admin($cuenta):$this->User->get_pass($cuenta); 
    }
    private function solo_letras($nombre){
        $valido=preg_match('/^[a-zñáéíóú\s]{4,254}$/i',$nombre);
        return $valido ? $nombre : false;
    }
    private function contrasean($pass){
        $valido=preg_match('/^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ_.*]{4,255}$/', $pass);
        return $valido ? $pass : false;
    }
    function graficas(){
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->anio)){
            echo json_encode($this->get_Prestamos_de_anios($data->anio));
        }else if(!isset($data->rango->anioinicio)){
            echo json_encode($this->get_Prestamos(2012,date('Y')));
        }else{
            echo json_encode($this->get_Prestamos($data->rango->anioinicio,$data->rango->aniofin));
        }
    }
    private function get_Prestamos_de_anios($anio){
        $this->load->model('Prestamo_model','',TRUE);
        $prestamo=new Prestamo_model;
        $resultados['labels']=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","JUlio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $resultados['prestamosexternos']=$prestamo->get_prestamos_by_graficas_por_anio($anio,'E');
        $resultados['prestamosinternos']=$prestamo->get_prestamos_by_graficas_por_anio($anio,'I');
            $respuesta['datasets']=array();
            $respuesta['labels']=$resultados['labels'];
            if($resultados['prestamosexternos']){
                array_push($respuesta['datasets'],$this->get_Dataset($resultados,"prestamosexternos","Externos"));
            }
            if($resultados['prestamosinternos']){
                array_push($respuesta['datasets'],$this->get_Dataset($resultados,"prestamosinternos","Internos"));
            }

        return $respuesta;

    }
    /*Recibe año de inicio y año final->para consultar los prestamos realizados en dicho periodo */
    private function get_Prestamos($fechainicio,$fechafin){
        $anios=range(intval($fechainicio),intval($fechafin));
        $this->load->model('Prestamo_model','',TRUE);
        $prestamo=new Prestamo_model;
        $resultados['labels']=$anios;
        $and="and year(fechaprestamo) between ".$fechainicio." and ".$fechafin;
        $resultados['prestamosexternos']=$prestamo->get_prestamos_by_graficas('E',$and);
        $resultados['prestamosinternos']=$prestamo->get_prestamos_by_graficas('I',$and);
        $respuesta['datasets']=array();
        $respuesta['labels']=$resultados['labels'];
        if($resultados['prestamosexternos']){
            array_push($respuesta['datasets'],$this->get_Dataset($resultados,"prestamosexternos","Externos"));
        }
        if($resultados['prestamosinternos']){
            array_push($respuesta['datasets'],$this->get_Dataset($resultados,"prestamosinternos","Internos"));
        }
        return $respuesta;
    }
    /*Formato de respuesta para utilizar en las graficas hightcharts */
    private function get_Dataset($resultados,$tipo,$label){
        $dataset=array();
            $color='rgba('.rand(0,255).','.rand(0,255).','.rand(0,255); 
            $dataset['fillColor']=$color.",0.5)";
            $dataset['strokeColor']=$color.",0.8)";
            $dataset['highlightFill']=$color."0.75)";
            $dataset['highlightStroke']=$color.",1)";
            $dataset['data']=$this->procesar_resultado($resultados,$tipo);
            $dataset['label']=$label;
            return $dataset;
    }
    private function procesar_resultado($arreResultados,$tipo){
        $arregloretorno=array();
        $data=array();
        foreach ($arreResultados[$tipo] as $key => $value) {
            $data[$value['anio']]=$value['total'];
        }
        foreach ($arreResultados['labels'] as $value) {
            if(isset($data[$value])){
                array_push($arregloretorno,intval($data[$value]));
            }else{
                array_push($arregloretorno,0);
            }
        }
        return $arregloretorno;
    }

}
    
?>