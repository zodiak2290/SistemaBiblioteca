<div id="page-wrapper" class="col-md-12" >
<div class="row row-centered">
<div class="col-lg-12" ng-app="appBiblio" ng-controller="prestamoscontroller" ng-cloak>
<div ng-init="url='<?php echo base_url()?>home/prestamosv'"></div>
<div ng-init="get_prestamos()"></div>
    <div class="col-lg-12">
      <div class="col-lg-12">
         <?php $this->load->view('prestamo/menuprestamo');?> 
      </div>
    </div>
              <div class="table-responsive col-lg-12"><br/>
                  <table class="table table-bordered table-hover table-striped" id="tabla">
                      <thead>
                        <tr>
                          <th colspan="2">
                           <em>N° de registros: </em>
                            <select ng-model="limite" ng-change="cargaprestamos()" class="form-control">
                              <option value="10">10</option>
                              <option value="20">20</option>
                              <option value="30">30</option>
                            </select>
                          </th>
                          <th>
                            <label>Búsqueda por:</label>
                          </th>
                          <th colspan="2">
                            <input type="number" class="form-control" min="1" ng-model="nadqui" placeholder="N° Adquisición" ng-keyup="buscar($event,'n')">
                            <input type="number" class="form-control" min="0" ng-model="cuenta" placeholder="Cuenta Usuario" ng-keydown="espacio($event)"  ng-keyup="buscar($event,'c')">
                          </th>
                          <th>
                              <div class="col-lg-6 alert-warning" id="press" hidden>
                                <label><em>Presiona Enter para buscar</em>
                                </label>
                              </div>
                          </th>
                        </tr>                     
                         <tr>        
                          <th><span ng-if="inicio>0" ng-click="paginar('izq')"  class="icon-arrow-left" style="cursor: pointer;"></span> 
                          </th>
                          <th ng-click="ordenar('cuenta')" style="cursor:pointer">Cuenta Usuario <span class="caret " ></span></th>
                          <th ng-click="ordenar('nombre')" style="cursor:pointer">Nombre Usuario <span class="caret " ></span></th>
                          <th ng-click="ordenar('nadqui')" style="cursor:pointer">N° Adquisición <span class="caret " ></span></th>
                          <th ng-click="ordenar('titulo')" style="cursor:pointer">Título <span class="caret " ></span></th>
                          <th ng-click="ordenar('entrega')" style="cursor:pointer">Dias de retraso <span class="caret " ></span></th>
                          <th><span ng-if="limite==resultados.length" ng-click="paginar('der')" class="icon-arrow-right" style="cursor: pointer;"></span></th>
                          </tr>
                      </thead>
                      <tbody ng-repeat="n in resultados|orderBy:campo:orden">
                        <tr >
                          <td><a  data-toggle="modal" href="#myModal" type="button" ng-click="infoven(n.cuenta,n.nadqui)" ><span class="icon-eye"></span></a></td>                                                            
                          <td class="cuenta">{{n.cuenta}}</td>
                          <td class="nombre">{{n.pnombre|capitalize}}</td>
                          <td class="nadqui">{{n.nadqui}}</td>
                          <td class="titulo">{{n.titulo|capitalize}}</td> 
                          <td class="fechae">{{n.diasretraso}}</td>
                          <td></td>   
                        </tr>
                      </tbody>
                      <tbody ng-if="resultados.length<=0">
                       <tr>
                          <td colspan="8" align="center">
                            No hay resultados
                        </td>
                       </tr> 
                      </tbody>
                  </table>
                            <div class="col-lg-12">
                                  <div id="cargando" hidden class="pull-rigth windows8">
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
              <!-- /.table-responsive -->
          <div class="modal fade " id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog" >
                               <div class="modal-content">
                                    <div class="modal-header" >
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>  
                                        <em class="pull-left"><strong>N° Adquisición: {{datosvencimiento.nadqui}}</strong></em>
                                        <br/>
                                        <em><h4>Título:{{datosvencimiento['titulo']|capitalize}}</h4></em>
                                        <br/>  
                                        <em class="text-muted pull-left">Nombre de usuario:{{datosvencimiento['pnombre']|capitalize}} Cuenta:{{datosvencimiento['cuenta']}} </em>
                                        <br/>
                                    </div>
                                    <div class="modal-body container-fluid col-md-12">                                         
                                        <div class="row">
                                            <div class="col-md-12 pull-left">
                        Fecha entrega: {{datosvencimiento['diaentrega']}} {{datosvencimiento['diaen']}} de {{datosvencimiento['mesentrega']}}
                                              <br>
                                              Dias de retraso: {{datosvencimiento['diasretraso']}}
                                            </div>
                                            <div class="col-md-12 pull-left" >
                                              <em ng-if="datosvencimiento['nombreaval'].length>0" class="text-muted pull-left">Nombre de aval:{{datosvencimiento['nombreaval']|capitalize}} </em>
                                            <br/>
                                            <em ng-if="datosvencimiento['nombreaval'].length>0" class="text-muted pull-left">Email de aval:{{datosvencimiento['emailaval']}} </em>
                                            <br>
                                            <em ng-if="datosvencimiento['telefonoaval'].length>0" class="text-muted pull-left">Teléfono de aval:{{datosvencimiento['telefonoaval']}} </em>
                                            <br/>
                                            </div> 
                                            </div>
                                       </div> 
                                  
                                       <hr>
                                        <div class="container-fluid">
                                            <div class="row">
                                                
                                            </div>                                        
                                        </div>
                                          <p>        
                                </div>
                    </div>
                </div>
               </div>
    </div> 
<script src="<?php echo site_url(); ?>js/scriptdatos/prestamos.js"></script> 
    <script type="text/javascript">
    $( document ).ready(function() {
            $('#vencido').attr("class","active");
        });
    </script>
</div>