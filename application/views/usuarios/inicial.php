<div ng-app="recomendacion" ng-controller="recomendacionescontroller" ng-cloak>
    <?php $this->load->view('usuarios/menuusuarios');?>
    <md-content layout-padding>
        <fieldset class="standard">
            <legend>Buscar ejemplar</legend>
            <div layout="column" layout-wrap layout-gt-sm="row">
            <div flex-xs flex="100">
                <div class="col-lg-13 alert alert-warning" ng-if="retraso==true">
                    <p align="justify">
                      <strong>Retraso ejemplar:</strong>
                        <em>{{n.titulo|capitalize}}</em>
                      Mucho le agreadeceremos devuelva a la mayor brevedad el
                      material bibliográfico que la biblioteca le concedío en 
                      préstamo a fin de que otras personas tengan la oportunidad 
                      de utilizarlo.
                      Asimismo, le recordamos que la biblioteca posee una gran 
                      variedad de libros que puede usted solicitar en el momento 
                      de entregar sus préstamos con puntualidad.
                    </p>
                </div>
            </div>
                <div flex-xs flex="40">
                    <?php $this->load->view('usuarios/formulariouser');?>    
                </div>
                <div flex-xs flex="60">
                    <div layout-xs="column" flex-xs="100">
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
                    </div> 
                    <div layout-xs="column" flex-xs="100">
                        <div id="reserva" hidden>
                        <md-whiteframe  class="md-whiteframe-14dp" flex-sm="100" flex-gt-sm="100" flex-gt-md="100" layout layout-align="center center">
                            <md-content class="md-padding">
                            <strong>Reservar</strong>
                                <div   ng-repeat="n in reserva" align="center">
                                    <div class="list-group-item list-group-item-info " id="areservar">
                                        <div id="delet{{n.id}}" class="pull-left" ng-click="eliminarre($index)">
                                            <span class=" text-muted small icon-cross" style="cursor: pointer;"></span>
                                        </div>  
                                        <div id="resrva{{n.id}}" class="pull-right" >
                                            <a  data-toggle="modal" href="#myModal" type="button" ng-click="buscaridficha(n.nadqui,'centro',$index)" >
                                                <span class=" text-muted small icon-eye"></span>
                                            </a>
                                        </div>  
                                        <hr/>  
                                        {{n.id}}
                                        <span ng-if="disponible"><p align="justify">{{disponible}}</p></span>
                                        <hr />  
                                        <i class="fa fa-comment fa-fw"></i> {{n.titulo|capitalize}} 
                                        <br/>
                                        <span  class="pull-center text-muted small" ng-if="n.nad!=' '">N° Adquisición: <em>{{n.nadqui}}</em> 
                                        </span>
                                     </div>
                                    <md-button  id="btnreservar" ng-disabled="permitirreserva"  ng-click="reservar(n.nadqui,true)" flex="20" class="md-primary md-raised" >Reservar</md-button> 
                                    <button class="btn btn-danger"  ng-click="eliminarre($index)">Cancelar</button>
                                </div> 
                            </md-content>
                        </md-whiteframe>
                        </div>
                    </div> 
                    <div layout-xs="column" flex-xs="100">
                        <?php $this->load->view('usuarios/tablaresultados');?>  
                    </div>  
                </div>
            </div>
        </fieldset>
            <md-sidenav class="md-sidenav-right md-whiteframe-z2" md-component-id="right">
      <md-toolbar class="md-theme-light">
        <h1 class="md-toolbar-tools">Sugerencias</h1>
      </md-toolbar>
      <md-content ng-controller="RightCtrl" layout-padding>
            <div class="panel-heading">
                {{sugerencias.length}} sugerencias
                <md-button class="md-raised" id="vaciarlista" ng-click="close()">Continuar buscando</md-button>
            </div>
                        <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="list-group" >
                    <div   ng-repeat="n in sugerencias" >
                        <div class="list-group-item list-group-item-info ">
                            <div id="delet{{n.id}}" class="pull-left" ng-click="eliminar($index)">
                                <span class=" text-muted small icon-cross" style="cursor: pointer;" ></span>
                            </div>  
                            <div id="resrva{{n.id}}" class="pull-right" >
                                <a  data-toggle="modal" href="#myModal" type="button" ng-click="buscaridficha(n.nad,'centro',$index)" >
                                    <span class=" text-muted small icon-eye"></span>
                                </a>
                            </div> 
                            <hr>  
                            <i class="fa fa-comment fa-fw"></i> {{n.titulo|capitalize}}
                            <span  class="pull-center text-muted small" ng-if="n.nad!=' '"><em>{{n.nad}}</em></span>
                            <hr>
                         <button class="btn btn-success"   ng-click="reservar(n.nad,true)">Reservar</button>  
                         </div>    
                    </div>
                </div>
            </div>
      </md-content>
    </md-sidenav>
    </md-content> 
