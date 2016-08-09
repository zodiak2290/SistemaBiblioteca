<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Actividad extends MY_Controller  {
    var $namimg="";
    //cargamos las librerias necesarios y el modelo a utilizar
	function __construct(){
        parent::__construct();
        $this->load->library('form_validation'); 
        $this->load->model('ActividadModel','',TRUE);
    }
    //funcion principal muestra la pagina de inicio
	function index(){	
       $this->cargar_vista();
    }
    function actividadessf(){
        if(validarsesion()){
            if(validarsesion()['rol']==4){
                $arr_data=array();
                $actividades=$this->ActividadModel->actividadessinfecha();
                if($actividades){
                    $arr_data['actividadesf']=$actividades;
                }
                echo json_encode($arr_data);
            }else{
                redirect('home','refresh');
            }
        }else{
            redirect('home','refresh');
        }
    }
    /*
        Recibe año (2012,2015) para seleccionar las actividades realizadas
        Retorna json vea->Calendar boostrap 
    */
    function jsoneventos($anio){
        $out = array();
        $arr=array('anio'=>'');
        //$arr['anio']=$anio;
        $actividades=$this->ActividadModel->show("s",$anio);
        if($actividades){
            foreach ($actividades as $row) {
               $class=$row->enregistro==0 ? 'parpadea' : '';
               $out[] = array(
                    'id' => $row->idactividad,
                    'title' => utf8_encode(ucwords(strtolower(utf8_decode($row->nombre)))),
                    'url' => base_url().'home/actividad/ver/'.$row->idactividad,
                    'start' => strtotime($row->dia." ".$row->horainicio).'000',
                    'end' => strtotime($row->dia." ".$row->horafin).'000',
                    'class'=>$class 
                );
            }
        }    
        echo json_encode(array('success' => 1, 'result' => $out));
    }
    /*
        carga pagina para agregar nuevas actividades
     */
	function cargar_vista($errores= array('' => '' )){
        if(validarsesion()){    
            $data=$errores;
         	$data['cuentaempleado'] = get_cuenta_sesion();
         	$data['pnombre'] = get_nombre_persona_en_session();
         	$data['rol'] = get_rol_sesion();
    	 	$data['content']='evento/nuevaacti'; 		
         	$this->load->helper('url');   
         	$this->load->view('layouts/layoutadmin', $data); //llamada a la vista general
        }else{
         //If no session, redirect to login page
            redirect('login', 'refresh');
        }	
    }
    /* 
        Verifica la sesion 
            Que el usario se el correcto(rol)
            Recibe datos enviados por post y comprueba que sean los esperados, elimina la actividad deseada
    */
    function eliminardia(){
        if(validarsesion()){
            if(validarsesion()['rol']==4){
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data->ida)&&isset($data->idactiv)){
                    $actividad=$this->ActividadModel->findById($data->idactiv);  
                    if($actividad){
                        $dirigido="";
                        foreach ($actividad as $row) {
                            $dirigido=$row->dirigidoa;
                        }
                        $exito=(strcmp($dirigido,"te")==0) ?$this->ActividadModel->deletera($data->ida):$this->ActividadModel->deleteac($data->ida);
                        echo $exito ? "Eliminado":"Intente de nuevo";
                    }else{
                        echo "Intente de nuevo";
                    }
                }
            }else{
                redirect('home','refresh');
            }
        }else{
            redirect('home','refresh');
        }
    }
     function registrar(){
        if(validarsesion()){
            if(validarsesion()['rol']==4||validarsesion()['rol']==9){
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data->ida)&&isset($data->valor)){
                    if(is_int(intval($data->valor))){
                        if(isset($data->dir)){
                            if(strcmp($data->dir,"ninos")==0||strcmp($data->dir,"jovenes")==0||strcmp($data->dir,"adultos")==0){
                                $registro=$this->ActividadModel->registroactividadte(intval($data->valor),substr($data->ida,0,strlen($data->ida)-1),$data->dir);
                            }  
                        }else{
                            $registro=$this->ActividadModel->registroactividad(intval($data->valor),$data->ida);
                        }
                        if($registro){
                           echo "Listo";
                        }else{
                            echo "Intente de nuevo";
                       }
                    }
                }else{
                    redirect('home','refresh');
                }
            }else{
                redirect('home','refresh');
            }
        }else{
            redirect('home','refresh');
        }
    }
    function delete_actividad($id){
    if(validarsesion())
    {  
        if($this->dejarpasar(validarsesion()['rol'])){
            $imagen='';
            $datos=$this->ActividadModel->findById($id);
            if($datos){    
                foreach ($datos as $row)
                    {
                        $imagen=$row->imagen;
                    }   
                }
             $this->borrarimagen($imagen);
             $borrar = $this->ActividadModel->delete($id);
            $this->session->set_flashdata('correcto', 'Eliminado');
          redirect(base_url().'home/actividades', 'refresh');
        }else{
            redirect('home', 'refresh');
        } 
    }else
    {
        //If no session, redirect to login page
        redirect('login', 'refresh');
        }
    }
 
    function addfechas(){
        if(validarsesion())
        {  
            $arre['inserta']=false;
            if($this->dejarpasar(validarsesion()['rol'])){
                $data = json_decode(file_get_contents("php://input"));
                if(isset($data)) {
                    if(isset($data->id)&&isset($data->datos)){
                        $arre=array();$valido=true;
                        foreach ($data->datos as $fecha){       
                           $valido=$valido&&$this->validacion($fecha->dia,"fecha");
                        }
                           if($valido&&$this->validacion($data->id,"id")){
                                if($this->insertar_dias_act($data->datos,$data->id)){
                                    $arre['insert']="Registro exitoso"; 
                                    $arre['inserta']=true;    
                                }else{
                                    $arre['insert']="Error al actualizar. Intente de nuevo";
                                }
                            }else{
                                $arre['error']="Algunos campos no son correctos. Verifíquelos";
                            }
                        echo json_encode($arre);
                    }else{
                        echo "datos";
                    }
                }else{
                    echo "sindatos";
                }
            }else{
              redirect(base_url().'home/actividades', 'refresh'); 
            }
        }
        redirect('home', 'refresh'); 
    }  
    private function insertar_dias_act($arrdias,$id){
        $insercioncorrecta=true;
        $con=$this->ActividadModel->totalActividadesByEvento($id);
        $con=$con+1;
        foreach ($arrdias as $dia) {
            $datos=$this->ActividadModel->diasactividad($dia,$id,$con);   
            $insercioncorrecta=$insercioncorrecta&&$datos;
            $con++;
        }
        return $insercioncorrecta;

    }  
  private function validacion($dato,$tipo){
        //validamos los diversos campos de empleado
        $valido=false;
        if(strcmp($tipo, "id")==0){
            $valido=preg_match('/^a[\d]{15,24}$/', $dato);
        }else if (strcmp($tipo,"lugar")==0) {
            $valido=preg_match('/^[A-Za-zñáéíóú\s-.\/]{0,100}$/i',$dato);
        }else if(strcmp($tipo,"hora")==0){
            $valido=preg_match('/^(\d\d\:\d\d){1,1}$/', $dato);
            $valido=$valido||preg_match('/^(\d\:\d\d){1,1}$/', $dato);
        }elseif (strcmp($tipo,"fecha")==0) {
            $valido=preg_match('/^(\d\d\d\d\-\d\d\-\d\d){1,1}$/', $dato);
        }
        return ($valido==1) ?$dato:false;       
    }
     private function dejarpasar($rol){ 
        $accesso=false;
      if($rol==1||$rol==4){
        $accesso=true;
      }
    return $accesso;
 }
    private function validar_hora($inicio,$fin){
        if($this->validacion($inicio,"hora")&&$this->validacion($fin,"hora")){
          $horainicial=intval(explode(":",$inicio)[0]);
                    $min=intval(explode(":",$inicio)[1]);
          $horafinal=intval(explode(":",$fin)[0]);
          $min2=intval(explode(":",$fin)[1]);
        if($horainicial==$horafinal){
            $horainicial<=$horafinal&&$horafinal<24&&$horainicial<24&&$min<60&&$min2<60&&$min<$min2;  
        }else{
            return $horainicial<=$horafinal&&$horafinal<24&&$horainicial<24&&$min<60&&$min2<60;
        }
        }
        else{
            return false;
        }
    }
    function nueva(){
        $arr_data=array();
        $mesnaje="";
        $titulo=$this->input->post('titulo');
        $fin=$this->input->post('fin');
        $imagen="";
        $subir="";
        $desc=$this->input->post('desc');
        $inicio=$this->input->post('inicio');
        $dirigido=$this->input->post('dirigido');
        $lugar=$this->input->post('lugar');
        if(isset($titulo)&&isset($dirigido)&&isset($desc)&&isset($lugar)&&isset($inicio)&&isset($fin)){
            if($this->validar_hora($inicio,$fin)){
               if(strcmp($titulo,"undefined")!=0&&strcmp($desc,"undefined")!=0){
                    if(strcmp($dirigido,"undefined")==0){
                        $dirigido="hd";
                    }
                           $imgin=$this->cargarimagen();
                           if(strcmp($imgin,"Imagen agregada")==0){ 
                            $cuenta = validarsesion()['cuentausuario'];
                           $subir=$this->ActividadModel->subir($titulo,$this->namimg,$desc,$cuenta,$dirigido,$lugar,$inicio,$fin); 
                           if($subir){
                                $mesnaje="Registrada";
                                $imagen=explode(".",$this->namimg)[0]."_thumb.".explode(".",$this->namimg)[1];                              
                           }
                           }else{
                            $mesnaje=$imgin;
                           }
                        }else{
                            $mesnaje= "Faltan campos";
                        }
            }else{
                $mesnaje= "Formato de hora inválido";
            }
        }else{
            $mesnaje="Faltan campos";
        }
        $arr_data['mensaje']=$mesnaje;$arr_data['imagen']=$imagen;$arr_data['id']=$subir;
        echo json_encode($arr_data);
    }
    private function cargarimagen(){
        //verificamos que eista la imagen
        if(isset($_FILES)){
            $nombre=$_FILES['file']['name'];

            $nameimg=date('YmdHms').".".explode(".",$nombre)[1];
            //tamaño de la imagen
            if($_FILES['file']['size']<1000000){  
                if($this->extecorrecta($_FILES['file']['type'],array('jpg' => 'image/jpeg','png' => 'image/png','gif' => 'image/gif'))){
                    $file=$nameimg;
                    $this->namimg=$file;
                    if(!is_dir("./images/")){
                        mkdir("./images/",0777);
                    }
                    if($file && move_uploaded_file($_FILES["file"]["tmp_name"], "./images/".$file)){
                        $this->_create_thumbnail($file);
                        return "Imagen agregada";
                    }
                }else{
                    return "Formato de imagen inválida. Intente con JPG,PNG,GIF";
                }    
              }else{ 
              return "Imagen demasiado pesada";
              } 
           }else{
            return "Falta imagen";
           } 
    }
    function camabiandoimagen(){
        $nuevonombre=$_FILES['userfile']['name'];
                            $id = $this->input->post('id');
        if(isset($_FILES)&&strlen($nuevonombre)>0){
            $nameimg=substr($nuevonombre,0,10).".".explode(".",$nuevonombre)[1];
            $this->borrarimagen($this->input->post('imagen')); 
            $config['upload_path'] = './images/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2000';
            $config['max_width'] = '2024';
            $config['max_height'] = '1024';
            $config['file_name'] ="img".date('YmdHms').".".explode(".",$nameimg)[1];
            //$file_element_name = 'userfile';        
            $this->load->library('upload', $config);
            $exito=$this->upload->do_upload();
                if (!$exito){
                    $data = array('error' => $this->upload->display_errors());
                    foreach ($data as $er) {
                        $this->session->set_flashdata('correcto', $er);
                        redirect(base_url().'home/actividad/ver/'.$id, 'refresh');
                    }
                }else {
                    $file_info = $this->upload->data();
                    $this->_create_thumbnail($file_info['file_name']);
                    $data = array('upload_data' => $this->upload->data());
                    $imagen = $config['file_name'];
                    $this->ActividadModel->updateimage($id,$imagen); 
                    redirect(base_url().'home/actividad/ver/'.$id, 'refresh');
                }
        }else{
            redirect(base_url().'home/actividades', 'refresh');
        }
    }
    function editar(){
        if(validarsesion()['rol']==4){
            $data = json_decode(file_get_contents("php://input"));
                $mensaje="";
                if(isset($data)){  
                    // if(strcmp($data->dato,"descripcion")==0){
                        if(count($data->valor)<301){
                            $desc=str_replace("<","",$data->valor);
                            $actividad[$data->dato]=$data->valor; 
                            $mensaje=$this->update($actividad,$data->ida);            
                        }else{
                            $mensaje="Número máximo de caraceres: 300";
                        }
                        echo $mensaje;
                     //}
                 }
        }else{
            echo "No tienes permisos para editar esta actividad";
        }
    }
