<div ng-app="appBiblio" ng-controller="ejemplarcontroller" ng-cloak>
  <md-content layout-padding>
    <fieldset class="standard">
      <div layout="column" layout-wrap layout-gt-sm="row">
        <div flex-xs flex="100">
          <div class="col-lg-8 contaier" style="margin-left:10%">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <i class="fa fa-bar-chart-o fa-fw"></i> Buscar ejemplar
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                      <md-input-container>
                            <label>N° Adquisición</label>
                              <input ng-model="nadq" type="number" min="1" >
                      </md-input-container>
                      <md-button  id="btnb" ng-click="cargalibros()" class="md-raised md-primary pull-right">Buscar</md-button>
                  </div>
                  <!-- /.panel-body -->   
              </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                              <h5 align="center">Resultados</h5>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >
                            <div class="row" style="margin:3px">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                            </thead>
                                            <tbody ng-if="list_data.nadqui" class="small" style="font-size:15px">
                                            <tr>
                                                <td>Ficha:<span  class="pull-center text-muted" ><em>{{list_data.idficha}}</em></span></td>
                                                <td colspan="2" align="center"><strong>Datos del ejemplar</strong></td>
                                                <td colspan="2">
                                                <section layout="row" layout-sm="column" layout-align="center center" layout-wrap> 
                                                  <md-button ng-if="list_data.status!=3 && list_data.status!=4"  ng-click="eliminar()" class="md-fab md-danger" aria-label="Eliminar">
                                                    <md-icon class="icon-bin"></md-icon>
                                                  </md-button>
                                                  <md-button ng-click="editar(list_data)" class="md-fab md-primary" aria-label="Editar">
                                                    <md-icon class="icon-pencil"></md-icon>
                                                  </md-button>
                                                  </section>         
                                                </td>
                                            </tr>
                                              <tr >                                                           
                                                <td colspan="3">
                                                Titulo:
                                                    <span  class="pull-center text-muted"><em>{{list_data.titulo|capitalize}}</em></span>
                                                </td>
                                                <td>
                                                Clasificación:
                                                    <span  class="pull-center text-muted"><em>{{list_data.clasificacion}}</em></span>
                                                </td>
                                                <td>
                                                Autor:
                                                <span  class="pull-center text-muted"><em>{{list_data.nameautor|capitalize}}</em></span>
                                                </td> 
                                              </tr>
                                              <tr>
                                                  <td colspan="3" >ISBN:
                                                    <span  class="pull-center text-muted"><em>{{list_data.isbn}}</em></span>
                                                  </td>
                                                  <td>
                                                    Idioma:
                                                   <span  class="pull-center text-muted"><em>{{list_data.ci}}</em></span>
                                                  </td>
                                                  <td>
                                                  Edición:
                                                  <span  class="pull-center text-muted"><em>{{list_data.edicion}}</em></span>
                                                  </td>
                                              </tr>
                                              <tr>
                                                <td colspan="2">
                                                Editorial:
                                                <span  class="pull-center text-muted"><em>{{list_data.edit|capitalize}}</em></span>
                                                </td>
                                                <td colspan="2">
                                                Descr. Fisica:
                                                <span  class="pull-center text-muted"><em>{{list_data.des}}</em></span>
                                                </td>
                                                <td colspan="2">
                                                Serie:
                                                <span  class="pull-center text-muted"><em>{{list_data.asiento|capitalize}}</em></span>
                                                </td>              
                                              </tr>
                                              <tr>
                                                <td colspan="2">
                                                Nota General:
                                                <span  class="pull-center text-muted"><em>{{list_data.nota|capitalize}}</em></span>
                                                </td>
                                                <td>
                                                Contenido:
                                                    <span  class="pull-center text-muted"><em>{{list_data.cont}}</em></span>
                                                </td>
                                                <td colspan="2">
                                                
                                                <span  class="pull-center text-muted"><em>{{list_data.tema}}</em></span>
                                                </td>              
                                              </tr>
                                              <tr>
                                                <td colspan="4">
                                                Materia:
                                                    <span  class="pull-center text-muted"><em>{{list_data.stema|capitalize}}</em></span>
                                                </td>
                                                <td colspan="2">{{list_data.nombrec}}</td>
                                              </tr>
                                              <tr>
                                                <td colspan="2">
                                                Ejemplar:
                                                    <span  class="pull-center text-muted"><em>{{list_data.ejemplar}}</em></span>
                                                </td>
                                                <td>
                                                Tomo:
                                                <span  class="pull-center text-muted"><em>{{list_data.tomo}}</em></span>
                                                </td>
                                                <td colspan="2">
                                                Volumen:
                                                <span  class="pull-center text-muted"><em>{{list_data.volumen}}</em></span>
                                                </td>
                                              </tr>
                                              <tr >
                                              <td>
                                                Agregado el::
                                                <span  class="pull-center text-muted"><em>{{list_data.add}}</em></span>
                                                  
                                              </td>
                                                <td ng-if="list_data.status==1">
                                                   Estado:
                                                  <span  class="pull-center text-muted"><em>Disponible</em></span></td>
                                                <td ng-if="list_data.status==2"> 
                                                   Estado:
                                                  <span  class="pull-center text-muted"><em>En reparación</em></span></td>
                                                <td ng-if="list_data.status==3">
                                                    Estado:
                                                   <span  class="pull-center text-muted"><em>Prestámo</em></span></td>
                                                <td ng-if="list_data.status==4">
                                                    Estado:
                                                   <span  class="pull-center text-muted"><em>Reservado</em></span></td>
                                                <td ng-if="list_data.status==5">
                                                    Estado:
                                                   <span  class="pull-center text-muted"><em>No disponible</em></span></td>
                                                <td ng-if="list_data.status==6">
                                                    Estado:
                                                   <span  class="pull-center text-muted"><em>Dado de baja</em></span>
                                                </td>
                                                    <td colspan="3" ng-if="list_data.numej>1">Hay {{list_data.numej}} libros mas como este,N° adquisición: {{list_data.ejemplares}}</td>
                                                    <td colspan="3" ng-if="list_data.numej==1">Hay {{list_data.numej}} libro mas como este,N° adquisición: {{list_data.ejemplares}}</td>
                                                    <td  colspan="3" ng-if="list_data.numej<1">Único ejemplar</td>
                                              </tr>
                                              <tr><td colspan="5" align="center"><strong>Prestámos internos</strong></td></tr>
                                              <tr>
                                              <td colspan="3">Utilizado por ultima vez: {{list_data.uso}}</td>
                                              <td colspan="2">Total:{{list_data.totalinter}}</td>
                                              </tr>
                                              <tr><td colspan="5" align="center"><strong>Prestámos a domicilio</strong></td></tr>
                                              <tr>
                                              <td colspan="3">Utilizado por última vez: {{list_data.usod}}</td>
                                              <td colspan="2">Total:{{list_data.totalinterd}} 
                                              </td>
                                              </tr>
                                              <tr>
                                                <td colspan="6" align="center"><strong>Estadísticas</strong></td>
                                              </tr>
                                              <tr>
                                                <td colspan="5" align="center"><a ng-click="enviar('<?php echo base_url()?>home/prestamo/nadqui','morris-donut-chart')" data-toggle="modal" href="#myModal" ><span class=" icon-stats-dots"  ></span></a></td>
                                              </tr>         
                                              <tr>                                                 
                                                  <td colspan="2" ng-if="list_data.status!=3 && list_data.status!=4">Cambiar estado:
                                                    <span ng-if="list_data.status!=1" class="pull-center text-muted"><em>Disponible</em></span>
                                                    <span ng-if="list_data.status!=2" class="pull-center text-muted"><em>Reparación</em></span>
                                                    <md-checkbox  ng-model="status" aria-label="statu"></md-checkbox>
                                                    <md-button ng-click="estado(status)" class="md-accent md-raised md-hue-1">Aceptar</md-button>
                                                  </td>
                                                  <td colspan="3">Mostrar online
                                                    <span ng-if="list_data.bloq==false" class="pull-center text-muted"><em>No mostrar</em></span>
                                                    <span ng-if="list_data.bloq!=false" class="pull-center text-muted"><em>Mostrar</em></span>
                                                    <md-checkbox  ng-model="mostrarche" aria-label="mostrarc"></md-checkbox>
                                                    <md-button ng-click="bloqdes(mostrarche)" class="md-accent md-raised md-hue-1">Aceptar</md-button>  
                                                    <div class="alert alert-info" ng-if="bloq.length>0">
                                                      {{bloq}}
                                                    </div>
                                                  </td>                                                
                                              </tr>
                                              <tr>
                                                <section layout="row" layout-sm="column" layout-align="center center" layout-wrap> 
                                                  <td colspan="5" style="font-size:12px;">
                                                    <span class="danger icon-barcode"></span>
                                                    <md-button ng-click="addcb()" class="md-accent md-raised md-hue-1">Generar código de barras</md-button>
                                                    <div id="bc"></div>
                                                  </td>
                                                </section>
                                              </tr>
                                            </tbody>
                                            <tbody ng-if="list_data.length<=0">
                                             <tr>
                                                <td colspan="8" align="center">
                                                  No hay resultados
                                              </td>
                                             </tr> 
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                            
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>       
                          <md-sidenav class="md-sidenav-right md-whiteframe-z2" md-component-id="right">
                            <md-toolbar class="md-theme-light">
                              <h1 class="md-toolbar-tools">Descartar</h1>
                            </md-toolbar>
                            <md-content  layout-padding>
                              <form name="form">
                                <md-input-container>
                                  <label for="testInput">Criterio</label>
                                  <input type="text" id="obser" ng-model="criterio" md-autofocus>
                                </md-input-container>
                                <md-input-container>
                                  <label for="testInput">Observaciones</label>
                                  <input type="text" id="obser" ng-model="observaciones" md-maxlength="70">
                                  <div>Observaciones</div>       
                                </md-input-container>
                              </form>
                              <md-button ng-disabled="!form.$valid" class="md-raised md-warn" ng-click="baja('t')">Eliminar</md-button>
                              <md-button class="md-raised" ng-click="close()">Cancelar</md-button>
                            </md-content>
                          </md-sidenav>   
            </div>
        </div>
      </div>
    </fieldset>
  </md-content>
      <div  ng-click="enviar('<?php echo base_url()?>home/prestamo/nadqui','morris-donut-chart')" class="modal fade " id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">   
        <div class="row modal-content">
          <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>  
                          <strong>Préstamos</strong>
              </div>
          <div class="row">                                          
                <div  class="col-sm-12 text-center" style="margin:8px;">    
                    <div id="morris-donut-chart"></div>           
                </div>
          </div> 
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
angularRoutingApp.controller("ejemplarcontroller",function ($scope,miService,confighttpFactori,$mdSidenav){
   $scope.titulo="";$scope.autor="";$scope.isbn="";$scope.limitar=10;$scope.nadq=""; 
   $scope.close = function () {
      $mdSidenav('right').close()
        .then(function () {
          console.log("close RIGHT is done");
        });
    };
   $scope.enviar=function(url,elemento){
      document.getElementById(elemento).innerHTML='';
      confighttpFactori.setData(url,'POST',{nadqui:$scope.list_data.nadqui});      
      miService.getAll()
      .then(function (result) {
        recibirdatos(result.datos,elemento);
      }).catch(function (message) {
        alertify.error("No fue posible conectarse al servidor");
      });
  }
  recibirdatos =function(result,elemento){
    datos=[];
    for(attr in result){
      datos.push({y:attr,a:Number(result[attr]['I']),b:Number(result[attr]['E'])});
    }
    graficar(elemento,datos,'y',['a', 'b'],['Internos', 'Externos'],'auto',true,2);    
  }
  graficar=function(elemento,data,xkey,ykeys,labels,hidehover,ajuste,sizepoitn,tipografi){   
    config={element:elemento,data:data,xkey:xkey,ykeys:ykeys,labels:labels,pointSize:sizepoitn,hideHover:hidehover,resize:ajuste
        ,lineColors: ["blue", "green"]};  
            Morris.Line(config);
  }
  function llamadaalservidor(url,datos,metodo,idelem){
    confighttpFactori.setData(url,metodo,datos);
      miService.getAll()
      .then(function (result) {
        console.log(result);
            alertify.success(result.mensaje);
            if(result.datos){
              $scope.list_data= result['datos'];
            }
        }).catch(function (message) {
          alertify.error("No fue posible conectarse al servidor");
      });
  }
  $scope.mostrarche=false;         
 $scope.bloqdes=function(mostrar){
  if(mostrar){
       llamadaalservidor('<?php echo base_url()?>home/ejemplar/bloquear',
                          {nad:$scope.nadq},
                          'POST'
        );
    }
 }
  $scope.cargalibros=function(){
    if($scope.nadq){
       document.getElementById('morris-donut-chart').innerHTML='';
        $("#bc").hide();
        $("#divbus").attr("class","windows8");
        $('#btnb').text('...');     
        llamadaalservidor('<?php echo base_url()?>home/libros/json',
              {nad:$scope.nadq},
              'POST'
        );
        $("#divbus").removeAttr("class","windows8");
        $('#btnb').text('Buscar');
      }else{
        alertify.error("Ingrese número de adquisición");
      }   
  } 
  $scope.estado=function(status){
      if(status){
         llamadaalservidor('<?php echo base_url()?>home/ejemplar/estado',
            {nad:$scope.nadq},
            'POST'
          );
      }
  }  
  $scope.criterio="";
            $scope.observaciones="";
  $scope.eliminar=function(){
          alertify.confirm("<strong style='color:red'>Advertencia:</strong>¿Esta seguro de eliminar este ejemplar?.", function (e) {
          if (e) {
            
              $scope.toggleRight();
                      $scope.isOpenRight();
          }
      });
  }
  $scope.baja=function(t){
      baja=t;
      llamadaalservidor('<?php echo base_url()?>home/libros/baja',
          {nad:$scope.list_data.nadqui,baja:baja,criterio:$scope.criterio,obs:$scope.observaciones},
          'POST'
      );
      $scope.close();
      $scope.cargalibros();
  }
  $scope.addcb =function(){
    alertify.log("Espere un momento...")
    llamadaalservidor('<?php echo base_url()?>home/ejemplar/barcode',
        {nad:$scope.list_data.nadqui},
        'POST'
      );
      $('#bc').show(100);
  }
  $scope.editar=function(libro){
    alertify.confirm("<p>¿Desea editar este libro?<br>"+libro.titulo+"<br>", function (e) {
        if (e) {
            window.localStorage.setItem("idficha",libro.idficha); 
            window.location = "<?php echo base_url(); ?>home/libros";
        }
      }); 
      return false               
    }
    $scope.toggleRight = buildToggler('right');
    $scope.isOpenRight = function(){
            return $mdSidenav('right').isOpen();
    };
          function debounce(func, wait, context) {
              var timer;
              return function debounced() {
                var context = $scope,
                    args = Array.prototype.slice.call(arguments);
                $timeout.cancel(timer);
                timer = $timeout(function() {
                  timer = undefined;
                  func.apply(context, args);
                }, wait || 10);
              };
            }
              function buildDelayedToggler(navID) {
                return debounce(function() {
                  $mdSidenav(navID)
                    .toggle()
                    .then(function () {
                      console.log("toggle " + navID + " is done");
                    });
                }, 200);
              }
              function buildToggler(navID) {
                return function() {
                  $mdSidenav(navID)
                    .toggle()
                    .then(function () {
                      console.log("toggle " + navID + " is done");
                    });
                }
              }
});    
</script>