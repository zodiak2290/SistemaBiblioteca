<?php
Class Reporte_model extends CI_Model
{
  public function construct(){
        parent::__construct();
  }
   function usuariosatendidos($mes,$anio,$edad){
      $this->db->select("edad,count(*) as total");
      $this->db->from("visitas");
      $this->db->where("month(fechavisita)",$mes);
      $this->db->where("year(fechavisita)",$anio);
      $this->db->where("hour(fechavisita) between 7 and 21");
      $this->db->where("edad",$edad);
      $this->db->group_by(" month(fechavisita),edad");
      $query = $this->db->get();
      return $this->realizar_query($query);
   }
   function actividades($mes,$anio,$edad,$tipo){
      if(strcmp($tipo,'contar')==0){
        $select="count(dirigidoa) as total";
      }else if(strcmp($tipo,'sumar')==0){
        $select="sum(asistencia) as total";
      }
      $this->db->select($select);
      $this->db->from("actividades as ac");
      $this->db->join("actividadesdia as ad","ac.idactividad=ad.actividad_id");
      $this->db->where("month(dia)",$mes);
      $this->db->where("year(dia)",$anio);
      $this->db->where("dirigidoa",$edad);
      $query = $this->db->get();
      return $this->realizar_query($query);
   }
  function actividadeste($mes,$anio,$tipo,$edad=""){
      if(strcmp($tipo,'contar')==0){
        $select="count(*) as total";
      }else if(strcmp($tipo,'sumar')==0){
        $select="sum(".$edad.") as total";
      }
      $this->db->select($select);
      $this->db->from("actividades as ac");
      $this->db->join("registroactividadte as ad","ac.idactividad=ad.actividadid");
      $this->db->where("month(dia)",$mes);
      $this->db->where("year(dia)",$anio);
      $query = $this->db->get();
      return $this->realizar_query($query);
   }
  
  function prestamos($mes,$anio,$tipo,$like){
      $this->db->select("count(*) as total");
      $this->db->from("prestamos as p");
      $this->db->join("detalleprestamo as dp","p.idprestamo=dp.prestamo_id");
      $this->db->join("ejemplar as ej","ej.nadqui=dp.nadqui");
      $this->db->join("libros as et","et.idlibro=ej.nficha");
      if(strcmp($like,"I")==0){
        $this->db->join('clasificacion as c','c.idclasificacion=et.clasificacion_id');
        $this->db->like("clasificacion","I","after");
      }elseif(strcmp($like,"C")==0){
        $this->db->join('clasificacion as c','c.idclasificacion=et.clasificacion_id');
       // $this->db->where("clasificacion rlike '^C'");
       $this->db->where("(clasificacion like 'C%' or clasificacion rlike '^[0-9]')");
        //$this->db->or_where("clasificacion rlike '^[0-9]'");  
      }
      $this->db->where("month(fechaprestamo)",$mes);
      $this->db->where("year(fechaprestamo)",$anio);
      $this->db->where("p.tipo",$tipo);
      $this->db->where("hour(fechaprestamo) between 6 and 21");
      $query = $this->db->get();
      return $this->realizar_query($query);
   }
   function usuarionuevos($mes,$anio){
      $this->db->select('count(*) as total');
      $this->db->from('personas as p'); 
      $this->db->join('usuariobiblio as ub','p.curp=ub.pcurp');
      $this->db->where('year(created_at)',$anio);
      $this->db->where('month(created_at)',$mes);
      $query = $this->db->get();
      return $this->realizar_query($query);
   }
    private function realizar_query($query){
        if($query->num_rows() > 0)
         {
            foreach ($query->result() as $row) {
                return $row->total;
            }
         }
         else
         {
           return 0;
         }
      }

}


