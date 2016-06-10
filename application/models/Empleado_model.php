<?php
Class Empleado_model extends CI_Model
{
  public function construct() {
        parent::__construct();
    }
    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    private function get_rol($area){
      if(strcmp($area,"analistas")==0){
        $rol=2;
      }else if(strcmp($area,"difusores")==0){
        $rol=4;
      }else if(strcmp($area,"prestador")==0){
        $rol=3;
      }
      else if(strcmp($area,"encargadosala")==0){
        $rol=5;
      }else if(strcmp($area,"recepcion")==0){
        $rol=6;
      }
      return $rol;
    }
    private function realizar_query($query){
      return ($query->num_rows() > 0) ?  $query->result() :false;
    }
    function finbyemail($email){
      $this->db->select("*");
      $this->db->from("empleados");
      $this->db->where("email",$email);
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
    function existetoken($token){
      $query=$this->db->query("select *,date_add(creado,interval 30 minute) as caducidad from users where token='".$token."'");
      return $this->realizar_query($query); 
    }
    function existeidusuario($idusuario){
      $this->db->select("*");
      $this->db->from("users");
      $this->db->where("idusuario",$idusuario);
      $query = $this->db->get();
      return $this->realizar_query($query);  
    }
    function deleteuser($curp){
      $this->db->where('idusuario',$curp); 
      return $this->db->delete("users"); 
    }
    function inserttemp($iduser,$curp,$cuenta,$token){
        $data['iduser']=$iduser;
        $data['idusuario']=$curp;
        $data['username']=$cuenta;
        $data['token']=$token;
        $data['creado']=date("Y-m-d H:i:s");
        return $this->db->insert('users', $data);
    }
    function deleteem($tabla,$id){
       $this->db->where('curp',$id); 
       return $this->db->delete("empleados"); 
    }
     function show($inicio=0,$contar,$arwhere)
    {
      $rol=$this->get_rol($arwhere);
      if(is_int($inicio)){
      $query=$this->db->query("select curp as id, cuenta,nombre FROM empleados where rol=".$rol."
          limit ".$inicio.",10");
      if(strcmp($contar, "contar")==0){
        return $query->num_rows(); 
      }else{
          return $this->realizar_query($query);
        }
      } 
    }
    function editpass($usuario,$tabla,$id){
      $this->db->where('curp',$id);
      return $this->db->update("empleados",$usuario);
    }
     public function get_pass($tabla,$id){
        $this->db->select('password_d');
        $this->db->from("empleados");
        $this->db->where('curp',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){
          foreach ($query->result() as $row) {
           return $row->password_d;    
          }
        }else{
           return false;
        }
    }
    function agregar($usuario){
      return $this->db->insert("empleados", $usuario);
    }
    function findempleado($tabla,$id){
      $this->db->select("*");
      $this->db->from("empleados");
      $this->db->where("curp",$id);
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
    function findempleado_by_cuenta($cuenta,$id){
      $this->db->select("*");
      $this->db->from("empleados");
      $this->db->where("cuenta",$cuenta);
      $this->db->where("curp",$id);
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
    function update($datos,$id){
        $this->db->where('curp',$id);
        return $this->db->update("empleados", $datos); 
    }
    
}

