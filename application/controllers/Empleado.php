<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Empleado extends MY_Controller {
var $usuario= array('nombre' =>'' ,
                        'cuenta'=>'',
                        'password_d'=>'',
                        'rol'=>'',
                        'curp'=>'');
var $tabla="";    
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation'); 
        $this->load->model('Empleado_model','',TRUE);
    }
    function delete($tabla,$id){
        if(validarsesion())
        {
            if(validarsesion()['rol']==8)
            {
                $isdelete=$this->Empleado_model->deleteem($tabla,$id);
                $mensaje=$isdelete?'Usuario eliminado':"Error al intentar eliminar";
                $this->redirect_a($mensaje,$tabla);
            }else{
                redirect('home','refresh');    
            }
        }else{
            redirect('home','refresh');
        }
    }
      public function username_check($str)
    {
        if (strpos($str, " ")){
             $this->form_validation->set_message('username_check', 'EL %s no puede tener espacios en blanco');
            return FALSE;
          } else {
            return TRUE;
        }
    }
 
    function agregar(){
        if(validarsesion())
        { 
            if(validarsesion()['rol']==8)
            { 
                $rol=$this->input->post('rol');
                $this->tabla=$this->get_tabla($rol);
                $this->form_validation->set_rules('curp','Curp','trim|required|is_unique[empleados.curp]|min_length[17]|max_length[19]|callback_curp_check');
                $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[4]|max_length[255]');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]');
                $this->form_validation->set_message('matches', 'Las contraseñas no coinciden');
                $this->form_validation->set_rules("cuenta", 'Nombre de usuario', 'required|trim|is_unique[empleados.cuenta]|min_length[4]|max_length[20]|callback_username_check|callback_cuenta_validar');
                $this->form_validation->set_rules("email", 'Email', 'required|trim|is_unique[personas.email]|is_unique[empleados.email]|min_length[4]|max_length[254]|callback_username_check');
                $this->form_validation->set_message('min_length','El campo %s es muy corto');
                $this->form_validation->set_message('max_length','Ha superado el límite permitido para el campo %s ');
                $this->form_validation->set_message('required','El %s es requerido'); 
                $this->form_validation->set_message('alpha','Nombre de usuario %s ');       
                $this->form_validation->set_message('is_unique', ' %s no disponible');
                ($this->form_validation->run()==TRUE)?$this->cargardatos():$this->cargar_vista();           
             }   
        }else{
             redirect('login', 'refresh');
        }
    }
    function curp_check($curp){
        return (preg_match('/^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/',$curp)) ?true:false ;
    }
    private function cargardatos(){
            $this->set_datos_usuario($this->input->post('nombre'),
                                        $this->input->post('cuenta'),
                                        $this->input->post('password'),
                                        $this->input->post('rol'),
                                        $this->input->post('curp'),
                                        $this->input->post('email'));
            $this->insertar();
    }
     private function set_datos_usuario($nombre,$cuenta,$password_d,$rol,$curp,$email){
        $this->usuario['nombre']=$nombre;
        $this->usuario['cuenta']=$cuenta;
        $this->usuario['password_d']=md5($password_d);
        $this->usuario['rol']=$rol;
        $this->usuario['curp']=$curp;
        $this->usuario['email']=$email;
    }
    private function insertar(){
        $user=$this->Empleado_model->agregar($this->get_usuario());
        $mensaje=$user ? 'Usuario agregado correctamente':"Error al insertar los datos";
        $this->redirect_a($mensaje,$this->tabla);
    }
    private function redirect_a($mensaje="",$tabla){
        $this->session->set_flashdata('correcto', $mensaje);
        redirect(base_url().'home/empleados/'.$tabla, 'refresh');
    }
    private function get_usuario(){
        return $this->usuario;
    }
     private function cargar_vista($pagina="",$errores= array('' => '' )){
        if(validarsesion())
        {
            $data=$errores;
            $pagina='empleados/agregarempl';
            $data['cuentaempleado']=validarsesion()['cuentausuario'];
            $data['pnombre'] = validarsesion()['pnombre'];
            $data['rol'] = validarsesion()['rol'];
            $data['idpersona'] = validarsesion()['idpersona'];
            $data['content']=$pagina;
            $this->load->helper('url');   
            $this->load->view('layouts/layoutadmin', $data); //llamada a la vista general
        }else{
             redirect('login', 'refresh');
        }   
    }
    private function get_tabla($rol){
    $tabla="analistas";
    $roles=array(2 => 'analistas',3=>'prestador',4=>'difusores',5=>'encargadosala',
                6=>'recepcion');
        return $roles[$rol];
    }
    function editar(){
        if(validarsesion()['rol']==1||validarsesion()['rol']==8){
            $data = json_decode(file_get_contents("php://input"));
            $arr=array();
            if(isset($data)){    
                if(strlen($data->dato)>0&&(strlen($data->valor)>0)||($data->valor ? 'true':'false')){
                   if($this->validar_campos($data->dato,$data->valor)){
                        $this->load->model('Biblioteca_model','',TRUE);
                        $bibli[$data->dato]=$data->valor;
                        $correcto=$this->Biblioteca_model->update($bibli);
                        $arr['mensaje']=($correcto)?"Actualización Exitosa":"No fue posible actualizar los datos. Intente de nuevo";
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
    public function editpass($tabla,$id){
        if(validarsesion()['rol']==1){
            $arr=array();
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data)&&isset($data->nueva)&&isset($data->actual)&&isset($data->confi)){    
                if(strlen($data->nueva)>0&&strlen($data->actual)>0&&strlen($data->confi)>0){
                   if(strcmp(md5($data->actual),$this->get_pass($tabla,$id))==0&&strcmp($data->confi,$data->nueva)==0){
                        if($this->contrasean($data->nueva)){
                            $usuario['password_d']=md5($data->nueva);
                            $editado=$this->Empleado_model->editpass($usuario,$tabla,$id);
                            $arr['mensaje']=($editado)?"Actualización exitosa":"No se pude actualizar la contraseña. Intente de nuevo";
                        }else{
                           $arr['mensaje']="Contraseña con caracteres alfanúmericos y - _ . *";  
                        }
                   }else{
                    $arr['mensaje']="Falta contraseña"; 
                   }
                }else{
                    $arr['mensaje']="Falta contraseña de confirmación";   
                }
            }
            echo json_encode($arr);    
        }
    }
    private function contrasean($pass){
        $valido=preg_match('/^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ_.*]{4,255}$/', $pass);
        return $valido ? $pass:false;
    }
    private function get_pass($tabla,$id){
        return $this->Empleado_model->get_pass($tabla,$id);
    }
    private function validar_campos($dato,$valor){
        if(strcmp($dato,'email')==0){
            return preg_match('/^[^0-9][a-zA-Z0-9\_.-]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $valor);
        }else if(strcmp($dato,'password')==0){
             return preg_match('/^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ_.*]{4,255}$/', $valor);
        }else if(strcmp($dato,"permitireservas")==0||strcmp($dato,"permitirbusqpri")==0
                ||strcmp($dato,"busquedaspu")==0||strcmp($dato,"accesousuarios")==0
                ||strcmp($dato,"prestamos")==0||strcmp($dato,"acccesoempl")==0){
            return ($valor==1||$valor==0) ? true :false; 
        }else{
            return preg_match('/^[a-zñÑáéíóú\d_\/\-\s]{0,255}$/i', $valor);
        }
    }

    ///en proceso
    public function editaremp($tabla,$id){
        if(validarsesion()['rol']==1){
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data)){ 
                $arr=array();   
                if(strlen($data->dato)>0&&strlen($data->valor)>0){
                  if($this->validar_campos($data->dato,$data->valor)){
                     $emailexiste=false;$emailpersonexiste=false;
                        if(strcmp($data->dato,'email')==0){
                            $this->load->model('Persona_model','',TRUE);
                            $emailexiste=$this->email_existe($data->valor);
                            $emailpersonexiste=$this->Persona_model->findaemail($data->valor);
                        }
                        if(!$emailexiste&&!$emailpersonexiste){
                            $this->load->model('Empleado_model','',TRUE);
                            $empleado[$data->dato]=$data->valor;
                            $correcto=$this->Empleado_model->updateem($empleado,$id,$tabla);
                            $arr['mensaje']=$correcto ?"Actualización Exitosa":"No fue posible actualizar los datos. Intente de nuevo";
                        }else{
                            $arr['mensaje']="Email no disponible";       
                        }
                   }else{
                     $arr['mensaje']="Formato no válido"; 
                   }
                }else{
                    $arr['mensaje']="Falta Nombre";   
                }
                echo json_encode($arr);
            }    
        }
    }
    public function email_existe($email){
        $this->load->model('User','',TRUE);
        $user = $this->User->email($email);
        return $user;
    }
    public function cuenta_validar($cuenta){
        $this->load->model('User','',TRUE);
        $user=$this->User->get_pass($cuenta);
        return $user?true:false;
    }
}
?>