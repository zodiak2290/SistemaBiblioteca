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
class Users extends CI_Controller {
    var $persona= array('curp'=>'','pnombre' =>"",
                        'apPat'=>'','apMat'=>'',
                        'email'=>'','direccion'=>'',
                        'cp'=>'','fechaNaci'=>'',
                        'telefono'=>'','ocupacion'=>'',
                        'tel2'=>'','nombreaval'=>'',
                        'emailaval'=>'','telefonoaval'=>'');
    var $usuario= array('pcurp' =>'' ,'cuentausuario'=>'',
                        'password_d'=>'');
    var $arr_data=[];
    var $grupo=array("grupo_id"=>'',"user_id"=>'');
	function __construct() {
        parent::__construct();
         $this->load->library('form_validation'); 
         $this->load->model('Persona_model','',TRUE); 
         $this->load->model('Empleado_model','',TRUE); 
         $this->load->model('Prestamo_model','',true);   
         $this->load->model('User','',TRUE);
        $this->load->helper('sesion_helper');        
    }
	function index(){  
       $this->cargar_vista();
    }
    function renovar($id){
        if(validarsesion()['rol']==3){
            $datos['updated_at']=date('Y-m-d H:i:s');
            if($this->Persona_model->update($datos,$id)){
                $this->session->set_flashdata('correcto','Renovación exitosa');
                redirect('home', 'refresh');
            }
        }else{
            redirect('home','refresh');
        }
    }
    private function validarfechareserva($reservas){
        $this->load->model('Reserva_model','',TRUE); 
        $hoy=date("Y-m-d H:i:s");
        foreach ($reservas as $reserva) {
            if(strtotime($reserva->limite)<strtotime($hoy)){
                $this->Reserva_model->eliminarreserva($reserva->idreserva);
                return false;
            }else{
                //returna true
                return true;
            }
        }
     }
    function get_datos_prestamos_by_usuario(){
        $data = json_decode(file_get_contents("php://input"));
        if(validarsesion()){
            if(validarsesion()['rol']==7){
                $this->usuario_web(validarsesion()['cuentausuario']);
            }else{
                redirect('home','refresh');
            }
        }else if(isset($data->cuenta)){
            $this->usuario_web($data->cuenta);
        }else{
            echo "redirigido";
            //redirect('home','refresh');
        }
    }
    private function usuario_web($cuenta){
        $this->load->model('Prestamo_model','',TRUE); 
        $this->load->model('Reserva_model','',TRUE); 
        $datos=$this->get_datos_prestamos_usuario($cuenta);
        $reservas=$this->get_reservas_usuario($cuenta);
        if($reservas){
            $reservavalida=$this->validarfechareserva($reservas);
            $reservas=$this->Reserva_model->get_reservas($cuenta);
            foreach ($reservas as $reserva){
                $reserva->mensaje=$this->getstatus($reserva->nadqui);
            }
        } 
        $this->enviardatos($datos,$reservas);
    }
    //funcion tomada de Libro _Controller line 915
    private function getstatus($nadqui){
    $this->load->model('Libro_model','',TRUE); 
    $prestado=$this->Libro_model->prestado($nadqui);
    $reservado=$this->Libro_model->reservado($nadqui);
    $reparacion=$this->Libro_model->reparacion($nadqui);
    if($prestado){
        return "El ejemplar aún esta en préstamo";
    }elseif($reparacion){
        return "El ejemplar esta en reparación";
    }else{
        return "El ejemplar esta disponible";
    }
    
}
    private function enviardatos($datos,$reservas){
        $arr_data['datos']=$datos; 
        $arr_data['reservas']=$reservas; 
        echo json_encode($arr_data);    
    }
    private function get_datos_prestamos_usuario($cuenta){
        return $this->Prestamo_model->get_prestamos($cuenta);
    }
    private function get_reservas_usuario($cuenta){
        return $this->Reserva_model->get_reservas($cuenta);
    }
     function get_recomendaciones(){
        if(validarsesion()){
            if(validarsesion()['rol']==7){
                $this->load->model('Libro_model','',TRUE); 
                $adquisiciones=new Libro_model;
                $datosadqui=$adquisiciones->adquisiciones(validarsesion()['cuentausuario']); 
                echo json_encode($datosadqui);
            }else{
                redirect('home','refresh');
            }
        }else{
            redirect('home','refresh');
        }
    }
    function cambiarpass(){
        if(validarsesion()){
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]');
                $this->form_validation->set_message('matches', 'Las contraseñas no coinciden');   
            if($this->form_validation->run()==TRUE){
                $pass=$this->input->post('password');
                $cuenta=$this->input->post('cuenta');
                $id=$this->input->post('id');
                $empleado = $this->editar_pas($pass,$cuenta,$id);
                $mensaje="La contraseña del usuario: ". strval($cuenta) ." ha sido cambiada con éxito";
                $this->acciones(3,$mensaje);
              }else{
                $this->acciones(1);           
            }
        }else{
        redirect('login','refresh');
        }
    }
    private function editar_pas($pass,$cuenta,$id){
        $this->usuario['pcurp']=$id;
        //$this->usuario['cuentausuario']=$cuenta;
        $this->usuario['password_d']=MD5($pass);
        return $this->Persona_model->editpass($this->usuario);
    }
    private function cargar_vista($pagina="",$errores= array('' => '' )){
        if(validarsesion())
        {
        $data=$errores;
        $empleado="";
        $pagina=$pagina;
        if(strcmp($pagina,'index')==0){
            $pagina='empleados/agregar';
        }elseif ((strcmp($pagina,'edit'))==0){
            $empleado = $this->Persona_model->findbyid($id=$this->input->post('id'));
            $pagina='empleados/show';    
        }
            $session_data = validarsesion();
            $data['cuentaempleado'] = $session_data['cuentausuario'];
            $data['pnombre'] = $session_data['pnombre'];
            $data['rol'] = $session_data['rol'];
            $data['content']=$pagina;
            $data['datos']=$empleado;        
            $this->load->helper('url');   
            $this->load->view('layouts/layoutadmin', $data); //llamada a la vista general
        }else{
             redirect('login', 'refresh');
        }   
    }
  
    function agregar(){  
         if(validarsesion())
        { 
            $flujo="";
            $flujo2=3;
            $mensaje="";
            if(!($this->Persona_model->findbyid($this->input->post('id'),$this->get__tipo_user()))){
                $this->form_validation->set_rules('curp','Curp','trim|required|is_unique[personas.curp]|min_length[17]|max_length[19]|callback_curp_check');
                $this->form_validation->set_rules('name', 'Nombre', 'required|min_length[3]|max_length[255]');
                $this->form_validation->set_rules('apepat', 'Apellido Paterno', 'trim|min_length[3]|max_length[50]');
                $this->form_validation->set_rules('apemat', 'Apellido Materno', 'trim|min_length[3]|max_length[50]');
                $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[200]|callback_username_check|is_unique[personas.email]|is_unique[empleados.email]');
                $this->form_validation->set_rules('emailaval', 'Email Aval', 'valid_email|max_length[200]|callback_username_check');
                $this->form_validation->set_rules('cp', 'C.P.', 'trim|numeric|max_length[7]|callback_username_check');
                $this->form_validation->set_rules('direccion', 'Dirección', 'alpha_dash|max_length[254]');
                $this->form_validation->set_rules('fechan', 'Fecha','callback_fecha_check_date');
                $this->form_validation->set_rules('ocupacion', 'Ocupación', 'max_length[30]');
                $this->form_validation->set_rules('tel', 'Teléfono', 'max_length[12]');
                $this->form_validation->set_rules('tel2', 'Teléfono Trabajo', 'max_length[12]');
                $this->idgrupo=$this->input->post('grupo');
                if(isset($grupo)){
                    $this->form_validation->set_rules('grupo', 'Grupo','integer|required');
                    $this->form_validation->set_message('integer','Seleccione grupo');
                }
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|min_length[5]|max_length[254]');
                $this->form_validation->set_rules("cuenta", 'Nombre de usuario', 'required|trim|is_unique[usuariobiblio.cuentausuario]|is_unique[empleados.cuenta]|min_length[4]|max_length[20]|callback_username_check');
                $this->form_validation->set_message('trim','El campo %s no puede tener espacios');
                $this->form_validation->set_message('alpha_numeric','El campo %s no cumple con el formato especificado');
                $this->form_validation->set_message('is_unique', ' %s no disponible');
                $this->form_validation->set_message('fecha_check_date','La Fecha no es válida');
                $this->form_validation->set_message('callback_curp_check','La Curp no es válida');
                $this->form_validation->set_message('required','El %s es requerido');
                $this->form_validation->set_message('alpha_dash','La %s solo puede contener caracateres alfanuméricos,- y _');
                $this->form_validation->set_message('numeric','El %s es incorrecto');
                $this->form_validation->set_message('alpha','El %s solo puede contener letras');
                $this->form_validation->set_message('min_length','El campo %s es muy corto');
                $this->form_validation->set_message('max_length','Ha superado el límite permitido para el campo %s ');
                $this->form_validation->set_message('matches', 'Las constraseñas no coinciden');
                $mensaje='Usuario agregado correctamente';             
                $flujo=3;
                $flujo2=0;
           }    
            if($this->form_validation->run()==TRUE){
                $this->cargardatos();
                $this->acciones($flujo,$mensaje);
              }else{
                $this->acciones($flujo2);           
            }
        }else{
             redirect('login', 'refresh');
        }
    }    
       public function fecha_check_date($str)
    {
        if(preg_match('/^(\d\d\-\d\d\-\d\d\d\d){1,1}$/',$str)||$this->voltearfecha($str)){
            return TRUE;
          } else {
            return FALSE;
          }
    } 
     function curp_check($curp){
        if (preg_match('/^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/',$curp)){
            return true;
        }else{
            return false;
        }
    }
    private function voltearfecha($fecha){
        $time = strtotime($fecha);                    
        $fechas = date("d-m-Y", $time);
        if(preg_match('/^(\d\d\-\d\d\-\d\d\d\d){1,1}$/',$fechas)|| preg_match('/^(\d\d\/\d\d\/\d\d\d\d){1,1}$/',$fechas)){
            return true;
          } else {
            return false;
          }
    }
    private function acciones($fluj,$mensaje=''){
            switch($fluj) {
                    case 0: // Primera posibilidad
                    $this->cargar_vista('index');
                    break;
                    case 1:
                    $this->cargar_vista('edit');                    
                    break;
                    case 3: // Posiblidad si no es ninguna de las anteriores
                    $this->redirect_a($mensaje);
                    break;
                    }
    }
    function delete($cuenta){
        if($this->get_pertmitiracceso()){
            $datosusuario=$this->get_datos_prestamos_usuario($cuenta);
            if(!$datosusuario){   
                $usuario=$this->Persona_model->findcuenta($cuenta);
                if($usuario){
                    foreach ($usuario as $campos) {
                        $curp=$campos->curp;
                    }
                    $this->Persona_model->delete($curp);
                }
                $this->redirect_a('Eliminado');
            }else{
                $this->redirect_a('El usuario tiene préstamos, no puede ser eliminado');
            }
        }else{
            redirect('home', 'refresh');
        } 
    }
    private function redirect_a($mensaje=""){
        $this->session->set_flashdata('correcto', $mensaje);
        redirect(base_url().'home/usuarios', 'refresh');
    }
    public function user_existe($id,$cuenta){
        $cue=$this->input->post('cuenta');
        if(isset($cue)){
            $cuenta=$cue;
            $id=$this->input->post('curp');
        }else if(isset($cuenta)){
            $id=$id;
            $cuenta=$cuenta;
        }
        $user = $this->Persona_model->cuentaid($id,$cuenta);
        if($user){
             $this->form_validation->set_message('user_existe','EL %s no esta disponible');
            return FALSE;     
        }
        else{
            return TRUE;
        }
    }
    public function email_existe($id,$email){
        $em=$this->input->post('email');
        if(isset($em)){
            $email=$em;
            $id=$this->input->post('curp');
        }else {
            $id=$id;
            $email=$email;
        }
        $user = $this->Persona_model->emailid($id,$email);
        if($user){
             $this->form_validation->set_message('email_existe','EL %s no esta disponible');
            return FALSE;     
        }
        else{
            return TRUE;
        }
    }
    public function username_check($str)
    {
        if (strpos($str, " ")){
            $this->form_validation->set_message('username_check', 'EL %s no puede tener espacios en blanco');
            return FALSE;
        }else {
            return TRUE;
        }
    }

    private function get__tipo_user(){
        $userbi="userbi"; 
        return $userbi;
    }
     private function permitiracceso($rol){
        return $rol ?true:false;
    }
    private function get_pertmitiracceso(){
        if(validarsesion())
        { 
            return $this->permitiracceso(validarsesion()['rol']);  
        }else{
             redirect('login', 'refresh');
        }
    }
    function buscaemail(){
          if($this->get_pertmitiracceso()){
            $data = json_decode(file_get_contents("php://input"));
            $email= (isset($data->email)) ? $data->email : "";
            $resultado = $this->Persona_model->findaemail($email); 
            $resultadodos=$this->User->email($email);     
                if($resultado||$resultadodos){ 
                    $arr_data['existe']='No disponible';
                 }elseif(!$this->validacion("email",$email)){
                    $arr_data['existe']='No válido';
             }else{
                    $arr_data['existe']='Disponible';
                 }
            echo json_encode($arr_data); 
        }else{
          redirect('home', 'refresh');  
        }
    }
    function buscacuenta(){
         if($this->get_pertmitiracceso()){
            $data = json_decode(file_get_contents("php://input"));   
            $cuenta=(isset($data->cuenta))?$data->cuenta :"";
            $resultado=false;
            $resultadodos=false;
            if(is_numeric($cuenta)){
                $cuenta=intval($cuenta);
                $resultado = $this->Persona_model->findcuenta($cuenta);
            }else{
                $resultadodos = $this->User->get_pass_admin($cuenta);
            }         
                if($resultado||$resultadodos){   
                    $arr_data['existecuenta']='No disponible';
                }elseif(!$this->validacion("cuenta",$cuenta)){
                        $arr_data['existecuenta']='No válida';
                }else{
                    $arr_data['existecuenta']='Disponible';
                }
                echo json_encode($arr_data); 
         }else{
          redirect('home', 'refresh');  
        }
    }  
  
    private function validarinsertar($var){
        if(gettype($var)=="string"){
            $retorno=true;
        }elseif (is_bool($var)) {
                $retorno=$var;       
        }
        return $retorno;
    }
    private function validar_datos_persona($persona){
        $permitirinsertar=true;
        foreach ($persona as $dato){
           $permitirinsertar=$permitirinsertar&&$this->validarinsertar($dato);
        }
        return $permitirinsertar;
    }
     private function cargardatos(){
                $this->set_datos_persona($this->input->post('curp'),
                                        $this->input->post('name'),
                                        $this->input->post('apepat'),
                                        $this->input->post('apemat'),
                                        $this->input->post('email'),
                                        $this->input->post('direc'),
                                        $this->input->post('cp'),
                                        $this->input->post('fechan'),
                                        $this->input->post('ocupacion'),
                                        $this->input->post('tel'),
                                        $this->input->post('tel2'),
                                        $this->input->post('nombreaval'),
                                        $this->input->post('emailaval'),
                                        $this->input->post('telefonoaval')
                                        );
                    
                                    
                 $idgrupo=$this->input->post('grupo');
                 if(!isset($idgrupo)){
                    $idgrupo=1;
                 }   
                 $this->grupo['grupo_id']=$idgrupo;
                $this->set_datos_usuario($this->persona['curp'],
                                       $this->input->post('cuenta'),
                                        $this->input->post('password'));
                $this->insertar_actualizar();
    }
    private function email_data($id,$email){
        if(strlen($email)>0){
            $email=$this->validacion($email,"email");
            $this->arr_data['emaildisponible']=$this->email_existe($id,$email);
        }else{
            $email="";
            $this->arr_data['emaildisponible']=true;
        }
        return $email;
    }
    private function get_nombre_data($nombre){
        if(isset($nombre)){
           $nombre=$this->validacion($nombre,"nombre");
        }else{
            $nombre="";
        }
        return $nombre;
    }

    private function get_pnombre_data($nombre){
        if(isset($nombre)){
           $nombre=$this->validacion($nombre,"pnombre");
        }else{
            $nombre="";
        }
        return $nombre;
    }
    private function get_fecha_data($fecha){
        $time = strtotime($fecha);                    
        $fechas = date("d-m-Y", $time);
        $fec=$this->validacion($fechas,"fecha");
        if($fec){
            return $fecha;
        }else{
            return false;
        }
    }
    /*
        @params recibe ide del grupo o escula,nuevo valor para cambiar al usuario
                y la curp del usuario 
        returna un mensaje de exito o fracaso
    **/
    private function editar_grupo_escuela($dato,$curp,$valor){
        if(strcmp($dato,"idgrupo")==0){
            $exito=$this->agregar_a_grupo($curp,$valor);
        }else{
            $exito=$this->agregar_a_escuela($curp,$valor);
        }    
         if($exito){
            return "Actualización exitosa";
         }else{
            return "No fue posible actualizar los datos. Intente de nuevo";
         }        
    }
    private function editar_datos($modelo,$persona,$curp){
        $correcto=$modelo->update($persona,$curp);
        if($correcto){
            return "Actualización exitosa";
        }else{
            return "No fue posible actualizar los datos. Intente de nuevo";
        }
    }
       function editar(){
        $data = json_decode(file_get_contents("php://input"));
        $rol=validarsesion()['rol'];
        if($rol==3||($rol)==8){
            $arr=array();
            if(isset($data)){    
                if(strlen($data->dato)>0&&strlen($data->valor)>0){
                   if($this->validacion($data->dato,$data->valor)){
                        if(strcmp($data->dato,"idgrupo")!=0&&strcmp($data->dato,"idescuela")!=0){
                            if($rol==3){        
                                $modelo=new Persona_model;
                            }else if($rol==8){
                                $modelo=new Empleado_model;
                            }
                            $persona[$data->dato]=$data->valor;
                            $arr['mensaje']=$this->editar_datos($modelo,$persona,$data->idus);
                        }else{
                            $arr['mensaje']=$this->editar_grupo_escuela($data->dato,$data->idus,$data->valor);
                        }
                   }else{
                     $arr['mensaje']="Formato no válido"; 
                   }
                }else{
                    $arr['mensaje']="Faltan Campos";   
                }
               echo json_encode($arr);
            }  
        }

    }
    private function comparare_to($dato,$comparar){
        return strcmp($dato,$comparar)==0;
    }
    private function relizar_pmatch($match,$dato){
        return preg_match($match,$dato);
    }
    private function validacion($tipo,$dato){
        $arr_dato_match= array('email'=>'/^[^0-9][a-zA-Z0-9\_.-]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',
                                    'nombre'=>'/^[a-zñáéíóú\s]{4,50}$/i',
                                    'apellido'=>'/^[a-zñáéíóú\s]{0,50}$/i',
                                    'cuenta'=>'/^[a-z\d_.-]{4,20}$/i',
                                    'ocudire'=>'/^[a-zñÑáéíóú\d_\/\-\s]{0,255}$/i',
                                    'cp'=>'/^[\d\s]{0,10}$/i',
                                    'fechaNaci'=>'/^(\d\d\d\d-\d\d\-\d\d){1,1}$/',
                                    'pass'=>'/^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ_.*]{4,255}$/',
                                    'ids'=>'/^(\d){1,10}$/',
                                    'naval'=>'/^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ\s]{4,255}$/',
                                    'telefono'=>'/^[0-9\s]{4,25}$/');
      
        //validamos los diversos campos de empleado
        $valido=0;
        if($this->comparare_to($tipo,"email")||$this->comparare_to($tipo, "emailaval")){
            $llave="email";
        }else if($this->comparare_to($tipo,"pnombre")||$this->comparare_to($tipo,"nombre")){
            $llave='nombre';
        }else if($this->comparare_to($tipo,"apPat")||$this->comparare_to($tipo,"apMat")){
            $llave='apellido';
        }else if($this->comparare_to($tipo,"cuenta")){
            $llave='cuenta';
        }else if($this->comparare_to($tipo, "direccion")||$this->comparare_to($tipo, "ocupacion")){
            $llave='ocudire';
        }elseif ($this->comparare_to($tipo, "cp")) {
            $llave='cp';
        }elseif($this->comparare_to($tipo,"fechaNaci")) {
            $llave='fechaNaci';
        }elseif($this->comparare_to($tipo,"pass")){
            $llave='pass';
        }elseif($this->comparare_to($tipo,"idgrupo")||$this->comparare_to($tipo,"idescuela")){
            $llave='ids';
        }elseif($this->comparare_to($tipo,"nombreaval")){
            $llave='naval';
        }elseif($this->comparare_to($tipo,"telefono")){
            $llave="telefono";
        }
        $valido=preg_match($arr_dato_match[$llave],$dato);
        return ($valido==1) ? $dato : false;           
    }
    private function set_datos_persona($curp,$pnombre,$apPat,$apMat,$email,$direccion,$cp,$fechaNaci,$ocupacion,$telefono,$tel2,$nombreaval,$emailaval,$telefonoaval){
        $this->persona['curp']=$curp;
        $this->persona['pnombre']=$pnombre;
        $this->persona['apPat']=$apPat;
        $this->persona['apMat']=$apMat;
        $this->persona['email'] =$email;
        $this->persona['direccion']=$direccion;
        $this->persona['cp']=$cp;
        $this->persona['fechaNaci']=$fechaNaci;
        $this->persona['ocupacion']=$ocupacion;
        $this->persona['telefono']=$telefono;
        $this->persona['tel2']=$tel2;
        $this->persona['nombreaval']=$nombreaval;
        $this->persona['emailaval']=$emailaval;
        $this->persona['telefonoaval']=$telefonoaval;
    }
    private function set_datos_usuario($persona_id,$cuentausuario,$password_d){
        $this->usuario['pcurp']=$persona_id;
        $this->usuario['cuentausuario']=$cuentausuario;
        $this->usuario['password_d']=$password_d;
    }
    private function get_persona(){
        return $this->persona;
    }
    private function get_usuario(){
        return $this->usuario;
    }
    private function insertar_actualizar(){
            $idescuela=$this->input->post('escuela');
            $ides="";
                    if(isset($idescuela)){
                        $ides=$idescuela;
                    }elseif(isset($this->idesc)){
                        $ides=1;
                    }
            $usuario=$this->get_usuario();
            $this->grupo['user_id']=$usuario['pcurp'];
            $escuela['user_id']=$usuario['pcurp'];
            $escuela['escuela_id']=$ides;
            $insertpersona = $this->Persona_model->crearedit($this->get_persona(),
                                                            $usuario,
                                                            $this->grupo,
                                                            $escuela);
    }
    private function agregar_a_grupo($curp,$idgrupo){
        $this->load->model('Grupo_model','',TRUE);
        return $this->Grupo_model->agregar_a_grupo($curp,$idgrupo);
    }
    private function agregar_a_escuela($persona_id,$idescuela){
        $this->load->model('Escuela_model','',TRUE);
        return $this->Escuela_model->agregar_a_escuela($persona_id,$idescuela);
    }
    function desbloquear($cuenta){
        if(validarsesion()){
            $rol_sesion =validarsesion()['rol'];  
            if($rol_sesion==1){
                $is_emppleado=$this->User->is_empleado($cuenta);
                if($is_emppleado){
                    $this->desbloquar_user($cuenta);
                }else{
                    $this->redirect_a("No tienes permisos para desbloquear a este usuario");
                }
            }elseif($rol_sesion==3){
                $is_userbiblio=$this->User->is_userbiblio($cuenta);
                if($is_userbiblio){
                    $this->desbloquar_user($cuenta);
                }else{
                    $this->redirect_a("No tienes permisos para desbloquear a este usuario");
                }
            }else{
                 redirect('home','refresh');
            }
        }else{
           redirect(base_url());
        }
    }
    function bloquear(){
        if(validarsesion()){
            $data = json_decode(file_get_contents("php://input"));
            $arr=array();
            if(isset($data)&&isset($data->cuenta)){
                $observ="";
                $cuenta=$data->cuenta;
                if(isset($data->obser)){
                    $observ= $data->obser;
                }
                $rol_sesion =validarsesion()['rol']; 
                if($rol_sesion==1){    
                    $is_emppleado=$this->User->is_empleado($cuenta,$tabla);
                    if($is_emppleado){
                        $this->bloquear_user($cuenta);
                    }else{
                        $this->redirect_a("No tienes permisos para bloquear a este usuario");
                    }
                }elseif($rol_sesion==3){
                    $is_userbiblio=$this->User->is_userbiblio($cuenta);
                    if($is_userbiblio){
                           $arr['mensaje']=$this->bloquear_user($cuenta,$observ);
                           $arr['exito']=true;
                       echo json_encode($arr);
                    }else{
                        $this->redirect_a("No tienes permisos para bloquear a este usuario");
                    }
                }else{
                     redirect('home','refresh');
                }
            }else{
                redirect('home','refresh');
            }
        }else{
           redirect(base_url());
        }
    }
    private function bloquear_user($cuenta,$obs){
        $bloqueado=$this->User->bloquearuser($cuenta,$obs);
            if($bloqueado){
               //$this->redirect_a("El usuario ".$cuenta." ha sido bloqueado");
               return "El usuario ".$cuenta." ha sido bloqueado";
            }else{
                $this->redirect_a("No ha sido posible bloquear al usuario " .$cuenta. ", intente de nuevo, si el problema continúa consulte al adminsitrador del sistema");
               //return "El usuario ".$cuenta." ha sido bloqueado";
            }
    }
    private function desbloquar_user($cuenta){
        $desbloqueado=$this->User->desbloquearuser($cuenta);
            if($desbloqueado){
               $this->redirect_a("El usuario ".$cuenta." ha sido desbloqueado");
            }else{
                $this->redirect_a("No ha sido posible desbloquear al usuario " .$cuenta. ", intente de nuevo, si el problema continúa consulte al adminsitrador del sistema");
            }
    }
    public function get_monto_multa(){
        $cuenta = $this->input->post('cuenta');
        $datos=$this->User->total_multa_saldo($cuenta);
        $existe= $datos ? true : false;
        $arr_data['datos']=$datos;
        $arr_data['existe']=$existe;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($arr_data);
    }
    /*
   recibe datos via post (von angular) vista estadistica/visitaregistro
   retorna arreglo en formato json
 */
  function agregarvisita()
    {
    $data = json_decode(file_get_contents("php://input"));
     if(validarsesion()||isset($data->user)){
        $rol =validarsesion()['rol'];  
        if($rol==6||isset($data->rol)){
            if(isset($data)){
              $arr_data=array();
              $this->load->model('Registro_model','',TRUE);
              $edad=$data->edad;
              if(strcmp($edad,'hd')==0||strcmp($edad,'et')==0||strcmp($edad,'md')==0){
                $datos=$this->Registro_model->agregarregistro($edad);
                $arr_data['mensaje']=($datos) ? "Se agregó correctamente ":"Ocurrió un problema al agregar el registro. Intente de nuevo";
              }else{
                $arr_data['mensaje']='Valor inválido';  
              } 
            }
        }
       }
        echo json_encode($arr_data);   
    }

}
?>