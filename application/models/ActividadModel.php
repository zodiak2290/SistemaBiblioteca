<?php
namespace application\controllers;

class ActividadModel extends CI_Model
{
    private $_mnjactividadregistrada="Actividad ya registrada";
    public function construct()
    {
        parent::__construct();
    }
    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    public function diasactividad($dia, $idactividad, $cont)
    {
        $actividad=$this->findById($idactividad);
        $dirigidoa="";
        foreach ($actividad as $row) {
            $dirigidoa=$row->dirigidoa;
        }
        $datos=array(
          'dia'=>$dia,
          'idactividad'=>$idactividad,
          'cont'=>$cont
        );
        return (strcmp($dirigidoa, "te")==0)?
        $this->crearregistro($datos):$this->crearactividad($datos);
    }
    private function crearactividad($datos)
    {
        extract($datos);
        if (!$this->verificarevento($idactividad, $dia->dia)) {
            $data['actividad_id']=$idactividad;
            $data['idactividadesdia']=$idactividad.$cont;
            $data['dia']=$dia->dia;
            $data['asistencia']=0;
            return $this->db->insert('actividadesdia', $data);
        } else {
            return $this->_mnjactividadregistrada;
        }
    }
    public function update($actividad, $id)
    {
        $this->db->where('idactividad', $id);
        return $this->db->update('actividades', $actividad);
    }
    private function crearregistro($datos)
    {
        extract($datos);
        if (!$this->verificarregistro($idactividad, $dia->dia)) {
            $data['actividadid']=$idactividad;
            $lav=str_replace("-", "", $dia->dia);
            $data['idregistroactividadte']=$idactividad.$lav;
            $data['dia']=$dia->dia;
            $data['adultos']=0;
            $data['jovenes']=0;
            $data['ninos']=0;
            return $this->db->insert('registroactividadte', $data);
        } else {
            return $this->_mnjactividadregistrada;
        }
    }
    public function verificarregistro($actividadId, $dia)
    {
        $this->db->select('*');
        $this->db->from('registroactividadte');
        $this->db->where('actividadid', $actividadId);
        $this->db->where('dia', $dia);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    public function actividadessinfecha()
    {
        $query=$this->db->query(
            "select idactividad,nombre,lugar 
        FROM actividades
        left join actividadesdia on actividades.idactividad=actividadesdia.actividad_id
        where dirigidoa!='te'
        group by idactividad
        having count(idactividadesdia)=0
        union all
        SELECT idactividad,nombre,lugar
        FROM actividades
        left join registroactividadte on actividades.idactividad=registroactividadte.actividadid
        where dirigidoa='te'
        group by idactividad
        having count(idregistroactividadte)=0
        "
        );
        return $this->realizar_query($query);
    }
  
    public function registroactividadte($valor, $id, $tipo)
    {
        if (strcmp($tipo, "ninos")==0) {
            $data['ninos']= $valor;
        } elseif (strcmp($tipo, "jovenes")==0) {
            $data['jovenes']= $valor;
        } elseif (strcmp($tipo, "adultos")==0) {
            $data['adultos']=$valor;
        }
          $this->db->where('idregistroactividadte', $id);
          return $this->db->update('registroactividadte', $data);
    }
    public function registroactividad($valor, $id)
    {
        $data['asistencia']= $valor;
        $data['registrado']=1;
        $this->db->where('idactividadesdia', $id);
        return $this->db->update('actividadesdia', $data);
    }
    public function verificarevento($actividadId, $dia)
    {
        $this->db->select('*');
        $this->db->from('actividadesdia');
        $this->db->where('actividad_id', $actividadId);
        $this->db->where('dia', $dia);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    public function actividadesmes($cont = 0, $dia = "")
    {
        $where=(strlen($dia)>0)?"and day(dia)=".$dia:" ";
        $this->load->library('periodos');
        $nombredias=$this->periodos->get_nombredia("actividadesdia.dia", "periodo");
        $mes=$this->periodos->get_nombre_mes("actividadesdia.dia", "mes");
        $nombredia=$this->periodos->get_nombredia("registroactividadte.dia", "periodo");
        $me=$this->periodos->get_nombre_mes("registroactividadte.dia", "mes");
        $query=$this->db->query(
            "
        select idactividad,nombre, descripcion,imagen,
        horainicio,horafin,".$nombredias.","
            .$mes.",day(dia) as dia
        FROM actividades
        join actividadesdia on actividades.idactividad=actividadesdia.actividad_id 
        where year(actividadesdia.dia)=year(now())
        and month(actividadesdia.dia)=month(now()) ".$where.
            " and dia>date_sub( now(),interval 1 day)
        union all
        SELECT idactividad,nombre,descripcion,imagen,horainicio,horafin,".$nombredia.","
            .$me.",day(dia) as dia
        FROM actividades
        join registroactividadte on actividades.idactividad=registroactividadte.actividadid
        where year(registroactividadte.dia)=year(now())
        and month(registroactividadte.dia)=month(now()) ".$where."
        and dia>date_sub( now(),interval 1 day)
        order by dia
        limit " .$cont." ,10"
        );
        return $this->realizar_query($query);
    }
    public function contaractividadesmes()
    {
        $query=$this->db->query(
            "select(select count(*)
        FROM actividades
        join actividadesdia on actividades.idactividad=actividadesdia.actividad_id 
        where year(actividadesdia.dia)=year(now())
        and month(actividadesdia.dia)=month(now()) 
        and dia>date_sub( now(),interval 1 day))
        +
        (SELECT count(*)
        FROM actividades
        join registroactividadte on actividades.idactividad=registroactividadte.actividadid
        where year(registroactividadte.dia)=year(now())
        and month(registroactividadte.dia)=month(now()) 
        and dia>date_sub( now(),interval 1 day)
        ) as total"
        );
        return $this->get_total($query);
    }
    private function realizarQuery($query)
    {
        return ($query->num_rows() > 0) ? $query->result():false;
    }
    private function getTotal($query)
    {
        $result=$this->realizar_query($query);
        $total=0;
        foreach ($result as $toal) {
            $total=$toal->total;
        }
        return  $result ? $total  :0;
    }
    public function totalActividadesByEvento($idactividad)
    {
        $this->db->select('count(*) as total');
        $this->db->from('actividadesdia');
        $this->db->where('actividad_id', $idactividad);
        $query = $this->db->get();
        return $this->get_total($query);
    }
    public function show($contar, $anio, $inicio = 0)
    {
        $where=" ";
        $and=" ";
        if (isset($anio)) {
            $where=" where year(actividadesdia.dia)=".$anio;
            $and="   where year(registroactividadte.dia)=".$anio;
        }
        $query=$this->db->query(
            "select idactividad,nombre,actividad_id,dirigidoa, dia,horainicio,horafin,
(case when registrado=1 then true
        when registrado=0 and dia>now() then true
        when registrado=0 and dia<now() then false
         end) as enregistro
        FROM actividades
        join actividadesdia on actividades.idactividad=actividadesdia.actividad_id 
       ".$where."    
        union all
        SELECT idactividad,nombre,actividadid as actividad_id,dirigidoa, dia,horainicio,horafin,
(case when adultos>0 or jovenes >0 or ninos>0 then true
  when (adultos=0 and jovenes =0 and ninos=0) and dia>now() then true
        when (adultos=0 and jovenes =0 and ninos=0) and dia<now() then false
         end) as enregistro
        FROM actividades
        join registroactividadte on actividades.idactividad=registroactividadte.actividadid
         ".$and."   
         order by dia"
        );
        return (strcmp($contar, "contar")==0) ?$query->num_rows():$this->realizar_query($query);
    }
    public function findByIdCalendar($idactividad)
    {
        $actividad=$this->findById($idactividad);
            $dirigidoa="";
        if ($actividad) {
            foreach ($actividad as $row) {
                $dirigidoa=$row->dirigidoa;
            }
        }
        $this->db->select('*');
        $this->db->from('actividades');
        if (strcmp($dirigidoa, "te")==0) {
            $this->db->join('registroactividadte', 'actividades.idactividad=registroactividadte.actividadid');
        } else {
            $this->db->join('actividadesdia', 'actividades.idactividad=actividadesdia.actividad_id');
        }
        $this->db->where('idactividad', $idactividad);
        $this->db->order_by('dia', 'asc');
        $query = $this->db->get();
        return $this->realizar_query($query);
    }

    public function findById($idactividad)
    {
        $this->db->select('*');
        $this->db->from('actividades');
        $this->db->where('idactividad', $idactividad);
         $query = $this->db->get();
        return $this->realizar_query($query);
    }
    public function subir($titulo, $imagen, $descripcion, $cuenta, $dirigido, $lugar, $inicio, $fin)
    {
        $id='a'.date('YmdHms').rand(1, 9).rand(1, 9).rand(1, 9);
        $data = array(
         'idactividad'=>$id,
         'nombre' => $titulo,
         'descripcion' => $descripcion,
         'imagen' => $imagen,
         'fechapublicacion'=> date("Y-m-d H:i:s"),
         'dirigidoa'=>$dirigido,
         'cuentaempleado'=>$cuenta,
         'lugar'=>$lugar,
         'horainicio'=>$inicio,
         'horafin'=>$fin
        );
        return ($this->db->insert('actividades', $data)) ? $id:false;
    }
    public function updateimage($id, $image)
    {
        $data['imagen']= $image;
        $this->db->where('idactividad', $id);
        return $this->db->update('actividades', $data);
    }
    public function delete($id)
    {
        $this->db->where('idactividad', $id);
        return $this->db->delete('actividades');
    }
    public function deletera($id)
    {
        $this->db->where('idregistroactividadte', $id);
        return $this->db->delete('registroactividadte');
    }
    public function deleteac($id)
    {
        $this->db->where('idactividadesdia', $id);
        return $this->db->delete('actividadesdia');
    }
}
