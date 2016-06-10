<?php $contador=0; foreach ($datos as $row){
    $img=base_url()."images/".$row->imagen;
    ?>
    <?php 
            $titulo=$row->nombre;
            $descripcion=$row->descripcion;
            $imagen=$row->imagen;
            $id=$row->idactividad;
            $para=$row->dirigidoa;
       }     
 ?> 
<div id="page-wrapper"  hidden>
    <div class="row">
        <div class="col-lg-8">
          
        <a href="<?php echo base_url(); ?>home/actividad/ver/<?php echo $id; ?>" ><button type="button" class="btn btn-defautl" ><i class="fa fa-times"></i><span class="icon-undo2"></span></button></a>             
        </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row" ng-app="actividades"  ng-controller="actividadcontroller" >
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Actividad
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class=" col-lg-10" >
                                <div class="form-group ">
                                      Nombre: <?php echo $titulo ?>
                                </div>
                                <div class="form-group ">
                                    Descripción: 
                                    <p align="justify"><?php echo $descripcion ?></p>
                                </div>
                                <div class="form-group ">
                                    <?php if(strcmp($para,"hd")==0){
                                    $var="Niños";
                                    }elseif (strcmp($para,"et")==0) {
                                        $var="Jovenes";
                                    }elseif (strcmp($para,"md")==0) {
                                        $var="Adultos";
                                    }elseif (strcmp($para,"te")==0) {
                                        $var="Todas las edades";
                                    }  ?>
                                  Dirigido a:  <?php echo $var; ?>
                                </div>
                            </div>
                            <div class="col-lg-6" style="margin-top:3px;">
                                <img width="50%" src="<?php echo $img ?>">
                            </div>               
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-default" ng-if="listafechas.length>0">
                        <div class="panel-heading">
                                         <div id="divbus" class="pull-left">
                                                    <div class="wBall" id="wBall_1">
                                                    <div class="wInnerBall">
                                                    </div>
                                                    </div>
                                                    <div class="wBall" id="wBall_2">
                                                    <div class="wInnerBall">
                                                    </div>
                                                    </div>
                                                    <div class="wBall" id="wBall_3">
                                                    <div class="wInnerBall">
                                                    </div>
                                                    </div>
                                                    <div class="wBall" id="wBall_4">
                                                    <div class="wInnerBall">
                                                    </div>
                                                    </div>
                                                    <div class="wBall" id="wBall_5">
                                                    <div class="wInnerBall">
                                                    </div>
                                                    </div>
                                            </div>
                                <h5 align="right">Fechas</h5>
                        </div>

                        <!-- /.panel-heading -->
                            <?php $this->load->view('evento/partial_tabla_list_fechas'); ?>         
                            <!-- /.table -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
               <?php $this->load->view('evento/partial_add_fechas'); ?>

                <!-- /.col-lg-4 -->

            </div>
<script type="text/javascript">
    var app=angular.module("actividades",[]).filter('capitalize', function() {
    return function(input, all) {
      return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
  });
        app.controller("actividadcontroller",function ($scope,$http){
            $scope.idac="<?php echo $id ?>";
          $scope.listafechas=[];
            $scope.agregardatos=function(indice){
                   dia=$('#dia').val();
                   var existe=false;
                   $scope.listafechas.forEach(function(elem,posicion){
                        if(Date.parse(elem.dia)==Date.parse(dia)){
                                existe=true;
                            }
                        });  
                 if(existeFecha(dia)) {
                    if(!existe){
                         $scope.listafechas.push({dia:dia});
      alertify.success("Se agrego "+dia+" a la lista");
                     }else{
                        alertify.error("La fecha ya esta en la lista");
                     }
               }else{
                   alertify.error("Fecha no válida");
                   $('#dia').val('');
                }
                console.log($scope.listafechas);

            }    
            function existeFecha(fecha){
              var fechaf = fecha.split("-");
              var day = fechaf[0];
              var month = fechaf[1];
              var year = fechaf[2];
              var date = new Date(year,month,'0');
              if((day-0)>(date.getDate()-0)){
                    return true;
              }else{
              return false;
            }
        }     
            $scope.eliminar=function(indice){
                $scope.listafechas.splice(indice,1);
            }
                $scope.subir=function(){
                    $("#divfechas").fadeIn(100);
                    $("#fechas").hide(100);
                 $http({
                            url:'<?php echo base_url()?>home/actividad/addgafechas',
                            method:'POST',
                            data:{id:$scope.idac,datos:$scope.listafechas}
                        }).success(function(result){ 
                            if(result.insert){
                                alertify.success(result.insert);
                            }else{
                                alertify.error(result.error);
                            }
                            
                            $scope.resultados= result;
                            if(result.inserta==true){
                                $scope.listafechas=[];
                            }
                            console.log(result);        
                        }).error(function(result){
                             console.log(result);
                      });
         $("#divfechas").hide(100);
                    $("#fechas").show(100);
            }
            $scope.editar=function(indice){
             $('#dia').val($scope.listafechas[indice].dia);
             $('#lugar').val($scope.listafechas[indice].lugar);
             $('#inicio').val($scope.listafechas[indice].inicio);   
             $('#fin').val($scope.listafechas[indice].fin);    
            }
        }); 
       $( document ).ready(function() {
            $("#page-wrapper").show();
            $('#fechas').fadeIn();
        });
            
</script>
        </div>
