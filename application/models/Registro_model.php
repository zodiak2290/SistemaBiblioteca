<?php
class Registro_model extends CI_Model
{
    public function construct()
    {
        parent::__construct();
    }
    function agregarregistro($edad)
    {
        $var=rand(0, 20);
        $var2=rand(0, 9);
        $data['idvisita']=date("YmdHis").$var.$var2;
        $data['edad'] = $edad;
        $data['fechavisita']=date("Y-m-d H:i:s");
        return $this->db->insert('visitas', $data);
    }

    function showvisitas($tipo, $periodo)
    {
        if (strcmp($tipo, "hora")==0) {
            $select='date(fechavisita) as dia,hour(fechavisita) as hora,edad,count(*) as total';
            $where=' day(fechavisita)='.$periodo['dia'];//dia
            $where.=' and month(fechavisita)='.$periodo['mes'];//mes
            $where.=' and year(fechavisita)='.$periodo['anio'];//anio
            $group=' month(fechavisita),date(fechavisita),hour(fechavisita),edad';
        } elseif (strcmp($tipo, "dia")==0) {
            $select='date(fechavisita) as dia,edad,count(*) as total,hour(fechavisita) as hora';
            $where='month(fechavisita)='.$periodo['mes'];
            $where.=' and year(fechavisita)='.$periodo['anio'];
            $group=' date(fechavisita),edad';
        } elseif (strcmp($tipo, "mes")==0) {
            $select='month(fechavisita) as dia,edad,count(*) as total,year(fechavisita) as hora';
            $where='year(fechavisita)='.$periodo['anio'];
            $group='month(fechavisita),edad';
        }
        $this->db->select($select);
        $this->db->from('visitas');
        $this->db->where($where);
        $this->db->where('hour(fechavisita) between 6 and 21');
        $this->db->group_by($group);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
