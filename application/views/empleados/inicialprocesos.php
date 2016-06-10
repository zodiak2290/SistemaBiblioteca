</br>
<div id="page-wrapper" hidden  >            
    <div class="row">
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row --></br>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="icon-books" style="font-size:500%"></span>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $totalejemplares?></div>
                            <div>Ejemplares</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row" ng-app="appBiblio" ng-controller="graficasController">
        <div class="col-lg-12" id="panelGrafica" ng-init="urlgraf='<?php echo base_url()?>'">                           
                         <md-content layout-padding>
                          <md-tabs md-dynamic-height md-border-bottom>
                            <md-tab label="Total de ejemplares por clasificación"> 
                              <md-content class="md-padding">   
                                <section layout="row" layout-sm="column" layout-align="center center" layout-wrap> 
                                    <canvas id="circular" style="heigt:200px width:100%" >
                                                                            
                                    </canvas>
                                </section>
                              </md-content>  
                            </md-tab>
                            <md-tab label="Préstamos por categoría">
                                <section layout="row" layout-sm="column" layout-align="center center" layout-wrap> 
                                   <canvas id="categoria" style="heigt:200px width:100%" >
                                                                            
                                    </canvas>
                                </section>
                            </md-tab>  
                          </md-tabs>
                          </md-content>

        </div>
    </div>
    
    <!-- /.row -->
</div> 
    <script>
     $( document ).ready(function() {
            $("#page-wrapper").show();
        });
         angularRoutingApp.controller("graficasController",function ($scope,miService,confighttpFactori){
        $scope.enviar=function(url,datos,idelem){
            confighttpFactori.setData(url,'POST',datos); 
            miService.getAll().then(function (result) { 
                console.log(result);
                console.log(idelem);
                set_Config(result,idelem);
            }).catch(function (message) {
                alertify.error("No fue posible conectarse al servidor");
            }).finally(function(){
                $("#grafica").removeClass('fondooscuro');
            });
        }
        $scope.init=function(){
            $scope.enviar('home/biblioteca/clasificacionlibros',[],'circular');
            $scope.enviar('home/biblioteca/prestamosporcategoria',[],'categoria');
        }
        function set_Config(datos,idelem){

            var ctx = document.getElementById(idelem).getContext("2d");
            window.myBar=new Chart(ctx).Doughnut(datos, {
                animateRotate : true,
                  animationEasing : "easeOutBounce",
                }
            );
            myBar.generateLegend();
        }
        $scope.init();
     
    });


    </script>
