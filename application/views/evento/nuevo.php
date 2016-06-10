<div id="page-wrapper"  >
         <script src="<?php echo base_url()?>js/angular.min.js"></script>
         <script src="<?php echo base_url()?>js/jquery.min.js"></script>
         <script src="<?php echo base_url()?>js/w2ui-fields-1.0.min.js"></script>
            <div class="container">
                <div class="col-lg-8">
                    
                </div>
                <!-- /.col-lg-12 -->
            <div id="conaac" class="container" ng-app="actividades"  ng-controller="actividadcontroller" hidden >
                <div class="col-lg-8 row row-centered">
                    <div class="col-lg-8 container" id="divcarga" hidden>
                            <div  class="pull-left windows8 row"style="width:25%;height: 40px;" >
                                <div class="wBall" id="wBall_1" style="width:35%;height: 50px;">
                                <div class="wInnerBall">
                                </div>
                                </div>
                                <div class="wBall" id="wBall_2" style="width:35%;height: 50px;">
                                <div class="wInnerBall">
                                </div>
                                </div>
                                <div class="wBall" id="wBall_3" style="width:35%;height: 50px;">
                                <div class="wInnerBall">
                                </div>
                                </div>
                                <div class="wBall" id="wBall_4" style="width:35%;height: 50px;">
                                <div class="wInnerBall">
                                </div>
                                </div>
                                <div class="wBall" id="wBall_5" style="width:35%;height: 50px;">
                                <div class="wInnerBall">
                                </div>
                                </div>
                            </div>
                            <div class="row">Espere un momento..</div>
                    </div>
                    <div class="panel panel-default" id="nuevoeento" >
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> A través del siguiente formulario se hará el registro de un(a) nuevo(a) evento/actividad.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body list-group-item">
                            <div class=" col-lg-8  " >
                            <form name="upload" ng-submit="uploadFile()">
                                <div class="form-group"> ¿Cómo se llama la actividad?
                                    <input id="intitulo" type="text"  class="form-control"  placeholder="Titulo *" ng-model="titulo" />
                                    <span id="sptitulo" ><span/>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group" id="cargar">Elige la imagen que servirá como cartel.
                                    <input type="file"  class="form-control"  placeholder="Imagen *"  id="userfile" name="userfile" uploader-model="file" required/>
                                    <p class="help-block text-danger"></p>
                                </div>
                              
                                <div class="form-group">¿En dónde se llevará a cabo?
                                <input id="lugar" type="text" class="form-control" placeholder="Lugar" ng-model="lugar"/>
                                 <SPAN id="splugar"></SPAN>
                                </div>

                                <div class="form-group col-lg-12" align="left" > ¿De qué se trata?
                                    <textarea id="descripcion"  class="form-control" placeholder="Descripción de la actividad" style="max-width:100%" cols="61.7" ng-model="desc" maxlength="1000"  >                   
                                    </textarea>
                                    <div id="ld">{{desc.length}}</div>
                                    <div style="text-align: justify"><span id="sptext"></span></div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-lg-4">
                                       De: 
                                <input id="inicio" type="us-timeA" placeholder="11:30"><span id="spini"></span>
                                    </div>
                                    <div class=" col-lg-4">
                                       a: 
                                        <input id="fin" type="us-timeA" placeholder="17:30" ><span id="spfin"></span>
                                    </div>
                                </div>
                                     <div class="col-lg-12">
                                     <div class="col-lg-4"></br>
                                   
                                  Dirigido a:  <select id="dirigido" ng-model="dirigido" ng-init="hd">
                                                                <option  ng-selected='true' value="hd">Niños</option>
                                                                <option value="et">Jovenes</option>
                                                                <option value="md">Adultos</option>
                                                               <option value="te">Todas las edades</option>
                                                </select>
                                                <span id="spdirigido"></span>
                                </div>
                                </div>
                                <div class="col-lg-6">
                                   
                                </div>
                                <div  class="form-group">
                                     <input id="enviar" class="btn btn-info" type="submit" value="Subir"/>
                                 </div>  
                            </form>
                                    <div  ng-if="mensaje.length>0"><div class="col-lg-12 alert alert-danger " >{{mensaje}}</div></div>
                            </div> 
                            <div class="col-lg-4" style="margin-top:3px;" hidden id="image">
                                <img id="img" width="80%" src=>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-default" id="listafechas">
                        <div class="panel-heading">
                                <h5 align="center">Fechas</h5>
                        </div>
                        <!-- /.panel-heading -->
                         <?php $this->load->view('evento/partial_tabla_list_fechas'); ?> 
                        <!-- /.partial -->
                    </div>
                    <!-- /.panel -->

                    <!-- /.panel -->
                </div>
    
                <!-- /.col-lg-8 -->
                    <?php $this->load->view('evento/partial_add_fechas'); ?>
                <!-- /.col-lg-4 -->
            </div>
    </div>
