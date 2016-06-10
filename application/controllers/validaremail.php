<?php
class validaremail extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation'); 
		$this->load->model('Empleado_model','',TRUE);
		$this->load->model('Persona_model','',TRUE);
	}
	/*
		funcion index para restrablecer contraseña
		recibe via post el correo del cual se va a restablecer
		busca si existe un empleado o usuario con el email introducido
		redirige con un mensaje de lo sucedido con su solcitud
	*/
	function index(){
		if(isset($_POST['email'])){	
			$email = $_POST['email'];
	 	}
		if(isset($email)){
			$curp="";$cuenta="";	
			$usuario = $this->Empleado_model->finbyemail($email);
			$usuarib=$this->Persona_model->findaemail($email);
			   if($usuario){
				      foreach ($usuario as $row){
				      	$curp=$row->curp;
				      	$cuenta=$row->cuenta;
				      }      
					$mensaje=$this->usuariempleado($usuario,$email,$curp,$cuenta);
			   }else if($usuarib){
			   		foreach ($usuarib as $row){
				      	$curp=$row->curp;
				      	$cuenta=$row->cuentausuario;
				      }      
					$mensaje=$this->usuariempleado($usuario,$email,$curp,$cuenta);
			   }else{
	      			$mensaje="No existe una cuenta asociada a ese correo";
	      		}
		}else{  		
	    	$mensaje ="Debes introducir el email de la cuenta";
		}
		$this->session->set_flashdata('correcto',$mensaje);
		redirect(base_url().'restablecer', 'refresh');
	}
	/*
		params usuario registrado en el sitema, email de usuario ,curp de suario  y cuenta
		si el usuario ya habia realizado este proceso se eliminan de la tabla user los datos generados
		generamos un link que sera valido temporalmente con su curp y cuenta
		y enviamos el mail con las instrucciones para restablcer la contraseña
	*/
	private function usuariempleado($usuario,$email,$curp,$cuenta){
		$existe=$this->Empleado_model->existeidusuario($curp);
		if($existe){
			$this->Empleado_model->deleteuser($curp);
		}
		$linkTemporal = $this->generarLinkTemporal($curp,$cuenta);
		$mensajeaenviar ='<body>
	       <p>Hemos recibido una petición para restablecer la contraseña de tu cuenta.</p>
	       <p>Si hiciste esta petición, haz clic en el siguiente enlace. Si no hiciste esta petición puedes ignorar este correo.</p>
	       <p>
	         <strong>Enlace para restablecer tu contraseña. Este enlace caducará en 30 minutos</strong><br>
	         <a href="'.$linkTemporal.'"> Restablecer contraseña </a>
	       </p>
	     </body>';
		if($linkTemporal){
			try {
				$datos = array('email' =>$email,
								 'mensaje'=>$mensajeaenviar ,'asunto'=>"Recuperar contraseña",
								 'nombre'=>$cuenta,'altBody'=>"Ingrese al link para restablcer su contraseña");			
				return ($this->enviarEmail($datos)) ? "Un correo ha sido enviado a su cuenta de email con las instrucciones para restablecer la contraseña":"Ocurrió un problema al tratar de enviar el correo a su cuenta. Verifique su conexión a Internet";
			} catch (Exception $e) {
				return 'Excepción capturada: '.$e->getMessage()."\n";	
			}
		}
	}
	/* params curp y cuenta
		returna el link para restablecer cotnraseña
	*/
	function generarLinkTemporal($idusuario, $username){
		// Se genera una cadena para validar el cambio de contraseña
		$cadena = $idusuario.$username.rand(1,9999999).date('Y-m-d');
		$token = sha1($cadena);
		// Se inserta el registro en la tabla tblreseteopass
		$idus=date("YmdHis");
		$resultado = $this->Empleado_model->inserttemp($idus,$idusuario,$username,$token);   
		// Se devuelve el link que se enviara al usuario 
		return $resultado ? base_url().'pass/restablecer/idusuario/'.sha1($idusuario).'/'.$token:false;		
	}
	var $datosbiblio=array('email'=>'',
						'password'=>''
	);
	/* 
		inicializa los datos correo y contraseña de la biblioteca
	*/
