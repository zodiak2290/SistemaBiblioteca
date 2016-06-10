<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reservar extends MY_Controller {
	function __construct() {
        parent::__construct(); 
       $this->load->model('Reserva_model','',TRUE);
    }
	 public function delete(){
	 	$data = json_decode(file_get_contents("php://input"));
	 	if(validarsesion()||isset($data->cuenta)){
	 			if(validarsesion()['rol']==7||isset($data->cuenta)){
	 				$arr_data=array();
                	$data = json_decode(file_get_contents("php://input"));
                	if(isset($data->idreserva)){
                		$this->load->model('Reserva_model','',TRUE);
                		$eliminada=$this->Reserva_model->eliminarreserva($data->idreserva);
                		$arr_data['mensaje']=$eliminada ? "Su reserva ha sido cancelada satisfactoriamente":"No fue posible cancelar. Intente mas tarde";
                		echo json_encode($arr_data);
                	}
	 			}
	 		}else{
	 			echo "string";
	 		}
	 } 
	 public function realizarprestamo(){
	 		if(validarsesion()){
	 			if(validarsesion()['rol']==3){
	 				$this->load->model('Biblioteca_model','',TRUE);
 					if($this->Biblioteca_model->getpermisoBiblio('prestamos')){  
		                $arr_data=array();
		                $data = json_decode(file_get_contents("php://input"));
		                if(isset($data->foli)){
				                	$reserva=$this->Reserva_model->find_reservar($data->foli);
				                   if($reserva){
										$cuenta="";$nad="";$cont=0;
				                   			foreach ($reserva as $row) {
				                   				$cuenta=$row->cuentausuario;
				                   				$nad=$row->nadqui;
				                   			}
							                   		$this->load->model('Libro_model','',TRUE);		                   			
				                   			if(!$this->Libro_model->prestado($nad)){
						                   		$this->load->model('Prestamo_model','',TRUE);
						                   		if($this->Prestamo_model->permitirprestamo($cuenta)){
													$this->load->model('User','',TRUE);
						                   			if(!$this->User->en_prestamovencido($cuenta)){	
						                   			
							                   	    $idprestamo=date('YmdHis')."E";
							                   		$fechaprestamo=date('Y-m-d H:i:s');
							                   		$exito=$this->Libro_model->addprestamo($cuenta,$nad,$idprestamo,$idprestamo.$cont,$fechaprestamo);
							                   		if($exito){
							                   			$this->Reserva_model->eliminarreserva($data->foli);
							                   			$arr_data['mensaje']='Prestamo Registrado correctamente';
							                   			$arr_data['exito']=true;
							                   		}else{
							                   			$arr_data['mensaje']='Ocurrió un problema, consulte al administrador del sistema';
							                   		}
							                   		}else{
														$arr_data['mensaje']= "El usuario tiene préstamos vencidos";
							                   		}
							                   	}else{
							                   		$arr_data['mensaje']='El usuario necesita devolver un libro';
							                   	}	
							                   		 echo json_encode($arr_data); 
							               }else{
							               	$arr_data['mensaje']='Ejemplar en préstamo';
							               	$arr_data['exito']=False;
							               	echo json_encode($arr_data); 
							               }
				                    }else{
				                        $arr_data['mensaje']='No se encontraron los datos de reserva';

				                    echo json_encode($arr_data);    
				                    }
		                }else{
		                    $arr_data['mensaje']='No se han recibido datos de búsqueda';
		                    echo json_encode($arr_data);
		                }
		            }else{
	            	$arr_data['mensaje']='Servicio no disponible';
	            	echo json_encode($arr_data);
	            }     

            }else{
	 			redirect('home', 'refresh');
	 		}
	 	}redirect('home', 'refresh');
	 }
}
?>



