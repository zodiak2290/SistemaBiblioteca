<div class="panel-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" >
                    <thead>
                        <tr> 
                            <th ng-click="ordenar('t')">Dia</th>
                            <th ng-if="listafechas.length>0"><button ng-click="subir()">Publicar</button></th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="n in listafechas" ng-if="listafechas.length>0">
                      <tr ng-if="!n.id">
                        <td><a ng-click="eliminar($index)"><span class="icon-bin"></span></a></td>                                                            
                        <td class="dia">{{n.dia}}</td> 
                      </tr>
                    </tbody>
                    <tbody ng-if="listafechas.length<=0">
                     <tr>
                        <td colspan="8" align="center">
                          No hay fechas
                      </td>
                     </tr> 
                    </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <div id="divfechas" hidden class="pull-left windows8 container">
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
         <!-- /.col-lg-12-->
    </div>
     <!-- /.row -->
    <div class="col-md-12" align="center" ng-if="insert.length>0">
        <span class="alert alert-info">{{insert}}</span>
    </div>                     
</div>
<!-- /.panel-body -->