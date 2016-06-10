<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ficha extends CI_Controller {
  var $libro= array('idlibro'=>'',
                        'isbn' =>"",
                        'idioma'=>'',
                        'clasificacion_id'=>'',
                        'titulo'=>'',
                        'edicion'=>'',
                        'descfisica'=>'',
                        'serie'=>'',
                        'notageneral'=>'',
                        'contenido'=>'');
  var $clasificacion=array('idclasificacion'=>'',
                            'clasificacion'=>'');
  var $editorial=array('ideditorial' => '',
                        'nameeditorial'=>'');
  var $autor=array();
	function __construct() {
      parent::__construct(); 
      $this->load->model('Ficha_model','',TRUE);
      $this->load->helper('sesion_helper');
  }
  function verificar_arreglo_valido($arr_data){
      $valor=true;
      foreach ($arr_data as $elemento){
         if($elemento===false){
            $valor=$valor&&false;
         }
       }
       return $valor;
    }
 private function set_datos_libro($libro){
    $this->set_libro(intval($libro['idlibro']),$libro['isbn'],$libro['idioma'],$libro['clasificacion'],$libro['titulo']
                        ,$libro['edicion'],$libro['desc'],$libro['serie'],$libro['notag'],$libro['contenido']);
    if(isset($libro['clasificacion'])){
      $this->creaclasificacion($libro['clasificacion']);
      $this->findclasificacion($libro['clasificacion']);
    }
    if(isset($libro['editorial'])){
      $this->creaeditorial($libro['editorial']);
      $this->findeditorial($libro['editorial']);
    }             
 }
 private function procesar_autores($autores){
    foreach ($autores as $autor){
      $autor=trim($autor);
      if(strlen($autor)>4){
        $this->crearautor($autor);
        $this->findautor($autor);    
      }
    }
 }
 private function procesar_materias($materias){
    foreach ($materias as $materia) {
      $materia=trim($materia);
      if(strlen($materia)>3){
        $this->Ficha_model->findmateriabyname(mb_strtolower($materia,"UTF-8"));
      } 
    }  
 }   
 private function porcesar_datos($data){
    $libro=json_decode(json_encode($data->lib),true);
    if(isset($libro['idlibro'])){
        $existe=$this->Ficha_model->findbyidficha(intval($libro['idlibro']));
          if(!$existe){
             $this->set_datos_libro($libro);
              if(isset($data->aut)){
                $this->procesar_autores($data->aut);
              }
              $materias=[];
              if(isset($data->mat)){
                $this->procesar_materias($data->mat);
                $materias=$data->mat;
              }
             if($this->insertarlibro($materias)){
                $arr_data['mensaje']="Datos agregados correctamente";
              }else{
                $arr_data['mensaje']="Ocurrió un problema al intentar registrar los datos";
              }             
          }else{
            $arr_data['mensaje']="Este libro ya esta registrado. Vaya al apartado de Libros, para agregar un ejemplar";
          }
    }else{
      $arr_data['mensaje']="No hay datos que agregar";
    }
    return $arr_data;              
 }  
 function recibedatoslibros(){
    if(validarsesion())
        {
          $arr_data=[];
          if(validarsesion()['rol']==2){
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data)&&isset($data->lib)){    
               $arr_data=$this->porcesar_datos($data);   
            }
        }
      echo json_encode($arr_data);
    }
 }
 private function insertarlibro($materias){
    $libro=$this->get_libro();
     $clasificacion=$this->get_clasificacion();
     $libro['clasificacion_id']=$clasificacion['idclasificacion'];
      $agregado=$this->Ficha_model->agregalibro($libro);
      if($agregado){
        $this->insertar_fichatemas($materias,intval($libro['idlibro']));
        $editorial=$this->get_editorial();
        $this->publicacion($editorial['ideditorial'],$libro['idlibro']);
        $autores=$this->get_autor();
        if(count($autores)>0){
          foreach ($autores as $autor) {
            $this->insertarautoresc($autor['idautor'],$libro['idlibro']);
          }
        }

        return true;
      }else{
        return false;
      }
      
 }
