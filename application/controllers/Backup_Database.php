<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * technically replacement for Mysql backup from native CI which only can use Mysql
 * @property CI_DB_active_record $db
 */
class Backup_Database extends MY_Controller {
    private $ruta ="./dbr/biblioteca.sql"; 
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation'); 
        $this->load->model('Backuo_model','',TRUE);
    }
    /*
        Respalda la base de datos, almacenandola en la ruta especificada. 
    */
    function restore(){
        if(validarsesion()){
            if(validarsesion()['rol']==8){
                if(file_exists($this->ruta)){
                    unlink($this->ruta);
                }
                $realizado=$this->iniciar_backup();
                $data['mensaje']=$realizado?"Se completo satisfactoriamente, su respaldo":"No fue posible realizar la copia de seguridad,intente de nuevo";
                //$this->session->set_flashdata('correcto',$mensaje);
                echo json_encode($data);
                //redirect(base_url().'home', 'refresh');
            }else{
                redirect('home');
            }
        }else{
            redirect('');
        }
    }
    /*
        Descarga la base de datos almacenada en la ruta especificada, necesario iniciar sesion
    */
    function descargar_database(){
        if(validarsesion()){
            if(validarsesion()['rol']==8){ 
                header ("Content-Disposition: attachment; filename=biblioteca.sql"); 
                header ("Content-Type: application/octet-stream");
                header ("Content-Length: ".filesize($this->ruta));
                readfile($this->ruta);
            }else{
                redirect('home');
            }
        }else{
            redirect('');
        }
    }
    //Proceso de respaldo 
    private function iniciar_backup(){
        $backup=new Backuo_model;
        $reult=$backup->backup();
        $this->load->helper('file');
        $realizado=!write_file(getcwd().$this->ruta,$reult)?false:true;
        return $realizado;
    }
    function editkey(){
        $this->form_validation->set_rules('usuario', 'Usuario', 'required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('pass', 'Password', 'trim|required|matches[passconf]');
        $this->form_validation->set_message('matches', 'Las contraseñas no coinciden');
        $this->form_validation->set_message('min_length','El campo %s es muy corto');
        $this->form_validation->set_message('max_length','Ha superado el límite permitido para el campo %s ');
        $this->form_validation->set_message('required','El %s es requerido'); 
        ($this->form_validation->run()==TRUE)?$this->editar():redirect('home/ajustes/respaldo','refresh');   
    }
    //Cambia llaves de acceso a recuperacion de DB
    private function editar(){
        $exito=false;
        $usuario= $this->input->post('usuario');
        $pass= $this->input->post('pass');
        $fp = fopen(getcwd()."/csv/datos.txt", "w");
            if(fputs($fp,sha1(md5($usuario)).",".sha1(md5($pass)))){
                $exito=true;
            }
        fclose($fp);
        $this->session->set_flashdata('correcto', 'Cambios realizados correctamente');
        redirect('home');
    }
}