private function update($actividad,$id){
    $mensaje="";
    $correcto=$this->ActividadModel->update($actividad,$id);
    $mensaje=$correcto ? "Actualización Exitosa" : "No fue posible actualizar los datos. Intente de nuevo";
    return $mensaje;
}

 	 function cargar_actividad($accion="c") {
       if(validarsesion())
        {  
        if($accion=="i"){
            $this->borrarimagen($this->input->post('imagen'));   
        }
        $this->form_validation->set_rules('titulo', 'Título','required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('descripcion', 'Descripción','min_length[0]|max_length[300]');
        $this->form_validation->set_message('required', 'El campo no puede ir vacío');
        $this->form_validation->set_message('min_length', 'El título debe tener al menos 3 caracteres');
        $this->form_validation->set_message('max_length', 'El campo no puede tener más de %s caracteres');
        $file_element_name = 'userfile';
        if ($this->form_validation->run() == TRUE) 
        {

            if($accion=="c" || $accion=="i" ){
                $config['upload_path'] = './images/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2000';
                $config['max_width'] = '2024';
                $config['max_height'] = '1024';         
                $this->load->library('upload', $config);
                $exito=$this->upload->do_upload($file_element_name);
                    if (!$exito){
                        $data = array('error' => $this->upload->display_errors());
                        foreach ($data as $er) {
                            echo $er;
                        }
                    }else {
                        $file_info = $this->upload->data();
                        $this->_create_thumbnail($file_info['file_name']);
                        $data = array('upload_data' => $this->upload->data());
                        $titulo = $this->input->post('titulo');
                        $imagen = $file_info['file_name'];
                        $descripcion = $this->input->post('descripcion');
                        $cuenta=$this->input->post('cuenta');
                        $dirigido = $this->input->post('dirigido');
                        $this->cargadatos($accion,$titulo,$imagen,$descripcion,$cuenta,$dirigido);
                    }
                }else{
                        $titulo = $this->input->post('titulo');
                        $imagen = $this->input->post('imagen');  
                        $descripcion = $this->input->post('descripcion');
                        $dirigido = $this->input->post('dirigido');

                        $cuenta=$this->input->post('cuenta');
                        $this->cargadatos($accion,$titulo,$imagen,$descripcion,$cuenta,$dirigido);
            }
        }else{
        //SI EL FORMULARIO NO SE VÁLIDA LO MOSTRAMOS DE NUEVO CON LOS ERRORES
             $this->index();
        }
        }else
    {
        //If no session, redirect to login page
        redirect('login', 'refresh');
        }
    }
    private function cargadatos($accion="c",$titulo,$imagen,$descripcion,$cuenta,$dirigido){
        redirect(base_url().'home/actividades', 'refresh');
    }
    //FUNCIÓN PARA CREAR LA MINIATURA A LA MEDIDA QUE LE DIGAMOS
    private function _create_thumbnail($filename){
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
        $config['source_image'] = './images/'.$filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = False;
        //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
        $config['new_image']='./images/thumbs/';
        $config['width'] = 424;
        $config['height'] = 323;
        $this->image_lib->initialize($config); 
        $this->image_lib->resize();
     }
    }
?>