<script type="text/javascript">
    var app=angular.module("actividades",[])
        .controller("actividadcontroller",['$scope','upload','$http',function ($scope,upload,$http){
                $scope.listafechas=[];
                $scope.subir=function(){
        alertify.log("Espere un momento...");
                    $("#divfechas").fadeIn(100);
                    $("#fechas").hide(100);
                 $http({
                            url:'<?php echo base_url()?>home/actividad/addgafechas',
                            method:'POST',
                            data:{id:$scope.idac,datos:$scope.listafechas}

                        }).success(function(result){ 
                            alertify.success(result.insert); 
                            $scope.resultados= result;
                            if(result.inserta==true){
                                $scope.listafechas=[];
                            }
                                   
                        }).error(function(result){
                             console.log(result);
                      });
                    $("#divfechas").hide(100);
                    $("#fechas").show(100);
                                        
                }
                $scope.agregardatos=function(){
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
                $scope.uploadFile=function(){
                    $("#divcarga").fadeIn(200);
                    $("#nuevoeento").fadeOut(200);
                    $("#listafechas").fadeOut(200);
                    
                    var file=$scope.file;
                    var name=$scope.titulo;
                    var desc=$scope.desc;
                    var dirigido=$scope.dirigido;
                    var inicio=$('#inicio').val();
                    var fin=$('#fin').val();
            var lugar=$scope.lugar;
                    upload.uploadFile(name,file,dirigido,desc,lugar,inicio,fin).then(function(res){
                        console.log(res.data);
                        $scope.mensaje=res.data.mensaje;
                        $scope.imagen=res.data.imagen;
                        $scope.idac=res.data.id;
                        if($scope.imagen.length>0){
                             document.getElementById('img').setAttribute('src',"<?php echo base_url()?>images/thumbs/"+$scope.imagen);
                            $("#image").show(200);$("#enviar").hide(200);
                            $("#sptitulo").show(200);$("#intitulo").hide(200);$("#cargar").hide(200);
                            $("#sptitulo").text("Titulo: "+$scope.titulo);
                            $("#dirigido").hide(200);$("#spdirigido").show(200);
                            $("#lugar").hide(200);$("#splugar").show(200);$("#splugar").text($scope.lugar);
                            $("#inicio").hide(200);$("#spini").show(200);$("#spini").text($('#inicio').val());
                            $("#fin").hide(200);$("#spfin").show(200);$("#spfin").text($('#fin').val());
                            $("#ld").hide(100);
                            $("#descripcion").hide(200);$("#sptext").show(200);$("#sptext").text("Descripción: "+$scope.desc);
                            $("#fechas").show(200);
                            if($scope.dirigido=="hd"){
                                $("#spdirigido").text("Niños");
                            }else if($scope.dirigido=="et"){
                                $("#spdirigido").text("Jovenes");
                            }else if($scope.dirigido=="md"){
                                $("#spdirigido").text("Adultos");
                            }else if($scope.dirigido=="td"){
                                $("#spdirigido").text("Todas las edades");
                            }else if($scope.dirigido==undefined){
                                $("#spdirigido").text("Niños");
                            }

                        }
                    $("#divcarga").fadeOut(200);
                    $("#nuevoeento").fadeIn(200);
                    $("#listafechas").fadeIn(200);
                    
                    })
                }
         
        }]) 
        .directive('uploaderModel',["$parse",function ($parse) {
    return {
        scope: 'A',
        link: function (scope,iElement, iAttrs) {
            iElement.on("change",function(e){
                $parse(iAttrs.uploaderModel).assign(scope,iElement[0].files[0]);
            });
        }
    };
}])
        .service('upload',["$http","$q",function ($http, $q){
            this.uploadFile=function(titulo,file,dirigido,desc,lugar,inicio,fin){
            
                var deferred=$q.defer();
                var formData=new FormData();
                formData.append("titulo",titulo);
                formData.append("file",file);
                formData.append("dirigido",dirigido);
                formData.append("desc",desc);
                formData.append("lugar",lugar);
                formData.append("inicio",inicio);
                formData.append("fin",fin);           
                return $http.post("<?php echo base_url()?>home/actividad",formData,{
                    headers:{
                        "Content-type":undefined
                    },
                    transformRequest: formData
                }).success(function(res){
                    deferred.resolve(res);
                })
                .error(function(msg,code){
                    deferred.reject(msg);
                })
                return deferred.promise;
            }
        }]);


       $( document ).ready(function() {
            $("#conaac").show();
        });
            
</script>
<script>
$(function () {
    $('input[type=us-timeA]').w2field('time', 
        { format: 'h24', start: '7:00 am', end: '9:00 pm'
        });

});
</script>
