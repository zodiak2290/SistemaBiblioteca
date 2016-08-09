<?php
class Reserva_model extends CI_Model
{

    public function construct()
    {
        parent::__construct();
    }
    function find_reservar($id)
    {
        $this->db->select('*');
        $this->db->from('reservas');
        $this->db->where('idreserva', $id);
        $query = $this->db->get();
        return ($query->num_rows()==1) ? $query->result() :false;
    }
    function get_reservas($cuenta)
    {
        $this->load->library('periodos');
        $fechaentrega="DATE_ADD(fecha,interval 2 day)";
        $mesv=$this->periodos->get_nombre_mes($fechaentrega, "mesven");
        $namediav=$this->periodos->get_nombredia($fechaentrega, "diaven");
        $query=$this->db->query(
            "select idreserva, titulo,ejemplar.nadqui,".$mesv.",".$namediav.",day(".$fechaentrega.") as dia,year(fecha) as anio,fecha
        ,date_add(fecha,interval 2 day) as limite  
        from reservas join ejemplar on ejemplar.nadqui=reservas.nadqui 
        join libros on libros.idlibro=ejemplar.nficha
        where cuentausuario='".$cuenta."'"
        );
        return $this->realizar_query($query);
    }
    function eliminarreserva($id)
    {
        $this->db->where('idreserva', $id);
        return $this->db->delete('reservas');
    }
    function obtener_datos($inicio = 0, $cantidad = 10, $nadqui = false, $cuenta = false, $tipo = "")
    {
        $andnadqui=$nadqui?$andnadqui=" and ej.nadqui=".$nadqui:" ";
        $andcuenta=$cuenta?" and ub.cuentausuario='".$cuenta."' ":" ";
        $this->load->library('periodos');
        $mes=$this->periodos->get_nombre_mes("fecha");
        $namdia=$this->periodos->get_nombredia("fecha", "nombredia");
        // seleccionamos titulo, cuentadeusuario, renovvaciones realizadas
        //año mes y dia del prrestamo , año mes dia de entrega
        $fechaentrega="DATE_ADD(fecha,interval 2 day) ";
        $mesentrega=$this->periodos->get_nombre_mes($fechaentrega, "mesvencimiento");
        $diaentrega=$this->periodos->get_nombredia($fechaentrega, "diavecimiento");
        $query=$this->db->query(
            "select idreserva,ub.cuentausuario as cuenta,pnombre,
        titulo,ej.nadqui,year(fecha) as anio,".$mes.",".$namdia.",day(fecha) as dia,
        fecha,".$fechaentrega." as vencimiento,
        year(".$fechaentrega.") as aniov,day(".$fechaentrega.") as diave,
        ".$mesentrega.",".$diaentrega."
        from reservas as res 
        join ejemplar as ej on res.nadqui=ej.nadqui
        join libros as et on et.idlibro=ej.nficha
        join usuariobiblio as ub on ub.cuentausuario =res.cuentausuario
        join personas as per on per.curp =ub.pcurp
        and DATE_ADD(fecha,interval 2 day)>now()
        ".$andnadqui.$andcuenta." 
        limit ".$inicio.",".$cantidad
        );
        return $this->realizar_query($query);
    }
    private function realizar_query($query)
    {
        return ($query->num_rows() > 0) ? $query->result():false;
    }
    function agregarreserva($nadqui, $cuenta, $fecha)
    {
          $data['idreserva']=$nadqui.$cuenta;
          $data['nadqui'] = $nadqui;
          $data['cuentausuario'] = $cuenta;
          $data['fecha'] = $fecha;
          return $this->db->insert('reservas', $data);
    }
    function eliminar_reservas_vencidas()
    {
        $this->db->trans_start();
        $this->db->where('date_add(fecha,interval 2 day)<now()');
        $this->db->where("idreserva!=''");
        $this->db->delete('reservas');
        $this->db->where('date_add(creado,interval 30 minute)<now()');
        $this->db->where("iduser!=''");
        $this->db->delete('users');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function contar_reservas()
    {
        $this->db->from('reservas');
        return $this->db->count_all_results();
    }
}
