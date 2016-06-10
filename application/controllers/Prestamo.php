<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prestamo extends CI_Controller {
    function __construct() {
        parent::__construct(); 
        $this->load->model('Libro_model','',TRUE);
        $this->load->model('Prestamo_model','',TRUE);
        $this->load->model('User','',TRUE);
    }
     function json(){       
     if($this->session->userdata('logged_in'))
       {
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data)){
            $arr_data=array();
            if(isset($data->ejemplares)){
                $idprestamo=date('YmdHis')."I";
                $fechaprestamo=date('Y-m-d H:i:s');
                $cont=0;
                $cuenta=isset($data->cuenta) ? $data->cuenta : NULL;
                foreach ($data->ejemplares as $ejemplar) {
                    //verificamos que sean n° de adquisicion válidos
                    if($this->validacion($ejemplar->nadqui)){
                        $exito=$this->Libro_model->addprestamo($cuenta,$ejemplar->nadqui,$idprestamo,$idprestamo.$cont,$fechaprestamo,"I");
                        $exito=$exito&&$exito;
                        $cont++;
                    }
                    if($exito){
                        $arr_data['mensaje']='Préstamo registrado correctamente';
                    }else{
                        $arr_data['mensaje']='Ocurrió un problema, consulte al administrador del sistema';
                    }                           
                }  
            }
            echo json_encode($arr_data);  
        }
    } else {
             //
             redirect(base_url(), 'refresh');
           }
     }
      private function validacion($dnad){
        $valido=false;
        $retorno=false;
        $libro=new Libro_model;
        //validacion del numero de adquisicion
        $valido=preg_match('/^[\d]{1,15}$/', $dnad);
        if($valido==1){
            $datos=$libro->findbynad($dnad);
            $retorno=$datos ? true: false;        
        }
        return $retorno;
    }
     private function get_sesion(){
        return $this->session->userdata('logged_in');
    }
     private function get_rol_sesion($sesion){
        return $sesion['rol'];
    }

    function saldarmulta(){
        $sesion=$this->get_sesion();
        if(isset($sesion)){
            if($this->get_rol_sesion($sesion)==3){
                $arr_data=array();
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data->multa)&&isset($data->mont)){
                      if($this->Prestamo_model->saldar($data->multa,$data->mont)){
                        $arr_data['mensaje']="Correcto";
                      }else{
                        $arr_data['mensaje']="No fue posible realizar la acción";
                      }
                }else{
                    $arr_data['mensaje']="Falta N° de Adquisición";
                }
                echo json_encode($arr_data);
            }else{
                redirect('home', 'refresh');
            }
        }else{
             redirect('home', 'refresh');
        }
    }

    function devolver(){
        $sesion=$this->get_sesion();
        if(isset($sesion)){
            if($this->get_rol_sesion($sesion)==3){
                $arr_data=array();
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data->nadqui)){
                   ///  saber si el usuario cumplio con la fecha $this->datos_prestamo($data->nadqui);
                   /// conocer si el libro tiene reservas
                    $multa=$data->multar?true:false;
                    return  $this->Prestamo_model->devolucion($data->nadqui,$multa);         
                }else{
                    $arr_data['mensajeacionlibro']="Falta N° de Adquisición";
                }
            }else{
                redirect('home', 'refresh');
            }
        }else{
             redirect('home', 'refresh');
        }
    }
    function multar(){
        $sesion=$this->get_sesion();
        if(isset($sesion)){
            if($this->get_rol_sesion($sesion)==3){
                $arr_data=array();
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data->nadqui)&&isset($data->cuenta)){
                   echo $data->cuenta;                   
                }else{
                    $arr_data['mensajeacionlibro']="Falta N° de Adquisición";
                }
            }else{
                redirect('home', 'refresh');
            }
        }else{
             redirect('home', 'refresh');
        }
    }
    function fin_user_prestamo_inter(){
        $sesion=$this->get_sesion();
        if($sesion){
            $arr_data=array();
            if($this->get_rol_sesion($sesion)==5){
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data->cuenta)){ 
                     $this->load->model('User','',TRUE); 
                     $usuario=$this->User->find_by_cuenta($data->cuenta);     
                            if($usuario){
                                $arr_data['usuario']=$usuario;
                            }else{
                                $arr_data['mensaje']="El usuario no existe"; 
                            }
                               
                }
            }
        }        
         echo json_encode($arr_data);
    }
     function find_user_by_id_in_prestamo(){
        $sesion=$this->get_sesion();
        if($sesion){
            if($this->get_rol_sesion($sesion)==3){
                $arr_data=array();
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data->cuenta)){
                   if(strlen($data->cuenta)>0){
                        $this->load->model('User','',TRUE); $arr_data['usuario']="";
                        $usuario=$this->User->find_by_cuenta($data->cuenta);     
                        if($usuario){
                            $datosuser=$this->get_datosusuario($usuario);
                            $hoy=date("Y-m-d H:i:s");
                            if(strtotime($datosuser['vigencia'])>strtotime($hoy)){
                                    $bloqueo=$this->User->userbloqueado($data->cuenta);
                                    $multas=$this->User->multas($data->cuenta);
                                    $enprestamo=$this->User->en_prestamo($data->cuenta);
                                    $maxrenovacion=$this->get_renovacion($datosuser);
                                    $diasentrega=$this->get_diasentrega($datosuser);
                                    $prestados=$enprestamo ? $this->prestmamosvencidos($enprestamo,$hoy,$maxrenovacion,$diasentrega):[];
                                    $arr_data['usuario']=$usuario;
                                    $arr_data['bloqueado']=$bloqueo;
                                    $arr_data['multas']=$multas;
                                    $arr_data['mensaje']="";
                                    $arr_data['total']=$this->Libro_model->total_en_prestamo($data->cuenta); 
                                    $arr_data['prestados']=$prestados;
                                    $arr_data['datosuser']=$datosuser;       
                                    echo json_encode($arr_data);
                            }else{
                                $arr_data['mensaje']='Renovar vigencia';
                                echo json_encode($arr_data);    
                            }
                        }else{
                            $arr_data['mensaje']='El usuario no existe';
                            $arr_data['usuario']='';$arr_data['total']='';
                            echo json_encode($arr_data);
                        } 
                    }else{
                        $arr_data['mensaje']='Ingrese Cuenta Usuario';
                        echo json_encode($arr_data);    
                    }
                }else{
                    $arr_data['mensaje']='No se han recibido datos de búsqueda';
                    echo json_encode($arr_data);
                }
            }else{
                redirect('home', 'refresh');
            }
        }
    }
    private function get_renovacion($datosuser){
        $renovacion=0;
        foreach ($datosuser as $key => $value){
            if(strcmp($key,'renovar')==0){
                $renovacion=$value;
            }
        }        
        return $renovacion;
    }
     private function get_diasentrega($datosuser){
        $diasentrega=0;
        foreach ($datosuser as $key => $value){
            if(strcmp($key,'diasentrega')==0){
                $diasentrega=$value;
            }
        }        
        return $diasentrega;
    }
    private function prestmamosvencidos($enprestamo,$hoy,$maxrenovacion,$diasentrega){
        $prestados=[];
        foreach ($enprestamo as $clave=>$prestamo) {
            $libroprestado['reservado']=false;
            $libroprestado['idpre']=$prestamo->iddetalleprestamo;
            $libroprestado['titulo']=$prestamo->titulo;
            $libroprestado['renopermi']=$maxrenovacion-$prestamo->contreno;
            $libroprestado['contreno']=$prestamo->contreno;
            $libroprestado['fechaprestamo']=$prestamo->fechaprestamo;
            $libroprestado['entrega']=$prestamo->entrega;
            $libroprestado['nadqui']=$prestamo->nadqui;
            //$manana = strtotime ( '+1 day' , strtotime ($hoy ) ) ;
            $vencido=strtotime($prestamo->entrega)<strtotime($hoy);
            $libroprestado['vencido']=strcmp($prestamo->entrega,date('Y-m-d'))==0?FALSE: $vencido;
            $libroprestado['porcentaje']=$prestamo->porcentaje;
            //verificamos estaod del libros si fue reservado, si la fecha limite de reserva a pasado la
            //reserva sera eliminada
            $reservado=$this->Libro_model->reservado($prestamo->nadqui);
            if($reservado){
                $reservavalida=$this->validarfechareserva($reservado);
                $libroprestado['reservado']=$this->Libro_model->reservado($prestamo->nadqui);
            }

            if($vencido){   
                $libroprestado['alert']='danger';  
               $s=strtotime($hoy)-strtotime($prestamo->entrega); 
                $d=intval($s/86400); 
                if($d<1){
                    $d=1;
                } 
                $retraso = $d;
                $libroprestado['diasretraso']=$retraso; 
            }else{
                //$s=strtotime($prestamo->entrega)-strtotime($hoy); 
                //$d=intval($s/86400);  
                //$restantes = $d;
                //$diasparaentregar=$diasentrega+($prestamo->contreno*2);
                //$procentaje=((($diasparaentregar)-$restantes)/$diasparaentregar)*100;
                
                $libroprestado['alert']='info';
               //$libroprestado['disretraso']=0;
            }
            array_push($prestados, $libroprestado);
        }
        return $prestados;
    }
    private function ejemplarreservado($nadqui){
        $reserva=$this->Libro_model->reservado($nadqui);
    }
     private function validarfechareserva($reservas){
        $hoy=date("Y-m-d H:i:s");
        foreach ($reservas as $reserva) {
            if(strtotime($reserva->limite)<strtotime($hoy)){
                $this->Libro_model->eliminarreserva($reserva->nadqui);
                return false;
            }else{
                //returna true
                return true;
            }
        }
     }
   private function get_datosusuario($usuario){
        $maxpermitido=0;  $diasentrega=0; $renovar=0; $multa=0; $vigencia=0; $registrado="";
        $infousuario=[];
        foreach ($usuario as $row) {
            $infousuario['maxpermitido']=$row->cantlibros;
            $infousuario['diasentrega']=$row->diasentrega;
            $infousuario['renovar']=$row->renovacion;
            $infousuario['multa']=$row->montomulta;
            $infousuario['vigencia']=$row->vigente;
        }
        return $infousuario;
    }
     function renovar(){
        $sesion=$this->get_sesion();
        if($sesion){
            if($this->get_rol_sesion($sesion)==3){
                $arr_data=array();
                $data = json_decode(file_get_contents("php://input"));
                if(!$this->User->en_prestamovencido($data->cuenta)&&!$this->User->multas($data->cuenta)){
                    if(isset($data->iddetalle)){
                        $this->Prestamo_model->renovar($data->iddetalle);
                        $arr_data['mensaje']="Renovacion exitosa";
                        $arr_data['alertify']="1";
                    }
                }else{
                    $arr_data['mensaje']="El usuario tiene prestamos vencidos o multas registradas";
                }
                echo json_encode($arr_data);  
            }
        }
    }
    private function consulta_prestamos($tipo,$modelo){
        $sesion=$this->get_sesion();
        if($sesion){
            if($this->get_rol_sesion($sesion)==3){
                $arr_data= array();
                $data = json_decode(file_get_contents("php://input"));
                $inicio=(isset($data->inicio))?$data->inicio:0;
                $cantidad=(isset($data->cantidad))?$data->cantidad:10;
                $nadqui=(isset($data->nadqui))?$data->nadqui:false;
                $cuenta=(isset($data->cuenta))?$data->cuenta:false;
                $resultados=$modelo->obtener_datos($inicio,$cantidad,$nadqui,$cuenta,$tipo);
                $arr_data['resultado']=$resultados ?$resultados:[];
                return $arr_data;
            }
        }
    }
    function get_vencidos(){
        $modelo= new Prestamo_model;
        echo json_encode($this->consulta_prestamos("vencidos",$modelo));
    }
    function get_prestamos(){
        $modelo= new Prestamo_model;
        echo json_encode($this->consulta_prestamos("enprestamo",$modelo));
    }
    function get_reservas(){
        $this->load->model('Reserva_model','',TRUE);
        $modelo= new Reserva_model;
        $modelo->eliminar_reservas_vencidas();
        echo json_encode($this->consulta_prestamos("reservas",$modelo));
    }
}
?>

