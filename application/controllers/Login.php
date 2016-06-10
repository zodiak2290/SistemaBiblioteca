<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
		 function __construct()
		 {
		   parent::__construct();
		 }		 
		 function index()
		 {
		    $this->load->helper(array('form'));
		    $this->load->helper('url');	
	 		$datos['content']='../login/login_view';
            $datos['token']= $this->token();
     		$this->load->view('layouts/layoutprincipal', $datos);
		 }
    public function token()
    {
        $token = md5(uniqid(rand(),true));
        $this->session->set_userdata('token',$token);
        return $token;
    }

	}


?>