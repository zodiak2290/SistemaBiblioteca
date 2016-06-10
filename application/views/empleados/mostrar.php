<div id="page-wrapper" class="col-md-12" ng-app="empleados" ng-controller="empleadoscontroller">
<div class="page-header">
  <h1></h1>
  <div class="row">
    <div class="col-lg-8">
          <a onclick=href="<?php echo base_url(); ?>home/empleado/agregar" class='btn btn-info'>Agrega empleado</a>
    </div>
  </div>
   <?php $correcto = $this->session->flashdata('correcto');
        if ($correcto) 
        {
        ?>
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
           <span id="registroCorrecto" ><?= $correcto ?></span>
        </div>
        <?php
        }
    ?>
</div>
    <div id="mainBody">
      <div>
        <div class="table-responsive col-lg-10" >
          <div align="center">
            <ul class="pagination ">
            <?php
              /* Se imprimen los números de página */           
              echo $this->pagination->create_links();
            ?>
            </ul>
          </div>
            <table class="table table-condensed table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>
      </th>
      <th>Nombre</th>
      <th>Cuenta</th>
      <th>
      </th>
    </tr>
  </thead>
  <tbody>
    <?php if($datos)
           {
            foreach ($datos as $row){
            
    ?>

      <tr <?php if(isset($row->iduserbloqueados)){?>
            class="danger" 
              <?php } ?>>
        <td><a href="<?php echo $_SERVER['REQUEST_URI']; ?>/ver/<?php echo $row->id;?>" ><span class="icon-eye"></span></a>
        </td>
        <td><?php echo utf8_encode(ucwords(strtolower(utf8_decode($row->nombre))));?></td>
        <td><?php echo $row->cuenta;?></td>  
        <td>
           <a ng-click="verificar('<?php echo $row->id;?>')"><span class="icon-bin"></a></td>    
      </tr>

    <?php }
    }; ?>
  </tbody>
</table>
          <div  id="eliminar"   class="alert alert-danger alert-dismissable" hidden>
              <button type="button" class="close" ng-click="verificar()" >&times;</button> 
              ¿Eliminar?
              <a ><span class="icon-bin"></a>  
              <button ng-click="eliminar()">Aceptar</button>&nbsp;<button ng-click="verificar()">Cancelar</button>
           </div>
          </div> 
      </div> 
    </div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
            $("#page-wrapper").show();
        });
var app=angular.module("empleados",[]);
    app.controller("empleadoscontroller",function ($scope){
      $scope.idpe="";
      $scope.verificar=function(s){
        $scope.idpe=s;
        if($('#eliminar').is(':visible')){
          $('#eliminar').hide(100);
        }else{
          $('#eliminar').show(100);
        }
      }
      $scope.eliminar=function(){
       window.location.replace('<?php echo $_SERVER['REQUEST_URI']; ?>/borrar/'+$scope.idpe);
      }
    });
</script>     


