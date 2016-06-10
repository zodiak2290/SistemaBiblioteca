<?php
Class Escuela_model extends CI_Model
{
  public function construct() {
      parent::__construct();
  }
  private function realizar_query($query){
    if($query->num_rows() > 0){
       return $query->result();
     }else{
       return false;
     }
  }
  function show($inicio=0,$contar=""){
      $this->db->select('*');
      $this->db->from('escuelas');
      $this->db->where('idescuela!=82');
      $this->db->order_by('nombre');
      if(strcmp($contar, "contar")==0){
          $query = $this->db->get();
          return $query->num_rows(); 
      }else{
        $this->db->limit(10,$inicio);
        $query = $this->db->get();
        return $this->realizar_query($query);
      }
    }
    function eliminar($id){
      $this->db->where('idescuela',$id);
      return $this->db->delete('escuelas');
    }
    function editar($escuela,$id){
      $this->db->where('idescuela',$id);
      return $this->db->update('escuelas',$escuela);
    }
    function agregar($nombre){
      $existe=$this->findescuela($nombre);
      if(!$existe){
        $escuela['nombre']=$nombre;
        return $this->db->insert('escuelas',$escuela);  
      }else{
        return "Escuela existe";
      }
    }
    function findescuela($nombre){
      $this->db->select('*');
      $this->db->from('escuelas');
      $this->db->where('nombre',$nombre);
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
    function agregar_a_escuela($persona_id,$idescuela){
      $idescuelauser=$this->find_persona_en_escuela($persona_id);
      $escuela['escuela_id']=$idescuela;
      $escuela['user_id']= $persona_id;
      if(!$idescuelauser){
        return $this->db->insert('escuelauser',$escuela);
      }else{
        $this->db->where('idescuelauser',$idescuelauser);
        return $this->db->update('escuelauser',$escuela);
      }
    }
    private function find_persona_en_escuela($persona_id){
      $this->db->select('idescuelauser');
      $this->db->from('escuelauser');
      $this->db->where('user_id',$persona_id);
      $query = $this->db->get();
      if($query->num_rows()>0){
          foreach ($query->result() as $row) {
              return $row->idescuelauser;
          }  
      }else{
         return false;
      }
    }
  }
 

