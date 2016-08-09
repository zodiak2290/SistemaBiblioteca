<?php
class Consulta_model extends CI_Model
{
    var $clasificaciones= array('filosofia' =>'[1][0-9][0-9]','religion'=>'[2][0-9][0-9]',
                            'ciencias sociales'=>'[3][0-9][0-9]',
                            'lenguas'=>'[4][0-9][0-9]' ,
                            'ciencias basicas'=>'[5][0-9][0-9]',
                            'ciencias aplicadas'=>'[6][0-9][0-9]',
                            'arte'=>'[7][0-9][0-9]',
                            'literatura'=>'[8][0-9][0-9]',
                            'historia'=>'[9][0-9][0-9]');
    public function construct()
    {
        parent::__construct();
    }
    function bytitulo($titulo, $inicio, $contar = "")
    {
          $this->db->select("ej.nadqui,titulo,nameautor,clasificacion,match(titulo) against('".$titulo."' in boolean mode) as rel");
          $this->get_from_join();
          $this->db->like("match(titulo) against('".$titulo."' in boolean mode)");
          $this->db->group_by("ej.nadqui");
          $this->db->order_by("rel", "desc");
          $this->db->having("rel>10");
          return $this->resultado($this, $inicio, $contar);
    }
    private function get_from_join()
    {
        $this->db->from('ejemplar as ej');
        $this->db->join('libros as et', 'ej.nficha=et.idlibro');
        $this->db->join('clasificacion as c', 'c.idclasificacion=et.clasificacion_id', "left");
        $this->db->join('autorescribio as ae', 'ae.libro_id=et.idlibro', "left");
        $this->db->join('autor', 'ae.autor_id=autor.idautor', "left");
        $this->db->join('librosbloqueados as lb', 'lb.nadqui=ej.nadqui', "left");
        $this->db->where("lb.nadqui is null");
        $this->db->where("ej.status=1");
        return $this;
    }
    function byautor($autor, $inicio, $contar = "")
    {
        $this->db->select("ej.nadqui,titulo,nameautor,clasificacion,match(nameautor) against('".$autor."' in boolean mode) as rel");
        $this->get_from_join();
        $this->db->where("match(nameautor) against('".$autor."' in boolean mode)");
        $this->db->order_by("rel", "desc");
        return $this->resultado($this, $inicio, $contar);
    }
    function bytema($tema, $inicio, $contar = "")
    {

        $this->db->select("ej.nadqui,titulo,nameautor,clasificacion");
        $this->get_from_join();
        $this->db->join('materialibro ml', 'ml.libro_id=et.idlibro');
        $this->db->join('materias as m', 'ml.materia_id=m.idmateria');
        if (!array_key_exists($tema, $this->clasificaciones)) {
            $this->db->like("namemateria", $tema, "both");
        } else {
            $this->db->where("clasificacion rlike '^".$this->clasificaciones[$tema]."'");
        }
        $this->db->group_by("ej.nadqui");
        return $this->resultado($this, $inicio, $contar);
    }
    private function resultado($cons, $inicio, $contar = '')
    {
        if (strcmp($contar, "contar")==0) {
            $query = $cons->db->get();
            return $query->num_rows();
        } else {
             $this->db->limit(10, $inicio);
             $query =$cons->db->get();
             return ($query->num_rows() > 0) ? $query->result():false;
        }
    }
}