private function creaclasificacion($clas){
  $this->load->model('Editorial_model','',TRUE);
  $this->Editorial_model->creaclasificacion($clas);
 }
 private function creaeditorial($nombre){
  $this->load->model('Editorial_model','',TRUE);
  $this->Editorial_model->creaeditorial($nombre);
 }
 function agregarclasificacion(){
  $data=$this->recibir_Data();
  if(isset($data->idficha)&&isset($data->clasificacion)){
    if(strlen($data->clasificacion)>0){
      $this->creaclasificacion($data->clasificacion);
      $this->findclasificacion($data->clasificacion);
      $clasificacion=$this->get_clasificacion();
      $libro['clasificacion_id']=$clasificacion['idclasificacion'];
      $libro['idlibro']=$data->idficha;
      $exito=$this->Ficha_model->agregalibro($libro);
     $this->acciones($exito,"Clasificación editada","No fue posible editar,intente mas tarde");
    }
  }
 }
 private function findclasificacion($clasificacion){
     $idclasificacion="";$cla="";
     $this->load->model('Editorial_model','',TRUE);
    $result=$this->Editorial_model->creaclasificacion($clasificacion);
            foreach ($result as $row ) {
                     $idclasificacion=$row->idclasificacion;
                     $cla=$row->clasificacion;
            }
    $this->set_clasificacion($idclasificacion,$cla);
 }
private function set_clasificacion($id,$clas){
  $this->clasificacion['idclasificacion']=$id;
  $this->clasificacion['clasificacion']=$clas;  
}
private function get_clasificacion(){
    return $this->clasificacion;
}

  private function findeditorial($editorial){
    $ideditorial="";$nameeditorial="";
     $this->load->model('Editorial_model','',TRUE);
    $result=$this->Editorial_model->creaeditorial($editorial);
            foreach ($result as $row ) {
                     $ideditorial=$row->ideditorial;
                     $nameeditorial=$row->nameeditorial;
            }
    $this->set_editorial($ideditorial,$nameeditorial);
}
private function set_editorial($id,$nombre){
  $this->editorial['ideditorial']=$id;
  $this->editorial['nameeditorial']=$nombre;  
}
private function get_editorial(){
    return $this->editorial;
}
 private function crearautor($nombre){
  $this->load->model('Editorial_model','',TRUE);
  $this->Editorial_model->creaautor($nombre);
 }
 private function creamaterias($materia){
  $this->load->model('Editorial_model','',TRUE);
  $this->Editorial_model->creamateria($materia);  
 }
   private function findautor($autor){
    $idautor="";$nameautor="";
     $this->load->model('Editorial_model','',TRUE);
    $result=$this->Editorial_model->creaautor($autor);
            foreach ($result as $row ) {
                     $idautor=$row->idautor;
                     $nameautor=$row->nameautor;
            }
    $this->set_autor($idautor,$nameautor);
}
private function set_autor($id,$nombre){
  $auto['idautor']=$id;
  $auto['nameautor']=$nombre;  
  array_push($this->autor,$auto);
  
}
private function get_autor(){
    return $this->autor;
}
 private function set_libro($idlibro='',$isbn='',$idioma='',$clasificacion_id='',$titulo='',$edicion='',$descfisica='',$serie='',$notageneral='',$contenido=''){
  $this->libro['idlibro']=$idlibro;
  $this->libro['isbn']=$isbn;
  $this->libro['idioma']=$idioma;
  $this->libro['clasificacion_id']=$clasificacion_id;
  $this->libro['titulo']=$titulo;
  $this->libro['edicion']=$edicion;
  $this->libro['descfisica']=$descfisica;
  $this->libro['serie']=$serie;
  $this->libro['notageneral']=$notageneral;
  $this->libro['contenido']=$contenido;
 }
 private function get_libro(){
  return $this->libro;
 }

      function publicacion($ideditorial,$idlibro){
         $idedit="e";//inicio de identificador de escribio
      $idedit.=$ideditorial."l".intval($idlibro);
            if($this->Ficha_model->publica($idedit)){//si esta llave ya se encuentra en el sistem continua sin hacer nada
            }else{//enc aso contraro agrega los respectivos datos a la tabla fichamateria 
              $this->Ficha_model->insertapublicacion($idedit,$ideditorial,intval($idlibro));
            }     
      }


  private function insertarautoresc($idautor,$idficha){
      $idescribio="l";//inicio de identificador de escribio
      $idescribio.=intval($idficha)."a".$idautor;//generamos idfichamateria a partir de idficha e idmateria
      if($this->Ficha_model->autorescribio($idescribio)){//si esta llave ya se encuentra en el sistem continua sin hacer nada
        $exito=true;
      }else{//enc aso contraro agrega los respectivos datos a la tabla fichamateria 
        $exito=$this->Ficha_model->insertaautorescribio($idescribio,$idautor,intval($idficha));
      }
    return $exito; 
  }
 private function agregando_materia($materia,$idficha){
    $materia=trim($materia);
    if(strlen($materia)>3){
    //$teman=explode("\\",$materia);//en el split anterior quedan algunas materias divididas por barras invertidas \ 
    // con este esplot separamos completamente las materias
    $idfichatema="l";//inicio de identificador de fichasmaterias
    //foreach ($teman as $tema) { //para cada materia buscaremos en la base de datos su idmateria 
        $idmateria=$this->get_idmateria($materia);//en caso de no existir la ficha_model se encargara de agregarlo y retorna el idmateroa
        $idfichatema=$idfichatema.intval($idficha)."m".$idmateria;//generamos idfichamateria a partir de idficha e idmateria
        if($this->Ficha_model->fichatema($idfichatema)){//si esta llave ya se encuentra en el sistem continua sin hacer nada
        $exito=true;
        }else{//enc aso contraro agrega los respectivos datos a la tabla fichamateria 
          $exito=$this->Ficha_model->insertafichatema($idfichatema,$idmateria,intval($idficha));
        }
        return $exito; 
    }
 }             
 private function insertar_fichatemas($materias,$idficha){
  //separamos las materias por , y guinoes generando un arreglo de materias
    //$arreglomaterias=preg_split("/[,-]/",$materias);
              $teman=array();
              foreach ($materias as $materia){
                $this->agregando_materia($materia,$idficha);                    
               // }
              }
 }
 function aggregarmateria(){
    $data=$this->recibir_Data();
    $this->Ficha_model->findmateriabyname($data->materia);
    $exito=$this->agregando_materia($data->materia,$data->idficha);
    $this->acciones($exito,"Se agrego la materia","No fue posible agregar,intente mas tarde");
 }
 function agregar_autor(){
    $data=$this->recibir_Data();
    $this->Ficha_model->findautorbyname($data->autor);
    $this->findautor($data->autor);
    $autor=$this->get_autor();
    $idautor=$autor[0]['idautor'];
    $exito=$this->insertarautoresc($idautor,$data->idficha);
    $this->acciones($exito,"Se agrego el autor","No fue posible agregar,intente mas tarde");
 }
 private function get_idmateria($materia){
  $idm="";
    $idmateria=$this->Ficha_model->findmateriabyname(mb_strtolower($materia,"UTF-8"));
    if($idmateria){
      foreach ($idmateria as $id){
        $idm=$id->idmateria;
      }
    }
  return $idm;
 }
