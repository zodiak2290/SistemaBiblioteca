            <div id="nuevoem" class="container col-lg-12" ng-app="appBiblio" ng-controller="picontroller" ng-cloak align="center">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3 class="section-subheading text-muted">Prestámo Interno</h3>
                </div>
				
            </div>
			 <div hidden class="encontrado" >
                            <button class="btn btn-warning" ng-click="cambiar()">Cambiar de usuario</button>
                        </div>  
            <?php 
        			 $dateInLocal=date("d-m-Y H:i:s");
        			 $dateInLocal=str_replace(" ", "T", $dateInLocal);
            ?>  
              <div class="row">
                <div class="col-lg-8">           
                      <div class="col-md-6"></div>
                      <div class="col-md-12 text-center" >
                        <div class="form-group buscando col-md-6">
                            <label>Cuenta del usuario que solicitó el ejemplar</label>
                              <input ng-model="cuenta" type="number" min='0' class="form-control text-center" placeholder="Cuenta (opcional)" id="nad" name="nad" required >
                              <p class="help-block text-danger"></p>
                            <span >
                                <button class="btn btn-info" ng-click="buscaruser()">Buscar
                                </button>
                            </span>
                        </div>
                                 
                          <div class="form-group col-md-6">
                            <label>N° de adquisición del ejemplar prestado</label>
                              <input ng-keyup="enter($event)" ng-model="nadqui" type="number" min='1' class="form-control text-center" placeholder="N° de Adquisición *" id="nad" name="nad" required >
								
							 <p class="help-block text-danger">Presione Enter para agregar</p>
                          </div>   
                      </div>
                 <div class="col-md-12">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>         
                                        <th >N° Adquisición</th>
                                        <th >Titulo  
                                        </th>
                                        <th>                      
                                            <div class="col-md-6 text-center" > 
                                              <button disabled="" id="registro" ng-click="confirmarprestamo()" type="submit" class="btn btn-info">Registrar</button>
                                            </div>
                                        </th>
                                      </tr>
                                </thead>
                                <tbody><label class="encontrado">Nombre del usaurio asociado a la cuenta: {{usuario.pnombre|capitalize}}</label>
                                    <tr ng-repeat="ej in ejemplares">              
                                    <td>{{ej.nadqui}}</td>
                                    <td >{{ej.titulo|capitalize}}</td>
                                    <td ng-click="eliminar($index)"><span class="icon-cross"></span>
                                    </td>  
                                  </tr>
                                </tbody>
                            </table>  
                        </div>
                            <!-- /.list-group -->
                    </div>    
                </div>
                  </div>
                </div>
            </div>
<script type="text/javascript">
angularRoutingApp.controller("picontroller",function ($scope,miService,confighttpFactori){
    $scope.ejemplares=[];$scope.cuenta=""
    $scope.cambiar=function(){;
        $scope.cuenta="";
        $scope.nadqui="";
        $scope.ejemplares=[];
        $(".encontrado").hide();
        $(".buscando").show();
    }
    function llamadaalservidor(url,datos,metodo){
      confighttpFactori.setData('<?php echo base_url()?>'+url,'POST',datos);
      miService.getAll()
      .then(function (result) {
            if(result.mensaje){
                alertify.success(result.mensaje);
            }
          if(result.datos){    
                agregar_ejempalr(result.datos);
          }if(result.usuario){
            $(".encontrado").show();
            $(".buscando").hide();
            $scope.usuario=result.usuario[0];
          }
        }).catch(function (message) {
        alertify.error("No fue posible conectarse al servidor");
      });
    }
    $scope.buscaruser=function(){
      url="home/prestamointerno/usuario";
      data={cuenta:$scope.cuenta};
      $scope.usuario=[];
      alertify.log("Espere un momento..");
      llamadaalservidor(url,data,"divbususer","user");
    }
    function agregar_ejempalr(datos){
        if(datos.titulo){
            existe=false;
            $scope.ejemplares.forEach(function(elem){
                if(elem.nadqui==datos.nadqui){
                    existe=true;
                }
            });
            if(!existe){
                    $scope.ejemplares.push({nadqui:datos.nadqui,titulo:datos.titulo});
                    $("#registro").removeAttr("disabled");
            }
            }
    }
    $scope.enter=function(evento){
      if(evento.keyCode==13){
            $scope.cargalibros();
       }
    }
    $scope.cargalibros=function(){ 
        if($scope.ejemplares.length<5){    
            if($scope.nadqui){
                llamadaalservidor(
                      'home/libros/json',
                      {nad:$scope.nadqui},
                      'POST'
                  );
              }else{
                alertify.error('Ingrese número de Adquisición');
              }
             }else{
                alertify.error("Solo es posible registrar 5 ejemplares, a la vez");
            }   
        }
        $scope.eliminar=function(indice){
            $scope.ejemplares.splice(indice,1);
            if($scope.ejemplares.length==0){
                $("#registro").attr("disabled",true);
            }
        }
    $scope.confirmarprestamo=function(){
      if($scope.cuenta<10){
        cuenta="000"+$scope.cuenta;
      }else if($scope.cuenta>9&&$scope.cuenta<100){
        cuenta="00"+$scope.cuenta;
      }else if($scope.cuenta>100&&$scope.cuenta<1000){
        cuenta="000"+$scope.cuenta;
      }
      mensaje=$scope.cuenta>0?"<p>¿Registrar ejemplares a la cuenta:<br>"+cuenta+"?<br>":"¿Registrar préstamo sin asociarlo a ninguna cuenta?";
      alertify.confirm(mensaje, function (e,int) {
          if (e) {
            $scope.agregapi();
          }
        }); 
    }
		$scope.nadqui="";
		$scope.agregapi=function(){
      llamadaalservidor('home/prestamo/agregar/json',
                      { ejemplares:$scope.ejemplares,cuenta:$scope.cuenta},'POST'); 
 			      $scope.cambiar();
                  $("#registro").attr("disabled",true);
              }
        });
</script>
