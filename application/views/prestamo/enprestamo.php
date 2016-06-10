<div class="col-md-12" id="page-wrapper" hidden>
<script src="<?php echo base_url()?>js/angular-locale.js"></script>
    <div class="row row-centered">
        <div class="col-lg-12" ng-app="appBiblio" ng-controller="prestamoscontroller">
        <div ng-init="url='<?php echo base_url()?>home/prestamos'"></div>
        <div ng-init="get_prestamos()"></div>
        <div class="col-lg-12">
                     <?php $this->load->view('prestamo/menuprestamo');?> 
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
                            <input type="number" class="form-control"min="1" ng-model="nadqui" placeholder="N° Adquisición" ng-keyup="buscar($event,'n')">
                            <input type="number" min="0" class="form-control" ng-model="cuenta" placeholder="Cuenta Usuario" ng-keydown="espacio($event)"  ng-keyup="buscar($event,'c')">
                          </th>
                          <th>
                              <div class="col-lg-6 alert-warning" id="press" hidden>
                                <label><em>Presiona Enter para buscar</em>
                                </label>
                              </div>
                          </th>
                        </tr>
                         <tr>        
                          <th>
                          <a ng-if="inicio>0" ng-click="paginar('izq')">
                                  <span   style="cursor: pointer;" class="icon-arrow-left"></span></a>
                          </th>
                          <th ng-click="ordenar('cuenta')" style="cursor:pointer">Cuenta Usuario <span class="caret "></span></th>
                          <th ng-click="ordenar('pnombre')" style="cursor:pointer">Nombre Usuario <span class="caret"></span></th>
                          <th ng-click="ordenar('titulo')" style="cursor:pointer">Título <span class="caret " ></span></th>
                          <th ng-click="ordenar('entrega')" style="cursor:pointer">Fecha Entrega <span class="caret " ></span></th>
                          <th><a ng-if="limite==resultados.length" ng-click="paginar('der')">
                                  <span style="cursor: pointer;" class="icon-arrow-right"></span></a></th>
                          </tr>
                      </thead>
                      <tbody  ng-repeat="n in resultados|orderBy:campo:orden">
                        <tr>
                          <td></td>                                                    
                          <td class="cuenta">{{n.cuenta}}</td>
                          <td class="nombre">{{n.pnombre|capitalize}}</td>
                          
                          <td class="titulo">{{n.titulo|capitalize}}</td> 
                          <td class="fechae">{{n.entrega}}</td>
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
         
               </div>
    </div> 
<script src="<?php echo site_url(); ?>js/scriptdatos/prestamos.js"></script> 
<script type="text/javascript">    
    $( document ).ready(function() {
            $("#page-wrapper").fadeIn(1000);
            $('#enprestamo').attr("class","active");
        });
    </script>
