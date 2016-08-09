<?php
class User extends CI_Model
{
    var $caseclasificacion="case  when clasificacion rlike '^[0][0-9][0-9]' then 'Generalidades'
                when clasificacion rlike '^[1][0-9][0-9]' then 'Filosofia'
                when clasificacion rlike '^[2][0-9][0-9]' then 'Religión'
                when clasificacion rlike '^[3][0-9][0-9]' then 'Ciencias Sociales'
                when clasificacion rlike '^[4][0-9][0-9]' then 'Lenguas'
                 when clasificacion rlike '^[5][0-9][0-9]' then 'Ciencias Basicas'
                 when clasificacion rlike '^[6][0-9][0-9]' then 'Ciencias Aplicadas'
                 when clasificacion rlike '^[7][0-9][0-9]' then 'Arte'
                 when clasificacion rlike '^[8][0-9][0-9]' then 'Literatura'
                 when clasificacion rlike '^[9][0-9][0-9]' then 'Historia'
                 when clasificacion rlike '^I' then 'Infantil'
                 when clasificacion rlike '^C' then 'Consulta'
                 when clasificacion rlike '^m' then 'Multimedia'               
                  else 'Otros'
            end";
          /*
          when clasificacion rlike '^dvd' then 'DVD'
                 when clasificacion rlike '^VC' then 'Videograbacion' 
          */
    function editadmin($user, $cuenta)
    {
        $this->db->where('cuenta', $cuenta);
        return $this->db->update('empleados', $user);
    }
    public function get_pass_admin($cuenta)
    {
        $this->db->select('password_d');
        $this->db->from('empleados');
        $this->db->where('cuenta', $cuenta);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                return $row->password_d;
            }
        } else {
            return false;
        }
    }
    public function get_email_admin()
    {
        $this->db->select('email');
        $this->db->from('empleados');
        $this->db->where('rol=1');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                // code...
                return $row->email;
            }
        } else {
            return false;
        }
    }
    function actualizarIntento($cuenta, $acion, $campo, $tabla)
    {
        if ($acion) {
            $this->db->set('intentos', 'intentos+1', false);
            $this->db->set('fechaintento', 'now()', false);
        } else {
            $this->db->set('intentos', '0', false);
        }
        $this->db->where($campo, $cuenta);
        $this->db->update($tabla);
    }
    function get_intentos_fecha($cuenta, $tipousuario, $name)
    {
        $query = $this->db->get_where($tipousuario, array($name => $cuenta));
        return  $this->realizar_query($query);
    }
    //encontar usuario para mostar en pagina de prestamo
    function mostrarporcuenta($cuenta)
    {
        $this->db->select('curp,pnombre,cuentausuario,email,created_at,iduserbloqueado');
        $this->db->from('personas');
        $this->db->join('usuariobiblio', "personas.curp=usuariobiblio.pcurp");
        $this->db->join('grupouser', "grupouser.user_id=usuariobiblio.pcurp", 'left');
        $this->db->join('grupos', "grupos.idgrupo=grupouser.grupo_id", 'left');
        $this->db->join('userbloqueados', "userbloqueados.cuentauser=usuariobiblio.cuentausuario", 'left');
        $this->db->where('cuentausuario', $cuenta);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    private function realizar_query($query)
    {
        return ($query->num_rows()== 1) ?  $query->result() :false;
    }
    function editpass($usuario, $cuenta)
    {
        $this->db->where('cuenta', $cuenta);
        return $this->db->update('empleados', $usuario);
    }
    function loginandroi($cuenta, $password)
    {
        $this->db->select('cuentausuario, pnombre,curp ');
        $this->db->from("personas");
        $this->db->join("usuariobiblio", "personas.curp=usuariobiblio.pcurp");
        $this->db->where("(cuentausuario='".$cuenta."'  or email='".$cuenta."')");
        $this->db->where('password_d', MD5($password));
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function is_usuariob($cuenta, $password)
    {
        $this->db->select('cuentausuario, pnombre,curp ');
        $this->db->from("personas");
        $this->db->join("usuariobiblio", "personas.curp=usuariobiblio.pcurp");
        if (is_numeric($cuenta)) {
            $this->db->where("cuentausuario", intval($cuenta));
        } else {
            $this->db->where("email", $cuenta);
        }
        $this->db->where('password_d', MD5($password));
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()==1) {
            $datouser['datos']=$query->result();
            $datouser['rol']=7;
            return $datouser;
        } else {
            return false;
        }
    }

    function usuariode($cuentausuario, $password)
    {
        $this->db->select('cuenta as cuentausuario,nombre as pnombre,curp,rol');
        $this->db->from("empleados");
        $this->db->where("(cuenta='".$cuentausuario."' or email='".$cuentausuario."')");
        $this->db->where('password_d', MD5($password));
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function emailid($email, $cuenta)
    {
        $this->db->select('*');
        $this->db->from('empleados');
        $this->db->where('email', $email);
        $this->db->where('cuenta!=', $cuenta);
        $this->db->limit(1);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? true :false;
    }
    function email($email)
    {
        $this->db->select('*');
        $this->db->from('empleados');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? true :false;
    }

    function login($cuentausuario, $password)
    {
        $this->db->select('cuentausuario,password_d,pnombre,rol,personas.id as id');
        $this->db->from('usuariobiblio');
        $this->db->join('personas', 'usuariobiblio.persona_id=personas.id');
        $this->db->where('cuentausuario', $cuentausuario);
        $this->db->where('password_d', MD5($password));
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    public function get_pass($cuenta)
    {
        $this->db->select('password_d');
        $this->db->from('usuariobiblio');
        $this->db->where('cuentausuario', intval($cuenta));
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                return $row->password_d;
            }
        } else {
            return false;
        }
    }
 
    function find_by_cuenta($cuenta)
    {
        $query=$this->db->query(
            "select pnombre,apPat,apMat,cantlibros,diasentrega,renovacion,montomulta 
    ,date_add(updated_at, interval vigencia year) as vigente
    FROM personas
    join usuariobiblio as ub on personas.curp=ub.pcurp
    left join grupouser as gp on gp.user_id=ub.pcurp
    left join grupos on gp.grupo_id=grupos.idgrupo
    where cuentausuario='".$cuenta."'"
        );
        return $this->realizar_query($query);
    }
    function uservigente($cuenta)
    {
        $query=$this->db->query(
            "select * FROM personas join usuariobiblio on personas.curp=usuariobiblio.pcurp
                          where date_add(updated_at, interval 2 year)>date(now()) 
                          and cuentausuario='".$cuenta."';"
        );
         return $this->realizar_query($query);
    }

    var $consultaenprestamo="select iddetalleprestamo,titulo,contreno,fechaprestamo,cantlibros,
  DATE_ADD(date(fechaprestamo),interval (diasentrega+(renovacion*contreno) )  day) as entrega,
ej.nadqui as nadqui,truncate(datediff(date(now()),fechaprestamo)/(diasentrega+(renovacion*contreno)),2)*100 as porcentaje 
from prestamos as p 
join detalleprestamo as dp on p.idprestamo=dp.prestamo_id
join ejemplar as ej on dp.nadqui=ej.nadqui
join libros as et on et.idlibro=ej.nficha
join usuariobiblio as ub on ub.cuentausuario =p.cuentausuario
join personas as per on per.curp =ub.pcurp
join grupouser as gp on gp.user_id=per.curp
join grupos as g on g.idgrupo=gp.grupo_id
where dp.fechadev is null
and ub.cuentausuario='";
    function get_consultaprestamos($cuenta, $tipo)
    {
        return "select iddetalleprestamo,titulo,contreno,fechaprestamo,cantlibros,tipo,
DATE_ADD(date(fechaprestamo),interval (diasentrega+(renovacion*contreno) )  day) as entrega,
ej.nadqui as nadqui,truncate(datediff(date(now()),fechaprestamo)/(diasentrega+(renovacion*contreno)),2)*100 as porcentaje 
from prestamos as p 
join detalleprestamo as dp on p.idprestamo=dp.prestamo_id
join ejemplar as ej on dp.nadqui=ej.nadqui
join libros as et on et.idlibro=ej.nficha
join usuariobiblio as ub on ub.cuentausuario =p.cuentausuario
join personas as per on per.curp =ub.pcurp
join grupouser as gp on gp.user_id=per.curp
join grupos as g on g.idgrupo=gp.grupo_id
where tipo='".$tipo."'
and ub.cuentausuario='".$cuenta."'";
    }
    function get_datos_prestamos_usuarui($cuenta, $tipo)
    {
        $query=$this->db->query($this->get_consultaprestamos($cuenta, $tipo));
          return ($query->num_rows() > 0) ? $query->result() :false;
    }

    function en_prestamo($cuenta)
    {
        $diasentrega=$this->diasentrega($cuenta);
        $query=$this->db->query($this->consultaenprestamo.$cuenta."'");
        return ($query->num_rows() > 0) ? $query->result() :false;
    }
    function en_prestamovencido($cuenta)
    {
        //$diasentrega=$this->diasentrega($cuenta);
        $query=$this->db->query($this->consultaenprestamo.$cuenta."' having porcentaje>100");
        return ($query->num_rows() > 0) ? $query->result() : false;
    }
    //devuelve el total de dias que puede un usuario llevarse un libro
    private function diasentrega($cuenta)
    {
        $this->db->select("diasentrega");
        $this->db->from("usuariobiblio as ub");
        $this->db->join("grupouser", "grupouser.user_id=ub.cuentausuario");
        $this->db->join("grupos ", "grupos.idgrupo=grupouser.grupo_id");
        $this->db->where("cuentausuario", $cuenta);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                return $row->diasentrega;
            }
        } else {
            return false;
        }
    }

    function userbloqueado($cuenta)
    {
        $query=$this->db->get_where('userbloqueados', array('cuentauser' =>$cuenta));
        return $this->realizar_query($query);
    }
    function desbloquearuser($cuenta)
    {
        return $this->db->delete('userbloqueados', array('cuentauser' => $cuenta));
    }
    function bloquearuser($cuenta, $observaciones)
    {
        if (!$this->userbloqueado($cuenta)) {
            $data = array(
             'iduserbloqueado' => date("YmdHis").rand(1, 9).rand(1, 9),
             'cuentauser' => $cuenta,
             'observaciones'=>$observaciones
            );
            return $this->db->insert('userbloqueados', $data);
        }
    }
    function is_userbiblio($cuenta)
    {
        $query=$this->db->get_where('usuariobiblio', array('cuentausuario' =>intval($cuenta)));
        return $this->realizar_query($query);
    }
    function multas($cuenta)
    {
        $query=$this->db->query(
            " select idmulta, fechamulta,datediff(fechadev,fechaprestamo)-diasentrega as retraso, (datediff(fechadev,fechaprestamo)-diasentrega) * montomulta as total
    FROM prestamos as p 
    join usuariobiblio as ub on p.cuentausuario=ub.cuentausuario
    join detalleprestamo as dp on dp.prestamo_id=p.idprestamo
    join multas as m on m.dtlleprestamo_id=dp.iddetalleprestamo
    join personas as per on per.curp=ub.pcurp
    join grupouser as gp on gp.user_id=per.curp
    join grupos as g on g.idgrupo=gp.grupo_id
    where pagado is null
    and ub.cuentausuario='".$cuenta."'"
        );
        return ($query->num_rows()>0) ? $query->result() : false;
    }
    function total_multa_saldo($cuenta)
    {
        $query=$this->db->query(
            "select count(*) as total ,sum(pagado) as monto
                    FROM multas 
                    join detalleprestamo on multas.dtlleprestamo_id=detalleprestamo.iddetalleprestamo
                    join prestamos on prestamos.idprestamo=detalleprestamo.prestamo_id
                    join usuariobiblio on usuariobiblio.cuentausuario=prestamos.cuentausuario
                    where usuariobiblio.cuentausuario=".$cuenta." 
                    and pagado is not null
                    having count(*)>0"
        );
        return ($query->num_rows()>0) ? $query->result() : false;
    }
    function total_multa_pendientes_saldo($cuenta)
    {
        $query=$this->db->query(
            "select count(*) as total ,sum(pagado) as monto
                    FROM multas 
                    join detalleprestamo on multas.dtlleprestamo_id=detalleprestamo.iddetalleprestamo
                    join prestamos on prestamos.idprestamo=detalleprestamo.prestamo_id
                    join usuariobiblio on usuariobiblio.cuentausuario=prestamos.cuentausuario
                    where usuariobiblio.cuentausuario='".$cuenta."' and pagado is null
                    having count(*)>0"
        );
        return ($query->num_rows()>0) ? $query->result() : false;
    }
    function nopermitirreserva($cuenta)
    {
        $query=$this->db->query(
            "select  count(*) as total
            FROM reservas
            where cuentausuario='".$cuenta."' 
            group by cuentausuario;"
        );
        if ($query->num_rows() > 0) {
            $total=0;
            foreach ($query->result() as $row) {
                $total=$row->total;
            }
            return ($total+1<3) ? true :false;
        } else {
            return true;
        }
    }
    function preferenciasusuario($cuenta, $tipo)
    {
        $query=$this->db->query(
            "select (".$this->caseclasificacion.") 
            as Clasificacion ,count(*) as total
            from libros
            join clasificacion on libros.clasificacion_id=clasificacion.idclasificacion
            join ejemplar on libros.idlibro=ejemplar.nficha
            join detalleprestamo on detalleprestamo.nadqui=ejemplar.nadqui
            join prestamos on prestamos.idprestamo=detalleprestamo.prestamo_id
            where prestamos.cuentausuario='".$cuenta."'
            and tipo='".$tipo."'  
            group by (".$this->caseclasificacion.") order by total desc"
        );
          return $query->num_rows()>0 ? $query->result() :false;
    }
}
