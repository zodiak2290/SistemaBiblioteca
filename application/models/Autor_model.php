<?php
Class Autor_model extends CI_Model
{
  public function construct() {
        parent::__construct();
  }
  private function realizar_query($query){
    return ($query->num_rows() > 0) ? $query->result():false;
  }
  function find_clasificacion($name){
    $this->db->select('clasificacion');
    $this->db->from('clasificacion');
    $this->db->LIKE("clasificacion",$name);
    $this->db->limit(10);
    $query=$this->db->get();
    return $this->realizar_query($query);
  }
  function findautorbyname($name)
	{
		$this->db->select('nameautor');
		$this->db->from('autor');
		$this->db->where("match(nameautor) against('".$name."' in boolean mode)>2");
		$this->db->limit(10);
		$query=$this->db->get();
		return $this->realizar_query($query);
	}
  function findmateriabyname($name){
    $this->db->select('namemateria');
    $this->db->from('materias');
    $this->db->like("namemateria",$name,"after");
    $this->db->limit(10);
    $query=$this->db->get();
    return $this->realizar_query($query);   
  }
  function contar_libros_by_autor($nameautor){
    $this->db->select('*');
    $this->db->from('libros');
    $this->db->join('autorescribio as ae','libros.idlibro=ae.libro_id');
    $this->db->join('autor','ae.autor_id=autor.idautor');
    $this->db->where("nameautor",$nameautor);
        $query=$this->db->get();
    return $this->realizar_query($query);  
  }
  function contar_libros_by_materia($name){
    $this->db->select('*');
    $this->db->from('libros');
    $this->db->join('materialibro as ml','libros.idlibro=ml.libro_id');
    $this->db->join('materias','ml.materia_id=materias.idmateria');
    $this->db->where("namemateria",$name);
    $query=$this->db->get();
    return $this->realizar_query($query);
  }
}
