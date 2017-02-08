<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends MY_Controller {
	function __construct() {
        parent::__construct();
         
		$this->load->model('Actividad_model','',TRUE);
		$this->load->model('Libro_model','',TRUE);
		$this->load->library('pagination');
       //$this->output->cache(1);
    }
    /*funcion inicial de la aplicaion
		@params vista inicial default welcome mensaje
		define un arreglo de vista a las que es posoble acceder 
		http://localhost/CodeIgniter/(vista)
		si se ingresa una vista que no este especificada en tal arreglo 
		envia a pagina de error
	*/
	public function index($pagina='inicio')
	{
		//$this->benchmark->mark('codigo_inicio');
		
		//$arrpaginas=["welcome_message","conocenos","servicios","consulta","contacto","login","actividades","permisos","datosejem","restablecer","cambiarpass","nodisponible"];
		//$accesso=in_array($pagina,$arrpaginas);
		//$pagina=$accesso ? $pagina : 'Error';
		$datos['content']=$pagina;
		$this->cargapage($datos);
		//$this->benchmark->mark('codigo_fin');
		//echo $this->benchmark->elapsed_time('codigo_inicio', 'codigo_fin');
	}
	/*
		@params dia
		muestra las actividades qe se realizaran en le dia
		si se recibe un dia mayo al total de dias del mes envia a vista de error
	*/
	function actividad($dia){
		$day=$dia;
		$data['content']='';
		//total de dias del mes
		$diasdelMes=date("d",mktime(0,0,0,date("m")+1,0,date("Y")));
		if($diasdelMes>=$day){
			$actividades=$this->Actividad_model->actividadesmes(0,$day);
			if($actividades){
				$data['acti']=$actividades;
				$data['content']='actividades';
			}else{
				$data['content']='actividanull';
			}
		}else{
			$pagina='Error';
			$data['content']=$pagina;	
		}
		$this->cargapage($data);
	}
	/* 
		@params contador para paginacion
		carga actividades en la pagina principal en la vista actividades  
	*/
	function actividades($cont=0){	
		$actividades=$this->Actividad_model->actividadesmes($cont);
		$config=$this->get_configuracion_paginacion(
					base_url().'actividades',
					$this->Actividad_model->contaractividadesmes(),
					'2');	
		$this->pagination->initialize($config);
		$data['acti']=isset($actividades)? $actividades : [];
		
		$data['content']='actividades';
		$this->cargapage($data);
	}
	/*params termino de busqueda
		elimina caracteres especiales del termino de busqueda
		retorna el termino limpio de caracteres que pudieran afectar la consulta
	*/
	private function sanear_busqueda($termino){
		$busqueda=preg_replace('/\&#(.)[^;]*;:/', '\\1', $termino);
		$busqueda=stripslashes($busqueda);
		$busqueda=str_replace("\'","",$busqueda);
		$busqueda=str_replace('\"',"",$busqueda);
		$busqueda=explode("\\",$busqueda);
		$busqueda=implode("",$busqueda);
		$busqueda = str_replace(
		        array("\\", "¨", "º", "-", "~",
		             "#", "@", "|", "!", "\"",
		             "·", "$", "%", "&", "/",
		             "(", ")", "?", "'", "¡",
		             "¿", "[", "^", "`", "]",
		             "+", "}", "{", "¨", "´",
		             ">", "< ", ";", ",", ":",
		             ".",""),
		        '',
				$busqueda);
		$busqueda=htmlspecialchars($busqueda);
		return $busqueda;
	}
	/* 
		@params vista a mostrar en el layout pricipal
		carga la  pagina de inicio con su respectiva vista 
	*/
	private function cargapage($datos){
		$this->load->view('angularIndex/index', $datos);
	}
	/*
		@params termino de busqueda tipo 1:titulo 2:autor 3:materia contador de paginacion
		verifica si se tienen los permisos para realziar busquedas

	*/ 
	public function buscar($busqueda="",$tipo=0,$cont=0){
		$this->load->model('Biblioteca_model','',TRUE);
		if($this->Biblioteca_model->getpermisoBiblio('permitirbusqpri')){
			$resultados=array();
			list($titulo,$materia,$autor,$bus)=array_fill(0, 4,"");
			$forbus=$this->input->post('busqueda');
			if(isset($forbus)){
				$busqueda=$this->sanear_busqueda($forbus);
				$tipo=$this->input->post('tipo');
			}else{
				$busqueda=$busqueda;
			}
			if(strlen($busqueda)<4){
				$busqueda="";
			}
			$this->load->model('Consulta_model','',TRUE);
				$total=0;
				if($tipo==1){
					$resultados=$this->Consulta_model->bytitulo($busqueda,$cont);
					$total=$this->Consulta_model->bytitulo($busqueda,$cont,"contar");
				}elseif ($tipo==2) {
					$resultados=$this->Consulta_model->byautor($busqueda,$cont);
					$total=$this->Consulta_model->byautor($busqueda,$cont,"contar");
				}elseif($tipo==3){
					$resultados=$this->Consulta_model->bytema($busqueda,$cont);
					$total=$this->Consulta_model->bytema($busqueda,$cont,"contar");
				}else{
					$resultados=[];
					$total=0;
				}
				//configuracion de paginacion					
				$config=$this->get_configuracion_paginacion(
					base_url().'buscar/'.$busqueda.'/'.$tipo,
					$total,
					'4');
			$this->pagination->initialize($config);
			if(count($resultados)>0&&$resultados){
				if($config['total_rows']>0){
					$data['total']='Se encontraron '.$config['total_rows']." ejemplares relacionados con la búsqueda
					".$bus.": ".$busqueda; 
				}
				$data['resultados']= $resultados;
     		}else{
     			$data['resultados']= array();
     		}
     		$data['content']='consulta';
     	}else{
     		$data['content']='nodisponible';
     	}

		$this->cargapage($data);
	}

}
?>