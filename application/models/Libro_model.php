<?php 
Class Libro_model extends CI_Model
{
  public function construct() {
        parent::__construct();
    }
     function creareditejemplar($ejemplar)
    {
    extract($ejemplar);
      $data = array(
          'volumen' => $volumen,
          'nejemplar' => $ejemplar,
          'tomo'=>$tomo,
          'nficha'=>$ficha,  
      );
      if(!$this->findbynad($nad)){
        $data['nadqui'] = $nad;
        $data['created_at']=date("Y-m-d H:i:s");
        //$data['updated_at']= date("Y-m-d H:i:s");
        return $this->db->insert('ejemplar', $data);
      }else{
        $this->db->where('nadui',$nad);
        return $this->db->update('ejemplar', $data);
      }
    }
    function eliminarnove($id){
        $this->db->where('idnovedad',$id); 
        return $this->db->delete('novedades');
    }
    function editarnove($novedad,$id){
        $this->db->where('idnovedad',$id);
        return $this->db->update('novedades', $novedad);
    }
    //obtener materias
    function get_materias($idficha){
        $this->db->select('namemateria');      
        $this->db->from('libros as et');
        $this->db->join('materialibro ml','ml.libro_id=et.idlibro');
        $this->db->join('materias as m','ml.materia_id=m.idmateria'); 
        $this->db->where('et.idlibro',$idficha);
        $query = $this->db->get();
        return $this->realizar_query($query); 
    }
    //paginacion libros dados de baja
       function obtener_datos($inicio=0,$cantidad=10,$nadqui=false,$cuenta=false,$tipo=""){
        $this->db->select('iddescarte,ed.nadqui,descartadopor,fechadescarte,observaciones,criterio,titulo');
        $this->db->from('ejemplaresdescartados as ed');
        $this->db->join('ejemplar','ed.nadqui=ejemplar.nadqui');
        $this->db->join('libros','ejemplar.nficha=libros.idlibro');
        if($nadqui){
          $this->db->where('ed.nadqui',$nadqui);
        }
        $this->db->limit($cantidad,$inicio);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    //realziar consulta
    private function realizar_query($query){
        if($query->num_rows() > 0)
         {
           return $query->result();
         }
         else
         {
           return false;
         }
    }
    function get_autores($idficha){
      $this->db->select('nameautor');      
      $this->db->from('libros as et');
      $this->db->join('autorescribio ae','ae.libro_id=et.idlibro');
      $this->db->join('autor as a','ae.autor_id=a.idautor'); 
      $this->db->where('et.idlibro',$idficha);
      $query = $this->db->get();
      return $this->realizar_query($query); 
    }
    function baja($nadqui,$cuenta,$criterio,$obs){
      $id='des'.date('YmdHms').rand(1,9).rand(1,9);
      $data=array('iddescarte' =>$id,
                    'descartadopor'=>$cuenta,
                    'fechadescarte'=>date("Y-m-d H:i:s"),
                    'observaciones'=>$obs,
                    'criterio'=>$criterio,
                    'nadqui'=>$nadqui);  
      $this->db->trans_start();
        //cambia estado de ejemplar
        $this->db->set('status','0',FALSE);
        $this->db->where('nadqui',$nadqui);
        $this->db->update('ejemplar');
        $this->db->insert('ejemplaresdescartados',$data);
        //agregamos a tabla descartados
        $this->db->trans_complete();
        return $this->db->trans_status(); 
    }
    function contar(){
      $this->db->select("count(*) as total");
      $this->db->from("ejemplar");
      $this->db->where("ejemplar.status=1");
      $query = $this->db->get();
      return $this->realizar_query($query); 
    }
    function findbynad($nad){
        $this->db->select('*');
        $this->db->from('ejemplar');
        $this->db->where('nadqui',$nad);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query); 
    }
    function librosreciente(){
        $this->db->select("ejemplar.nadqui,titulo");
        $this->db->from('ejemplar');
        $this->db->join('libros','ejemplar.nficha=libros.idlibro');
        $this->db->join('novedades','novedades.nadqui=ejemplar.nadqui',"left");
        $this->db->where('novedades.nadqui is null');
        $this->db->where('ejemplar.status=1');
        $this->db->order_by('nadqui','desc');
        $this->db->limit(10);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    
    function maxnadqui(){
      $this->db->select("max(nadqui) as nadqui");
      $this->db->from("ejemplar");
      $query = $this->db->get();
      return $this->realizar_query($query);    
    }
    function show($inicio=0)
    {
       $this->db->select('*');
       $this->db->from('ejemplar');
      $this->db->where('ejemplar.status=1');
       $this->db->limit(10,$inicio);
       $query = $this->db->get();
       return $this->realizar_query($query);
    }
// metodo llamado para mostar datos en busqueda
    function findbynadjson($nad){
        $this->db->select('*');
        $this->db->from('ejemplar as ej');
        $this->db->join('libros as et','ej.nficha=et.idlibro');
        $this->db->join('clasificacion as c','c.idclasificacion=et.clasificacion_id',"left");
        $this->db->join('autorescribio as ae','ae.libro_id=et.idlibro',"left");
        $this->db->join('autor','ae.autor_id=autor.idautor','left',"left");
        $this->db->join('materialibro ml','ml.libro_id=et.idlibro','left');
        $this->db->join('materias as m','ml.materia_id=m.idmateria','left');
        $this->db->join('publica as p','p.libro_id=et.idlibro','left');
        $this->db->join('editoriales as ed','ed.ideditorial=p.editorial_id','left');
        $this->db->where('nadqui',$nad);
        $this->db->where('ej.status=1');
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
     function findforprestamojson($nad){
        $this->db->select('titulo,nameautor,clasificacion,nadqui');
        $this->db->from('ejemplar');
        $this->db->join('libros','ejemplar.nficha=libros.idlibro',"left");
        $this->db->join("autorescribio as ae","ae.libro_id=libros.idlibro","left");
        $this->db->join('autor','autor.idautor=ae.autor_id',"left"); 
        $this->db->join('clasificacion','libros.clasificacion_id=clasificacion.idclasificacion',"left");
        $this->db->where('nadqui',$nad);
        $this->db->where('ejemplar.status=1');
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    function total_en_prestamo($cuenta){
      $this->db->select("count(*) as total");
      $this->db->from("prestamos as p");
      $this->db->join("detalleprestamo as dp","p.idprestamo=dp.prestamo_id");
      $this->db->where("dp.fechadev is null");
      $this->db->where("cuentausuario",$cuenta);
      $this->db->where("tipo","E");
      $query = $this->db->get();$total=0;
      if($query->num_rows() > 0)
         {
          foreach ($query->result() as $row) {
              $total=$row->total;    
          }
        }
        return $total;
      }
      function addprestamo($cuenta,$nadqui,$idprestamo,$idetalle,$fechaprestamo,$tipo="E"){
         $fechadev=strcmp($tipo,"I")==0 ? date("Y-m-d H:i:s"):NULL;
        if($this->findprestamoid($idprestamo)){
            return $this->insert_detalle_prestamo($idetalle,$nadqui,$idprestamo,$fechadev);
        }else{
          if($this->insert_prestamo($idprestamo,$fechaprestamo,$cuenta,$tipo)){

              return $this->insert_detalle_prestamo($idetalle,$nadqui,$idprestamo,$fechadev);
          }
        }
      }
      
      function insert_detalle_prestamo($iddetalle,$nadqui,$idprestamo,$fechadev){
          $data=array(
                'iddetalleprestamo' =>$iddetalle,
                 'nadqui'=>$nadqui,
                 'prestamo_id'=>$idprestamo,
                 'contreno'=>0,
                 'fechadev'=>$fechadev);
          return $this->db->insert('detalleprestamo',$data);
      }
      function insert_prestamo($idprestamo,$fechaprestamo,$cuentausuario,$tipo){
          $cuentausuario=strlen($cuentausuario)>0 ? $cuentausuario :NULL;
          $data=array('idprestamo'=>$idprestamo,
                      'fechaprestamo'=>$fechaprestamo,
                      'tipo'=>$tipo,
                      'cuentausuario'=>$cuentausuario
            );
          return $this->db->insert('prestamos',$data);
      }
      function findprestamoid($idprestamo){
        $this->db->select('*');
        $this->db->from("prestamos");
        $this->db->where('idprestamo',$idprestamo);  
        $query = $this->db->get();
        return $this->realizar_query($query);       
      }
      function prestado($nadqui){
        $this->db->select("DATE_ADD(fechaprestamo,interval (diasentrega+(renovacion*contreno) )  day)  as entregar
          ,(case  
  when DATE_ADD(fechaprestamo,interval(diasentrega+(renovacion*contreno) )day)<now() then 'El usuario que solicito este ejemplar en préstamo no 
  a realizado la devolución te sugerimos reserva otro ejemplar.'
      else ''
end) as mensaje");
        $this->db->from("prestamos as p");
        $this->db->join("detalleprestamo as dp","p.idprestamo=dp.prestamo_id");
        $this->db->join("usuariobiblio as ub","ub.cuentausuario=p.cuentausuario","left");
        $this->db->join("grupouser as gp","gp.user_id=ub.pcurp","left");
        $this->db->join("grupos as g","g.idgrupo=gp.grupo_id","left");
        $this->db->where("fechadev is null");
        $this->db->where("dp.nadqui",$nadqui);
        $this->db->where("tipo","E");
        $query = $this->db->get();
        return $this->realizar_query($query); 
      }
      function bloquearejemplar($nadqui){
        if(!$this->bloqueado($nadqui)){
          $data['idbloqueado']=date("YmdHis").$nadqui;
          $data['nadqui'] = $nadqui;

          //$data['updated_at']= date("Y-m-d H:i:s");
          return $this->db->insert('librosbloqueados', $data);
        }
      }
      //desbloquear ejemplar
      function desbloquearejemplar($nadqui){
        if($this->bloqueado($nadqui)){
          $this->db->where('nadqui',$nadqui); 
          return $this->db->delete('librosbloqueados');
        }
      }
      //esta bloqueado?
      function bloqueado($nadqui){
        $query=$this->db->get_where('librosbloqueados',array('nadqui' =>$nadqui)); 
        return $this->realizar_query($query); 
      }
      //esta reservado?
      function reservado($nadqui){ 
          $query=$this->db->query("select idreserva,cuentausuario,nadqui,date_add(fecha,interval 2 day) as limite  FROM reservas where nadqui='".$nadqui."'");
          return $this->realizar_query($query); 
      }
      //eliminar reserva 
      function eliminarreserva($nadqui){
        if($this->reservado($nadqui)){
          $this->db->where('nadqui',$nadqui); 
          return $this->db->delete('reservas');
        }
      }
      //poner en reparacion si no esta ya agregado
     function reparar($nadqui){
        if(!$this->reparacion($nadqui)){
          $data['idrep']=date("YmdHis").$nadqui;
          $data['nadqui'] = $nadqui;
          //$data['updated_at']= date("Y-m-d H:i:s");
          return $this->db->insert('reparacion', $data);
        }
      }
      //poner en disponible si esta en reparacion
      function disponible($nadqui){
        if($this->reparacion($nadqui)){
          $this->db->where('nadqui',$nadqui); 
          return $this->db->delete('reparacion');
        }
      }
      //esta en reparacion?
     function reparacion($nadqui){
        $query=$this->db->get_where('reparacion',array('nadqui' =>$nadqui)); 
        return $this->realizar_query($query); 
      }
      //buscar si la ficha ya esta registrada
    function findbyidjson($idficha){
        $this->db->select('*');
        $this->db->from("libros");
        $this->db->join("autorescribio","autorescribio.libro_id=libros.idlibro",'left');
        $this->db->join('autor','autor.idautor=autorescribio.autor_id','left');
        $this->db->join('clasificacion','clasificacion.idclasificacion=libros.clasificacion_id','left');
        $this->db->join('publica','publica.libro_id=libros.idlibro','left');
        $this->db->join('editoriales','editoriales.ideditorial=publica.editorial_id','left');
        $this->db->where('libros.idlibro',$idficha);
        $this->db->limit(1);
        $query = $this->db->get();
        return $this->realizar_query($query);
    }
    //retorna la consutla que se obtuvo mediante el parametro cons,limite de registros
    // inicio de registro, contar para saber  el total al realizar la consuulta 
    private function resultado($cons,$limite,$inicio,$contar=''){
       if(strcmp($contar,"contar")==0){
          $query = $cons->db->get();
          return $query->num_rows(); 
        }else{
            $this->db->limit($limite,$inicio);
            $query = $cons->db->get();
            return $this->realizar_query($query);
        }  
    }
    // devuelve la consulta para obtener materias
    private function get_query_materia($materia){
      $this->db->join('materialibro', 'materialibro.libro_id=libros.idlibro','left');
      $this->db->join('materias','materialibro.materia_id=materias.idmateria',"left");
      $this->db->like('namemateria',$materia,'both');
      return $this;   
    }
     function findajax($buscarpor,$inicio=0,$onlycont='',$materia='')
    {
      extract($buscarpor);
      if(strlen($materia)>0){
        $this->get_query_materia($materia);     
      }
      if($buscarejem){
        $select='*';
        $this->db->join('ejemplar','ejemplar.nficha=libros.idlibro');
        $this->db->where('ejemplar.status=1');
      }else{
        $select='libros.idlibro as nfic,clasificacion,titulo,isbn';
      }
       $this->db->select($select);
       $this->get_from_join_query();
      $this->get_resultados_consulta($buscarpor);
      $this->db->group_by('idlibro');
      return $this->resultado($this,$limitar,$inicio);
    }
    private function get_from_join_query(){
      $this->db->from("libros");
      $this->db->join("autorescribio","autorescribio.libro_id=libros.idlibro","left");
      $this->db->join('autor','autor.idautor=autorescribio.autor_id',"left");
      $this->db->join('clasificacion','clasificacion.idclasificacion=libros.clasificacion_id',"left");
      $this->db->join('publica','publica.libro_id=libros.idlibro',"left");
      $this->db->join('editoriales','editoriales.ideditorial=publica.editorial_id',"left");
      return $this;
    }
    function findajaxuser($buscarpor,$materiab='',$inicio=0,$onlycont='')
    {
      extract($buscarpor);
      if(strlen($materiab)>0){
         $this->get_query_materia($materiab);
      }
      $select='libros.idlibro as nfic,clasificacion,titulo,isbn,ejemplar.nadqui';
      $this->db->select($select);
      $this->db->join('ejemplar','ejemplar.nficha=libros.idlibro');
      $this->get_from_join_query();
      $this->db->join('librosbloqueados','librosbloqueados.nadqui=ejemplar.nadqui',"left");
      $this->db->where("librosbloqueados.nadqui is null");
      $this->db->where('ejemplar.status=1');
      $this->get_resultados_consulta($buscarpor);
      return $this->resultado($this,$limitar,$inicio,$onlycont);
    }
    private function get_resultados_consulta($buscarpor){
      extract($buscarpor);
      if(strlen($titulo)>0 && strlen($autor)>0&&strlen($isbn)>0&&strlen($editorial)>0){
            $this->db->where('match(titulo) against("'.$titulo.'")');
            $this->db->where('match(nameautor) against("'.$autor.'")');
            $this->db->like('isbn',$isbn,'both');
            $this->db->like('nameeditorial',$editorial,'both');
      }elseif(strlen($titulo)>0&&strlen($autor)>0&&strlen($isbn)==0&&strlen($editorial)==0){
            $this->db->where("match(titulo) against('".$titulo."')");
            $this->db->where("match(nameautor) against('".$autor."')");
      }elseif (strlen($titulo)==0&&strlen($autor)>0&&strlen($isbn)>0&&strlen($editorial)==0){
            $this->db->where('match(nameautor) against("'.$autor.'")');
            $this->db->like('isbn',$isbn,'both');
      }elseif(strlen($titulo)>0&&strlen($autor)==0&&strlen($isbn)>0&&strlen($editorial)==0){
            $this->db->like('isbn',$isbn,'both');
            $this->db->where('match(titulo) against("'.$titulo.'")');
      }elseif(strlen($titulo)>0 && strlen($autor)==0&&strlen($isbn)==0&&strlen($editorial)==0){
            $this->db->where('match(titulo) against("'.$titulo.'" IN BOOLEAN MODE)');
      }elseif(strlen($titulo)==0 && strlen($autor)>0&&strlen($isbn)==0&&strlen($editorial)==0){
            $this->db->where('match(nameautor) against("'.$autor.'" IN BOOLEAN MODE )');
      }elseif(strlen($titulo)>0 && strlen($autor)==0&&strlen($isbn)==0&&strlen($editorial)>0){
            $this->db->where('match(titulo) against("'.$titulo.'" IN BOOLEAN MODE)');
             $this->db->like('nameeditorial',$editorial,'both');
      }elseif(strlen($titulo)>0 && strlen($autor)>0&&strlen($isbn)==0&&strlen($editorial)>0){
            $this->db->where('match(titulo) against("'.$titulo.'")');
            $this->db->where('match(nameautor) against("'.$autor.'")');
            $this->db->like('nameeditorial',$editorial,'both');
      }elseif(strlen($titulo)==0 && strlen($autor)>0&&strlen($isbn)==0&&strlen($editorial)>0){   
            $this->db->where('match(nameautor) against("'.$autor.'")');
            $this->db->like('nameeditorial',$editorial,'both');;
      }elseif(strlen($titulo)==0 && strlen($autor)==0&&strlen($isbn)==0&&strlen($editorial)>0){   
            $this->db->like('nameeditorial',$editorial,'both');
      }elseif(strlen($isbn)>0){
            $this->db->where('isbn',$isbn);
      }
      return $this;
    }
     function numeejemplares($idficha,$volumen,$tomo)
    {
      $this->db->select('*'); 
      $this->db->from('ejemplar'); 
      $this->db->where('nficha',$idficha);
      $this->db->where('tomo',$tomo); 
      $this->db->where('volumen',$volumen);
      $this->db->where('status',1);
      $this->db->group_by(array("nadqui","volumen", "tomo","nejemplar")); 
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
    function ejemplar($ficha,$volumen,$tomo){
      $this->db->select("max(nejemplar) as nejem");
      $this->db->from("ejemplar");
      $this->db->where('nficha',$ficha); 
      $this->db->where('volumen',$volumen);
      $this->db->where('tomo='.$tomo);
      $query = $this->db->get();
       if($query->num_rows()== 1)
       {
        return $query->result();
       }
       else
       {
         return false;
       }    
    }
    function ultimousointer($nadqui,$tipo){
      return $this->get_prestamos_uso_total_query('max(date(fechaprestamo)) as dia',$nadqui,$tipo);
     }
     private function get_prestamos_uso_total_query($select,$nadqui,$tipo){
      $this->db->select($select);
      $this->db->from("prestamos");
      $this->db->join("detalleprestamo","prestamos.idprestamo=detalleprestamo.prestamo_id");
      $this->db->where("tipo",$tipo);
      $this->db->where("hour(fechaprestamo) between 6 and 21");
      $this->db->where('nadqui',$nadqui);
      $query = $this->db->get();
      return $this->realizar_query($query);
     }
     function totalprestamointerno($nadqui,$tipo){
      return $this->get_prestamos_uso_total_query('count(*) as total',$nadqui,$tipo);
     }
     function adquisiciones($cuenta){
      $materias=$this->get_materias_users($cuenta);
        if($materias){
         return $this->recomendapormaterias($materias);
       }else{
          return false;
       }
     }
      function temasejemplarreocmendar($nadqui){
        $materias=$this->get_materias_nadqui($nadqui);
        if($materias){
         return $this->recomendapormaterias($materias,$nadqui);
        }else{
          return false;
        }
      } 
      private function remplazar($materia){
        $materia = str_replace(
                array("\\", "¨", "º", "-", "~",
                     "#", "@", "|", "!", "\"",
                     "·", "$", "%", "&", "/",
                     "(", ")", "?", "'", "¡",
                     "¿", "[", "^", "`", "]",
                     "+", "}", "{", "¨", "´",
                     ">", "< ", ";", ",", ":",
                     ".",""),
                '',
                $materia);
        return $materia;
      }
     private function recomendapormaterias($materias,$nadqui=""){
          $and=" ";
          $andmaterias="";
          if($materias){
            $cont=0;
            $andmaterias="namemateria";
            foreach ($materias as $materia) {
              $materia=$this->remplazar($materia->namemateria);
              if($cont==0){
                  $andmaterias.="='".$materia."'";
              }else{
                $andmaterias.=" or namemateria='".$materia."'";
              }
              $cont++;
            }
          }
          if($nadqui){
            $and=" and ej.nadqui!=".$nadqui;
          }
          //agregado 2016-02-04: 11:56 join autoriescribio y autor
          $query=$this->db->query("select ej.created_at,titulo,ej.nadqui as nad,nameautor,clasificacion
              FROM ejemplar as ej join libros as et on ej.nficha=et.idlibro
              join materialibro as ft on ft.libro_id=et.idlibro 
              join clasificacion as clas on clas.idclasificacion=et.clasificacion_id 
join autorescribio as ae on ae.libro_id=et.idlibro 
join autor on autor.idautor=ae.autor_id 
              join materias as m on m.idmateria=ft.materia_id
              left join reservas as r on r.nadqui=ej.nadqui
              left join librosbloqueados as lb on lb.nadqui=ej.nadqui
              left join reparacion as re on re.nadqui=ej.nadqui
              left join detalleprestamo as dp on dp.nadqui=ej.nadqui
              where ( ".$andmaterias.") 
              ".$and." 
              and  r.nadqui is null
              and lb.nadqui is null
              and re.nadqui is null
              and ej.status=1
              group by ej.nadqui
              order by created_at desc,ej.nadqui desc
              limit 10");
          return $this->realizar_query($query);
     }

     private function get_materias_nadqui($nadqui){
        $this->db->select("namemateria");
        $this->db->from("ejemplar as ej");
        $this->db->join("libros as et","ej.nficha=et.idlibro");
        $this->db->join("materialibro as ft","ft.libro_id=et.idlibro","left");
        $this->db->join("materias as m","m.idmateria=ft.materia_id","left");
        $this->db->where("ej.nadqui",$nadqui);
        $this->db->limit(6);
        $query = $this->db->get();
        return $this->realizar_query($query);
     }
     private function get_materias_users($cuenta){
        $this->db->select("namemateria");
        $this->db->from("prestamos as p");
        $this->db->join("detalleprestamo as dt","p.idprestamo=dt.prestamo_id");
        $this->db->join("ejemplar as ej","dt.nadqui=ej.nadqui");
        $this->db->join("libros as et","ej.nficha=et.idlibro");
        $this->db->join("materialibro as ft","ft.libro_id=et.idlibro");
        $this->db->join("materias as m","m.idmateria=ft.materia_id");
        $this->db->where("cuentausuario",$cuenta);
        $this->db->group_by("namemateria");
        $this->db->order_by("count(namemateria)","desc");
        $this->db->limit(5);
        $query = $this->db->get();
        return $this->realizar_query($query);
     }
    function novedadsubir($titulo,$nomin,$desc,$nadqui){
      if(!$this->nadquinove($nadqui)){
        $data['idnovedad']=date('YmdHis');
        $data['nadqui']=$nadqui;
        $data['descripcion']=$desc;
        $data['imagen']=$nomin;
        return $this->db->insert('novedades', $data);
      }else{
        return "Novedad ya registrada";
      }
    }
    function nadquinove($nad){
      $this->db->select("*");
      $this->db->from("novedades");
      $this->db->where("nadqui",$nad);
      $this->db->limit(1);
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
     function nadquinoveid($id){
      $this->db->select("*");
      $this->db->from("novedades");
      $this->db->where("idnovedad",$id);
      $this->db->limit(1);
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
    function novedades(){
      $this->db->select('idnovedad,titulo,ejemplar.nadqui,descripcion');
      $this->db->from("novedades");
      $this->db->join("ejemplar","novedades.nadqui=ejemplar.nadqui");
      $this->db->join("libros","libros.idlibro=ejemplar.nficha");
      $this->db->order_by("ejemplar.nadqui","desc");
      $this->db->limit(15);
      $query = $this->db->get();
      return $this->realizar_query($query);
    }
    function novedadestotal(){
      $this->db->select('count(*) as total');
      $this->db->from("novedades");
      $query = $this->db->get();
      if($query->num_rows() > 0)
      {
        foreach ($query->result() as $row) {
                  return $row->total;
              }  
      }
      else{
        return false;
      } 
    }
    function prueba_acces(){
        $db_prueba=$this->load->database('acces',TRUE);
    }
    function get_clasificaciones_grafica(){
      $query=$this->db->query("select (case  when clasificacion rlike '^[0][0-9][0-9]' then 'Generalidades'
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
     when clasificacion rlike '^dvd' then 'DVD'
     when clasificacion rlike '^VC' then 'Videograbacion'
      else 'Otros'
end) as Clasificacion ,count(*) as total
from libros join ejemplar on libros.idlibro=ejemplar.nficha
join clasificacion on libros.clasificacion_id=clasificacion.idclasificacion
group by case
  when clasificacion rlike '^[0][0-9][0-9]' then 'Generalidades'
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
      when clasificacion rlike '^dvd' then 'DVD'
      when clasificacion rlike '^VC' then 'Videograbacion'
     else 'Otros'
  end
");
          return $this->realizar_querya_array($query);
    }
    function get_prestamos_por_categoria(){
      $query=$this->db->query("select (case  when clasificacion rlike '^[0][0-9][0-9]' then 'Generalidades'
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
     when clasificacion rlike '^dvd' then 'DVD'
     when clasificacion rlike '^VC' then 'Videograbacion'
      else 'Otros'
end) as Clasificacion ,count(*) as total
from libros join ejemplar on libros.idlibro=ejemplar.nficha
join clasificacion on libros.clasificacion_id=clasificacion.idclasificacion
join detalleprestamo on ejemplar.nadqui=detalleprestamo.nadqui
group by case
  when clasificacion rlike '^[0][0-9][0-9]' then 'Generalidades'
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
      when clasificacion rlike '^dvd' then 'DVD'
      when clasificacion rlike '^VC' then 'Videograbacion'
     else 'Otros'
  end");
return $this->realizar_querya_array($query);
    }
    private function realizar_querya_array($query){
        if($query->num_rows() > 0)
         {
           return $query->result_array();
         }
         else
         {
           return false;
         }
    }
}

