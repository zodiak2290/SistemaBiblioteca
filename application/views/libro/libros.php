<section id="update"  >        
      <md-content class="md-padding " layout="column" layout-align="center" ng-app="libros" ng-controller="librocontroller" ng-cloak>
        <section layout="row" layout-sm="column" layout-align="center center">
           <div class="col-lg-12">
                <?php $this->load->view('libro/menulibro');?> 
            </div>
        </section>
                <md-content class="md-padding " layout="column" layout-align="center">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Ficha del Libro 
                          <div id="marc" class="panel-body" >
                              <div>(Opcional) Llenar campos con formato MARC: <a ng-click="mostrarocultar()"><span id="aref">Ver Etiqueta</span></a></div> <div id="formato" style="font-size:10px;" class="hidden">
                                  ¦001000000012¦0052014-01-07 10:41:26¦020968-429-041-1¦035000000012¦041SPA¦082860U B4 1978¦100BENEDETTI, MARIO,1920-2009¦245LETRAS DE EMERGENCIA /MARIO BENEDETTI.¦2502A ED.¦260MEXICO :NUEVA IMAGEN,1978.¦300156 P.¦440¦500¦505¦600¦650LITERATURA URUGUAYA¦700¦710¦922JLV¦008ArrayÌ
                              </div>
                              <md-input-container class="md-block" flex-gt-sm>
                                <label>Etiqueta Marc</label>  
                                <input id="etmarc" type="text"  ng-model="marc" ng-keyup="etimar($event)"/>
                              </md-input-container>
                          </div>
                        </div>
                        <div id="manual" class="panel-body" >
                             <div class="row" id="datoslib">
                             <form novalidate name="userForm" id="miform">
                                <div class="col-md-12">             
                                    <div class="form-group col-md-4">                          
                                      <md-input-container class="md-block" flex-gt-sm>
                                          <label>N° Control</label>
                                          <input type="number" required min="1" name="ncontrol" ng-model="idlibro" />
                                          <div ng-messages="userForm.ncontrol.$dirty &&userForm.ncontrol.$error" ng-if="!showHints">
                                            <div ng-message="required">Campo Obligatorio.</div>
                                            <div ng-message="min">El Id de libro debe ser mayor que 0</div>
                                          </div>
                                      </md-input-container>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm>
                                        <label>ISBN</label>
                                        <input type="text"  name="isbn" ng-model="isbn"  ng-pattern="/^[0-9a-zA-Z-()ñáéíóú]{1,27}$/">
                                        <div ng-messages="userForm.isbn.$error" ng-if="!showHints">
                                          <div ng-message="pattern">No coincide con el patrón especificado</div>
                                        </div>
                                      </md-input-container>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm>
                                        <label>Idioma</label>
                                        <input type="text"   ng-model="idioma"  name="idioma" ng-pattern="/^[a-zA-Zñáéíóú]{1,7}$/">
                                        <div ng-messages="userForm.idioma.$dirty&&userForm.idioma.$error" ng-if="!showHints">
                                            <div ng-message="pattern">Formato no válido</div>
                                        </div>
                                      </md-input-container>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm>
                                        <label>Clasificación</label>
                                        <input type="text" name="clasificacion"  required  ng-model="clasificacion" ng-pattern="/^[a-zA-Z\s\dñáéíóú.\-]{1,20}$/">
                                        <div ng-messages="userForm.clasificacion.$dirty&&userForm.clasificacion.$error" ng-if="!showHints">
                                            <div ng-message="required">Campo Obligatorio</div>
                                            <div ng-message="pattern">Formato no válido</div>
                                        </div>
                                      </md-input-container>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <md-input-container class="md-block" flex-gt-sm>
                                          <label>Título</label>
                                          <input type="text" name="titulo" ng-model="titulo" required ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,255}$/">  
                                        <div ng-messages="userForm.titulo.$dirty&&userForm.titulo.$error" ng-if="!showHints">
                                            <div ng-message="required">Campo Obligatorio</div>
                                            <div ng-message="pattern">Formato no válido</div>
                                        </div>
                                        </md-input-container>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <md-input-container class="md-block" flex-gt-sm>
                                        <label>Edición</label>
                                    <input type="text" id="edicion" name="edicion" ng-model="edicion" ng-pattern="/^[A-Za-z0-9ñáéíóú_.\-:,;\s]{1,27}$/">
                                        <div ng-messages="userForm.edicion.$dirty&&userForm.edicion.$error" ng-if="!showHints">
                                            <div ng-message="pattern">Formato no válido</div>
                                        </div>                                          
                                        </md-input-container>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm>
                                        <label>Pie de imprenta</label>
                                        <input type="text" name="editorial" maxlength="80" minlength="5" ng-model="editorial" ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,80}$/">
                                        <div ng-messages="userForm.editorial.$dirty&&userForm.editorial.$error" ng-if="!showHints">
                                            <div ng-message="pattern">Formato no válido</div>
                                            <div ng-message="minlength">Campo demasiado corto</div>
                                            <div ng-message="maxlength">El campo es muy grande max:100 caracteres</div>
                                        </div>  
                                      </md-input-container>
                                    </div>
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm>
                                        <label>Descr. Fisica</label>
                                        <input type="text" ng-model="desf" name="desf" maxlength="80" ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,80}$/">
                                        <div ng-messages="userForm.desf.$dirty&&userForm.desf.$error" ng-if="!showHints">
                                            <div ng-message="pattern">Formato no válido</div>
                                            <div ng-message="maxlength">El campo es muy grande max:100 caracteres</div>
                                        </div> 
                                      </md-input-container>
                                    </div> 
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm>
                                        <label>Serie</label>
                                        <input type="text" ng-model="serie" name="serie" maxlength="80" ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:;,]{4,80}$/">
                                      </md-input-container>
                                        <div ng-messages="userForm.serie.$dirty&&userForm.serie.$error" ng-if="!showHints">
                                            <div ng-message="pattern">Formato no válido</div>
                                            <div ng-message="maxlength">El campo es muy grande max:100 caracteres</div>
                                        </div>                               
                                    </div>
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm>                                          
                                        <label>Nota General</label>
                                        <input type="text"   ng-model="ng" name="ng" maxlength="80" ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,80}$/">
                                        <div ng-messages="userForm.ng.$dirty&&userForm.ng.$error" ng-if="!showHints">
                                            <div ng-message="pattern">Formato no válido</div>
                                            <div ng-message="maxlength">El campo es muy grande max:100 caracteres</div>
                                        </div>  
                                      </md-input-container>
                                    </div> 
                                    <div class="form-group col-md-4">
                                      <md-input-container class="md-block" flex-gt-sm> 
                                        <label>Contenido</label>
                                        <input type="text" name="contenido"  ng-model="contenido" ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,80}$/">
                                        <div ng-messages="userForm.contenido.$dirty&&userForm.contenido.$error" ng-if="!showHints">
                                            <div ng-message="pattern">Formato no válido</div>
                                            <div ng-message="maxlength">El campo es muy grande max:100 caracteres</div>
                                        </div>  
                                      </md-input-container>
                                    </div>  
                              <md-content class="md-padding " layout="column" layout-align="center">
                                  <div class="form-group col-md-12">
                                    <md-chips ng-model="autores" md-require-match="false" readonly='false'>
                                          <md-autocomplete
                                                           md-search-text="searchText"
                                                           md-items="item in sugerira($event,searchText)"
                                                           md-item-text="item"
                                                           md-autoselect="false"
                                                           md-select-on-match="true"
                                                           md-no-cache="true"
                                                           md-floating-label="Autor"
                                                           placeholder="Seleccione autor..">
                                              <span md-highlight-text="searchText">{{item}}</span>
                                          </md-autocomplete>
                                          <md-chip-template >
                                              <span>{{$chip}}</span>
                                          </md-chip-template>
                                          <div ng-messages="userForm.autocomplete.$error">
                                            <div ng-message="required">This field is required</div>
                                          </div>
                                      </md-chips>
                                  </div>   
                                  <div class="form-group col-md-12">
                                     <md-chips ng-model="materias" md-autocomplete-snap  readonly='false' >
                                          <md-autocomplete 
                                                           md-search-text="searchText"
                                                           md-items="item in sugerirm($event,searchText)"
                                                           md-item-text="item"
                                                           md-autoselect="false"
                                                           md-select-on-match="true"
                                                           md-no-cache="true"
                                                           md-floating-label="Materia"                                                         
                                                           >
                                              <span md-highlight-text="searchText">{{item}}</span>
                                          </md-autocomplete>
                                          <md-chip-template >
                                              <span>{{$chip}}</span>
                                          </md-chip-template>
                                      </md-chips>
                                </div>
                            </md-content> 
                        </div>
                        <div class="bottom-sheet-demo inset" layout="row" layout-sm="column" layout-align="space-around" >
                          <md-button ng-click="guardar()" ng-disabled="!userForm.$valid" flex="20" class="md-primary md-raised" >Guardar</md-button>
                          <md-button ng-click="cancelar()"  flex="10" class="md-raised" >Limpiar</md-button>
                        </div>      
                            <!-- /.panel-body -->
                            </form>
                    </div>
                    <!-- /.panel -->
                </div>
        </div>
      </md-content><!-- -->
  </md-content><!-- Fin-->
