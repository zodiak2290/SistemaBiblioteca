<script src="<?php echo site_url(); ?>js/scriptdatos/prestamos.js"></script>
<div id="page-wrapper" class="col-md-12" >
<div class="row row-centered">
<div class="col-lg-12" ng-app="appBiblio" ng-controller="prestamoscontroller" ng-cloak>
<div ng-init="url='<?php echo base_url()?>home/descartados'"></div>
<div ng-init="get_prestamos()"></div>
              <div class="table-responsive col-lg-12"><br/>
                  <table class="table table-bordered table-hover table-striped">
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
                            <span ng-if="inicio>0" ng-click="paginar('izq')"  class="icon-arrow-left"></span> 
                          </th>
                          <th ng-click="ordenar('descartadopor')" >Descartado por: <span class="caret " style="cursor:pointer"></span></th>
                          <th ng-click="ordenar('fechadescarte')">Fecha de baja <span class="caret " style="cursor:pointer"></span></th>
                          <th ng-click="ordenar('nadqui')">N° Adquisición <span class="caret " style="cursor:pointer"></span></th>
                          <th ng-click="ordenar('titulo')">Título <span class="caret " style="cursor:pointer"></span></th>
                          <th ng-click="ordenar('criterio')">Criterio <span class="caret " style="cursor:pointer"></span></th>
                          <th >
                            <span ng-if="limite==resultados.length" style="cursor:pointer" ng-click="paginar('der')" class="icon-arrow-right"></span>
                          </th>
                          </tr>
                      </thead>
                      <tbody ng-repeat="n in resultados|orderBy:campo:orden">
                        <tr >
                          <td><a  data-toggle="modal" href="#myModal" type="button" ng-click="infoven(n.cuenta,n.nadqui)" >
                              <span class="icon-eye"></span></a></td>                                                            
                          <td class="cuenta">{{n.descartadopor}}</td>
                          <td class="nombre">{{n.fechadescarte}}</td>
                          <td class="nadqui">{{n.nadqui}}</td>
                          <td class="titulo">{{n.titulo|capitalize}}</td> 
                          <td class="fechae">{{n.criterio}}</td>
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
                                    </div>
                                    <div class="modal-body container-fluid col-md-12">                                         
                                        <div class="row">
                                            <p align="center">
                                                {{datosvencimiento['observaciones']|capitalize}}
                                            </p>
                                        </div>
                                    </div> 
                                       <hr>
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
    $( document ).ready(function() {
            $('#vencido').attr("class","active");
        });

    </script>
</div>