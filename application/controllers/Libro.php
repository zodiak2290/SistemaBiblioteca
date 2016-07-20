<?php
//eliminar despes de pruebas
        if (isset($_SERVER['HTTP_ORIGIN'])) {  
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");  
        header('Access-Control-Allow-Credentials: true');  
        header('Access-Control-Max-Age: 86400');   
    }  
      
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {  
      
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))  
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  
      
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))  
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");  
    }  

    //asta aca
defined('BASEPATH') OR exit('No direct script access allowed');
class Libro extends CI_Controller {
	var $libro= array('idlibro'=>'',
                        'isbn' =>"",
                        'idioma'=>'',
                        'clasificacion_id'=>'',
                        'titulo'=>'',
                        'edicion'=>'',
                        'descfisica'=>'',
                        'serie'=>'',
                        'notageneral'=>'',
                        'contenido'=>'',
                        'tema'=>'',
                        'coautor'=>'',
                        'coautorcorp'=>'');
    var $ejemplar= array('nad' =>'' ,
                        'ejemplar'=>'',
                        'tomo'=>1,
                        'volumen'=>1,
                        'ficha'=>'');
    private $profiler;
	function __construct() {
        parent::__construct(); 
        $this->load->helper(array('sesion_helper'));
       $this->load->model('Libro_model','',TRUE);
    }

