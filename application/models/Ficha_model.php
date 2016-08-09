<?php
class Ficha_model extends CI_Model
{
    public function construct()
    {
        parent::__construct();
    }
    function findautorbyname($name)
    {
        $this->db->select('*');
        $this->db->from('autor');
        $this->db->where('nameautor', $name);
        $this->db->limit(1);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
               return $query->result();
        } else {
            $this->insertaautor($name);
            $this->findautorbyname($name);
        }
    }
    function obtener_autor_by_nombre($name)
    {
        $this->db->select('*');
        $this->db->from('autor');
        $this->db->where('nameautor', $name);
        $this->db->limit(1);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
             return $query->result();
        } else {
            return false;
        }

    }
    function findclasificacion($clasificacion)
    {
        $this->db->select('*');
        $this->db->from('clasificacion');
        $this->db->where('clasificacion', $clasificacion);
        $this->db->limit(1);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
             return $query->result();
        } else {
            $this->insertarclasificacio($clasificacion);
            $this->findclasificacion($clasificacion);
        }
    }
    function insertarclasificacio($clas)
    {
        $data['clasificacion']=$clas;
        return $this->db->insert('clasificacion', $data);
    }
    function insertaautor($nameautor)
    {
        $data['nameautor']=$nameautor;
        return $this->db->insert('autor', $data);
    }
    function insertamateria($nametema)
    {
        $data['namemateria']=$nametema;
        return $this->db->insert('materias', $data);
    }

    function findmateriabyname($name)
    {
        $query=$this->db->query(
            "SELECT * FROM materias 
            where binary namemateria='".$name."'"
        );
        if ($query->num_rows() > 0) {
               return $query->result();
        } else {
            $this->insertamateria($name);
            $this->findmateriabyname($name);
        }
    }
    function obtener_propiedad_por_nombre($name, $tabla, $campo)
    {
        $query=$this->db->query("SELECT * FROM ".$tabla." where binary ".$campo."='".$name."' limit 1");
        return ($query->num_rows() > 0)? $query->result():false;
    }
    function insertaautorescribio($idescribio, $idautor, $idlibro)
    {
        $data['libro_id']=$idlibro;
        $data['idescribio']=$idescribio;
        $data['autor_id']=$idautor;
        return $this->insertar_relacion($data, 'autorescribio');
    }
    function insertafichatema($idfichatema, $idmateria, $idficha)
    {
        if (isset($idfichatema)&&isset($idmateria)&&isset($idficha)) {
            if (strlen($idfichatema)>0&&strlen($idmateria)>0&&strlen($idficha)>0) {
                $data['libro_id']=$idficha;
                $data['idmaterialibro']=$idfichatema;
                $data['materia_id']=$idmateria;
                return $this->insertar_relacion($data, 'materialibro');
            }
        }
    }
    function insertar_relacion($data, $tabla)
    {
        return $this->db->insert($tabla, $data);
    }
    function insertapublicacion($idpublica, $ideditorial, $idficha)
    {
        $data['libro_id']=$idficha;
        $data['idpublica']=$idpublica;
        $data['editorial_id']=$ideditorial;
        return $this->db->insert('publica', $data);
    }

 
    function fichatema($idfichatema)
    {
        return $this->existe_relacion('materialibro', 'idmaterialibro', $idfichatema);
    }
    function existe_relacion($tabla, $campo, $id)
    {
        $this->db->select('*');
        $this->db->from($tabla);
        $this->db->where($campo, $id);
        $query=$this->db->get();
        return ($query->num_rows()>0) ? true :false;
    }
    function autorescribio($idescribio)
    {
        return $this->existe_relacion('autorescribio', 'idescribio', $idescribio);
    }

    function publica($idedit)
    {
        $this->db->select('*');
        $this->db->from('publica');
        $this->db->where('idpublica', $idedit);
        $query=$this->db->get();
        if ($query->num_rows()>0) {
            return true;
        } else {
            return false;
        }
    }

    function eliminarrelacion($idmateria, $id, $tabla)
    {
        $this->db->where($id, $idmateria);
        return $this->db->delete($tabla);
    }
    function findbyidficha($idf)
    {
        $this->db->select('*');
        $this->db->from('libros');
        $this->db->where('idlibro', $idf);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function agregalibro($libro)
    {
        if (!$this->findbyidficha($libro['idlibro'])) {
            return $this->db->insert('libros', $libro);
        } else {
            $this->db->where('idlibro', $libro['idlibro']);
            return $this->db->update('libros', $libro);
        }
    }
    function editar_propiedad($idautor, $nuevoname, $campo, $tabla, $id)
    {
          $data[$campo]=strtolower($nuevoname);
          $this->db->where($id, $idautor);
          return $this->db->update($tabla, $data);
    }
    function eliminarautor($idautor)
    {
        $this->db->where('idautor', $idautor);
        return $this->db->delete('autor');
    }
    function eliminarmateria($idmateria)
    {
        $this->db->where('idmateria', $idmateria);
        return $this->db->delete('materias');
    }
    function editarmateria($idmateria, $nuevoname)
    {
          $data['namemateria']=strtolower($nuevoname);
          $this->db->where('idmateria', $idmateria);
          return $this->db->update('materias', $data);
    }
}
