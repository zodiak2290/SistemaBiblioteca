<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Periodos 
{
  
var $meses=array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril',
               '5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto',
               '9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
var $dias=array('Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miercoles',
                 'Thursday'=>'Jueves','Friday'=>'Viernes','Saturday'=>'Sabado',
                 'Sunday'=>'Domingo');
	private function get_consulta_periodo_when($campofecha,$periodo="periodo",$arregloperiodo,$diamesanio){
      $consutlawhen="(case ";
      foreach ($arregloperiodo as $llave => $valor) {
            $consutlawhen.= " when ".$diamesanio."(".$campofecha.")='".$llave."' then '".$valor."'";
      }  
      return  $consutlawhen.=" end )as ".$periodo."";
    }
    //meses y dias inicializados en config
    function get_nombre_mes($campofecha,$periodo="periodo"){
        return $this->get_consulta_periodo_when($campofecha,$periodo,$this->meses,'month');
    }
    function get_nombredia($campofecha,$periodo="periodo"){
        return $this->get_consulta_periodo_when($campofecha,$periodo,$this->dias,'dayname');
    }

}