    //busca etiqueta marc de libro en archivo acces en la carpeta csv
    function access(){
    	$arr_data = array();
    	//recibiendo data enviada de angular 
    	 $data = json_decode(file_get_contents("php://input"));
    	 if(isset($data)){
    	 	$db = getcwd().'/csv/PROMETEO.mdb';
    	 	if(file_exists($db)){
    	 	$totalcampos=0;
    	 	foreach ($data as $key=>$valor){
			    if(strcmp($key,'001')==0||strcmp($key,'020')==0){
			    	if(strlen($valor)>0){
			    		$totalcampos=3;
			    	}
			    }
			    if(strlen($valor)>0){
			    	$totalcampos+=1;
			    }
			}
		    	if($totalcampos==0){
		    		$arr_data['mensaje']="No hay datos de búsqueda";
		    	}elseif($totalcampos<3){
		    		$arr_data['mensaje']="Ingrese por lo menos 3 campos del formulario";
		    	}else{ 	
		    	 	//echo json_encode($data);
					$etiquetas=$this->_obtener_consulta($data);
					$arr_data['datos']=$etiquetas ? $etiquetas : [] ;
					$arr_data['mensaje']=$etiquetas ?'Se encontraron los siguientes libros':'No hay datos que coincidan con tu busqueda';
				}
				}else{
					$arr_data['mensaje']="No se encontró la base de datos access";
				}
				echo json_encode($arr_data);
    	 }else{
    	 	redirect('home');
    	 }
    }
    //realiza busqueda a partir de los datos recibidos de la funcion acces
    private function _obtener_consulta($data){
    	$data=(array)$data;$soloncontrol=false;$soloisbn=false;
    	foreach ($data as $key=>$valor){
    		if(strcmp($key,'020')==0){
    			if(strlen($valor)>0){
    				$isbn=$valor;	
    				$soloisbn=true;
    			}
    		}
    		if(strcmp($key,'001')==0){
    			if(strlen($valor)>0){	
    				$nficha=$valor;
    				$soloncontrol=true;
    			}
    		} 
    	}
    	// Se especifica la ubicación de la base de datos Access (directorio actual)
		$db = getcwd().'/csv/PROMETEO.mdb';
		// Se define la cadena de conexión
		$dsn = "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=$db";
		// Se realiza la conexón con los datos especificados anteriormente
		$conn = odbc_connect( $dsn,'','');
		if (!$conn) { exit( "Error al conectar:".$conn);
		}
		//echo json_encode(array_keys($dato))."\n";
		$sql="select f.EtiquetasMARC,f.Ficha_No from FICHAS f";
		if(!$soloncontrol&&!$soloisbn){	
			$sql.=(strlen($data['020'])>0) ? " where f.ISBN LIKE '%".$data['020']."%'":" "; 
			$sql.=strlen($titulo)>0 ? " and f.Titulo LIKE '%".$titulo."%'":" ";
		}else if($soloncontrol){
			$sql.=" where Ficha_No=".$nficha;
		}else if($soloisbn){
			$sql.=" where ISBN='".$isbn."'";
		}
		// Se define la consulta que va a ejecutarse
		// Se ejecuta la consulta y se guardan los resultados en el recordset rs
		$resultado= $this->realizar_consulta($sql,$conn);
	  	return	$resultado ? $this->procesar_consulta($resultado,$data,$conn,$soloncontrol,$soloisbn) : false;
	}
	private function procesar_consulta($rs,$data,$conn,$pornumerocontrol,$porisbn){
		$coincidencias=array();
		ini_set('memory_limit','512M');
  		set_time_limit(500);
		$cont=0;
		while ( odbc_fetch_row($rs)){ 
			$resultado=odbc_result($rs,"EtiquetasMARC");
			$ficha=odbc_result($rs,"Ficha_No");
			$resultado=utf8_encode($resultado);
			$datosficha=explode("¦",$resultado);
			//echo json_encode($resultado)."\n";
			//echo "\n";
			if(!$pornumerocontrol&&!$porisbn){
				$porcntajeetiquta= array();
				$porcntajeetiquta['porcentaje']=$this->buscar_coincidencias($datosficha,$data);
				if ($porcntajeetiquta['porcentaje']>50){
					$porcntajeetiquta['etiqueta']=$resultado;
					
				}
			}else{
				$porcntajeetiquta['etiqueta']=$resultado;
			}
			array_push($coincidencias,$porcntajeetiquta);
		 	$cont+=1;
		}
		//echo "contador:".$cont."\n"."Ficha:".$ficha;
		// Se cierra la conexión
		odbc_close($conn);
		//array_multisort(array_column($coincidencias, 'porcentaje'), SORT_DESC, $coincidencias);
		return count($coincidencias)>0 ? $coincidencias : false;
	}
	private function realizar_consulta($sql,$conn){
		$rs = odbc_exec( $conn, $sql );
		return $rs ; 
	} 
    /*
		buscamos coincidencias entre las busquedas de usuario, y los datos del access
    */
    private function buscar_coincidencias($resultado,$busquedas){
    	$busquedas=(array)$busquedas;
    	//creamos un arreglo de los campos de la etiqueta marc
    	$camposmarc=array();
    	foreach ($resultado as $campo) {
    		$valor=substr_replace($campo,"",3,strlen($campo));
    		$valorcampo=substr_replace($campo,"",0,3);
    		$camposmarc[$valor]=$valorcampo;
    	}
    	//echo json_encode($busquedas);
    	//buscamos si algun campo de la etiqueta coincide con su respectivo campo de busqueda    
    	$contterminos=0;$porcentajetotal=0;
    	foreach ($busquedas as $llave => $valor){
    		if(isset($llave)){
	   			if(strlen($valor)>0){
						$contterminos+=1;
							if(isset($camposmarc[$llave])){$cmpmarc=$camposmarc[$llave];}else{$cmpmarc="";}
							similar_text(strtolower($cmpmarc),strtolower($valor), $percent);	 	
	    				if(strcmp($llave,'001')==0){			
							similar_text($camposmarc['035'],$valor, $porc);
							$percent=$percent+$porc;
						}
					$porcentajetotal=$porcentajetotal+$percent; 
	    		}
	    	}	
    	}
    	$porcentajetotal=($porcentajetotal*(40))/100;
  		$esperado=$contterminos>0 ? $contterminos*40:1;
  		//$obtenido=($contterminos>0)? $porcentajetotal/$contterminos :0;
    	/*echo "De ".$contterminos." buscados el porcentaje es de ".$porcentajetotal."\n"
    		."Esperado:".$esperado."\n"
    		."Obtenido :".$porcentajetotal."\n";
    		return (($porcentajetotal*100)/$esperado);*/
    }
	 	/*
	 		@params  dato :dato a evaluar
	 				tipo:tipo de datos  nad ficha ,volumen y tomo
	 				retorna true sin son nmmericos 
	 	*/
	  private function validacion($dato,$tipo){
        //validamos los diversos campos de empleado
        $valido=false;
        if(strcmp($tipo, "nadq")==0||strcmp($tipo, "ficha")==0){
        	//validacion del numero de adquisicion
            $valido=preg_match('/^[\d]{1,15}$/', $dato);
        }elseif(strcmp($tipo,"vol")==0||strcmp($tipo,"tomo")==0||strcmp($tipo,"ej")==0){
 			$valido=preg_match('/^[\d]{1,6}$/', $dato);
        }
        return ($valido==1)? $dato:false; 
    }
    /*
    	 Validar numero de adquisicion
		Si el numero de adquisicion ya esta registrado retorna false
    */
    private function validarnad($nad){
    	$retorno=false;
    	if($nad){
    		$nadexiste= $this->Libro_model->findbynad($nad);
    		$retorno=$nadexiste ? false :$nad;
    	}
    	return $retorno;
    }
    /* sugerencias para clasificaciones*/
   function sugerirclasificacion(){
   		$data = json_decode(file_get_contents("php://input"));
		if(isset($data->clasificacion)){
	    	$sesion=$this->get_session();
	    	$arr_data=array();
	    	if($sesion){
	    		$rol = $this->get_rol_sesion();
	    		if($rol==2){
	    			if(strlen($data->clasificacion)>4){
	    				$this->load->model('Autor_model','',TRUE);
	    				$suge=$this->Autor_model->find_clasificacion($data->clasificacion); 
	    				if($suge){
	    					$arr_data['sugerencias']=$suge;	
	    				}else{
	    					$arr_data['mensaje']="No hay sugerencias que mostrar";
	    				}
	    			}
	    		}
	    	}
    		echo json_encode($arr_data);
		}else{
			echo json_encode($arr_data['mensaje']="Faltan datos");
		}
   }
    /* 
		Retorna las sugerencias obtenidas a parteri del autor o materia ingresada
		@params buscar:termino a buscar 
		        tipo autor o materia
		        returna sugerencias en caso de encontrarlas 
    */
    private function get_sugerencias($buscar,$tipo){
    	$sesion=$this->get_session();
    	$arr_data=array();
    	if($sesion){
    		$rol = $this->get_rol_sesion();
    		if($rol==2){
    			if(strlen($buscar)>4){
    				$this->load->model('Autor_model','',TRUE);
    				$suge=(strcmp($tipo,"autor")==0)?$this->Autor_model->findautorbyname($buscar):$this->Autor_model->findmateriabyname($buscar); 
    				if($suge){
    					$arr_data['sugerencias']=$suge;	
    				}else{
    					$arr_data['sugerencias']=false;
    					$arr_data['mensaje']="No hay sugerencias que mostrar";
    				}
    			}
    		}
    	}
    	return $arr_data;
    }
    /* 	
    	Recibe datos parasugerir por autor
    	retorna un json de las sugerencias encontradas
    */
    function sugerirautor(){
	     $data = json_decode(file_get_contents("php://input"));
		if(isset($data->autor)){
			echo json_encode($this->get_sugerencias($data->autor,"autor"));
		}else{
			echo json_encode($arr_data['mensaje']="Faltan datos");
		}
    }
     /* 	
    	Recibe datos parasugerir por materia
    	retorna un json de las sugerencias encontradas
    */
    function sugerirmateria(){
		$data = json_decode(file_get_contents("php://input"));
		if(isset($data->materia)){
			echo json_encode($this->get_sugerencias($data->materia,"materia"));
		}else{
			echo json_encode($arr_data['mensaje']="Faltan datos");
		}
    }
    	/* obtiene numero de ejemplar a partir del numero del numero de ficha volumen y tomo del ejemplar  
    	*/
    	private function get_numero_ejemplar($ejemplar){
    		extract($ejemplar);
    		$nejm=1;
			$nejemplar= $this->Libro_model->ejemplar($ficha,$volumen,$tomo); 
	   	     if($nejemplar){
	   			foreach ($nejemplar as $row ){
			  	 		$nejm=$row->nejem+1;
			  	 	}      	
	   	      }
	   	      return $nejm;
    	}
    	/* 
			Recibe los datos enviados por http de angular, verifica que sean validos
			crear un nuevo ejemplar, agregando el codigo de barras y bloqueandolo si asi se ha especificado,desde la vista
    	*/
	function agregar()
	{
	 	if($this->session->userdata('logged_in')){
		   	$data = json_decode(file_get_contents("php://input"));
		   	if(isset($data)){
		   	$ejemplar=$this->ejemplar;
		   	$status=(isset($data->dispo)) ? $data->dispo : true;
		   	$ejemplar['nad']=$this->validarnad($this->validacion($data->nad,'nadq'));
		   	$ejemplar['volumen']=isset($data->volumen) ? $this->validacion($data->volumen,'vol' ): $ejemplar['volumen'];
		   	$ejemplar['tomo']=isset($data->tomo) ? $this->validacion($data->tomo,'tomo') : $ejemplar['tomo'];
		   	$ejemplar['ficha']=isset($data->nfic) ? $this->validacion($data->nfic,'ficha') : 0;
		   	$ejemplar['ejemplar']=(isset($data->ejemplar)) ? $this->validacion($data->ejemplar,'ej') : $this->get_numero_ejemplar($ejemplar);
	   	if(!in_array(false,$ejemplar)){
			$impcb=$data->cb;		   
		   	$insertado=$this->Libro_model->creareditejemplar($ejemplar);      
		   	if($insertado){
		   		$arr_data['mensaje']="Ejemplar agregado correctamente";
		   		$arr_data['registro']=true;
		   		if($impcb===true){
		   			$this->agregarbarcode($ejemplar['nad']);
		   		}
		   		if($status==false){
		   			$this->bloquearlibro($ejemplar['nad']);
		   		}
		   	}else{
		   		$arr_data['mensaje']="Hubo un error al procesar los datos. Verifique el ejemplar";
		   	}
	   	}else{
	   		$mensaje="Algunos datos no son correctos, por favor corríjalos y vuelva a intentar";
	   		$arr_data['mensaje']=($ejemplar['nad']) ? $mensaje :$mensaje."\nEl N° de adquisición no es válido o ya está en uso";
	   	}
	   		echo json_encode($arr_data); 
	   	} 
	   	redirect('home', 'refresh');
	   }else{
		   redirect(base_url(), 'refresh');
		}
	 }
	 /* 
			cambia el estado del ejemplar a disponible o en reparacion
			retorna un mensaje para informar al usuario si la accion se realizo con exito
	 */
	 function estado(){
	 	$sesion=$this->get_session();
	 	if($sesion)
	   		{	
	   			$arr_data=array();
	   			$rol = $this->get_rol_sesion();
	      		if($rol==2){
	      			$data = json_decode(file_get_contents("php://input"));
	      			if(isset($data->nad)){
	      				$reparacion=$this->Libro_model->reparacion($data->nad);
	      				if($reparacion){
	      					$exito=$this->disponible($data->nad);
	      					$arr_data['mensaje']="Ejemplar disponible";
	      				}else{
	      					$exito=$this->reparacion($data->nad);
	      					$arr_data['mensaje']="Ejemplar en reparación";
	      				}
	      				if($exito){
	      					echo json_encode($arr_data);
	      				}else{
	      					echo "No fue posible realizar esta acción. Intente de nuevo";
	      				}
	      			}
	      		}
	      	}
	 }
	 // cambia el estado del ejemplar a disponible
	 private function disponible($nad){
	 	return	$this->Libro_model->disponible($nad);
	 }
	 //cambia el estado del ejemplar a en reparacion
	 private function reparacion($nad){
	 	return	$this->Libro_model->reparar($nad);
	 }
	 //bloquea el libro
	 private function bloquearlibro($nadqui){
		 return	$this->Libro_model->bloquearejemplar($nadqui);
	 }
	 //desbloquea el libro
	 private function desbloquearlibro($nadqui){
	 	return	$this->Libro_model->desbloquearejemplar($nadqui);
	 }
	 // permite bloquear o desbloquear el libro retorna un mensaje para informa del exito o fracaso 
	 public function bloquedesbloqueo(){
	 	$sesion=$this->get_session();
	 	if($sesion)
	   		{
	   			$arr_data=array();
	     		$rol = $this->get_rol_sesion();
	      		if($rol==2){
	      			$data = json_decode(file_get_contents("php://input"));
					if(isset($data->nad)){
	      				$bloqueado=$this->Libro_model->bloqueado($data->nad);
	      				$exito=false;
	      				if($bloqueado){
	      					$exito=$this->desbloquearlibro($data->nad);
	      					$arr_data['mensaje']="Ejemplar desbloqueado";
	      				}else{
	      					$exito=$this->bloquearlibro($data->nad);
	      					$arr_data['mensaje']="Ejemplar bloqueado";
	      				}
	      				echo $exito?json_encode($arr_data):"No fue posible realizar esta acción. Intenta de nuevo";
	      			}
	      		}
	      	}
	 }
// agrega un codigo de barras a la lista  solo si el usuario esta logeado y su rol es 2->procesos
	private function agregarbarcode($nadqui){
		if($this->session->userdata('logged_in'))
	   {
	   		$session_data = $this->session->userdata('logged_in');
	     	$rol = $session_data['rol'];
	      	if($rol==2){
				$this->load->model('Barcode_model','',TRUE);
				$inser=$this->Barcode_model->agregacodigo($nadqui,"g");
				return $inser ? true :false;
			}
		}	
	}
	//funcion invocada desde la vista para agregar codigo de barras 
	//recibe por medio de post de angular el n° de adquisicion a agregar
	function addbarcode(){
		if($this->session->userdata('logged_in'))
	   {
			$data = json_decode(file_get_contents("php://input"));
			if(isset($data)){
				if($data->nad){
					$agregado=$this->agregarbarcode($data->nad);
					$arr_data['mensaje']= $agregado ?'Código agregado correctamente':'Este N° de adquisicion ya en la lista';
			      	echo json_encode($arr_data);
				}
			}else{
				redirect('home','refresh');
			}	
		}else{
			redirect('home','refresh');
		}
	}
	//da de baja un ejemplar
	 function baja(){
	 	if(validarsesion())
	    {
	      	if(get_rol_sesion()==2){
	      		$data = json_decode(file_get_contents("php://input"));
	      		if(isset($data)){
	      			if(!$this->validarnad($data->nad)){
	      				$arr_data=array();
	      				$resultado = $this->Libro_model->baja($data->nad,get_cuenta_sesion(),$data->criterio,$data->obs);
	      				$arr_data['mensaje']=$resultado ?"Se dio de baja el ejemplar":"No se pudo dar de baja, intente de nuevo";
	      			echo json_encode($data);
	      			}
	      		}
	      	}
	   	}	
	 }
	 private function get_session(){
	 	return $this->session->userdata('logged_in');
	 }
	 private function get_rol_sesion(){
	 		$sesion=$this->get_session();
	 		return $sesion['rol'];
	 }
	 private function get_cuenta_sesion(){
	 		$sesion=$this->get_session();
	 		return $sesion['cuentausuario'];
	 }
	 //verifica si las reservas de un usuario son validas, enc aso contrario las elimina
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
	 /* Busqueda de ejemplar para realizar prestamo
	 	verifica que exista la sesion y que el rol sea 3->prestamos
		devuelve los datos del ejemplar
	 */
	 function buscar_ejemplar(){
	 	if($this->get_session()){
	 		if($this->get_rol_sesion()==3){
	 			 $data = json_decode(file_get_contents("php://input"));
	 			 $arr_data['ejemplar']="";
	 			 if(isset($data->nadqui)&&isset($data->cuenta)){
	 			 	$this->load->model('Prestamo_model','',TRUE);
                   	if($this->Prestamo_model->permitirprestamo($data->cuenta)){
                   		$this->load->model('User','',TRUE);
                   		if(!$this->User->en_prestamovencido($data->cuenta)){
				 			 	$arr_data['mensaje']="";
				 			 	//obtenemos ejemplar
				 			 	$ejemplar=$this->Libro_model->findforprestamojson($data->nadqui);
				 			 	//verificamos si el ejemplar esta reservado si es asi
				 			 	$reservado=$this->Libro_model->reservado($data->nadqui);
				 			 	//si esta reservado
				 			 	if($reservado){
				 			 		//verificamos si la reserva aun en valida
				 			 		$reservavalida=$this->validarfechareserva($reservado);
				 			 		//con la sentencia anterior se elimina la reserva en caso de no ser valida
				 			 		//checa por segunda vez si el ejemplar esta reservado
				 			 		$reservado=$this->Libro_model->reservado($data->nadqui);
				 			 	}
				 			 	//el libro esta en prestamo?
					            $prestamo=$this->Libro_model->prestado($data->nadqui);
					            //si se encontro el ejemplar 
				 			 	if($ejemplar){
				 			 		//si no esta reservado ni prestado
				 			 		if(!$reservado&&!$prestamo){
				                        $arr_data['ejemplar']=$ejemplar;
				                        $arr_data['reservado']=$reservado;
					                    $arr_data['prestado']=$prestamo;
					                }else{
					                	$arr_data['ejempsss']=$reservado;
					                	$arr_data['mensaje']='Ejemplar reservado o prestado';
					                }
				 			 	}else{
			                        $arr_data['mensaje']='El N° de Adquisición no existe';
				 			 	}
				 		}else{
				 			$arr_data['mensaje']='El usuario tiene préstamos vencidos';
				 		}	 	
		 			 }else{
	                   		$arr_data['mensaje']='Alcanzado límite de libros';
	                   		
	                 }		
	                 echo json_encode($arr_data); 
	 			 }else{
	 			 	$arr_data['mensaje']='No se han recibido datos de búsqueda';
                    echo json_encode($arr_data);
	 			 }	
	 		}else{
	 			redirect('home', 'refresh');
	 		}	
	 	}else{
	 		redirect('home', 'refresh');
	 	}
	 }
	 //retornaa true si el numero de ficha ya esta registrado
	  function buscaridficha(){
	 	if($this->get_session()){
	 		if($this->get_rol_sesion()==2){
	 			$arr_data=[];
	 			$data = json_decode(file_get_contents("php://input"));
	 			if(isset($data->idfic)){
	 				$existe=$this->Libro_model->findbyidjson(intval($data->idfic));
	 				$arr_data['existe']=$existe ? true :false;			 
	 				echo json_encode($arr_data);
	 			}else{
			 		redirect('home');
			 	}
	 		}else{
	 			redirect('home');
	 		}
	 	}else{
	 		redirect('home');
	 	}
	 }
	 /* verifica si el numero de adquisicion de cada ejemplar no esta en prestamo
	 	si es asi lo elimina del arreglo 
	 	devuelve los numeros de adquision a prestar
	 */
	private function nadquivalido($ejemplares=array()){
	 	$nadqui=[];
		 	foreach ($ejemplares as $key=>$ejemplar) {
		 		$ejemplar=$this->Libro_model->findbynad($ejemplares[$key]['nadqui']);
		 		if($ejemplar){	
			 		foreach($ejemplar as $row){
			 			$nad=$row->nadqui;
			 		}
			 		if($this->Libro_model->prestado($nad)||!$ejemplar){
			 			unset($ejemplares[$key]);
			 		}else{
			 			array_push($nadqui,$ejemplares[$key]['nadqui']);
			 		}
			 	}
		 			
		 }
	 	return $nadqui;
	 }
	 /* 
			Agrega los prestamos, verificando antes si el servicio esta disponible, si el usuario no tiene prestamos vencidos
	 */
	 public function realizarprestamo(){
 		if($this->get_session()){
 			if($this->get_rol_sesion()==3){
 				$this->load->model('Biblioteca_model','',TRUE);
 				if($this->Biblioteca_model->getpermisoBiblio('prestamos')){  
	                $arr_data=array();
	                $data = json_decode(file_get_contents("php://input"));
	                if(isset($data->ejemplares)&&isset($data->cuenta)){
	                   if(count($data->ejemplares)>0&&strlen($data->cuenta)){
	                   		$this->load->model('Prestamo_model','',TRUE);
	                   		if($this->Prestamo_model->permitirprestamo($data->cuenta)){
								$this->load->model('User','',TRUE);
	                   			if(!$this->User->en_prestamovencido($data->cuenta)){	
	                   			$arr_data['mensaje']=$this->Prestamo_model->permitirprestamo($data->cuenta);
		                   		//$ejamplaresvalidos=$this->nadquivalido($data->ejemplares);
		                   		$ejemplares=json_encode($data->ejemplares);
		                   		$datos= json_decode($ejemplares,true);
		                   		$numerosadqui=$this->nadquivalido($datos);
		                   		$cont=0;
		                   		$idprestamo=date('YmdHis')."E";
		                   		$fechaprestamo=date('Y-m-d H:i:s');
		                   		$exito=true;
		                   		foreach ($numerosadqui as $nad){
		                   			$exito=$this->Libro_model->addprestamo($data->cuenta,$nad,$idprestamo,$idprestamo.$cont,$fechaprestamo);
		                   			$exito=$exito&&$exito;
		                   			$cont++;
		                   		}
		                   		
		                   		if($exito){
		                   			$arr_data['mensaje']='Préstamo registrado correctamente';
		                   		}else{
		                   			$arr_data['mensaje']='Ocurrió un problema, consulte al administrador del sistema';
		                   		}
		                   		}else{
									$arr_data['mensaje']= "El usuario tiene préstamos vencidos";
		                   		}
		                   	}else{
		                   		$arr_data['mensaje']='Alcanzado límite de libros';
		                   	}	
		                   		echo json_encode($arr_data); 
	                    }else{
	                        $arr_data['mensaje']='Ingrese N° Adquisición';
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
	 	}
	 
	}
	// funcion para agregar una reserva , si el ejemplar no ha sido reservado 
	 private function agregareserva($nadqui,$cuenta,$prestado){
	 	$this->load->model('Reserva_model','',TRUE);
	 	$fecha="";
	 	if($prestado){
	 		foreach ($prestado as $row) {
	 			$fecha=$row->entregar;
	 			$mensaje="Tu reserva a sido registrada. Válida dos días después de la fecha de devolución del libro. Recoger antes del:".date("Y-m-d H:m:s",strtotime('+2 day',strtotime($fecha)));
	 		}
	 	}else{
	 		$fecha=date("Y-m-d H:i:s");
	 		$mensaje="Tu reserva a sido registrada. Recoger antes del: ".date("Y-m-d H:m:s",strtotime('+2 day',strtotime($fecha)));
	 	}
	 	if(!$this->Reserva_model->find_reservar($nadqui.$cuenta)){
		 	if($this->Reserva_model->agregarreserva($nadqui,$cuenta,$fecha)){
		 		return $mensaje;
		 	}else{
		 		return "No fue posible realizar tu solicitud. Intente de nuevo más tarde";
		 	}
		}else{
			return "Este ejemplar ya ha sido reservado por el usuario";
		}
	 }
	 /* 	
	 Antes de reservar un libro se verifica
	 	que el servicio esta disponible
	  	que el usuario tenga vigencia valida
	 	que el ejemplar no este en reparacion,reservado , prestamo, o bloqueado
	 	que el usuario o tenga prestmaos vencidos

	 */
	 private function accion_resrva($cuenta){
	 	$this->load->model('User','',TRUE);
	 	$this->load->model('Biblioteca_model','',TRUE);
	 			$data = json_decode(file_get_contents("php://input"));
	 			if($this->Biblioteca_model->getpermisoBiblio('permitireservas')){
	   			if(isset($data->nadqui)){
	   			$vigente=$this->User->uservigente($cuenta);
	   				if($vigente){	
			 			$bloqueado=$this->User->userbloqueado($cuenta);
			 			if(!$bloqueado){
			 				$multas=$this->User->multas($cuenta);
			 				if(!$multas){
			 					$prestamosvencidos=$this->User->en_prestamovencido($cuenta);
			 					$tienprestamos=$this->User->en_prestamo($cuenta);
			 					$mensajedereserva="";
				 				if($tienprestamos){	
				 					foreach ($tienprestamos as $row) {
				 									$permitidos=$row->cantlibros;
				 					}
				 					$mensajedereserva=((count($tienprestamos))==(intval($permitidos)))? "Actualmente tienes ".(count($tienprestamos))." libros en prestamo,necesitaras devolver 1 para poder continuar con tu proceso de reserva.":"";
				 				}	
			 					if(!$prestamosvencidos){
			 						if($this->User->nopermitirreserva($cuenta)){
				 						$this->load->model('Libro_model','',TRUE);
				 						$nadqui=$data->nadqui;
				 						$bloqueado=$this->Libro_model->bloqueado($nadqui);
				 						if(!$bloqueado){
				 							$reparacion=$this->Libro_model->reparacion($nadqui);
				 							if(!$reparacion){	
				 								$reservado=$this->Libro_model->reservado($nadqui);
				 								if(!$reservado){
				 									$prestado=$this->Libro_model->prestado($nadqui);
				 									if(!$prestado){
				 										if(isset($data->reservar)){
				 											$arr_data['mensaje2']=$this->agregareserva($nadqui,$cuenta,false);
				 											$arr_data['mensaje']="Reserva realizada correctamente";
				 										}else{
				 											$arr_data['mensaje']= "Ejemplar disponible";
				 											$arr_data['mensaje2']="Disponible. ".$mensajedereserva;
				 										}
				 									}else{
				 										if(isset($data->reservar)){
					 										$arr_data['mensaje2']=$this->agregareserva($nadqui,$cuenta,$prestado);
					 										$arr_data['mensaje']=" Reserva realizada correctamente ";
				 										}else{
					 										$arr_data['sugerencias']=$this->get_recomendaciones($nadqui);
					 										$arr_data['mensaje']="Ejemplar en préstamo".$this->mensaje;
					 										$arr_data['prestamo']=$prestado;
					 										$arr_data['mensaje2']=$mensajedereserva."Libro prestado. Fecha de entrega:";
					 									}				
				 									}
				 									$arr_data['ejemplar']=$this->Libro_model->findforprestamojson($nadqui);
				 								}else{
				 									$arr_data['sugerencias']=$this->get_recomendaciones($nadqui);
				 									$arr_data['mensaje']="El ejemplar ya ha sido reservado por otro usuario. ".$this->mensaje;				
				 								}
				 							}else{
				 								$arr_data['sugerencias']=$this->get_recomendaciones($nadqui);
				 								$arr_data['mensaje']="Este ejemplar esta en reparación".$this->mensaje;
				 							}
				 						}else{
				 							$arr_data['sugerencias']=$this->get_recomendaciones($nadqui);
				 							$arr_data['mensaje']= "Este ejemplar no puede ser reservado".$this->mensaje;
				 						}
				 					}else{
				 						$arr_data['mensaje']= "Lo sentimos solo es posible reservar 2 libros";
				 					}
			 					}else{
			 						$arr_data['mensaje']= "Tienes préstamos vencidos";
			 					}
			 				
			 				}else{
			 					$arr_data['mensaje']= "Tienes multas registradas,no puedes realizar reservas";
			 				}
			 			}else{
			 				$arr_data['mensaje']="No puedes realizar reservas,visita la Biblioteca para conocer los motivos";
			 			}
			 		}else{
			 			$arr_data['mensaje']="Acude a la biblioteca a renovar tu credencial";
			 		}	
		 		}else{
		 			$arr_data['mensaje']= "Falta número de adquisición";
		 		}
		 		}else{
		 			$arr_data['mensaje']="Este servicio no esta habilidato en este momento";	
		 		}	
	 			 return $arr_data;
	 }	
	 //reservar un ejemplar desde pagina web o dispositivo movil
	 public function reservar(){
	 	$sesion=$this->get_session();
	 	$data = json_decode(file_get_contents("php://input"));
	 	if($sesion){
	 		if($this->get_rol_sesion()==7){
	 			$cuenta=$this->get_cuenta_sesion();
	 			echo json_encode($this->accion_resrva($cuenta));
	 		}else{
	 			redirect('home');	
	 		}
	 	}else if(isset($data->cuenta)){
	 		$cuenta=$data->cuenta;
	 		echo json_encode($this->accion_resrva($cuenta));
	 	}
	 }
	 var $mensaje="";
	 //Obtiene recomencaciones a partir de la materias del numero de adquisicion recibido como parametros
	 //retorna un arreglo de libros
	 private function get_recomendaciones($nadqui){
	 	$recomendaciones=$this->Libro_model->temasejemplarreocmendar($nadqui);
	 	$this->mensaje=$recomendaciones ? " Quizá te puedan interesar algunos de los siguientes títulos" : "No hay sugerencias para mostrar";
		return $recomendaciones;
	 }
	 //devuelve datos del ejemplar buscando por su numero de adquisicion
	 // los muuestra en la pagina principal url+ejemplar/(:num)
	 public function ejemplar($nadqui){
	 	$this->load->model('Biblioteca_model','',TRUE);
	 	if($this->Biblioteca_model->getpermisoBiblio('permitirbusqpri')){   
		 	$bloqueado=$this->Libro_model->bloqueado($nadqui);
		    if(!$bloqueado){
				$result=$this->Libro_model->findbynadjson($nadqui);
				$resultados=$this->get_datosficha($result,"nadqui");
				$datos['resultados']= $resultados;

				if($datos['resultados']!=1){
					$datos['sugerencias']=$this->get_recomendaciones($nadqui);
				}
				$datos['content']=$resultados ? 'datosejem' :'Error';
			}else{
				$datos['content']='Error';
			}
		}else{
			$datos['content']='nodisponible';
		}
		$this->load->view('layouts/layoutprincipal', $datos);
	}
	//obtiene autores a partir del numero de ficha
	private function get_autores($idficha){
		$autores=$this->Libro_model->get_autores($idficha);
		$autores_string="";
		if($autores){
	 		$con=0;
	 		foreach ($autores as $autor){
	 			$final=($con<count($autores)-1) ? " | " :"";
	 			$autores_string=$autores_string.$autor->nameautor.$final;
	 			$con++;
	 		}
		}
		return $autores_string;	
	}
	//obtiene materias a partir del numero de ficha
	 private function get_materias($idficha){
	 	$materias=$this->Libro_model->get_materias($idficha);
	 	$materias_string="";
	 	$con=0;
	 	if($materias){
	 		foreach ($materias as $materia){
	 			$final=($con<count($materias)-1) ?"|":"";
	 			$materias_string=$materias_string.$materia->namemateria.$final;
	 			$con++;
	 		}
	 	}	
	 	return $materias_string;
	 }
	 /*obtiene informacion de un ejemplar  sus estado, si esta disponible, si esta en prestamo y cuando sera entregado
		atributos propios del libro y del ejemplar su uso interno y externo
		retorna un arreglo del libro
	 */
	 private function get_datosficha($resultado,$tipo){
	$arr_data=array();
	if($resultado){				 			
			    foreach ($resultado as $row ){
		    		$titulo=$row->titulo;
		    		$arr_data['titulo']= explode("/", $titulo)[0];
		  	 		$arr_data['clasificacion']=$row->clasificacion;
		  	 		$arr_data['nameautor']=$this->get_autores($row->idlibro);
		  	 		$arr_data['isbn']=$row->isbn;	
		  	 		$arr_data['ci']=$row->idioma;	
		  	 		$arr_data['edicion']=$row->edicion;	
		  	 		$arr_data['edit']=$row->nameeditorial;
		  	 		$arr_data['des']=$row->descfisica;	
		  	 		$arr_data['asiento']=$row->serie;
		  	 		$arr_data['nota']=$row->notageneral;	
		  	 		$arr_data['cont']=$row->contenido;	
				  	$arr_data['stema']=$this->get_materias($row->idlibro);
		  	 		$arr_data['idficha']=$row->idlibro;	
		  	 		if(strcmp($tipo,"nadqui")==0){
		  	 			$arr_data['nadqui']=$row->nadqui;
			  	 			$arr_data['status']=$this->getstatus($row->nadqui);
			  	 			$arr_data['bloq']=$this->is_bloqueado($row->nadqui);
			  	 			$arr_data['fechaentrega']=$this->fechaentrega;
			  	 			$arr_data['add']=$row->created_at;
			  	 			$arr_data['ejemplar']=$row->nejemplar;
			  	 			$arr_data['tomo']=$row->tomo;
			  	 			$arr_data['volumen']=$row->volumen;
			  	 			$ejemplares=$this->Libro_model->numeejemplares($arr_data['idficha'],$arr_data['volumen'],$arr_data['tomo']);
			  	 			$arr_data['numej']=count($ejemplares)-1;
			  	 			$vaejem="";
			  	 			if($ejemplares){
			  	 				foreach ($ejemplares as $ejemplar) {
			  	 					//muestra los ejemplares similares a este, sin inlcuir el que se muestra
			  	 					if( !(strcmp($arr_data['nadqui'],$ejemplar->nadqui)==0) ){
			  	 						$vaejem=$vaejem.$ejemplar->nadqui.",";
			  	 					}
			  	 				}
			  	 				$arr_data["ejemplares"]=$vaejem;
			  	 			}else{
			  	 				$arr_data["ejemplares"]="nada";
			  	 			}
  	 						$arr_data['totalinter']=$this->get_total_prestamoint($row->nadqui,"I");
			  	 			$arr_data['uso']=$this->get_ultimo_uso($row->nadqui,"I");
			  	 			$arr_data['totalinterd']=$this->get_total_prestamoint($row->nadqui,"E");
			  	 			$arr_data['usod']=$this->get_ultimo_uso($row->nadqui,"E");
		  	 		}		  	 		
		  	 	}
	  	 	}	
	  	 	return $arr_data;
}
//esta bloqueado?
private function is_bloqueado($nadqui){
	$bloqueado=$this->Libro_model->bloqueado($nadqui);
	return $bloqueado;
}
//obtenemos el stado del libro ,si sta en reparacion,prestamo,reserva
var $fechaentrega="";
private function getstatus($nadqui){
	$prestado=$this->Libro_model->prestado($nadqui);
	$reservado=$this->Libro_model->reservado($nadqui);
	$reparacion=$this->Libro_model->reparacion($nadqui);
	if($prestado){
		foreach ($prestado as $row) {
			$this->fechaentrega=$row->entregar;
		}
		return 3;
	}elseif($reservado){
		return 4;
	}elseif($reparacion){
		return 2;
	}else{
		return 1;
	}
	
}
/*	@params numero de adquisicion, tipo de prestamo 
devuelve el total de usos de un determinado ejemplar (interno o externo segun el tipo)
*/
private function get_total_prestamoint($nadqui,$tipo){
	$total=$this->Libro_model->totalprestamointerno($nadqui,$tipo);
	if($total){
		foreach ($total as $dato){
			$total=($dato->total) ?$dato->total :0;
		}
	}
	return $total;
}
/* 	@params numero de adquisicion tipo 
	devuelve la fecha de ultimo uso interno o externo
*/
private function get_ultimo_uso($nadqui,$tipo){
	$uso=$this->Libro_model->ultimousointer($nadqui,$tipo);
	$ultimo='Nunca';		  	 		
		if($uso){
			foreach($uso as $dato){
				$ultimo=$dato->dia?$dato->dia:'Nunca';
			}
		}
		return $ultimo;
} 
	/*params termino de busqueda
		elimina caracteres especiales del termino de busqueda
		retorna el termino limpio de caracteres que pudieran afectar la consulta
	*/
	 function sanear_busqueda($termino){
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
	 function json(){	 	
	   	$data = json_decode(file_get_contents("php://input"));	
	   		if(isset($data)){
	   				$arr_data=array();
	   				$resultado=[];
	   				if(isset($data->idficha)){
	   					//busca por numero de ficha
				   		$resultado = $this->Libro_model->findbyidjson($data->idficha);
				   		$arr_data=$this->get_datosficha($resultado,"ficha");
	   				}elseif(isset($data->nad)&&!empty($data->nad)){
	   					//busca por numero de adnquisicion
				   		$resultado = $this->Libro_model->findbynadjson($data->nad);
				   		$arr_data['mensaje']=$resultado ? "Encontrado" :"No se encontro el ejemplar";
				   		$arr_data['datos']=$this->get_datosficha($resultado,"nadqui");
	   				}else{
	   					//busca por diferentes criterios autor isbn editorial 
	   					$buscarpor=array('titulo'=>'','isbn'=>'','editorial'=>'','autor'=>'','materia'=>'',
	   										'limitar'=>10);
				 	   	//sanitizamos las variables de busqueda
				 	   	foreach ($data as $llave=>$valor){
				 	   		if(isset($valor)){
				 	   			$buscarpor[$llave]=$this->sanear_busqueda($valor);
				 	   		}
				 	   	}
				 	   	$inicio=0;
				 	   	//buscamos desde panel de usuario ejemplar true
						$buscarejem=isset($data->buscarejemplar) ? 1:0;
						$buscarpor['buscarejem']=$buscarejem; 	
				 		if(isset($data->limitar)&&(is_numeric($data->limitar))&&$data->limitar<40){
				 			 $buscarpor['limitar']=$data->limitar;
				 		}elseif(isset($data->inicio)) {
				 			$inicio=$data->inicio;
				 		} 	
				 		$sesion=$this->get_session();
					 	if($sesion){
				   			$rol = $this->get_rol_sesion();
				      		if($rol==7){
				      			$this->load->model('Biblioteca_model','',TRUE);
								if($this->Biblioteca_model->getpermisoBiblio('busquedaspu')){
				      				$resultado = $this->Libro_model->findajaxuser($buscarpor,$buscarpor['materia']);
			 					}else{
			 						$this->mensajeservicio ='Servicio temporalmente no disponible';
			 					}
			 				}else{
			 					$resultado = $this->Libro_model->findajax($buscarpor);
			 				}
				 		}elseif(isset($data->cuenta)){
				 			$arr_data['total'] = $this->Libro_model->findajaxuser($buscarpor,$buscarpor['materia'],$inicio,$onlycont='contar');
				 			$resultado = $this->Libro_model->findajaxuser($buscarpor,$buscarpor['materia'],$inicio);
				 		}			
		
				$arr_data['ejemplares']=$this->get_resultado($resultado,$buscarejem);
				$arr_data['mensaje']=$this->mensajeservicio;
	   	}	
  	 	echo json_encode($arr_data);  
		}
	 }
	 var $mensajeservicio="";
	 private function get_resultado($resultado,$buscarejem){
	 		$arr_data=array();
	 		if($resultado){	
					 	$tit="";
					 	$i=0;		
				    foreach ($resultado as $row) {
				    		$tit=$row->titulo;
				    		$arr_data[$i]['titulo']=explode("/", $tit)[0];
				  	 		$arr_data[$i]['clasificacion']=$row->clasificacion;
				  	 		$arr_data[$i]['nameautor']=$row->isbn;
				  	 		
				  	 		if($buscarejem){
				  	 			$arr_data[$i]['nadqu']=$row->nadqui;
				  	 		}else{
				  	 		$arr_data[$i]['nfic']=$row->nfic;	
				  	 		}
				  	 		$i++;
				  	 	}
		  	 	}
		  	 	return $arr_data;
	 }
	 function id_max(){
	 	$resultado = $this->Libro_model->maxnadqui();
		 	foreach ($resultado as $row) {
		 		$idmax=$row->nadqui;
		 	}
		if(!$idmax){
	 		$idmax=0;
		}
	 	echo $idmax;  
	 }
	 function editar(){
	 	$data = json_decode(file_get_contents("php://input"));
 		if(isset($data->idficha)&&isset($data->campo)&&isset($data->valor)){
 			//echo $data->idficha." ".$data->campo.":".$data->valor;
 			$libro['idlibro']=$data->idficha;
 			$libro[$data->campo]=$data->valor;
 			$this->load->model('Ficha_model','',TRUE);
            $correcto=$this->Ficha_model->agregalibro($libro);
            $arr_data['exito']=$correcto;
            if($correcto){
            	$arr_data['mensaje']="Actualización Exitosa";
            	
            }else{
            	$arr_data['mensaje']="No fue posible actualizar intente de nuevo";
            }
            echo json_encode($arr_data);
 		}
	 }
	 function chart(){
	 	$clasificacion=$this->Libro_model->get_clasificaciones_grafica();
	 	$data= array();
	 	foreach ($clasificacion as $dato) {
	 		array_push($data,$this->get_Dataset($dato));
	 	}
	 	echo json_encode($data);
	 }
	 function prestamospor_categoria(){
	 	$clasificacion=$this->Libro_model->get_prestamos_por_categoria();
	 	$data= array();
	 	foreach ($clasificacion as $dato) {
	 		array_push($data,$this->get_Dataset($dato));
	 	}
	 	echo json_encode($data);
	 }
	  private function get_Dataset($dato){
        $dataset=array();
            $color='rgba('.rand(0,255).','.rand(0,255).','.rand(0,255); 
            $dataset['color']=$color.",0.5)";
            $dataset['highlight']=$color.",0.8)";
            $dataset['value']=$dato['total'];
            $dataset['label']=$dato['Clasificacion'];
            return $dataset;
    }
    function get_bajas(){
        $this->load->model('Libro_model','',TRUE);
        $modelo= new Libro_model;
        echo json_encode($this->consulta_bajas("bajas",$modelo));
    }
    private function consulta_bajas($tipo,$modelo){
        $sesion=validarsesion();
        if($sesion){
            if(get_rol_sesion($sesion)==2){
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
}
?>
