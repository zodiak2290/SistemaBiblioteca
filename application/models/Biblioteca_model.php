<?php 
Class biblioteca_model extends CI_Model
{
  public function construct() {
       parent::__construct();
  }
  private function realizar_query($query){
    return ($query->num_rows() > 0) ? $query->result():false;
  }
  function getBiblio(){
      $query=$this->db->query("select * from datosbiblio;");
      return $this->realizar_query($query);
  }
  function getpermisoBiblio($campo){
      $query=$this->db->query("select * from datosbiblio where ".$campo."=true");
    return ($query->num_rows()==1) ? true:false;
  }
  function update($datos){
      $this->db->where('idbiblio',374);
      return $this->db->update('datosbiblio', $datos); 
  }
}