</section>

<script type="text/javascript">
var app=angular.module("libros",['ngMaterial','ngMessages']);   
app.config(function($mdThemingProvider) {
  $mdThemingProvider.theme('default')
    .primaryPalette('brown').accentPalette('red');;
}); 
app.controller("librocontroller",function ($scope,$http){
    //Varibale que almacena formato marm partido
    $scope.campos=[]; 
    //almacena autores del libro
    $scope.autores=[];
    //almacena materias elegidas para el libro
    $scope.materias=[];
    //campo marc ingresado 
    $scope.marc="";
    //ficha libro inicial
    $scope.ficha="";  
    //sugerencias materias
    $scope.sugmat=[];
    //sugerencias autores
    $scope.sugautores=[];
    $scope.cancelar=function(){
      $scope.materias=[];
      $scope.autores=[];
      $scope.idlibro="";
      limpiaForm($("#miform"));
    }
    $scope.addautor=function(chip){
      console.log(chip);
    }
  function limpiaForm(miForm) {
      // recorremos todos los campos que tiene el formulario
      $(':input', miForm).each(function() {
      var type = this.type;
      var tag = this.tagName.toLowerCase();
      //limpiamos los valores de los campos…
      if (type == 'text' || type == 'password' || tag == 'textarea'||tag=='number')
      this.value = "";
      // excepto de los checkboxes y radios, le quitamos el checked
      // pero su valor no debe ser cambiado
      else if (type == 'checkbox' || type == 'radio')
      this.checked = false;
      // los selects le ponesmos el indice a -
      else if (tag == 'select')
      this.selectedIndex = -1;
      });
  }
  $scope.etimar=function(tecla){
      if(tecla.keyCode==13){   
          if($scope.marc.length>30){
              $scope.autores=[];
              $scope.materias=[];
              $scope.campos=$scope.marc.split("¦");
              partir();
          }
       }   
  }
  $scope.sugerirm=function(tecla,texto){
    console.log(tecla);
      if(texto.length>4){
               url='<?php echo base_url()?>home/materia/sugerir';
                $http({
                url: url,
                 method: 'POST',
                 data: {materia:texto}     
                }).success(function(result){
                  if(result.sugerencias){
                    $scope.sugmat=result.sugerencias;
                  }else if(texto.length>10){
                    $scope.sugmat=[];
                    alertify.error("No se encontraron sugerencias");
                  }
                  if(result.mensaje){
                    alertify.error(result.mensaje);
                  }
              }).
                error(function(result){
                  console.log(result);
                });
         }   
      return get_sugerencias($scope.sugmat,'namemateria');
    }
  $scope.sugerira=function(tecla,texto){
      texto=texto.toLowerCase();
      if(texto.length>4){
        url='<?php echo base_url()?>home/autor/sugerir';
        $http({
        url: url,
        method: 'POST',
        data: {autor:texto}     
        }).success(function(result){
          console.log(result);
          if(result.sugerencias){
            $scope.sugautores=result.sugerencias;
          }else if(texto.length>10){
            $scope.sugautores=[];
            alertify.error("No se encontraron sugerencias");
          }
        }).
        error(function(result){
          console.log(result);
        });
      }     
      return get_sugerencias($scope.sugautores,'nameautor');
    }
  function get_sugerencias(datos,tipo){
    data=[];
    if(datos.length>0){
      datos.forEach(function(elem,posicion){
        data.push(elem[tipo]);
      });
    }
    return data;
  }
  String.prototype.capitalizar=function(){
     return this.toLowerCase().replace(/(^|\s)([a-z])/g, function(m, p1, p2) { return p1 + p2.toUpperCase(); });
  }
            partir=function(){
             $scope.campos.forEach(function(elem,posicion){
                if(elem.substring(0,3)=="001"){                    
                   $scope.control=Number(elem.replace(elem.substring(0,3),""));
                }else if(elem.substring(0,3)=="020"){
                    $scope.isbn=elem.replace(elem.substring(0,3),"");
                }else if(elem.substring(0,3)=="035"){
                    $scope.ncontrol2=Number(elem.replace(elem.substring(0,3),""));
                }else if(elem.substring(0,3)=="041"){
                    $scope.idioma=elem.replace(elem.substring(0,3),"");
                }else if(elem.substring(0,3)=="082"){
                    $scope.clasificacion=elem.replace(elem.substring(0,3),"");
                }else if(elem.substring(0,3)=="100"){
                    autor=elem.replace(elem.substring(0,3),"").capitalizar();
                    $scope.autores.push(autor);
                }else if(elem.substring(0,3)=="245"){
                    titu=elem.replace(elem.substring(0,3),"");
                    $scope.titulo=titu.split("/")[0].capitalizar();
                }else if(elem.substring(0,3)=="250"){
                    $scope.edicion=elem.replace(elem.substring(0,3),"");
                }else if(elem.substring(0,3)=="260"){
                    $scope.editorial=elem.replace(elem.substring(0,3),"").capitalizar();
                }else if(elem.substring(0,3)=="300"){
                    $scope.desf=elem.replace(elem.substring(0,3),"");
                }else if(elem.substring(0,3)=="440"){
                    $scope.serie=elem.replace(elem.substring(0,3),"").capitalizar();
                }else if(elem.substring(0,3)=="500"){
                    $scope.ng=elem.replace(elem.substring(0,3),"").capitalizar();
                }else if(elem.substring(0,3)=="505"){
                    $scope.contenido=elem.replace(elem.substring(0,3),"").capitalizar();
                }else if(elem.substring(0,3)=="600"){
                    $scope.tema=elem.replace(elem.substring(0,3),"");
                }else if(elem.substring(0,3)=="650"){
                    materias=elem.replace(elem.substring(0,3),"").capitalizar();
                    $scope.materias=materias.split(/[,-]/);
                }
             });
            $scope.idlibro=($scope.control>0&&$scope.control!=0&&$scope.control!='') ?$scope.control : $scope.ncontrol2;         
            buscarejemplar($scope.idlibro);
        }
        buscarejemplar=function(ficha){
            url='<?php echo base_url()?>home/libro/ficha';
                  $http({
                  url: url,
                   method: 'POST',
                   data: {idfic:ficha}     
                  }).success(function(result){
                    console.log(result);
                  if(result.existe){
                        alertify.success("Este libro ya esta registrado");
                        $("#datoslib").hide(100);
                        
                  }else{
                    $("#datoslib").show(100);
                  }              
                }).
                  error(function(result){
                    console.log(result);
                  });
        }
              $scope.mostrarocultar=function(){
                    if($("#formato").hasClass('hidden')){
                       $("#formato").removeClass( "hidden" );
                       $('#aref').text('Ocultar');  
                    }else{
                        $("#formato").addClass( "hidden" );
                        $('#aref').text('Ver Ejemplo'); 
                        } 
                }
                $scope.guardar=function(){
                  alertify.log("Espere un momento...");
                  libro={idlibro:$scope.idlibro,isbn:$scope.isbn,
                         idioma:$scope.idioma,clasificacion:$scope.clasificacion,
                         titulo:$scope.titulo,edicion:$scope.edicion,
                         editorial:$scope.editorial,desc:$scope.desf,
                         serie:$scope.serie,notag:$scope.ng,
                         contenido:$scope.contenido
                        };

                  var materias=[];
                  $scope.materias.forEach(function(elem,posicion){
                       materias.push(elem.replace("-"," "));
                  }); 
                 autores=[];
                 $scope.autores.forEach(function(elem,posicion){
                       autores.push(elem);
                    });             
                    url='<?php echo base_url()?>home/agregar/libros';
                  $http({
                  url: url,
                   method: 'POST',
                   data: {lib:libro,mat:materias,aut:autores}     
                  }).success(function(result){
                    localStorage.clear();
                    alertify.success(result.mensaje);              
                }).
                  error(function(result){
                    console.log(result);
                  });
                }
               $scope.init=function(){
                if(window.localStorage){
                  $scope.marc=localStorage.getItem('marc');
                }
               } 
              $scope.init();       
     });
$( document ).ready(function() {
  $("#nuevo").attr("class","active");    
});
        </script>