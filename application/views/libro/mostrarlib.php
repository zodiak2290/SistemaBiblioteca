        <!-- Navigation -->
<?php 
    $this->load->database(); 
    $query = $this->db->query("select max(nadqui) as id from ejemplar");
 ?>  
 <?php  foreach ($query->result() as $idmax){ ?>                
<?php $na=$idmax->id ?>               
<?php }; ?>
        <div id="page-wrapper" ng-app="libros"  ng-controller="listacontroller" hidden>
            <div class="row">
                <div class="col-lg-8">
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row" ng-controller="libroscontroller" >
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Buscar libro 
                            <button id="btnb" ng-click="cargalibros()" class="pull-right btn-info">Buscar
                                        </button>
                                  N° de registros:  <select ng-model="limitar" ng-change="cargalibros()">
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="30">30</option>
                                                              </select>
                                
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="terminosdebusqueda col-lg-12" >
                                <div class="container col-lg-6">
                                      Título: <input ng-model="titulo" placeholder= "  Título"  />
                                </div>
                                <div class="container col-lg-6">&nbsp;&nbsp;&nbsp;
                                      Autor: <input ng-model="autor" placeholder= "  Autor" />
                                </div>
                            </div>
                            <div class="col-lg-12" style="margin-top:3px;">
                                <div class="container col-lg-6">
                                  ISBN:&nbsp;    <input ng-model="isbn" placeholder= "  ISBN" />
                                </div>
                                <div class="container col-lg-6">
                                  Editorial: <input ng-model="editorial" placeholder= "  Editorial" />
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
                                                <th ng-click="ordenar('titulo')">Título</th>
                                                <th ng-click="ordenar('clasificacion')">Clasificación</th>
                                                <th ng-click="ordenar('nameautor')">ISBN</th>
                                                <th colspan="3"></th>
                                              </tr>
                                            </thead>
                                            <tbody ng-repeat="n in list_data| orderBy:campo:orden">
                                              <tr >
                                                <td><a  id="a{{n.nfic}}" data-toggle="modal" href="#myModal" type="button" ng-click="buscaridficha(n.nfic,'centro',$index)" ><span class="icon-eye"></span></a></td>                                                            
                                                <td class="titulo">{{n.titulo|capitalize}}</td>
                                                <td class="casificacion">{{n.clasificacion}}</td>
                                                <td class="nameautor">{{n.nameautor|capitalize}}</td>
                                                <td  ng-click="agregar(n.titulo,n.nfic)"><a href="" ><span class="icon-copy"/></td>    
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
                                <div class="form-group">
                                    <label id="labelinpout"></label>
                                        <input  type="text" id="inputedit" hidden class="editando">  
                                </div>
                               <div class="modal-content">
                                    <div class="modal-header" >
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>  
                                        <em class="pull-left"><strong>Ficha N°: {{datoslibro['idficha']}}</strong></em>
                                        <br>
                                        <div>
                                            <em>
                                                <h4>Título:{{datoslibro['titulo']|capitalize}}</h4>
                                                <a  class="editarlibro" hidden ng-click="editar('titulo','Titulo')" ><span class="icon-pencil"></span></a>
                                            </em>
                                            
                                        </div>
                                        <br>  
                                            <em class="text-muted pull-left">
                                                Edición:{{datoslibro['edicion']}} 
                                                <a id="edicionedit" class="editarlibro" hidden ng-click="editar('edicion','Edición')" ><span class="icon-pencil"></span></a> 
                                            </em>
                                        <br>
                                    </div>
                                    <div class="modal-body container-fluid col-md-12">                                       
                                        <div class="row">
                                            <div class="col-md-6" >
                                                <div ng-click="buscaridficha(datoslibro,'left',indice)" class="pull-left" style="font-size:200%">
                                                <div  ><span class="icon-arrow-left2"></span></div>
                                              </div>           
                                            </div>
                                            <div class="col-md-6" >
                                                     <div ng-click="buscaridficha(datoslibro,'right',indice)" class="pull-right" style="font-size:200%">
                                                    <div ><span class="icon-arrow-right2" ></span></div>  
                                                  </div>      
                                            </div>
                                         </div>   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div align="left" style="margin-left:10%" >                                                                                     
                                                        <em>
                                                            <strong>Autor:</strong>
                                                            {{datoslibro['nameautor']|capitalize}}
                                                            <a id="autoredit" class="editarlibro" hidden ng-click="editar('autor')" ><span class="icon-pencil"></span></a>  
                                                            <a id="autoradd" class="editarlibro" hidden ng-click="mostraragregarautor()" ><span class="icon-plus"></span></a>  
                                                            <ul id="selecautores" hidden>
                                                              <li ng-repeat="item in autores" ng-if="datoslibro['nameautor'].length>0">
                                                                <span ng-if="item.length>0">{{item}}</span>
                                                                <button  ng-if="item.length>0" class="btn btn-success btn-xs" ng-click="editarautor(item)">Editar</button>
                                                                <button  ng-if="item.length>0" class="btn btn-warning btn-xs" ng-click="quitarautor(item)">Quitar autor</button>
                                                                <button  ng-if="item.length>0" class="btn btn-danger btn-xs" ng-click="dardebajaautor(item)">Dar de baja autor</button>
                                                              </li>
                                                            </ul>
                                                            <div id="autorplus" hidden>
                                                                <button  class="btn btn-success btn-xs" ng-click="agregarautor(autorbuscar)">Agregar</button>
                                                                <input ng-model="autorbuscar" ng-change="cargaautores()" class="form-group" placeholder="Autor" />
                                                                <ul class="list-group">
                                                                    <li class="list-group-item"  ng-repeat="autorbuscar in autoreslista" >
                                                                        <a ng-click="cambiaautor(autorbuscar.nameautor)" class="btn" >
                                                                            {{autorbuscar.nameautor}}
                                                                        </a>

                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </em> 
                                                        <br>
                                                            <em>
                                                                <strong>Serie:</strong>{{datoslibro['asiento']|capitalize}} 
                                                                <a id="serieedit" class="editarlibro" hidden ng-click="editar('serie','Serie')" ><span class="icon-pencil"></span></a> 
                                                            </em> 
                                                        <br>
                                                        <em>
                                                            <strong>Clasificación:</strong>{{datoslibro['clasificacion']}}
                                                            <a id="clasificacionedit" class="editarlibro" hidden ng-click="editar('clasificacion')" ><span class="icon-pencil"></span></a> 
                                                             <div id="selectclasificacion" hidden>   
                                                                <button  class="btn btn-success btn-xs" ng-click="agregarclasificacion(nuevaclasificacion)">Editar</button>
                                                                <input ng-model="nuevaclasificacion" ng-change="cargarclasificacion()" class="form-group" placeholder="Clasificación" />
                                                                <ul class="list-group">
                                                                    <li class="list-group-item"  ng-repeat="nuevaclasificacion in clasificaciones" >
                                                                        <a ng-click="cambiarclasi(nuevaclasificacion.clasificacion)" class="btn" >
                                                                            {{nuevaclasificacion.clasificacion}}
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </em> 
                                                        <br>                                                        
                                                            <em><strong>ISBN:</strong>{{datoslibro['isbn']}}
                                                            <a id="isbnedit" class="editarlibro" hidden ng-click="editar('isbn','ISBN')" ><span class="icon-pencil"></span></a>         
                                                            </em>
                                                        <br>
                                                        <em>
                                                            <strong>Idioma:</strong>{{datoslibro['ci']|capitalize}}
                                                            <a id="idiomaedit" class="editarlibro" hidden ng-click="editar('idioma','Idioma')" ><span class="icon-pencil"></span></a>    
                                                        </em>
                                                        <br>
                                                        <em>
                                                            <strong>Descr. Física :</strong>{{datoslibro['des']|capitalize}}
                                                            <a id="descedit" class="editarlibro" hidden ng-click="editar('descfisica','Descripcion Fisica')" ><span class="icon-pencil"></span></a>         
                                                        </em>
                                                        <br>
                                                         <em>
                                                            <strong>Contenido:</strong>{{datoslibro['cont']|capitalize}}
                                                            <a id="contenidoedit" class="editarlibro" hidden ng-click="editar('contenido','Contenido')" ><span class="icon-pencil"></span></a>         
                                                        </em>
                                                         <br>
                                                         <em >
                                                            <strong>Nota General:</strong>{{datoslibro['nota']|capitalize}}
                                                            <a id="notaoedit" class="editarlibro" hidden ng-click="editar('notageneral','Nota General')" ><span class="icon-pencil"></span></a>         
                                                        </em>
                                                        <br>
                                                        <em>
                                                            <strong>Temas:</strong>{{datoslibro['tema']|capitalize}}
                                                            <a id="temasedit" class="editarlibro" hidden ng-click="editar('tema','Temas')" ><span class="icon-pencil"></span></a>         
                                                        </em>
                                                         <br>
                                                        <em>
                                                            <strong>Materia:</strong> {{datoslibro['stema']|capitalize}}
                                                            <a id="materiaedit" class="editarlibro" hidden ng-click="editar('materia')" ><span class="icon-pencil"></span></a>       
                                                            <a id="materiadd" class="editarlibro" hidden ng-click="mostraragregarmateria()" ><span class="icon-plus"></span></a>  
                                                            <ul id="selectmaterias" hidden class="list-group">
                                                              <li class="list-group-item" ng-repeat="item in materias" ng-if="datoslibro['stema'].length>0">
                                                                <span ng-if="item.length>0">{{item|capitalize}}</span>
                                                                <button  ng-if="item.length>0" class="btn btn-success btn-xs" ng-click="editarmateria(item)">Editar</button>
                                                                <button  ng-if="item.length>0" class="btn btn-warning btn-xs" ng-click="quitarmateria(item)">Quitar materia</button>
                                                                <button  ng-if="item.length>0" class="btn btn-danger btn-xs" ng-click="dardebajamateria(item)">Dar de baja materia</button>
                                                              </li>
                                                            </ul>
                                                            <div id="materiaplus" hidden class="col-lg-8">
                                                                <button  class="btn btn-success btn-xs" ng-click="agregarmateria(materiabuscar)">Agregar</button>
                                                                <input ng-model="materiabuscar" ng-change="cargamaterias()" class="form-group" placeholder="Materia" />
                                                                <ul class="list-group">
                                                                    <li class="list-group-item"  ng-repeat="materiabuscar in materislista" >
                                                                        <a ng-click="cambiamateria(materiabuscar.namemateria)" class="btn" >
                                                                            {{materiabuscar.namemateria}}
                                                                        </a>

                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </em>
                                                    
                                                    </div>
                                                </div>
                                                 
                                            </div>
                                       </div> 
                                       <div class="modal-footer">
                                           <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-12">
                                                    <em>
                                                        <strong>Pie de Imprenta:</strong>{{datoslibro['edit']|capitalize}}
                                                        <a id="imprentaedit" class="editarlibro" hidden ng-click="editar('editorial','Pie de Imprenta')" ><span class="icon-pencil"></span></a>         
                                                    </em>
                                                </div>
                                            </div>
                                           </div>
                                       </div>
                                       <hr>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-12">
                                                    <a href="" type="button" class="btn btn-primary btn-xl" data-dismiss="modal" ng-click="agregar(datoslibro['titulo'],datoslibro['idficha'])" ><i class="fa fa-times"></i>Agregar</a> 
                                                    <a type="button" class="btn btn-info btn-xl" data-dismiss="modal" >Cancelar</a> 
                                                    <a type="button" class="btn btn-success btn-xl" ng-click="iniciaredit()"  >Editar</a> 
                                                </div>
                                            </div>                                        
                                        </div>
                                          <p>        
                                </div>
                    </div>
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4" >
                    <div class="panel panel-default" >
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i>
                            {{listanuevos.length}} libros en la lista
                            <button id="vaciarlista" ng-click="vaciar()" class="pull-rigth hidden" >Eliminar todos</button>
                            <p>
                            <span  id="errorenvio" class="hidden">Error de envio verifiqué datos</span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group" >
                                <div   ng-repeat="n in listanuevos" >
                                <div class="list-group-item-{{n.panel}}">
                                 <button class="btn btn-info pull-right" ng-click="ver($index)" id="boton{{$index}}">Ver</button>
                                    <div id="delet{{n.id}}" class="pull-left" ng-click="eliminar($index)"><span class=" text-muted small icon-cross" ></span></div>    
                                        <hr>
                                        <span  class="pull-center text-muted small" ng-if="n.nad!=' '"><em>N° Adquisición: {{n.nad}}</em></span>
                                        <span  class="pull-center text-muted small" ng-if="n.nad==''"><em>Falta N° de adquisicion</em></span>    
                                    <p>
                                    <strong>Titulo:</strong>{{n.titulo|capitalize}} 
                                   
                                </div> 
                                    <div class="list-group-item  hidden" id="panel{{$index}}">
                                        <div id="edit{{n.id}}" ng-if="n.panel!='success'" class="pull-right" ng-click="datosejemplar($index)">Editar <span class="text-muted medium icon-pencil" ></span></div>  
                                        <hr>
                                        <span><input class="datosejem{{$index}} form-control" type="hidden" ng-keyup="agreganad($event,$index)"  placeholder="N° adquisición" id="nad{{$index}}" name="nadq" ></span>
                                        <span class="pull-center text-muted small"> Vol:{{n.volumen}}</span>
                                        <span><input class="datosejem{{$index}} form-control" type="hidden" ng-keyup="agregadatoejemplar($event,$index,'vol')"  placeholder="Volumen Ejemplo: 1" id="vol{{$index}}" name="desc" ></span>
                                        <span class="pull-center text-muted small"> Tomo:{{n.tomo}}</span>
                                        <span><input class="datosejem{{$index}} form-control" type="hidden" ng-keyup="agregadatoejemplar($event,$index,'tomo')"  placeholder="Tomo Ejemplo: 1" id="tomo{{$index}}" name="tomo" ></span>
                                        <span class="pull-center text-muted small"> Ej:{{n.ejemplar}}</span>
                                        <span><input class="datosejem{{$index}} form-control" type="hidden" ng-keyup="agregadatoejemplar($event,$index,'ejemplar')"  placeholder="N° Ejemplar " id="ejemplar{{$index}}" name="ej" ></span>
                                        <hr>
                                        <span ng-if="n.panel!='success'"><input id="dispo{{$index}}" type="checkbox" name="disponibilidad" ng-click="disponibleejem($index,'dispo')" checked />Mostar en consulta online</span>
                                        <hr>
                                        <span ng-if="n.panel!='success'"><input id="cb{{$index}}" type="checkbox" name="cb" ng-click="cbejem($index,'cb')" checked />Imprimir Código de barras</span>
                                        <br>
                                        <span class="pull-center text-muted small">{{n.mensaje}}</span>
                                        <hr>
                                        <span  class="pull-rigth text-muted small" ng-if="n.envio!=' '"><em>{{n.envio}}</em></span>
                                        <div class="container-fluid">
                                            <div id="car{{n.id}}" class="pull-left">
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
                                     </div>   
                                </div>     
                            </div>
                            <!-- /.list-group 
                            <a href="#" ng-click="eliminar($index)" class="btn btn-default btn-block">Cancelar</a>-->
                            <a href="#" ng-click="enviando()" class="btn btn-default btn-block">Enviar</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <!-- /.panel -->
                   
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
            </div>
