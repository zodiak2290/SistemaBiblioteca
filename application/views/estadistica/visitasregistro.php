            <div id="registro" class="container" ng-app="pi" ng-controller="picontroller" hidden>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading text-center">Registro</h2>
                    <h3 class="section-subheading text-muted"></h3>
                </div>
            </div>  
            <div class="row">
                <div class="col-lg-8">           
                        <div class="row" >
                         <div id="mesaje" class="alert alert-success alert-dismissable col-md-6" hidden>
		                  <button type="button" class="close" data-dismiss="alert">&times;</button>
		                   <span id="registroCorrecto" ></span>
		                </div>
                        <div class="col-md-6 text-center"></div>
                            <div class="clearfix"></div>
                            <div class="col-md-6"></div>
                            <div class="col-lg-12 text-center" > 
                                
                              <div class="col-md-4" > </br>
                                <button ng-click="agregaed('hd')" type="submit" class="btn btn-xl">Hasta 12 años</button>
                              </div> 
                              <div class="col-md-4" > </br> 
                                <button ng-click="agregaed('et')" type="submit" class="btn btn-xl">13 a 17 años</button>
                              </div>
                              <div class="col-md-4" >   </br>
                                <button ng-click="agregaed('md')" type="submit" class="btn btn-xl">18 en adelante</button>
                              </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
<script type="text/javascript">
 $( document ).ready(function() {
            $("#registro").show();
        });   
 var app=angular.module("pi",[]);
 app.controller("picontroller",function ($scope,$http){
		$scope.agregaed=function(edad){ 
      $scope.edad=edad;
 		$http({
          url: '<?php echo base_url()?>home/registro/agregar/json',
           method: 'POST',
           data: { edad:$scope.edad}     
          }).success(function(result){
            alertify.success(result.mensaje);
          console.log(result);
        }).
            error(function(result){
              console.log(result);
            });
            }
        });
</script>
