<div id="page-wrapper" ng-app="appBiblio"  ng-controller="libroscontroller" hidden>
    <div class="col-lg-12">
        <?php $this->load->view('libro/menulibro');?> 
    </div>
    <div class="row" >
        <div class="col-lg-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span> <EM>Campos obligatorios (*)</EM>  </span>  
            
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body wrapper">
                    <div class="terminosdebusqueda col-lg-12" >
                        <div class="col-lg-6">
                            <div class="form-group">
                                <em>N° Control (001):</em> 
                                <input type="number" min="0" ng-model="ncontrol" placeholder= "N° Control"  class="form-control" />
                            </div>               
                        </div>
                        <div class=" col-lg-6">
                            <div class="form-group">
                                <em>ISBN:</em>
                                 <input ng-model="isbn" placeholder="ISBN" class="form-control"  />
                            </div>
                        </div>              
                    </div>
                    <div class="col-lg-12" style="margin-top:3px;" hidden>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <em>Idioma:</em> 
                                <input ng-model="idioma" placeholder= "Idioma" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6" >
                              <em>Autor:</em> 
                              <input ng-model="autor" placeholder= "Autor" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-lg-12" style="margin-top:3px;" hidden>
                        <div class="col-lg-6">
                              <em>Título:</em> 
                              <input ng-model="titulo" placeholder= "Título *"  class="form-control"/>
                        </div>
                        <div class="col-lg-6" >
                                <em>Edición:</em> 
                                <input ng-model="edicion" placeholder= "Edición" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-lg-12" style="margin-top:3px;" hidden>
                        <div class="col-lg-6">
                            <em>Editorial:</em> 
                            <input ng-model="editorial" placeholder= "Editorial" class="form-control"/>
                        </div>
                        <div class="col-lg-6">
                              <em>Descripción fisica:</em>
                               <input ng-model="desc" placeholder= "Desc. Fisica"  class="form-control"/>
                        </div>
                    </div>
                    <div class="col-lg-12" style="margin-top:3px;">
                        <div class="col-lg-6" hidden>
                            <em>Serie:</em>
                             <input ng-model="serie" placeholder= "Serie" class="form-control"/>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group"> 
                            <button id="btnb" ng-click="cargalibros()" class="pull-right btn btn-success">Buscar
                            </button>
                          </div>
                        </div>   
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            <div class="panel panel-default">
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
                        <h5 align="center">Resultados</h5>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                       <tr>         
                                        <th >
                                        </th>
                                        <th ng-click="ordenar('245')">Título</th>
                                        <th ng-click="ordenar('082')">Clasificación</th>
                                        <th ng-click="ordenar('100')">ISBN</th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tbody ng-repeat="n in list_data| orderBy:campo:orden">
                                      <tr >
                                        <td><a  data-toggle="modal" href="#myModal" type="button" ng-click="buscaridficha('centro',n['con'])" ><span class="icon-eye"></span></a></td>                                                            
                                        <td class="245">{{n['245']|capitalize}}</td>
                                        <td class="082">{{n['082']}}</td>
                                        <td class="nameautor">{{n['020']}}</td>
                                        <td ng-click="vermarc(n['marc'])"><button class="btn btn-info etiquetbtn">Etiqueta</button></td>
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

            <!-- /.panel -->
        </div>
        <div class="modal fade " id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" >
                       <div class="modal-content">
                            <div class="modal-header" >
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>  
                                <em class="pull-left"><strong>Ficha N°: <em ng-if="datoslibro['001']!=0">{{datoslibro['001']}}</em> <em ng-if="datoslibro['001']==0"> {{datoslibro['035']}}</em></strong></em>
                                <br>
                                <em><h4>Título:{{datoslibro['245']|capitalize}}</h4></em>
                                <br>  
                                <em class="text-muted pull-left">Edicion:{{datoslibro['250']}}</em>
                                <br>
                            </div>
                            <div class="modal-body container-fluid col-md-12">                                       
                                <div class="row">
                                    <div class="col-md-6" >
                                        <div ng-click="buscaridficha('left',indice)" class="pull-left" style="font-size:200%">
                                        <div  ><span class="icon-arrow-left2"></span></div>
                                      </div>           
                                    </div>
                                    <div class="col-md-6" >
                                             <div ng-click="buscaridficha('right',indice)" class="pull-right" style="font-size:200%">
                                            <div ><span class="icon-arrow-right2" ></span></div>  
                                          </div>      
                                    </div>
                                 </div>   
                                <div class="row">
                                    <div class="col-md-12">
                                        <div align="left" style="margin-left:10%" >                                                                                     
                                                <em><strong>Autor:</strong>{{datoslibro['100']|capitalize}}</em> 
                                                <br>
                                                <em><strong>Serie:</strong>{{datoslibro['440']|capitalize}} </em> 
                                                <br>
                                                <em><strong>Clasificación:</strong>{{datoslibro['082']}}</em> 
                                                <br>
                                                
                                                <em><strong>ISBN:</strong>{{datoslibro['020']}}</em>
                                                <br>
                                                <em><strong>Idioma:</strong>{{datoslibro['041']|capitalize}}</em>
                                                <br>
                                                <em><strong>Descr. Física :</strong>{{datoslibro['300']|capitalize}}</em>
                                                <br>
                                                 <em><strong>Contenido:</strong>{{datoslibro['505']|capitalize}}</em>
                                                 <br>
                                                 <em ><strong>Nota General:</strong>{{datoslibro['500']|capitalize}}</em>
                                                <br>
                                                <em><strong>Temas:</strong>{{datoslibro['600']|capitalize}}</em>
                                                 <br>
                                                <em><strong>Materia:</strong> {{datoslibro['650']|capitalize}}</em>
                                            
                                            </div>
                                        </div>
                                         
                                    </div>
                               </div> 
                               <div class="modal-footer">
                                   <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <em><strong>Pie de Imprenta:</strong>{{datoslibro['260']|capitalize}}</em>
                                        </div>
                                    </div>
                                   </div>
                               </div>
                               <hr>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <a href="" type="button" class="btn btn-primary btn-xl" data-dismiss="modal"><i class="fa fa-times"></i>Aceptar</a> 
                                            
                                        </div>
                                    </div>                                        
                                </div>
                                  <p>        
                        </div>
            </div>
        </div>

    </div>
