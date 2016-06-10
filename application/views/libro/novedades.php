
<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>js/angular.min.js"></script>   
<div id="page-wrapper" hidden ng-app="novedades" ng-controller="novedadcontroller">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/libros/carga.css" type="text/css" media="screen"/> 
            <!-- /.row -->
            <div class="col-lg-8 container" id="divcarga" style="margin-left:40%; margin-top:10%;" hidden>
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
             <div class="row" id="novedades" >
               
                <!-- /.col-lg-8 -->
                <div class="col-lg-6" >
                    <div class="panel panel-default" id="col">
                        <div class="panel-heading" id="panelreci" style="cursor:pointer"> 1 - Para publicar una novedad, primero debes de elegir el ejemplar. Dando clic <i>aquí</i> se muestra una lista con los ejemplares recientemente agregados al acervo, elige uno.
                             
                        </div>
						
                        <!-- /.panel-heading -->
                        <ul class="list-group" hidden id="listareci">
                                <li style="cursor:pointer"  ng-repeat="n in novedades" class="list-group-item row" ng-animate="'animate'" ng-click="agregarnov($index)">
                                        <span class="pull-left text-muted medium icon-plus">      
                                        </span>
                                         {{n.titulo.split('/')[0]|capitalize}}
                                        <span class="pull-right badge text-muted small">N° adquisicion:{{n.nadqui}}</span>
                                </li> 
                        <!-- /.panel-body -->
                        </ul>
                    </div>
                <!-- /.col-lg-4 -->
                </div>
            <!-- /.row -->
			
			
			  <div class="col-lg-6" >
                    <div class="panel panel-default" id="col">
                        <div class="panel-heading" id="panelagregar" style="cursor:pointer"> 2 - Para completar el proceso, da clic
                            <i >aquí.</i> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="panelcuerpo" hidden>
                            <div class="panel-body">
                            <div class=" col-lg-8  " > El ejemplar que elegiste fue: 
                            <form name="upload" ng-submit="uploadFile()"></br>
                                <div class="form-group">
                                    <label  >{{titulo}}</label> 
                                </div>Elige una imagen que servirá como portada del ejemplar.
                                <div class="form-group" id="cargar"></br>
                                    <input type="file"  class="form-control"  placeholder="Imagen *"  id="userfile" name="userfile" uploader-model="file">
                                    <p class="help-block text-danger"></p>
                                </div>Ingresa una breve descripción acerca del ejemplar, por ejemplo ¿de qué trata?
                                <div class="form-group col-lg-12" ></br>
                                    <textarea  style="max-width:150%; max-height:160px; width:150%;" id="descripcion" placeholder="Ingrese su descripción aqui "  ng-model="desc" maxlength="1000"  >                   
                                    </textarea><div id="ld">{{desc.length}}</div>
                                        <div style="text-align: justify"><span id="sptext"></span></div>
                                </div>
                                <div>
                                  <input type="hidden" id="nadqui" name="nadqui" ng-model="nadqui">
                                </div>
                                <div class="col-lg-6">
                                    <input id="enviar" class="btn btn-success" type="submit" value="Subir"/>
                                    
                                </div> 
                            </form>
                            </div> 
                            <div class="col-lg-4" style="margin-top:3px;" hidden id="image">
                                <img id="img" width="80%" src=""/>
                            </div>
                            <button id="nuevab" hidden ng-click="novedadcargada()">Nueva</button>
                        </div>
                          </div>
                        <!-- /.panel-body -->
                      </div>
        
        <!-- /#page-wrapper -->
		<div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel-heading">
                                        <i class="fa fa-bell fa-fw">Novedades publicadas recientemente, las cuales se muestran en la vista principal</i> 
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                               <tr>         
                                                <th >
                                                </th>
                                                <th ng-click="ordenar('t')">Título</th>
                                                <th ng-click="ordenar('n')">N° Adquisición</th>
                                                <th >Descripción</th>
                                                
                                              </tr>
                                            </thead>
                                            <tbody ng-repeat="n in listanovedades">
                                              <tr id="fil{{n.nadqui}}">
                                                <td ng-click="eliminarnov($index)"><span class="icon-bin"></span></td>                                                            
                                                <td class="titulo">{{n.titulo|capitalize}}</td>
                                                <td class="nadqi">{{n.nadqui}}</td>
                                                <td class="descripcion">
                                                <span class="icon-eye" ng-click="ocultardes($index)" ></span>
                                                <div id="colfildes{{n.nadqui}}" hidden>   
                                                    <textarea hidden id="desced{{n.nadqui}}" cols="50" ng-model="desc" maxlength="1000"  >

                                                    </textarea><div id="lde{{n.nadqui}}">{{desc.length}}</div>
                                                    <button hidden id="guardar{{n.nadqui}}" ng-click="guardardesc($index)">Guardar cambios</button>
                                                    <p id="desc{{n.nadqui}}" align="justify">{{n.descripcion}}</p>&nbsp;
                                                    <span class=" text-muted small icon-cross" ng-click="ocultardes($index)" >&nbsp;<strong>Cancelar</strong></span>
                                                    <span ng-click="editardesc($index)" class="icon-pencil2 pull-right text-muted medium"></span>
                                
                                                </div> 
                                                </td>   
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
                  </div>
				  </div>
		
