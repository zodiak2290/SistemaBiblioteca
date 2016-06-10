<div id="page-wrapper" class="col-md-12" ng-app="grupo" ng-controller="grupocontroller">
<div class="page-header">
<?php $this->load->view('empleados/menuusuarios');?> 
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
    <div id="mainBody">
        <div class="table-responsive col-lg-12">
          <div align="center">
            <ul class="pagination pager">
            <?php
              /* Se imprimen los números de página */           
              echo $this->pagination->create_links();
            ?>
            </ul>
          </div>
            <table class="table table-condensed table-bordered table-striped table-hover">
  <thead>
    <tr align="center">
      <th></th>
      <th>Nombre</th>
    </tr>
  </thead>
  <tbody align="center">
    <?php if($datos)
           {
            foreach ($datos as $row){
    ?>
      <tr>
        <td ng-click="editar(<?php echo $row->idescuela ?>)"><span class="icon-pencil"></span>        
        </td>
        <td><?php echo utf8_encode(ucwords(strtolower(utf8_decode( $row->nombre))));?></td>
        <td ng-click="eliminar(<?php echo $row->idescuela ?>)"><span class="icon-bin"></span></td>    
      </tr>
    <?php 
  }
    }; ?>
  </tbody>
</table>
          </div> 
    </div>
</div>
     <div class="modal fade " id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog" >
                               <div class="modal-content">
                                    <div class="modal-header" > 
                                    Escuela                    
                                    </div>
                                    <div class="modal-body container-fluid col-md-12">              
                                        <div class="row">
                                            <div class="col-md-12">
                                               <div class="col-md-12"><input ng-model="nombre" type="text" id="Nombre" placeholder="Nombre"/></div>
                                             </div>       
                                          </div>
                                       </div> 
                                       <div class="modal-footer">
                                           <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-12">
                                                 </div>
                                            </div>
                                           </div>
                                       </div>
                                       <hr>
                                        <div class="container-fluid">
                                                <div class="col-xs-12 col-md-12">
                                                <div class="row">
                                                    <a ng-click="agregar()" type="button" class="btn btn-primary btn-xl" data-dismiss="modal"  ><i class="fa fa-times"></i>Agregar</a> 
                                                    <a type="button" class="btn btn-info btn-xl" data-dismiss="modal" ><i class="fa fa-times"></i>Cancelar</a> 
                                                </div>
                                                <br>
                                            </div>                                        
                                        </div>            
                                </div>
                    </div>
                </div>
            </div>

<style type="text/css">
    .navev{
      display: flex;
      list-style: none;
      justify-content: center; 
    }
    a{
      text-decoration: none;
    }
</style>
<script type="text/javascript">
var app=angular.module("grupo",[]).filter('capitalize', function() {
    return function(input, all) {
      return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
  });
        app.controller("grupocontroller",function ($scope,$http){
          $scope.editar=function(id){
            alertify.prompt("Nuevo :", function (e, str) { 
            if (e){
              $scope.nombre=str;
              edicion(id);
            }
          });
        }
         $scope.eliminar=function(id){
            alertify.confirm("¿Eliminar?", function (e) { 
            if (e){
              eliminando(id);
            }
          });
        }
          edicion=function(ide){
            url='<?php echo base_url()?>home/escuela/editar';
            data={nombre:$scope.nombre,id:ide};
            accion(url,data);
          }
          eliminando=function(id){
            url='<?php echo base_url()?>home/escuela/eliminar';
            data={idesc:id};
            accion(url,data);
          }
          accion=function(url,data){
            console.log(data);
             $http({
                  url: url,
                   method: 'POST',
                   data: data     
                  }).success(function(result){
                  alertify.success(result.mensaje);  
                }).
                  error(function(result){
                console.log(result);
                  });
          }
          $scope.agregar=function(){  
               url= '<?php echo base_url()?>home/escuela/agregar'; 
               data={nombre:$scope.nombre};
                accion(url,data);
          }
          $( document ).ready(function() {
             $('#escuelas').attr("class","active");
            });
          
          });
</script>