<script src="<?php echo site_url(); ?>js/servicioangular.js"></script> 
<script type="text/javascript">
    var app=angular.module("libros",[]).filter('capitalize', function() {
    return function(input, all) {
      return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
  });
    servicio_comunicacion_al_servidor(app); 
        app.controller("libroscontroller",function ($scope,Servidor){
          $scope.titulo="";$scope.autor="";$scope.isbn="";$scope.limitar=10;$scope.orden=false;$scope.campo="titulo";
          $scope.editorial="";
          $scope.autores=[];
          $scope.materias=[];
          $scope.ordenar=function(t){
                $scope.campo=t;
                orden();
          }
        $scope.agregarautor=function(name){
             comunicarconservidor('<?php echo base_url()?>home/autor/agregar',
                {autor:$.trim(name),idficha:$scope.datoslibro['idficha']},mensajeresutlado);
            $scope.buscaridficha($scope.datoslibro['idficha'],'centro',$scope.indice);
        }  
        $scope.agregarmateria=function(name){
            comunicarconservidor('<?php echo base_url()?>home/materia/agregar',
                {materia:$.trim(name),idficha:$scope.datoslibro['idficha']},mensajeresutlado);
            $scope.buscaridficha($scope.datoslibro['idficha'],'centro',$scope.indice); 
        }
        $scope.agregarclasificacion=function(name){
            alertify.confirm("<p>¿Editar esta clasificación "+$scope.datoslibro['clasificacion']+" </p><br>Por esta "+name+"?", function (e) {
                if (e) {
                    datos={idficha:$scope.datoslibro['idficha'],clasificacion:name}
                    accionautor(datos,'<?php echo base_url()?>home/clasificacion/editar');
                }
            }); 
        }
        comunicarconservidor=function(url,datos,funcion){
              Servidor.query(url,datos,'POST').success(function(result){ 
                 funcion(result,datos);
                 console.log(result);
                }).error(function(result){
                    console.log(result);
                     alertify.error("No fue posible comunicarse con el servidor");
              });
        }
        accionautor=function(datos,url){
            comunicarconservidor(url,datos,mensajeresutlado);
            $scope.buscaridficha($scope.datoslibro['idficha'],'centro',$scope.indice);           
        }  
    function mostrarsugerencias(result){
       $scope.materislista=result.sugerencias; 
    }
    function mostrarsugerenciasauto(result){
       $scope.autoreslista=result.sugerencias; 
    }
    function mostrarsclasificaciones(result){
        $scope.clasificaciones=result.sugerencias;
    }
    $scope.cargaautores=function(){
        comunicarconservidor('<?php echo base_url()?>home/autor/sugerir',{autor:$scope.autorbuscar},mostrarsugerenciasauto);     
    }
    $scope.cambiarclasi=function(claselegida){
        $scope.nuevaclasificacion=claselegida;
        $scope.clasificaciones=null;
    }
    $scope.cambiaautor=function(autorelegido){
        $scope.autorbuscar=autorelegido;
        $scope.autoreslista=null;
    }
    //Cada vez que modifiquemos el contenido del campo haremos una petición a nuestra base de datos
    $scope.cargamaterias = function(){
        comunicarconservidor('<?php echo base_url()?>home/materia/sugerir',{materia:$scope.materiabuscar},mostrarsugerencias);
    }
   //Cuando eliges una materia lo reemplaza en el campo de texto
    $scope.cambiamateria = function(materielegida){
        $scope.materiabuscar = materielegida;
        $scope.materislista = null;
    }
    $scope.cargarclasificacion=function(){
        comunicarconservidor('<?php echo base_url()?>home/clasificacion/sugerir',{clasificacion:$scope.nuevaclasificacion},mostrarsclasificaciones);    
    }
    $scope.cargalibros=function(){ 
              $("#divbus").attr("class","windows8");
              $('#btnb').text('...');
              datos={titulo:$scope.titulo,
                        autor:$scope.autor,
                        isbn:$scope.isbn,
                        limitar:$scope.limitar,
                        editorial:$scope.editorial};     
                        comunicarconservidor('<?php echo base_url()?>home/libros/json',datos,procesarresutlado);     
                      $("#divbus").removeAttr("class","windows8");
                      $('#btnb').text('Buscar');  
        }
        $scope.elementos=[];
        $scope.enviando=function(){
            $("#errorenvio").attr("class","hidden");  
            if(descripción()==0){ 
                $scope.listanuevos.forEach(function(elem , posicion){
                    if(elem.panel!="success"){   
                        $("#car"+elem.id).attr("class","windows8");
                        comunicarconservidor('<?php echo base_url()?>home/libros/guardar',elem,resutadosenvio);
                    }
                }); 
            }else{
                $("#errorenvio").removeAttr("class","hidden");
            }
        }
        function resutadosenvio(result,elem){
            $scope.resultados= result;
            $("#car"+elem.id).removeAttr("class","windows8");
            if($scope.resultados['registro']==true){
                elem.panel="success";
                elem.envio="Exito!";
                alertify.success("El ejemplar "+elem.nad+" se agrego correctamente", "", 0);
                elem.mensaje=$scope.resultados['mensaje'];
            }else{
                elem.panel="danger";
                alertify.error("No fue posible agregar el ejemplar N° Adquisición:"+elem.nad, "", 0);
                elem.envio="No fue posible agregar el ejemplar ";
                elem.mensaje=$scope.resultados['mensaje'];
            }
        }    
          function orden(){
            $scope.orden=($scope.orden==false) ? true : false;
          }
        $scope.buscaridficha=function(id,direccion,indice){ 
            $scope.indficha=[];
            if(direccion=="centro"){
                $scope.indice=indice;
                $scope.idficha=id;
            }else if(direccion=="left"){
                $scope.indice=(indice-1>-1) ? indice-1 : $scope.list_data.length-1;
                $scope.idficha=$scope.list_data[indice].nfic
            }else if(direccion=="right"){
                $scope.indice=(indice+1<$scope.list_data.length) ? $scope.indice=indice+1 : 0;
                $scope.idficha=$scope.list_data[indice].nfic
            }
            comunicarconservidor('<?php echo base_url()?>home/libros/json',{ idficha:$scope.idficha},mostrardatosficha);  
        }
        function edicion(elem,str){
            datos={idficha:$scope.datoslibro['idficha'],campo:elem,valor:str};
            comunicarconservidor('<?php echo base_url()?>home/libros/editar',datos,mensajeresutlado);
            $scope.buscaridficha($scope.datoslibro['idficha'],'centro',$scope.indice);
        }   
        mostrardatosficha=function(resultados){     
            $scope.datoslibro=resultados;
            $scope.autores=$scope.datoslibro['nameautor'].split('|');
            $scope.materias=$scope.datoslibro['stema'].split('|');
        }
        procesarresutlado=function(resultados){
            $scope.list_data=resultados.ejemplares;
        }
        mensajeresutlado=function(resultados){
            if(resultados.exito){
                alertify.success(resultados.mensaje);
            }else{
                alertify.error(resultados.mensaje);
            }
        }
        mostrarocultarlemento=function(elem){
            if($(elem).is(':visible')){
                $(elem).fadeOut();
            }else{
                $(elem).fadeIn();
            }
        }
        $scope.iniciaredit=function(){
            if($('.editarlibro').is(':visible')){
                $('.editarlibro').fadeOut(100);
                $('#selecautores').fadeOut(100);
                $('#autorplus').fadeOut(100);
                $('#selectmaterias').fadeOut(100);
            }else{
                $('.editarlibro').fadeIn(100);
            }
        }
        $scope.mostraragregarautor=function(){
            mostrarocultarlemento('#autorplus');
        }
        $scope.mostraragregarmateria=function(){
            mostrarocultarlemento('#materiaplus');   
        }
        $scope.ver=function(indice){
            if($("#panel"+indice).hasClass('hidden')){
                $("#panel"+indice).removeClass('hidden');
                $("#boton"+indice).text('Ocultar');
            }else{
                $("#panel"+indice).addClass('hidden');
                $("#boton"+indice).text('Ver');
            }
        }
        $scope.quitarautor=function(nameautor){
            alertify.confirm("<p>¿Estas seguro de quitar al autor de este libro?<br><br>", function (e) {
                if (e) {
                    datos={idficha:$scope.datoslibro['idficha'],name:nameautor}
                    accionautor(datos,'<?php echo base_url()?>home/autor/eliminar');
                }
            }); 
        }
        $scope.quitarmateria=function(namemateria){
            alertify.confirm("<p>¿Estas seguro de quitar la materia de este libro?<br><br>", function (e) {
                if (e) {
                    datos={idficha:$scope.datoslibro['idficha'],name:namemateria}
                    accionautor(datos,'<?php echo base_url()?>home/materia/eliminar');
                }
            });   
        }
        $scope.total_autores_por_autor=0;
        $scope.total_materia_por_libro=0;
        function get_total_autores_por_autor(result){
            $scope.total_autores_por_autor=result.total;
            alertify.confirm("<p>Esta acción afectara a "+$scope.total_autores_por_autor+" libros relacionados con este autor</p><p>¿Esta seguro de continuar?<br><br>", function (e) {
                if (e){
                    datos={nameautor:$scope.nameautor}
                    accionautor(datos,'<?php echo base_url()?>home/autor/baja');
                }
            }); 
        }
        function get_total_materia_por_libro(result){
            $scope.total_materia_por_libro=result.total;
            alertify.confirm("<p>Esta acción afectara a "+$scope.total_materia_por_libro+" libros relacionados con esta materia</p><p>¿Esta seguro de continuar?<br><br>", function (e) {
                if (e){
                    datos={namemateria:$scope.namemateria}
                    accionautor(datos,'<?php echo base_url()?>home/materia/baja');
                }
            }); 
        }
        $scope.dardebajaautor=function(nameautor){
            $scope.nameautor=nameautor;
            datos={nameautor:nameautor};
            comunicarconservidor('<?php echo base_url()?>home/autor/contar_por_autor',datos,get_total_autores_por_autor);
        }
        $scope.dardebajamateria=function(name){
            $scope.namemateria=name;
            datos={namemateria:name};
            comunicarconservidor('<?php echo base_url()?>home/materia/contar_por_materia',datos,get_total_materia_por_libro);
        }
        $scope.editarautor=function(nameautor){
            datos={name:nameautor,nuevoname:"",idficha:$scope.datoslibro['idficha']};
            ediciones(nameautor,'<?php echo base_url()?>home/autor/editar',
                "Editar autor: ",datos,accionautor);              
        }
        function ediciones(campo,url,mensaje,datos,funcion_a_ejecutar){
            alertify.prompt(mensaje+campo,function(e,str){
                if(e){
                    datos['nuevoname']=str;
                    funcion_a_ejecutar(datos,url);
                }
            });
        }
        $scope.editarmateria=function(namemateria){
            datos={name:namemateria,nuevoname:"",idficha:$scope.datoslibro['idficha']};
            ediciones(namemateria,'<?php echo base_url()?>home/materia/editar',
                "Editar materia: ",datos,accionautor);   
        }
        $scope.editar=function(elem,label){
            if(elem!='autor'&&elem!='clasificacion'&&elem!='materia'&&elem!='editorial'){
                alertify.prompt("Modificar "+label, function (e, str) { 
                if (e){
                  edicion(elem,str);
                }
              });
            }else if(elem=='autor'){
                mostrarocultarlemento('#selecautores');
            }else if(elem=='materia'){
                mostrarocultarlemento('#selectmaterias');
            }else if(elem=='clasificacion'){
                alertify.log();
                mostrarocultarlemento('#selectclasificacion');
            }
        }
        function descripción(){
            var existe=0;
            $scope.listanuevos.forEach(function(elem,posicion){
                valor=(document.getElementById("vol"+posicion).value!=''||elem.panel=="warning"||document.getElementById("tomo"+posicion).value!=''||document.getElementById("nad"+posicion).value!='');
                existe= valor ? existe+1 :existe +0;       
             });
            return existe;
        }
        buscar_storage_ficha=function(idficha){ 
            comunicarconservidor('<?php echo base_url()?>home/libros/json',{ idficha:idficha},funcionstorage); 
        }
        funcionstorage=function(result,datos){
            lista=[];
            result.nfic=result.idficha;
            lista.push(result);
            $scope.list_data=lista;
            $scope.buscaridficha(result.nfic,'centro',0)
            setTimeout(mostrarmodal,500);            
        }    
        mostrarmodal=function(){
            $('#myModal').modal('toggle');
            $scope.iniciaredit()
        }
            $scope.init=function(){
                if(window.localStorage.getItem('idficha')){
                    buscar_storage_ficha(window.localStorage.getItem('idficha'));
                    localStorage.clear();
                }
            }
        $scope.init();
        });
        app.controller("listacontroller",function ($scope,$http){
          $scope.listanuevos=[];
          //var con=0;
           $scope.agreganad=function(tecla,indice){
            if(tecla.keyCode==13){
                $scope.listanuevos[indice].nad=document.getElementById("nad"+indice).value;
                $scope.listanuevos[indice].edit="true";//en caso de editar coloca el atributo edit para no modificar el numero de adquisicion al llamar al metodo asignaadquisicion
                $('#nad'+indice).val('');
                $("#nad"+indice).attr("type","hidden");
                asignarnadquisicion();
            }
          }
        $scope.disponibleejem=function(indice,idelem){
            if(document.getElementById(idelem+indice).checked){
                $scope.listanuevos[indice].dispo=true;
            }else{
                $scope.listanuevos[indice].dispo=false;
            }
            console.log($scope.listanuevos)
        }
        $scope.cbejem=function(indice,idelem){
            if(document.getElementById(idelem+indice).checked){
                $scope.listanuevos[indice].cb=true;
            }else{
                $scope.listanuevos[indice].cb=false;
            }
            console.log($scope.listanuevos);
        }
           $scope.agregadatoejemplar=function(tecla,indice,idelem){
            if(tecla.keyCode==13){
                if(idelem=="tomo"){
                    $scope.listanuevos[indice].tomo=document.getElementById(idelem+indice).value;
                }else if(idelem=="vol"){
                    $scope.listanuevos[indice].volumen=document.getElementById(idelem+indice).value;    
                }else if(idelem=="ejemplar"){
                    $scope.listanuevos[indice].ejemplar=document.getElementById(idelem+indice).value;    
                }
                $('#'+idelem+indice).val('');
                $("#"+idelem+indice).attr("type","hidden");
            }
          }
     
          $scope.agregar=function(titulo,nfic){
          if($scope.listanuevos.length<5){ 
            //agregar un nuevo ejemplar  a la lista de envios
                $scope.listanuevos.push({titulo:titulo,nfic:nfic,panel:"info",dispo:1,cb:true});
              asignarnadquisicion(); 
              botonevaciar();
            }else{
                alertify.error("Solo es posible enviar 5 libros a la vez");
            } 
        }
        $scope.vaciar=function(){
            $scope.listanuevos=[];
            botonevaciar();
          }
         $scope.datosejemplar=function(indice){
            if($(".datosejem"+indice).is(':hidden')){
                $(".datosejem"+indice).removeAttr("type","hidden");
            }else{
                $(".datosejem"+indice).attr("type","hidden");
            }
         }
          asignarnadquisicion=function(){
             $http({
                url:'<?php echo base_url()?>home/libros/id_max',
                method:'GET'
            }).success(function(result){ 
                $scope.idmax= result;
                asignando();   
            });
            
        }
        asignando=function(){
            con=1;
            $scope.listanuevos.forEach(function(elem,posicion){
                    if(elem.edit!="true"&&elem.panel!="success"){
                        elem.nad=parseInt($scope.idmax)+con;
                        con=con+1;
                    }
                });  
            $scope.listanuevos.forEach(function(elem){
            if(panelwarni(elem.nad)>1||elem.nad==""){
                elem.panel="warning";
                }else if(elem.panel!="success"){
                    elem.panel="info";
                    } 
            });                
          }
            panelwarni=function(nd){
                cont=0;
                $scope.listanuevos.forEach(function(elem,posicion){
                    if(elem.nad==nd){
                        cont=cont+1;
                    }
                });
                return cont;
            }
        botonevaciar=function(){
            if($scope.listanuevos.length>0){
                $("#vaciarlista").removeAttr("class","hidden");
            }else{
                $("#vaciarlista").attr("class","hidden");
            }
        }
         $scope.eliminar=function(ids){
            $scope.listanuevos.splice(ids,1);
            botonevaciar();
            asignarnadquisicion(); 
        }
        }); 
       $( document ).ready(function() {
            $("#page-wrapper").show();
        });
            
</script>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
<link rel="stylesheet" href="<?php echo site_url(); ?>css/libros/carga.css" type="text/css" />
