<?php
Class Grupo_model extends CI_Model
{
    public function construct() 
    {
        parent::__construct();
    }
    private function realizar_query($query)
    {
        if($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }
    function show($inicio=0,$contar="")
    {
        $this->db->select('*');
        $this->db->from('grupos');
        if(strcmp($contar, "contar")==0) {
            $query = $this->db->get();
            return $query->num_rows(); 
        }else{
            $this->db->limit(10, $inicio);
            $query = $this->db->get();
            return $this->realizar_query($query);
        }
    }
    function get_datos_grupo($cuenta)
    {
        $this->db->select('*');
        $this->db->from('grupouser');
        $this->db->join("usuariobiblio as us", "grupouser.user_id=us.pcurp");
        $this->db->join("grupos", "grupos.idgrupo=grupo_id");
        $this->db->where('user_id', $cuenta);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            foreach ($query as $row){
                return $query->result();
            }
        }else{
            return false;
        }
    }
    function agregar_a_grupo($cuenta,$idgrupo)
    {
        $idgrupouser=$this->find_persona_en_grupo($cuenta);
        $grupo['grupo_id']=$idgrupo; 
        if(!$idgrupouser) {
            $grupo['user_id']= $cuenta;
            return $this->db->insert('grupouser', $grupo);
        }else{
            $this->db->where('idgrupouser', $idgrupouser);
            return $this->db->update('grupouser', $grupo);
        }
    }
    private function find_persona_en_grupo($cuenta)
    {
        $this->db->select('idgrupouser');
        $this->db->from('grupouser');
        $this->db->where('user_id', $cuenta);
        $query = $this->db->get();
        if($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                return $row->idgrupouser;    
            }
        }else{
            return false;
        }
    }
    function contar()
    {
        $this->db->select('count(*) as total');
        $this->db->from('grupos');
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function crearedit($id,$nombre,$multa,$dias,$reno,$cant,$vigencia)
    {
        $data = array(
          'namegrupo' => $nombre,
          'montomulta' => $multa,
          'diasentrega' => $dias,
          'renovacion'=>$reno,
          'cantlibros'=>$cant,
          'vigencia'=>$vigencia
        );
        if(!$this->findbyname($nombre)) {     
            return $this->db->insert('grupos', $data);
        }else{
            return false;
        }
    }
    private function findbyname($name)
    {
        $this->db->select("*");
        $this->db->from("grupos");
        $this->db->where("namegrupo", $name);
        $query=$this->db->get();
        return $this->realizar_query($query);
    }

}