private function correocontrasea(){
	$this->load->model('Biblioteca_model','',TRUE);
	$datos=$this->Biblioteca_model->getBiblio();
	foreach ($datos as $row) {
		$this->datosbiblio['email']=$row->email;
		$this->datosbiblio['password']=$row->password;
	}
} 
/* 
	datos de la direccion de la bibliteca
*/
function correoadmin(){
	$this->load->model('User','',TRUE);
	$datos=$this->User->get_email_admin();
	if ($datos) {
		$this->datosadmin['email']=$datos;
	}
} 
/**envia un correo 
params extraemos los parametros con extract
email de quien recibira el correo
mensaje para el destinatario
aunto del correo
nombre de quien recibira el correo
*/
function enviarEmail($datos){
	extract($datos);
	$this->correocontrasea();
	$this->load->library('my_phpmailer');
	$mail = new PHPMailer();
	$mail->IsSMTP();
	try {
		$mail->CharSet = 'UTF-8';
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;                  // autentificacion
		$mail->SMTPSecure = "tls";                 // segridad
		$mail->Host       = "smtp.gmail.com";      // servidor a usar
		$mail->Port       = 587;                   // puerto
		$mail->Username   = $this->datosbiblio['email'];  // email de usuario
		$mail->Password   = $this->datosbiblio['password'];  //pass de usuario
		$mail->From       = $this->datosbiblio['email'];
		$mail->FromName   = 'Biblioteca Pública';
		$mail->Subject    = $asunto;
		$mail->AltBody    = $altBody; //Texto Body
		$mail->WordWrap   = 50;
		$mail->MsgHTML($mensaje);
		$mail->AddReplyTo($this->datosbiblio['email'],"Dirección");
		$mail->AddAddress($email,$nombre);
		$mail->IsHTML(true); // usar formato HTML
		return (!$mail->Send()) ? false :true;
	}catch (phpmailerException $e) {
  		return $e->errorMessage(); //en caso de error
	} catch (Exception $e) {
  		return $e->getMessage(); //
	}	
}
/* 	
	@params curp y token
	link al que se ingresa para restablecer contraseña
	si existe y aun es valido carga la vista para cambiar contraseña
*/
	function restablecer($idusuario,$token){
		$resultado=$this->Empleado_model->existetoken($token); 
		if($resultado){
			$usuarioid="";$caducidad="";$idus="";
			foreach ($resultado as $row) {
				$idus=$row->idusuario;
				$usuarioid=sha1($row->idusuario);
				$caducidad=$row->caducidad;
			}
			$ahora=date("Y-m-d H:i:s");
			if(strcmp($idusuario,$usuarioid)==0){
				if(strtotime($caducidad)>strtotime($ahora)){
					$datos['token']=$token;
					$datos['usuario']=$usuarioid;
     				$datos['content']='cambiarpass';
					$this->load->view('layouts/layoutprincipal', $datos);
				}else{
					$this->Empleado_model->deleteuser($idus);
					redirect(base_url(), 'refresh');
				}
			}else{
				redirect(base_url(), 'refresh');
			}
		}else{
			redirect(base_url(), 'refresh');
		}
	}
	/*
		valida que las contraseñas sen iguales
		y que se reciba el token y id de usuario
		si existe el token en la tabla users
		edita la contraseña y elimina el usuario de la tabla user 
	*/
	function cambiarpass(){
                $this->form_validation->set_rules('password1', 'Password Confirmacion', 'required');
                $this->form_validation->set_rules('password2', 'Password', 'trim|required|matches[password1]|min_length[5]|max_length[254]');
                $this->form_validation->set_message('matches', 'Las contraseñas no coinciden');
                if($this->form_validation->run()==TRUE){
                   if(isset($_POST['token'])&&isset($_POST['idusuario'])){	
						$token = $_POST['token'];
						$usuario = $_POST['idusuario'];
						$pass = $_POST['password1'];
						$resultado=$this->Empleado_model->existetoken($token);
						if($resultado){
							$username="";$curp="";
							foreach ($resultado as $row) {
								$username=$row->username;
								$curp=$row->idusuario;
							}
							$usuario=array();
							$usuario['password_d']=md5($pass);
							//buscamos is es empleado o usuario
							$this->Empleado_model->deleteuser($curp);
							if($this->Empleado_model->findempleado_by_cuenta($username,$curp)){
								if($this->Empleado_model->editpass($usuario,"empleado",$curp)){
									$mensaje="Tu contraseña ha sido cambiada";		
								}	
							}else if($this->Persona_model->findusuario_by_cuenta($username,$curp)){
								if($this->Persona_model->editpass($usuario,$curp)){
									$mensaje="Tu contraseña ha sido cambiada";
								}//auqi podira enviarse un mensaje de contraseña cambiada
							}
							$this->session->set_flashdata('correcto',$mensaje);		
							redirect(base_url().'login', 'refresh');
						}
				 	}
                }else{
                    $this->session->set_flashdata('correcto',"Las contraseñas no coinciden");
					redirect(base_url().'cambiarpass', 'refresh');          
                }
        }
        //funcion enviar para mail de contacto
        /*
        	validamos que los datos recibidos sean correctos
        	obtenemos los datos del director correoadmin para enviar email
        	creamos el mensaje a enviar
        	redireccionamos con un mensaje para informar lo sucedido con el correo
        */
        function enviar(){
			$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[3]|max_length[255]');
			$this->form_validation->set_rules('mensaje', 'Mensaje', 'max_length[500]'); 
			$this->form_validation->set_rules('asunto', 'Asunto', 'max_length[20]');  
			$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[600]');
			$this->form_validation->set_message('required','El %s es requerido'); 
			$this->form_validation->set_message('valid_email','La dirección de correo proporcionada no es válida');      
			$this->form_validation->set_message('alpha_numeric','La %s solo puede contener caracateres alfanuméricos');
			$this->form_validation->set_message('min_length','El campo %s es muy corto');
			$this->form_validation->set_message('max_length','Ha superado el límite de 600 permitido para el campo %s ');
			if($this->form_validation->run()==TRUE){
				$nombre=$this->input->post("nombre");
				$asunto=$this->input->post("asunto");
				$mensajerecibido=$this->input->post("mensaje");
				$email=$this->input->post("email");
				$this->correoadmin();
				$mensajeaenviar="<body style='margin: 10px;'>
									<div style='width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;'>
									<br>
									&nbsp;Email recibido de contacto.<br>
									<br>".$mensajerecibido.".<br>
									<br>
									Enviado por:".$nombre."(".$email.")"."<br />
									</div>
								</body>";
				$datos = array('email' =>$this->datosadmin['email'],
								 'mensaje'=>$mensajeaenviar ,'asunto'=>$asunto,
								 'nombre'=>"Dirección",'altBody'=>"Email de contacto");
				$mensaje=($this->enviarEmail($datos))?"Su mensaje ha sido enviado":"No fue posible enviar su correo. Intente de nuevo";				 			
				$this->session->set_flashdata('correcto',$mensaje);
        		redirect(base_url().'contacto', 'refresh');
			}else{
				$data['content'] = 'contacto';
        		$this->load->view('layouts/layoutprincipal',$data);
			}
 	}	
}