</div>
<!-- jQuery -->
<script src="<?php echo site_url();?>js/dist/jquery.min.js"></script>
<script>
function mostrarocultar(idelem){
        if($(idelem).is(':visible')){
            $(idelem).fadeOut(400);
        }else{
            $(idelem).fadeIn(400);
        }   
}
    $("#panelreci").click(function(){
        mostrarocultar("#listareci");
    });
    $("#panelagregar").click(function(){
        mostrarocultar("#panelcuerpo");
    });
 $(function(){
    $("input[name='file']").on("change", function(){
        var formData = new FormData();
        formData.append("file",$("#formulario")[0]);
        formData.append("id",file);
        $.ajax({
            url: "<?php echo base_url()?>home/editimg/novedad",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(datos)
            {
                $("#respuesta").html(datos);
            }

        });
    });
 });
$( document ).ready(function() {
        $("#page-wrapper").show();
});
var app=angular.module("novedades",[]).filter('capitalize', function() {
return function(input, all) {
return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
}
});
app.controller("novedadcontroller",function ($scope,$http,upload){
$scope.novedades=[];
    $scope.ocultardes=function(indice){
    nad=$scope.listanovedades[indice].nadqui;
        if($("#colfildes"+nad).is(':visible')){
            $("#colfildes"+nad).hide();
        }else{
            $("#colfildes"+nad).show();
        }
    }
    $scope.agregarnov=function(indice){
      $("#panelcuerpo").show();
      $scope.titulo=$scope.novedades[indice].titulo.capitalizar();
      $scope.nadqui=$scope.novedades[indice].nadqui;
    }
    String.prototype.capitalizar=function(){
       return this.toLowerCase().replace(/(^|\s)([a-z])/g, function(m, p1, p2) { return p1 + p2.toUpperCase(); });
    }
    $scope.get_novedades=function(){
    $http({url:'<?php echo base_url()?>home/lista/novedades',method:'GET'}).success(function(result){ 
       $scope.novedades=result;
       console.log(result);
      }).error(function(result){            
           
    });
    }
    $scope.changimage=function(indice){
        $scope.aeditar=$scope.listanovedades[indice].nadqui;
        $scope.taeditar=$scope.listanovedades[indice].titulo.capitalizar();
        $scope.indiceae=indice;
    }
    $scope.eliminarnov=function(indice){
        alertify.confirm("<p>¿Deseas eliminar este registro?<br><br>", function (e) {
        if (e) {
            eliminarn(indice);
        }
    }); 
    }
    eliminarn=function(indice){
        alertify.log("Espera un momento..");
        nad=$scope.listanovedades[indice].nadqui;
        id=$scope.listanovedades[indice].idnovedad;
        data={nadqui:nad,idb:id};
        fucntionserver('<?php echo base_url()?>home/novedad/eliminar',data,"elim");
    }
    $scope.guardardesc=function(indice){
        nad=$scope.listanovedades[indice].nadqui;
        id=$scope.listanovedades[indice].idnovedad;
            alertify.log("Espera un momento..");
            data={nadqui:nad,valor:$('#desced'+nad).val(),idb:id};
            fucntionserver('<?php echo base_url()?>home/novedad/editar',data,"edit");
    }
    fucntionserver=function(url,dat,accion){
        $http({
        url:url,
         method:'POST',
        data:dat
        }).success(function(result){
            if(accion=="edit"&&result.exito==true){
                $scope.listanove();
            }else if(accion=="elim"&&result.exito==true){
               $scope.listanove();
               $scope.get_novedades();
            } 
            alertify.success(result.mensaje);
            console.log(result);
        }); 
    }
    $scope.editardesc=function(indice){
        nad=$scope.listanovedades[indice].nadqui;
        if($("#desc"+nad).is(':visible')){
            $("#desc"+nad).hide();
            $("#desced"+nad).show();
            $("#guardar"+nad).show();
            $("#lde"+nad).show();
        }else{
            $("#desc"+nad).show();
            $("#desced"+nad).hide();
            $("#guardar"+nad).hide();
            $("#lde"+nad).hide();
        }
    }
    $scope.listanove=function(){
        $http({url:'<?php echo base_url()?>home/difusion/ennovedades',method:'GET'}).success(function(result){ 
       $scope.listanovedades=result;
       console.log(result);
      }).error(function(result){            
           
    });   
    }
    $scope.init=function(){         
      $scope.get_novedades();
      $scope.listanove();
    }
    $scope.novedadcargada=function(){
        if($("#descripcion").is(':visible')){
            document.getElementById('img').setAttribute('src',"<?php echo base_url()?>images/novedades/"+$scope.imagen);
            $("#image").show(200);
            $("#enviar").hide(200);
            $("#nuevab").show(200);
            $("#descripcion").hide(200);
            $("#sptext").show(200);
            $("#sptext").text("Descripción: "+$scope.desc);
            $("#ld").hide();
        }else{
            document.getElementById('img').setAttribute('src',"");
            $("#image").hide(200);
            $("#enviar").show(200);
            $("#nuevab").hide(200);
            $("#descripcion").val("");
            $("#descripcion").show(200);
            $("#sptext").hide(200);
            $("#sptext").text("");
            $("#ld").show();
            $scope.titulo="";$scope.desc="";
        }
    }
    $scope.init();
    $scope.uploadFile=function(){
        $("#divcarga").show();
        $("#novedades").hide();
        var file=$scope.file;var name=$scope.titulo;var desc=$scope.desc;var nadqu=$scope.nadqui;
        url="<?php echo base_url()?>home/agregar/novedad";
        id="";
        upload.uploadFile(name,file,desc,nadqu,id,url).then(function(res){
        console.log(res.data);
        $scope.mensaje=res.data.mensaje;
            if(res.data.mensaje){
                     alertify.success(res.data.mensaje);
                    }
            $scope.imagen=res.data.imagen;
            $scope.idac=res.data.id;
            if($scope.imagen.length>0){
                $scope.listanove();
                $scope.novedadcargada();
                $scope.get_novedades();
            }
        $("#divcarga").hide(100);
        $("#novedades").show(100); 
    })
    
}

})
.directive('uploaderModel',["$parse",function ($parse) {
    return {
        scope: 'A',
        link: function (scope,iElement, iAttrs) {
            iElement.on("change",function(e){
                $parse(iAttrs.uploaderModel).assign(scope,iElement[0].files[0]);
            });
        }
    };
}]).service('upload',["$http","$q",function ($http, $q){
            this.uploadFile=function(titulo,file,desc,nadq,id,url){
                var deferred=$q.defer();
                var formData=new FormData();
                formData.append("titulo",titulo);
                formData.append("file",file);
                formData.append("desc",desc);
                formData.append("nadqui",nadq);
                formData.append("ida",id);          
                return $http.post(url,formData,{
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

 
</script>
<style type="text/css">
  #col{
    margin-top: 30px;
  }
  .windows8 .wBall .wInnerBall{
position: absolute;
width: 14px;
height: 14px;
background: #B88B32;
left:0px;
top:0px;
-moz-border-radius: 12px;
-webkit-border-radius: 12px;
-ms-border-radius: 12px;
-o-border-radius: 12px;
border-radius: 12px;
}
</style>