<div class="modal fade " id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>          
                <br>
                <em><h4>Título:{{datoslibro['titulo']|capitalize}}</h4></em>
                <br>  
                <em class="text-muted pull-left">Edicion:{{datoslibro['edicion']}}</em>
                <br>
            </div>
            <div class="modal-body container-fluid col-md-12">                                       
                <div class="row">
                    <div class="col-md-6" >
                        <div ng-click="buscaridficha(datoslibro,'left',indice)" class="pull-left" style="font-size:200%">
                            <div>
                                <span class="icon-arrow-left2"></span>
                            </div>
                        </div>           
                    </div>
                    <div class="col-md-6" >
                        <div ng-click="buscaridficha(datoslibro,'right',indice)" class="pull-right" style="font-size:200%">
                            <div><span class="icon-arrow-right2" ></span></div>  
                        </div>      
                    </div>
                 </div>   
                <div class="row">
                    <div class="col-md-12">
                        <div align="left" style="margin-left:10%" >                                                                                     
                            <em><strong>Autor:</strong>{{datoslibro['nameautor']|capitalize}}</em> 
                            <br>
                            <em><strong>Serie:</strong>{{datoslibro['asiento']|capitalize}} </em> 
                            <br>
                            <em><strong>Clasificación:</strong>{{datoslibro['clasificacion']}}</em> 
                            <br>
                            
                            <em><strong>ISBN:</strong>{{datoslibro['isbn']}}</em>
                            <br>
                            <em><strong>Idioma:</strong>{{datoslibro['ci']|capitalize}}</em>
                            <br>
                            <em><strong>Descr. Física :</strong>{{datoslibro['des']|capitalize}}</em>
                            <br>
                             <em><strong>Contenido:</strong>{{datoslibro['cont']|capitalize}}</em>
                             <br>
                             <em ><strong>Nota General:</strong>{{datoslibro['nota']|capitalize}}</em>
                            <br>
                            <em><strong>Materia:</strong> {{datoslibro['stema']|capitalize}}</em>
                        </div>
                    </div>                 
                </div>
            </div> 
           <div class="modal-footer">
               <div class="container-fluid">
               </div>
           </div>
            <hr/>        
        </div>
    </div>
</div>

