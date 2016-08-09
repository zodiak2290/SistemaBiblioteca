<?php
Class Editorial_model extends CI_Model
{
    public function construct() 
    {
        parent::__construct();
    }
    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function creaeditorial($nombreeditorial)
    {
        $this->db->select('*');
        $this->db->from('editoriales');
        $this->db->where('nameeditorial', $nombreeditorial);
        $this->db->limit(1);
        $query=$this->db->get();
        return ($query->num_rows() > 0) ? $query->result():$this->insertareditorial($nombreeditorial);
    }
    function insertareditorial($editorial)
    {
        $data['nameeditorial']=$editorial;
        return $this->db->insert('editoriales', $data);
    } 
    function creaautor($nombre)
    {
        $this->db->select('*');
        $this->db->from('autor');
        $this->db->where('nameautor', $nombre);
        $this->db->limit(1);
        $query=$this->db->get();
        return ($query->num_rows() > 0) ? $query->result(): $this->insertarautor($nombre); 
    }
    function insertarautor($nombre)
    {
        $data['nameautor']=$nombre;
        return $this->db->insert('autor', $data);
    } 
    function creaclasificacion($clasificacion)
    {
        $this->db->select('*');
        $this->db->from('clasificacion');
        $this->db->where('clasificacion', $clasificacion);
        $this->db->limit(1);
        $query=$this->db->get();
        return ($query->num_rows() > 0) ? $query->result():$this->insertarclasi($clasificacion);
    }
    function insertarclasi($clasificacion)
    {
        $data['clasificacion']=$clasificacion;
        return $this->db->insert('clasificacion', $data);
    } 

}