private function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
    if($d && $d->format($format) == $date){
      $retorno=$date;
    }else{
      $retorno=false;
    }
    return $retorno;
}

 private function validacion($dato,$tipo){
        //validamos los diversos campos de empleado
        $valido=false;
        if(strcmp($tipo,"ncontrol")==0) {
            $valido=preg_match('/^[\d]{0,15}$/', $dato);
            if($valido){
              $iddexiste= $this->Ficha_model->findbyidficha($dato);
              $valido=$iddexiste ? 0 :1;
            }else{
              $valido=0;
            }
        }elseif (strcmp($tipo,"titulo")==0) {
          $valido=!preg_match('/<script>/i',$dato);
        }elseif(strcmp($tipo,"date")==0){
          $format = 'Y-m-d H:i:s';
            $d = DateTime::createFromFormat($format, $dato);
          $valido=($d && $d->format($format) == $dato)?1:0;
        }      
        $retorno=($valido==1) ? $dato :false; 
        return $retorno;
    }
    private function recibir_Data(){
      $data = json_decode(file_get_contents("php://input"));
      return $data;
    }
    function contar_by_autor(){
      $data=$this->recibir_Data();
      $this->load->model('Autor_model','',TRUE);
      $resultado=$this->Autor_model->contar_libros_by_autor($data->nameautor);
      $arr_data['total']=count($resultado);
      echo json_encode($arr_data);
    }
    function contar_by_materia(){
      $data=$this->recibir_Data();
      $this->load->model('Autor_model','',TRUE);
      $resultado=$this->Autor_model->contar_libros_by_materia($data->namemateria);
      $arr_data['total']=count($resultado);
      echo json_encode($arr_data); 
    }
    private function acciones($exito,$mensajeexito,$mensajefracaso){
      if($exito){
        $arr_data['mensaje']=$mensajeexito;
        $arr_data['exito']=true;
      }else{
        $arr_data['mensaje']=$mensajefracaso;
        $arr_data['exito']=false;
      }
      echo json_encode($arr_data);
    }
    function bajaautor(){
      $data=$this->recibir_Data();
      if(isset($data->nameautor)){
        $autor=$this->get_propiedad_by_name($data->nameautor,'autor','nameautor');
        $idautor=$this->obtener_idpropiedad($autor,'idautor');
        $exito=$this->Ficha_model->eliminarautor($idautor);
          $this->acciones($exito,"Se dio de baja el autor","No fue posible dar de baja al autor,intente mas tarde");
      }
    }
    function bajamateria(){
      $data=$this->recibir_Data();
      if(isset($data->namemateria)){
        $materia=$this->get_propiedad_by_name($data->namemateria,'materias','namemateria');
        $idmateria=$this->obtener_idpropiedad($materia,'idmateria');
        $exito=$this->Ficha_model->eliminarmateria($idmateria);
          $this->acciones($exito,"Se dio de baja la materia","No fue posible dar de baja la materia,intente mas tarde");
      } 
    }
    private function accion_editar($tabla,$campo,$idtabla,$identificador,$idrelacion,$tablarelacion,$data_t,$mensaje_existe){
      $data=$this->recibir_Data();
      if(isset($data->idficha)&&isset($data->name)&&isset($data->nuevoname)){
        $propiedad=$this->get_propiedad_by_name($data->name,$tabla,$campo);
        $idpropiedad=$this->obtener_idpropiedad($propiedad,$idtabla);
        $propiedadedit=$this->get_propiedad_by_name($data->nuevoname,$tabla,$campo);
        $mensaje="No fue posible editar intente de nuevo mas tarde";
        if(!$propiedadedit){
            $exito=$this->Ficha_model->editar_propiedad($idpropiedad,$data->nuevoname,$campo,$tabla,$idtabla);
        }else{
          $idpropiedadnuevo=$this->obtener_idpropiedad($propiedadedit,$idtabla);
          $idpropiedadeliminar=$this->get_idrelacion($identificador,$data->idficha,$idpropiedad);
          $this->Ficha_model->eliminarrelacion($idpropiedadeliminar,$idrelacion,$tablarelacion);
          $idnewpropiedad=$this->get_idrelacion($identificador,$data->idficha,$idpropiedadnuevo);
          if(!$this->Ficha_model->existe_relacion($tablarelacion,$idrelacion,$idnewpropiedad)){
              $data['libro_id']=$data->idficha;
              $data[$idrelacion]=$idnewpropiedad;
              $data[$data_t]=$idpropiedadnuevo;
              $exito=$this->Ficha_model->insertar_relacion($data,$tablarelacion);
          }else{
            $exito=false;
            $mensaje=$mensaje_existe;
          }
        }
        $this->acciones($exito,"Edición exitosa",$mensaje);
      }
    }
    function editarautor(){
      $this->accion_editar('autor','nameautor','idautor','a','idescribio','autorescribio','autor_id',
                      "Esta autor ya esta agregado al libro");
    }
    function editarmateria(){
        $this->accion_editar('materias','namemateria','idmateria','m','idmaterialibro','materialibro','materia_id',
          "Esta materia ya esta agregada al libro");    
    }
    private function get_propiedad_by_name($nombre,$tabla,$campo){
      $propiedad=$this->Ficha_model->obtener_propiedad_por_nombre(trim($nombre),$tabla,$campo);
      return $propiedad;
    }
    private function obtener_idpropiedad($propiedad,$idpropiedad){
      $id="";
      if($propiedad){
        foreach ($propiedad as $campo) {
          $id=$campo->$idpropiedad;
        }
      }
      return $id;
    }
    private function accion_quitar($mensajeexito,$mensajefracaso,$identificador_propiedad,$tabla,$campo,$id,$tablarelacion,$idpro){
        $data=$this->recibir_Data();
        if(isset($data->idficha)&&isset($data->name)){
          $propiedad=$this->get_propiedad_by_name($data->name,$tabla,$campo);
          $idpropiedad=$this->obtener_idpropiedad($propiedad,$idpro);
          $idrelacion=$this->get_idrelacion($identificador_propiedad,$data->idficha,$idpropiedad);
          $exito=$this->Ficha_model->eliminarrelacion($idrelacion,$id,$tablarelacion);
          $this->acciones($exito,$mensajeexito,$mensajefracaso);
        }
    }
    function quitarmateria(){
      $this->accion_quitar("Se quito la materia de este libro",
                          "No fue posible quitar la materia intente de nuevo mas tarde",
                          'm','materias','namemateria','idmaterialibro','materialibro','idmateria');
    }
    function quitarautor(){
      $this->accion_quitar("Se quito el autor de este libro",
                          "No fue posible quitar el autor intente de nuevo mas tarde",
                          'a','autor','nameautor','idescribio','autorescribio','idautor');
    }
    private function get_idrelacion($identificador_propiedad,$idlibro,$idpropiedad){
      return 'l'.$idlibro.$identificador_propiedad.$idpropiedad;
    }
}
?>

