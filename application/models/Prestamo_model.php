<?php
Class Prestamo_model extends CI_Model
{
    var $consultaprestamosindevolucion=" from prestamos as p 
            join detalleprestamo as dp on p.idprestamo=dp.prestamo_id
            join ejemplar as ej on dp.nadqui=ej.nadqui
            join libros as et on et.idlibro=ej.nficha
            join usuariobiblio as ub on ub.cuentausuario =p.cuentausuario
            join personas as per on per.curp =ub.pcurp
            join grupouser as gp on gp.user_id=per.curp
            join grupos as g on g.idgrupo=gp.grupo_id
            where dp.fechadev is null";
    var $fechaentrega="DATE_ADD(date(fechaprestamo),interval (diasentrega+(renovacion*contreno) )  day)";           
    public function construct() 
    {
        parent::__construct();

    }

    function contar_prestamos_activos($soloretrasos=false)
    {
        $and= $soloretrasos?">7":"<8";
        $query=$this->db->query("select count(*) as total".$this->consultaprestamosindevolucion. " and datediff(now(),fechaprestamo)".$and);
        $result=$this->realizar_query($query);
        if($result) {
            foreach ($result as $row) {
                return $row->total;
            }
        }
    }
    function get_prestamos_by_graficas($tipo,$and="")
    {
              $query=$this->db->query(
                  "
                select year(fechaprestamo) as anio,count(*) as total
                from prestamos join detalleprestamo on prestamos.idprestamo=detalleprestamo.prestamo_id
                join ejemplar on detalleprestamo.nadqui=ejemplar.nadqui join libros on libros.idlibro=ejemplar.nficha
                join clasificacion on libros.clasificacion_id=clasificacion.idclasificacion
                where fechaprestamo is not null " .$and." 
                and (clasificacion like 'C%' or clasificacion rlike '^[0-9]' or clasificacion like 'I%')
                and hour(fechaprestamo) between 6 and 21 and tipo='".$tipo."'
                group by year(fechaprestamo)
                order by year(fechaprestamo)"
              );
              if($query->num_rows()>0) {
                     return $query->result_array(); 
              }
                else
                {
                    return false;
                } 
    }
    function get_prestamos_by_graficas_por_anio($anio,$tipo)
    {
        $query=$this->db->query(
            "
        select case  when month(fechaprestamo)=1 then 'Enero'
                  when month(fechaprestamo)=2 then 'Febrero'
                   when month(fechaprestamo)=3 then 'Marzo'
                   when month(fechaprestamo)=4 then 'Abril'
                                     when month(fechaprestamo)=5 then 'Mayo'
                                     when month(fechaprestamo)=6 then 'Junio'
                                     when month(fechaprestamo)=7 then 'Julio'
                                     when month(fechaprestamo)=8 then 'Agosto'
                                     when month(fechaprestamo)=9 then 'Septiembre'
                                     when month(fechaprestamo)=10 then 'Octubre'
                                     when month(fechaprestamo)=11 then 'Noviembre' 
                  else 'Diciembre'
            end as anio
,count(*) as total
from prestamos join detalleprestamo on prestamos.idprestamo=detalleprestamo.prestamo_id
join ejemplar on detalleprestamo.nadqui=ejemplar.nadqui join libros on libros.idlibro=ejemplar.nficha
join clasificacion on libros.clasificacion_id=clasificacion.idclasificacion
where fechaprestamo is not null
and (clasificacion like 'C%' or clasificacion rlike '^[0-9]' or clasificacion like 'I%')
and hour(fechaprestamo) between 6 and 21 and tipo='".$tipo."'
and year(fechaprestamo)=".$anio."
group by month(fechaprestamo)
order by month(fechaprestamo)
        "
        );
        if($query->num_rows()>0) {
                 return $query->result_array(); 
        }
        else
           {
            return false;
        } 
    }

    function agregarprestamo($nad)
    {
        $this->db->trans_start();
           $data['idprestamo']=date("YmdHis")."".$nad."I";
           $data['fechaprestamo']=date("Y-m-d H:i:s");
           $data['tipo']='I';
           $this->db->insert('prestamos', $data);
           $dato['prestamo_id']=$data['idprestamo'];
           $dato['nadqui'] = $nad;
           $dato['iddetalleprestamo'] =$data['idprestamo'].$this->contdetalleprestamo($data['idprestamo']);
           $dato['fechadev']=date('Y-m-d H:i:s');
           $this->db->insert('detalleprestamo', $dato);
         $this->db->trans_complete();
         return $this->db->trans_status(); 
    }
    function renovar($id)
    {
        $this->db->set('contreno', 'contreno+1', false);
        $this->db->where('iddetalleprestamo', $id);
        return $this->db->update('detalleprestamo'); 

    }
    function saldar($multa,$monto)
    {
        $data['pagado']=$monto;
        $this->db->where('idmulta', $multa);
        return $this->db->update('multas', $data); 
    }
    private function insertartdetalle($idprestamo,$nad)
    {
        $dato['prestamo_id']=$idprestamo;
        $dato['nadqui'] = $nad;
        $dato['iddetalleprestamo'] =$idprestamo.$this->contdetalleprestamo($idprestamo);
        return $this->db->insert('detalleprestamo', $dato); 
    }
    private function contdetalleprestamo($idprestamo)
    {
        $this->db->select('count(*) as total');
        $this->db->from('detalleprestamo');
        $this->db->where('prestamo_id', $idprestamo);
        $query = $this->db->get();
        if($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                return $row->total+1;
            }  
        }                
        else
            {
            return 0;
        }
    }

    function devolucion($idpres,$multa)
    {
          $this->db->select('iddetalleprestamo'); 
          $this->db->from("detalleprestamo as dp");
          $this->db->where("dp.fechadev is null");
          $this->db->where("iddetalleprestamo", $idpres);
          $query = $this->db->get();
        if($query->num_rows()==1) {
            foreach ($query->result() as $row) {
                $idd=$row->iddetalleprestamo;
            }
            $this->devolver($idd, $multa);  
        }                
        else
           {
            return false;
        } 
    }
    private function multar($detalleprestamoid)
    {
        $data['dtlleprestamo_id']=$detalleprestamoid;
        $data['fechamulta']=date("Y-m-d H:i:s");
        $data['idmulta']=date("YmdHis").rand(0, 9).rand(0, 9);
        return $this->db->insert('multas', $data);
    }
    private function devolver($detalleprestamoid,$multa)
    {
        $data=array('fechadev' =>date("Y-m-d H:i:s"));
        $this->db->where("iddetalleprestamo", $detalleprestamoid);
        if($this->db->update('detalleprestamo', $data)) {
            if($multa) {
                $this->multar($detalleprestamoid);
            }
        }
         
    }
    function datos_prestamo($nadqui)
    {
        $this->db->select("*");
        $this->db->from("prestamos");
        $this->db->where("fechaentrga is null");
        $this->db->where("nadqui", $nadqui);
         $query = $this->db->get();
         return $this->realizar_query($query); 

    }
      //inserciones para pruebas eliminar: insertart y buscar
    private function insertart()
    {
        $cont=0;
        while($cont<5000){
            $nadqui=rand(1, 53534);
            if($this->buscar($nadqui)) {
                $data['idprestamo']="201s2sf".rand(1, 60).rand(2, 50).$nadqui;
                $data['nadqui'] = $nadqui;
                $data['fechahora']="2015-".rand(5, 11)."-".rand(9, 13)." ".rand(1, 9).":".rand(1, 30).":".rand(1, 59);

                $this->db->insert('prestamointer', $data);       
            }
            $cont++;
        }
    }
    private function buscar($nadqui)
    {
        $this->db->select("*");
        $this->db->from("ejemplares");
        $this->db->where("nadqui", $nadqui);
        $query = $this->db->get();
        return $this->realizar_query($query); 
    }
    function permitirprestamo($cuenta)
    {
        $query=$this->db->query(
            "select (case when cantlibros>count(nadqui) then true
        when cantlibros=count(nadqui) then false
        when count(nadqui)<3 then true 
                          end
          ) as permitir
          FROM usuariobiblio as ub
          join prestamos as p on ub.cuentausuario=p.cuentausuario
          left join detalleprestamo as dp on p.idprestamo=dp.prestamo_id
          left join grupouser as gu on gu.user_id=ub.pcurp
          left join grupos as g on gu.grupo_id=g.idgrupo
          where ub.cuentausuario='".$cuenta."' and fechadev is null"
        );
        if($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                return $row->permitir;
            } 
        }
        else
            {
            return false;
        } 
    }
    function construir_consulta_para_graficas_procesos($nadqui)
    {
        $consulta="select tipo,year(fechaprestamo) as anio,count(*) as total
                  from prestamos as p 
                  join detalleprestamo as d on p.idprestamo=d.prestamo_id
                  join ejemplar on d.nadqui=ejemplar.nadqui
                  where ejemplar.nadqui=".$nadqui."
                  and hour(fechaprestamo) between 6 and 21 
                  group by tipo,year(fechaprestamo);";
        $query=$this->db->query($consulta);
        return $this->realizar_query($query);
    }

    public function get_prestamos($cuenta)
    {
        $query=$this->db->query(
            $this->get_consulta_prestamo()." datediff(date(now()),".$this->fechaentrega.") as diasretraso,
          datediff(date(now()),fechaprestamo) as diastranscurridos ,
          truncate(datediff(date(now()),fechaprestamo)/(diasentrega+(renovacion*contreno)),2)*100 as porcentaje,
          ".$this->get_barra('fechaprestamo')." ".$this->consultaprestamosindevolucion." and ub.cuentausuario='".$cuenta."'"
        );
        return $this->realizar_query($query);  
    }
    function obtener_datos($inicio=0,$cantidad=10,$nadqui=false,$cuenta=false,$tipo="")
    {
        if($nadqui) {
            $andnadqui=" and ej.nadqui=".$nadqui;
        }else{
            $andnadqui=" ";
        }
        if($cuenta) {
            $andcuenta=" and p.cuentausuario=".$cuenta."";
        }else{
            $andcuenta=" ";
        }
        if(strcmp($tipo, "enprestamo")==0) {
            $query=$this->en_prestamo($inicio, $cantidad, $andnadqui, $andcuenta);
        }else if(strcmp($tipo, "vencidos")==0) {
            $query=$this->prestamosvencidos($inicio, $cantidad, $andnadqui, $andcuenta);
        } 
        return $this->realizar_query($query); 
    }
    private function prestamosvencidos($inicio=0,$cantidad=10,$nadqui,$cuenta)
    {
        $query=$this->db->query(
            $this->get_consulta_prestamo()." datediff(date(now()),".$this->fechaentrega.") as diasretraso,
          nombreaval,emailaval,telefonoaval ".$this->consultaprestamosindevolucion." 
          and DATE_ADD(fechaprestamo,interval (diasentrega+(renovacion*contreno)) day)<curdate()
          ".$nadqui.$cuenta." limit ".$inicio.",".$cantidad
        );
        return $query;
    }
    private function en_prestamo($inicio=0,$cantidad=10,$nadqui,$cuenta)
    {
        $query=$this->db->query(
            $this->get_consulta_prestamo()." nombreaval,emailaval,telefonoaval "
            .$this->consultaprestamosindevolucion.
            " and DATE_ADD(fechaprestamo,interval (diasentrega+(renovacion*contreno)) day)>curdate()
        ".$nadqui.$cuenta." limit ".$inicio.",".$cantidad
        );
        return $query;  
    }
    private function realizar_query($query)
    {
        if($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }
    private function get_consulta_prestamo()
    {
        $this->load->library('periodos');
        $mes=$this->periodos->get_nombre_mes("fechaprestamo");
        $namdia=$this->periodos->get_nombredia("fechaprestamo", "nombredia");
        // seleccionamos titulo, cuentadeusuario, renovvaciones realizadas
        //año mes y dia del prrestamo , año mes dia de entrega          
        $fechaentrega=$this->fechaentrega;
        $mesentrega=$this->periodos->get_nombre_mes($fechaentrega, "mesentrega");
        $diaentrega=$this->periodos->get_nombredia($fechaentrega, "diaentrega");
        $consulta="select ub.cuentausuario as cuenta,pnombre,
        titulo,ej.nadqui,contreno,year(fechaprestamo) as anio,".$mes.",".$namdia.",day(fechaprestamo) as dia,
        fechaprestamo,".$fechaentrega." as entrega,
        year(".$fechaentrega.") as anioe,day(".$fechaentrega.") as diaen,
        ".$mesentrega.",".$diaentrega.",diasentrega+(renovacion*contreno)  as diastotal,";
        return $consulta;
    }

    function get_barra($campofecha)
    {
        return "(case when truncate(datediff(date(now()),".$campofecha.")/(diasentrega+(renovacion*contreno)),2)*100<80 then 'success' 
                      when truncate(datediff(date(now()),".$campofecha.")/(diasentrega+(renovacion*contreno)),2)*100<89 then 'warning' 
                      when truncate(datediff(date(now()),".$campofecha.")/(diasentrega+(renovacion*contreno)),2)*100>98.9 then 'danger'  
                                 end)as barra";
    }
}

