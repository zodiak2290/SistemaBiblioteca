<?php
Class Barcode_model extends CI_Model
{
    public function construct() 
    {
        parent::__construct();
    }
    function vaciar($tipo)
    {
        $this->db->where('tipo', $tipo); 
        return $this->db->delete('impbarcode'); 
    }
    function eliminar($dato,$tipo)
    {
        $this->db->where('dato', $dato); 
        $this->db->where('tipo', $tipo); 
        return $this->db->delete('impbarcode'); 
    }
    function agregacodigo($dato,$tipo)
    {
        $data = array(
          'idcodigo' => date("YmdHis").rand(1, 9).$dato,
          'dato' => $dato,
          'tipo'=>$tipo
        );
        if(!$this->findbydat($dato, $tipo)) {
            return $this->db->insert('impbarcode', $data);
        }
    }
    private function realizar_query($query)
    {
        return ($query->num_rows() > 0) ? $query->result():false;
    }
    private function findbydat($dato,$tipo)
    {
        $this->db->select("dato");
        $this->db->from("impbarcode");
        $this->db->where("dato", $dato);
        $this->db->where("tipo", $tipo);
        $query = $this->db->get();
        return $this->realizar_query($query); 
    }
    function get_codigos($tipo)
    {
        $this->db->select("dato");
        $this->db->from("impbarcode");
        $this->db->where("tipo", $tipo);
        $query = $this->db->get();
        return $this->realizar_query($query); 
    }
}