<script type="text/javascript">
angularRoutingApp.controller("libroscontroller",function ($scope,miService,confighttpFactori){
    $scope.ncontrol="";$scope.isbn="";
    $scope.idioma="";$scope.autor="";
    $scope.titulo="";$scope.edicion="";
    $scope.editorial=""; $scope.desc="";
    $scope.serie="";
    $scope.inicio=0;$scope.orden=false;
    $scope.campo="titulo";
    $scope.editorial="";
    $scope.ordenar=function(t){
          $scope.campo=t;
          orden();
    }
    function orden(){
      $scope.orden=($scope.orden==false) ? true : false;
    }    
    spliteardatos_recibidos=function(datos){
      con=0;
      if(datos){
          if(datos.length>0){
              datos.forEach(function(elem,posicion){
                  limpiarcampo_etiqueta_libro(elem.etiqueta.split("¦"),elem.etiqueta,con);
                  con+=1;
              });
          }
      }
    }
    limpiarcampo_etiqueta_libro=function(objeto,etiqueta,con){
      libro=[];
      objeto.forEach(function(campo,posicion){
              llave=campo.substring(0,3);
              valor=campo.substring(3,campo.length)
              libro[llave]=valor;
              libro['marc']=etiqueta;
              libro['con']=con;
      });
      $scope.list_data.push(libro);
    }  
    $scope.cargalibros=function(){ 
      $("#divbus").attr("class","windows8");
      $('#btnb').text('...');
      $(".form-control").attr('disabled', 'disabled');    
      $(".etiquetbtn").attr('disabled', 'disabled');
      confighttpFactori.setData('<?php echo base_url()?>home/buscaretiqueta/access','POST',{'001':$scope.ncontrol,'020':$scope.isbn,
                    '041':$scope.idioma,'100':$scope.autor,   
                      '245':$scope.titulo,'250':$scope.edicion,
                      '260':$scope.editorial,'300':$scope.desc,
                      '440':$scope.serie
      } );    
      miService.getAll().then(function (result) {
          if(result.mensaje){
              alertify.success(result.mensaje);
          }
          $("#divbus").removeAttr("class","windows8");
          $('#btnb').text('Buscar');
          $(".form-control").removeAttr('disabled'); 
          $(".etiquetbtn").removeAttr('disabled');
          console.log(result);
          //inicializamos la data para msotrar en tabla
          $scope.list_data=[];
         if(result.datos){
              spliteardatos_recibidos(result.datos);
          }else{
              alertify.error("No hay datos que mostar");
          }
      }).catch(function (message) {
          alertify.error("No fue posible conectarse al servidor");
      });
    }
    $scope.vermarc=function(marc){
      alertify.confirm("<p>¿Copiar Etiqueta?<br>"+marc+"<br>", function (e) {
        if (e) {
            window.localStorage.setItem("marc",marc); 
            window.location = "<?php echo base_url(); ?>home/fichas";
        }
      }); 
      return false 
    }
    $scope.buscaridficha=function(direccion,indice){ 
         if(direccion=="centro"){
            $scope.indice=indice;
            $scope.datoslibro=$scope.list_data[$scope.indice];
         }else if(direccion=="left"){
                if(indice-1>-1){
                    $scope.indice=indice-1;
                }else{
                    $scope.indice=$scope.list_data.length-1;
                }
            $scope.datoslibro=$scope.list_data[$scope.indice];
          } else if(direccion=="right"){
                if(indice+1<$scope.list_data.length){
                $scope.indice=indice+1;
                }else{
                    $scope.indice=0;
                }
              $scope.datoslibro=$scope.list_data[$scope.indice];
          } 
    }
}); 
$( document ).ready(function() {
    $("#page-wrapper").show();
    $("#etiqueta").attr("class","active"); 
});         
</script>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
<link rel="stylesheet" href="<?php echo site_url(); ?>css/libros/carga.css" type="text/css" />
