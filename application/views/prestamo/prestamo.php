<div ng-app="appBiblio" ng-controller="prestamocontroller" ng-cloak>
    <div class="row">
        <div class="col-lg-12">
            <?php $this->load->view('prestamo/menuprestamo');?> 
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <md-content layout-padding>
        <fieldset class="standard">
            <legend>Realizar préstamo</legend>
            <div layout="column" layout-wrap layout-gt-sm="row">
                <div flex-xs flex="25">
                    <?php $this->load->view('prestamo/busquedaejemplares');?> 
                </div>
                <div flex-xs flex="25">
                    <?php $this->load->view('prestamo/renovaciones');?> 
                </div>
                <div flex-xs flex="50">
                        <div class="bottom-sheet-demo inset" layout="row" layout-sm="column" layout-align="space-around" >
                            <md-button id="btnprestar" ng-click="prestar()"  flex="50" class="md-primary md-raised btn btn-success" >
                                Realizar Préstamo
                            </md-button>
                        </div> 
                     <md-whiteframe  class="md-whiteframe-10dp" flex-sm="50" flex-gt-sm="90" flex-gt-md="90" layout layout-align="center center">
                         <md-content ng-if="listapresta.length>0" layout-padding>
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                   <tr>         
                                    <th >Título</th>
                                    <th >Renovación</th>
                                    <th >Fecha Entrega</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody ng-repeat="n in listapresta">
                                    <tr ng-click="presionado($index)"  id="fila{{$index}}" class="active {{n.alert}}" style="cursor: pointer;" >                                                           
                                    <td >{{n.titulo.split("/")[0]|capitalize}}</td>
                                    <td >{{n.contreno}}</td>
                                    <td >{{n.entrega}}</td>
                                    <td ><span ng-if="n.enproceso==true" class="icon-cross" ng-click="eliminar($index)"></span></td>    
                                  </tr>
                                </tbody>
                                <tbody ng-if="listapresta.length<=0">
                                 <tr>
                                    <td colspan="8" align="center">
                                      No tiene préstamos registrados
                                  </td>
                                 </tr> 
                                </tbody>
                            </table>
                        </md-content>
                    </md-whiteframe>                    
                </div>
            </div>    
        </fieldset>
    </md-content>
                <div class="row">
                   <div class="modal fade "  id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" >
                    <div class="modal-dialog" >
                       <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body container-fluid col-md-12">
                                    <p>{{mensajedevol.split(',')[0]}}</p>
                                    <p>{{mensajedevol.split(',')[1]}}</p>
                                    <p>{{mensajedevol.split(',')[2]}}</p>
                                    <p>{{mensajedevol.split(',')[3]}}</p>
                                <button class="btn btn-info" data-dismiss="modal" ng-click="devolucion(true)">Multar</button> 
                                <button ng-click="devolucion(false)" data-dismiss="modal" class="btn btn-success">Condonar</button>                                        
                            </div> 
                            <div class="container-fluid">
                                <div class="row">
                                        
                                </div>                                        
                            </div>       
                        </div>
                    </div>
                </div>
            </div>
