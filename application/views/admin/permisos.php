<?php 
    $this->load->database(); 
    $query = $this->db->query("select permitireservas,permitirbusqpri,busquedaspu,accesousuarios,prestamos,acccesoempl from datosbiblio;");
    $config = array('pr' =>"",'pbp'=>"",'pbpu'=>"",'au'=>"",'pres'=>"",'pae'=>"");
    foreach ($query->result() as $row){
          $config['pr']=$row->permitireservas;
          $config['pbp']=$row->permitirbusqpri;
          $config['pbpu']=$row->busquedaspu;
          $config['au']=$row->accesousuarios;
          $config['pres']=$row->prestamos;
          $config['pae']=$row->acccesoempl;
    }
    if($rol==8){

?>
<div id="page-wrapper" class="col-md-11 text-center" ng-app="setting" ng-controller="setcontroller" >
<div ng-init="urlpermisos='<?php echo base_url()?>home/empleado/edit'"></div>

            <div class="panel-body" >                 
              <ul class="list-group">
                  <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Permitir realizar reservas desde panel de usuario</strong></div>
                      <div class="col-lg-4"><span><input type="checkbox" id="permitireservas" <?php if($config['pr']==1){ echo "checked"; }?> /></span></div> 
                        <div class="col-lg-4"><button  class="btn-info" ng-click="guardarcambios('permitireservas')"  >Guardar Cambios</button></div>
                  </li>
                  <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Permitir búsquedas desde pagina principal</strong></div>
                      <div class="col-lg-4"><span><input type="checkbox" id="permitirbusqpri"<?php if($config['pbp']==1){ echo "checked"; }?> /></span></div> 
                        <div class="col-lg-4"><button class="btn-info" ng-click="guardarcambios('permitirbusqpri')">Guardar Cambios</button></div>
                  </li>
                  <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Permitir búsquedas desde panel de usuario</strong></div>
                      <div class="col-lg-4"><span><input type="checkbox" id="busquedaspu" <?php if($config['pbpu']==1){ echo "checked"; }?>/></span></div> 
                        <div class="col-lg-4"><button class="btn-info" ng-click="guardarcambios('busquedaspu')">Guardar Cambios</button></div>
                  </li>
                  <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Permitir acceso de usuarios</strong></div>
                      <div class="col-lg-4"><span><input type="checkbox" id="accesousuarios" <?php if($config['au']==1){ echo "checked"; }?>/></span></div> 
                        <div class="col-lg-4"><button class="btn-info" ng-click="guardarcambios('accesousuarios')">Guardar Cambios</button></div>
                  </li>
                  <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Permitir acceso de empleados</strong></div>
                      <div class="col-lg-4"><span><input type="checkbox" id="acccesoempl" <?php if($config['pae']==1){ echo "checked"; }?>/></span></div> 
                        <div class="col-lg-4"><button class="btn-info" ng-click="guardarcambios('acccesoempl')">Guardar Cambios</button></div>
                  </li>
                  <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Permitir préstamos</strong></div>
                      <div class="col-lg-4"><span><input type="checkbox" id="prestamos" <?php if($config['pres']==1){ echo "checked"; }?>/></span></div> 
                      <div class="col-lg-4"><button class="btn-info" ng-click="guardarcambios('prestamos')">Guardar Cambios</button></div>
                  </li>
              </ul>  
            </div>
        
  </div>
<?php }?>