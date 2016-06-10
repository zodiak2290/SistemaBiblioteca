
<script src="<?php echo base_url()?>js/angular.min.js"></script>
<div id="page-wrapper" class="col-md-12" ng-app="grupo" ng-controller="grupocontroller">
<div class="page-header">
  <h1>Grupos</h1>
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
      <div>
        <div class="table-responsive col-lg-10">
          <div align="center">
            <ul class="pagination">
            <?php
              /* Se imprimen los números de página */           
              echo $this->pagination->create_links();
            ?>
            </ul>
          </div>
            <table class="table table-condensed table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th></th>
      <th>Nombre</th>
      <th>Multa ($)</th>
      <th>Dias</th>
      <th>Renovación</th>
      <th>Libros Max</th>
      <th>Vigencia (años)</th>
      <th>
      </th>
    </tr>
  </thead>
  <tbody align="center">
    <?php if($datos)
           {
            foreach ($datos as $row){
    ?>
      <tr>
        <td><!--<?php if($row->idgrupo!=1){ ?><span class="icon-pencil"><?php }?></span>        
        </td>-->
        <td><?php echo $row->namegrupo;?></td>
        <td><?php echo $row->montomulta;?></td>
        <td><?php echo $row->diasentrega;?></td>
          <td><?php echo $row->renovacion;?></td>
          <td><?php echo $row->cantlibros;?></td>
          <td><?php echo $row->vigencia;?></td> 
        <td ng-click="eliminar(<?php echo $row->idgrupo ?>)"><?php if($row->idgrupo!=1){ ?> <span class="icon-bin"></span><?php }?></td>    
      </tr>
    <?php 
  }
    }; ?>
  </tbody>
</table>
 
          </div> 
      </div> 
    </div>
</div>
<div class="row">
  <div class="container navev">
        <a href="#myModal"data-toggle="modal" class='btn btn-info'>Nuevo</a>
                                             
  </div>
</div>
      <div class="modal fade " id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog" >
                               <div class="modal-content">
                                    <div class="modal-header" > 
                                    Nuevo Grupo                     
                                    </div>
                                    <div class="modal-body container-fluid col-md-12">              
                                        <div class="row">
                                            <div class="col-md-12">
                                               <div class="col-md-6"><input ng-model="nombre" type="text" id="Nombre" placeholder="Nombre"/></div>
                                                <div><input  ng-model="multa" type="text" id="multa" placeholder="Monto Multa ($)" /></div>
                                               <div class="col-md-6"><input ng-model="dias" type="text" id="dias" placeholder="Dias de entrega"/></div>
                                             
                                                <div class="col-md-6"><input ng-model="reno" type="text" id="reno" placeholder="N° de renovaciones"/></div>
                                                <div class="col-md-6"><input  ng-model="cant" type="text" id="canlibros" placeholder="N° de libros por prestámo"/></div>
                                                <div class="col-md-6"><input ng-model="vigencia" type="text" id="vigencia" placeholder="Vigencia (años)"/></div>
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
 <script type='text/javascript' src="<?php echo base_url(); ?>js/jquery.js"></script>
<script type="text/javascript">
var app=angular.module("grupo",[]).filter('capitalize', function() {
    return function(input, all) {
      return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
  });
        app.controller("grupocontroller",function ($scope,$http){
          $scope.lista="os";
          $scope.agregar=function(){

            //alert($scope.nombre,$scope.multa,$scope.dias,$scope.cant,$scope.reno,$scope.vigencia);
            // $("#divbus").attr("class","windows8");
          //$('#btnb').text('...').button("refresh");     
                  $http({
                  url: '<?php echo base_url()?>home/grupos/agregar/json',
                   method: 'POST',
                   data: { nombre:$scope.nombre,multa:$scope.multa,dias:$scope.dias,cant:$scope.cant,reno:$scope.reno,vigencia:$scope.vigencia}     
                  }).success(function(result){
                    alertify.success("Agregado correctamente");
                     window.location.replace('<?php echo base_url(); ?>home/grupos');
                  //$("#divbus").removeAttr("class","windows8");
                  //$('#btnb').text('Buscar').button("refresh");  
                }).
                  error(function(result){
                console.log(result);
                  });
          }
          $scope.eliminar=function(indice){
            alertify.confirm("<p>¿Deseas eliminar?<br><br>", function (e) {
                if (e) {
                     window.location.replace('<?php echo base_url(); ?>home/grupos/delete/'+indice);
                }
            }); 
          }
          
          });
</script>