</div>
<script type="text/javascript">
angularRoutingApp.controller("prestamocontroller",function ($scope,miService,confighttpFactori){
$scope.nadquidevol="";//Numero de adquisicion a devolver
    $scope.idprestamo="";//??
    $scope.presionado=function(indice){
        if(!$scope.listapresta[indice].enproceso){   
            $("#accionlibro").show(100);
            $scope.acionlibro=$scope.listapresta[indice];
            $scope.indice=indice;
            $scope.paraentregarel=$scope.listapresta[indice].entrega;
        }  
    }
    $scope.saldar=function(indice){
        idmulta=$scope.multas[indice].idmulta;
        monto=$scope.multas[indice].total;
        url="home/devolucion/saldarmulta";
        data={multa:idmulta,mont:monto};
        eliminarmulta(indice);
        buscarusuario(url,data,"","saldar");  
    }
    eliminarmulta=function(indice){
        $scope.multas.splice(indice,1);
    }
    $scope.renovar=function(nadqui){
        console.log($scope.listapresta);
        if($scope.listapresta[$scope.indice].reservado==false){
            if($scope.listapresta[$scope.indice].renopermi>0){
                var porcentaje=$scope.listapresta[$scope.indice].porcentaje;
                if(porcentaje>=85&&porcentaje<=100){
                    url="home/prestamo/renovar";
                    data={iddetalle:$scope.listapresta[$scope.indice].idpre,cuenta:$scope.cuenta};
                    $("#accionlibro").hide(800);   
                    buscarusuario(url,data,"","dev");
                }else if($scope.listapresta[$scope.indice].porcentaje<85){
                    alertify.success("Aún no es posible realizar una renovación del préstamo");   
                }else if($scope.listapresta[$scope.indice].porcentaje>100){
                    console.log($scope.listapresta[$scope.indice].porcentaje);
                    alertify.error("Prestámo vencido");
                }
            }else{
                alertify.error("Límite de renovaciones alcanzado");    
            }
        }else{
            alertify.error("Este ejemplar ha sido reservado por otro usuario,no es posible renovar");
        }
    }           
    $scope.devolver=function(nadqui){
        $scope.nadquidevol=$scope.listapresta[$scope.indice].idpre;
        multa=false;
        if($scope.listapresta[$scope.indice].vencido==true){
            $scope.mensajedevol="El usuario se ha hecho acreedor a una multa.,"+
                                "Prestámo: "+$scope.listapresta[$scope.indice].fechaprestamo+
                                ",Entrega: " + $scope.listapresta[$scope.indice].entrega +",Dias retraso:"+$scope.listapresta[$scope.indice].diasretraso;
            $('#myModal').modal('toggle');
        }else{
            $scope.devolucion(multa);
        }
    }
    $scope.devolucion=function(mult){
        url="home/devolucion";
        data={nadqui:$scope.nadquidevol,multar:mult};
        $scope.datosdev=[];
        $scope.eliminar($scope.indice);
        buscarusuario(url,data,"divdev","dev");
        $("#accionlibro").hide(800);      
    }
    $scope.buscaruser=function(){
        url="home/prestamo/usuario";
        data={cuenta:$scope.cuenta};
        $scope.datosuser=[];
        alertify.log("Espere un momento..");
        buscarusuario(url,data,"divbususer","user");
    }                       
     $scope.nuevouser=function(){
        $scope.cuenta="";$scope.mensaje="";$scope.mensajepres="";
        $scope.mensajemulta="";
        $scope.listapresta=[];$scope.datosuser=[];
        ocultarelem({"#buslibro":100,"#nu":100,"#limite":100,"#accionlibro":100});
        mostrarelem({"#btnb":1,"#inpuser":1});
        $scope.bloqueo="";
        $scope.mensajepres="";    
     }
     function ocultarelem(elementos){
        $.each(elementos,function(iddelm, value ){
            $(iddelm).hide(value);
        });
     }
    function mostrarelem(elementos){
        $.each(elementos,function(iddelm, value ){
            $(iddelm).show(value);
        });  
    }
    recibedatosuser=function(result){
        console.log(result.usuario);
        if(result['usuario']){
            if(result.bloqueado){
                $scope.mensajepres="El usiario ha sido bloqueado."; 
            }else if(result.multas){
                $scope.mensajemulta="El usuario ha generado multas";
                $("#buslibro").hide(); 
            }
            $scope.datosuser=result['usuario'];
            ocultarelem({"#btnb":1,"#inpuser":1});
        }
        $scope.mensaje=result['mensaje'];               
        $scope.multas=result['multas'];
        $scope.bloqueado=result['bloqueado'];
        if($scope.datosuser.length>0&&$scope.bloqueado==false&&$scope.multas==false){
            $("#buslibro").show(100);
            $("#divuser").hide(100);
        }
        if($scope.datosuser[0]){
            $("#nu").show(100);
            limite();
        }
        $scope.listapresta=result['prestados']?result['prestados']:[];
    } 
    recibedatoslibro=function(result){
        ejemplar=result['ejemplar'];
        if(ejemplar.length>0){
            if(buscarnadquienlista(ejemplar[0]['nadqui'])<1){
                $scope.listapresta.push({nadqui:ejemplar[0]['nadqui'],titulo:ejemplar[0]['titulo'],contreno:0,nadqui:$scope.nadqui,enproceso:true});
                $("#btnprestar").removeAttr('disabled'); 
                if($scope.datosuser[0].cantlibros==$scope.listapresta.length){
                    limitealcanzado();
                }    
            }
        }
        $scope.mensaje=result['mensaje'];   
    }
    buscarnadquienlista=function(nadqui){
        cont=0;
        $scope.listapresta.forEach(function(elem,posicion){
            if(elem.nadqui==nadqui){
                cont=cont+1;
            }
        });
        return cont;
    }
    limitealcanzado=function(){
        $("#buslibro").hide(100);
        $("#limite").show(100);
    }
    limite=function(){
        prestados=$scope.datosuser[0].cantlibros;       
        if($scope.total>=prestados){
            limitealcanzado();    
        }
    }
    $scope.permiripres=function(){
            $("#buslibro").show(100);
            $("#limite").hide(100);
    }
    buscarusuario=function(urls,datos,elemntocarrga,tipo){
        $("#"+elemntocarrga).attr("class","windows8");
        confighttpFactori.setData('<?php echo base_url()?>'+urls,'POST',datos);
        miService.getAll().then(function (result){
            console.log(result);
            $scope.total=result.total;
                if(tipo=="user"){
                    recibedatosuser(result);
                }else if(tipo=="ejemplar"){
                    recibedatoslibro(result);
                    $scope.nadqui="";
                    limite();
                }else if(tipo=="dev"){
                    if(result.mensaje){
                            if(result.alertify){
                                alertify.success(result.mensaje);
                            }else{
                                alertify.error(result.mensaje);
                            }
                        }
                    $scope.mensajeacionlibro=result;
                    $scope.buscaruser();
                }else if(tipo=="prestamo"){
                    $("#btnprestar").attr('disabled', 'disabled'); 
                    alertify.log(result.mensaje);
                }else if(tipo=="saldar"){
                    alertify.log(result.mensaje);
                }
        }).catch(function (message) {
            alertify.error("No fue posible conectarse al servidor");
        });
        $("#"+elemntocarrga).removeAttr("class","windows8");
    }
    $scope.buscarejem=function(){
        url="home/ejemplares/nadqui";
        dat={nadqui:$scope.nadqui,cuenta:$scope.cuenta};
        buscarusuario(url,dat,"","ejemplar");
    }
    $scope.prestar=function(){
        var nuevoejemplar=0;
        $scope.listapresta.forEach(function(elem,posicion){
            nuevoejemplar=(!elem.fechaprestamo)?nuevoejemplar+1:nuevoejemplar;
        });      
        if(nuevoejemplar>0){
            url="home/realizarprestamo";
            dat={ejemplares:$scope.listapresta,cuenta:$scope.cuenta};
            buscarusuario(url,dat,"","prestamo"); 
            $("pres").show();  
            $scope.buscaruser();
        }else{
            alertify.success("No hay ejemplar para préstamo");
        }
    }
    $scope.eliminar=function(indice){
        $scope.listapresta.splice(indice,1);
        $scope.total=$scope.total-1;
        $("#btnprestar").attr('disabled', 'disabled'); 
        $scope.permiripres();
    }
    $scope.bloquear=function(s){
        alertify.confirm("<p>¿Desbloquear a este usuario?<br>"+s+"<br>", function (e) {
          if (e) {
            window.location.replace('<?php echo base_url(); ?>home/usuario/desbloqueo/'+s);
          }
          }); 
    }
    });
$( document ).ready(function() {
    $('#nuevo').attr("class","active");
    $("#buscar").fadeIn();
    $("#nu").hide();
    $("#btnprestar").attr('disabled', 'disabled'); 
    $('.dropdown-toggle').dropdown();
});   
</script>
<style type="text/css">
    .md-whiteframe-10dp, .md-whiteframe-z4{
        border-radius: 8px;
    }
</style>