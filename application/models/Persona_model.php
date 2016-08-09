<?php
Class Persona_model extends CI_Model
{
    public function construct() 
    {
        parent::__construct();
    }
    function editpass($usuario,$curp)
    {
        $this->db->where('pcurp', $curp);
        return $this->db->update('usuariobiblio', $usuario);
    }
    function crearedit($persona,$usuario,$grupo,$escuela)
    {
        if(!$this->curpocupada($persona['curp'])) {
            $this->db->trans_start();
            $persona['created_at']=date("Y-m-d H:i:s");
            $persona['updated_at']= date("Y-m-d H:i:s");
            $this->db->insert('personas', $persona);
            $usuario['password_d']=MD5($usuario['password_d']);
            $this->db->insert('usuariobiblio', $usuario);
            $this->db->insert('grupouser', $grupo);
            $this->db->insert('escuelauser', $escuela);
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    }
    //editar un usaurio desde la vista show 
    function update($datos,$curp)
    {
        $this->db->where('curp', $curp);
        return $this->db->update('personas', $datos); 
    }
    private function curpocupada($curp)
    {
        $this->db->select("*");
        $this->db->from('personas');
        $this->db->join("usuariobiblio", 'personas.curp=usuariobiblio.pcurp');
        $this->db->where('personas.curp', $curp);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function show($inicio=0,$contar="")
    {
        $this->db->select("*");
        $this->db->from('personas');
        $this->db->join("usuariobiblio", "personas.curp=usuariobiblio.pcurp");
        $this->db->join("userbloqueados", "usuariobiblio.cuentausuario=userbloqueados.cuentauser", 'left');
        $session_data = $this->session->userdata('logged_in');
        $rol=$session_data['rol'];
        $this->db->join("grupouser", "usuariobiblio.pcurp=grupouser.user_id", "left");
        $this->db->join("grupos", "grupos.idgrupo=grupouser.grupo_id", 'left');  
        if(strcmp($contar, "contar")==0) {
            $query = $this->db->get();
            return $query->num_rows(); 
        }else{
            $this->db->limit(10, $inicio);
            $query = $this->db->get();
            return $this->realizar_query($query);
        }
    } 
    function findbyid($curp,$user)
    {
        $this->db->select("*,date_add(updated_at, interval vigencia year) as 'vigente'");
        $this->db->from('personas');
        $this->db->join("usuariobiblio", 'personas.curp=usuariobiblio.pcurp');
        if(strcmp($user, "userbi")==0) {
            $this->db->join("grupouser", "usuariobiblio.pcurp=grupouser.user_id", 'left');
            $this->db->join("grupos", "grupos.idgrupo=grupouser.grupo_id", 'left');
            $this->db->join("escuelauser", "usuariobiblio.pcurp=escuelauser.user_id", 'left');
            $this->db->join("escuelas", "escuelas.idescuela=escuelauser.escuela_id", 'left');
        }
        $this->db->where('personas.curp', $curp);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function findadmin($cuenta)
    {
        $this->db->select("*");
        $this->db->from('empleados');
        $this->db->where('cuenta', $cuenta);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function findaemail($email)
    {
        $this->db->select('*');
        $this->db->from('personas');
        $this->db->join('usuariobiblio', 'personas.curp=usuariobiblio.pcurp');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    //para mostrar en panel
    function findcuentapanel($cuenta)
    {
        $this->db->select('cuentausuario as cuenta,pnombre as nombre,email');
        $this->db->from('personas');
        $this->db->join('usuariobiblio', 'personas.curp=usuariobiblio.pcurp');
        $this->db->where('cuentausuario', $cuenta);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function findcuenta($cuenta)
    {
        $this->db->select('*');
        $this->db->from('personas');
        $this->db->join('usuariobiblio', 'personas.curp=usuariobiblio.pcurp');
        $this->db->where('cuentausuario', $cuenta);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function delete($id)
    {
        $this->db->where('curp', $id); 
        return $this->db->delete('personas'); 
    }
    function cuentaid($id,$cuenta)
    {
        $this->db->select('*');
        $this->db->from('personas');
        $this->db->join('usuariobiblio', 'personas.curp=usuariobiblio.pcurp');
        $this->db->where('cuentausuario', $cuenta);
        $this->db->where('curp!=', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query); 
    }
    function emailid($id,$cuenta)
    {
        $this->db->select('*');
        $this->db->from('personas');
        $this->db->join('usuariobiblio', 'personas.curp=usuariobiblio.pcurp');
        $this->db->where('email', $cuenta);
        $this->db->where('curp!=', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query); 
    }
    function contar()
    {
        $this->db->select("count(*) as total");
        $this->db->from("personas");
        $this->db->from("usuariobiblio");
        $this->db->where("personas.curp=usuariobiblio.pcurp");
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    //buscar usuario para valdiar email
    function findusuario_by_cuenta($cuenta,$id)
    {
        $this->db->select("*");
        $this->db->from("usuariobiblio");
        $this->db->where("cuentausuario", $cuenta);
        $this->db->where("pcurp", $id);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    private function realizar_query($query)
    {
        if($query->num_rows() > 0) {
            return $query->result();
        }
        else
         {
            return false;
        }
    }

}