</div>
<style type="text/css">
    .navbar{
        background: rgba(112,75,35,1);
        border-radius: 5px;
        position: relative;
    }
    .whiteframedemoBasicClassUsage {
  /* For breakpoint `-xs` */
  /* For breakpoint `-sm` */
  /* For breakpoint `-md` */
  /* For breakpoint `-gt-md` */ }
  .whiteframedemoBasicClassUsage md-whiteframe {
    background: #fff;
    margin: 30px;
    height: 100px; }
  @media (max-width: 599px) {
    .whiteframedemoBasicClassUsage md-whiteframe {
      margin: 7px;
      height: 50px;
      background-color: #c8e4fa; }
    .whiteframedemoBasicClassUsage md-whiteframe > span {
      font-size: 0.4em; } }
  @media (min-width: 600px) and (max-width: 959px) {
    .whiteframedemoBasicClassUsage md-whiteframe {
      margin: 20px;
      height: 75px; }
    .whiteframedemoBasicClassUsage md-whiteframe > span {
      font-size: 0.6em; } }
  @media (min-width: 960px) and (max-width: 1279px) {
    .whiteframedemoBasicClassUsage md-whiteframe {
      margin: 20px;
      height: 90px;
      background-color: #fcddde; }
    .whiteframedemoBasicClassUsage md-whiteframe > span {
      font-size: 0.9em; } }
  @media (min-width: 1280px) {
    .whiteframedemoBasicClassUsage md-whiteframe {
      margin: 25px;
      height: 100px;
      background-color: #F2FCE2; }
    .whiteframedemoBasicClassUsage md-whiteframe > span {
      font-size: 1.0em; } }
</style>
<script type="text/javascript">
 $("#menu-toggle").hide();
var app=angular.module('recomendacion',['ngMaterial','ngMessages']).filter('capitalize', function(){
    return function(input, all){
        return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase()+txt.substr(1).toLowerCase();}):'';
        }
});  
    app.controller('RightCtrl',function($scope, $timeout, $mdSidenav, $log){
        $scope.close = function () {
            $mdSidenav('right').close()
            .then(function () {
                $log.debug("close RIGHT is done");
            });
        }
    });
 
    app.controller('recomendacionescontroller',function($scope,$http,$timeout, $mdSidenav, $log){
    $scope.titulo="";
    $scope.autor="";
    $scope.isbn="";
    $scope.limitar=10;
    $scope.orden=false;
    $scope.campo="titulo";
    $scope.editorial="";
    $scope.buscando=true;
    $scope.permitirreserva=false;
    $scope.toggleLeft = buildDelayedToggler('left');
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
            $log.debug("toggle " + navID + " is done");
          });
      }, 200);
    }
    function buildToggler(navID) {
      return function() {
        $mdSidenav(navID)
          .toggle()
          .then(function () {
            $log.debug("toggle " + navID + " is done");
          });
      }
    }
    $scope.cargalibros=function(){ 
        $("#divbus").attr("class","windows8");
        $scope.buscando=false;    
        $http(
            {
                url: '<?php echo base_url()?>home/libros/json',
                method: 'POST',
                data:{ 
                        titulo:$scope.titulo,autor:$scope.autor,isbn:$scope.isbn,materia:$scope.materia,
                        limitar:$scope.limitar,editorial:$scope.editorial,buscarejemplar:true
                    }     
                }).success(function(result){
                    $scope.list_data=result.ejemplares;
                    if(result.mensaje){
                        alertify.error(result.mensaje);
                        $scope.nodispo=true;
                    }else{
                        $scope.nodispo=false;
                    }
                    //console.log(result); 
                }).
                  error(function(result){
                    console.log(result);
                  }).finally(function(){
                    $("#divbus").removeAttr("class","windows8");
                    $scope.buscando=true;                    
                    navigator.vibrate(1000);
                  });
    }
    $scope.get_datosuser=function(){
        $http({url:'<?php echo base_url()?>home/usuario/prestamosactuales',method:'GET'}).success(function(result){ 
            $scope.datosuser=result.datos;
            $scope.reservas=result.reservas;
            $scope.retraso=false;
            if($scope.datosuser){
                $.each($scope.datosuser,function(key,value){
                    if(value.porcentaje>100){
                        $scope.retraso=true;
                    }
                })
            }
            //console.log(result)
        }).error(function(result){                
        });
    }
    $scope.eliminareserv=function(indice){
        alertify.confirm("<p>¿Seguro deseas cancelar la reserva?<br><br>", function (e) {
            if (e){
                cancelarreserva($scope.reservas[indice].idreserva);
            }
        }); 
        return false
    }
    cancelarreserva=function(id){
        $http({url:'<?php echo base_url()?>home/reserva/delete',method:'POST',data:{idreserva:id}}).success(function(result){ 
            alertify.success(result.mensaje);
            $scope.get_datosuser();
        }).error(function(result){            
             console.log(result);
        });
    }
    $scope.ordenar=function(t){
        $scope.campo=t;
        orden();
    }
    function orden(){
        $scope.orden=$scope.orden==false?true:false;
    }
    $scope.reservar=function(nad,reserva){
        $scope.permitirreserva=false;
        $scope.sugerencias=[];
        alertify.log("Espera un momento...");   
        $http({url: '<?php echo base_url()?>home/ejemplar/reservar',
                method: 'POST',
                data: {nadqui:nad,reservar:reserva}     
        }).success(function(result){
                //console.log(result);
                if(result.sugerencias){
                    $scope.toggleRight();
                    $scope.sugerencias=result.sugerencias;
                }
                if(result.ejemplar){
                    $("#reserva").show();
                    $scope.reserva=result.ejemplar;
                    if(result.prestamo){
                        if(result.prestamo[0].mensaje.length>0){
                             $scope.permitirreserva=true;
                        }
                        $scope.fechaentrega=result.prestamo[0].entregar;
                        $scope.disponible=result.mensaje2+$scope.fechaentrega+" "+result.prestamo[0].mensaje;
                    }else{
                        $scope.disponible=result.mensaje2;
                    }
                }
            $scope.get_datosuser();
            alertify.set({ delay: 20000 });
            alertify.success(result.mensaje);   
        }).
        error(function(result){
            console.log(result);
        });
    }
            $scope.buscaridficha=function(id,direccion,indice){ 
                $scope.indficha=[];
             if(direccion=="centro"){
                $scope.indice=indice;
                $scope.idficha=id;
             }else if(direccion=="left"){
                    if(indice-1>-1){
                        $scope.indice=indice-1;
                    }else{
                        $scope.indice=$scope.list_data.length-1;
                    }
                $scope.idficha=$scope.list_data[indice].nadqu
              } else if(direccion=="right"){
                    if(indice+1<$scope.list_data.length){
                    $scope.indice=indice+1;
                    }else{
                        $scope.indice=0;
                    }
                    $scope.idficha=$scope.list_data[indice].nadqu
              }  
             $http({
                  url: '<?php echo base_url()?>home/libros/json',
                   method: 'POST',
                   data: { nad:$scope.idficha}  
                  }).success(function(result){
                  $scope.datoslibro=result.datos;
                   //console.log(result);
                }).
                  error(function(result){
                    console.log(result);
                  });
        }
        $scope.eliminar=function(indice){
          $scope.sugerencias.splice(indice,1);
        }
        $scope.eliminarre=function(indice){
            $("#reserva").hide();
            $scope.reserva.splice(indice,1);
        }     
    $scope.init=function(){         
        $scope.get_datosuser();
    }
    $scope.init();                
});
</script>