             
                        
<div class="row" id="act" hidden>

    <div class="col-lg-7"  ng-app="actividad" ng-controller="actcontroller"></br>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
			<div class="table-responsive col-lg-12">
				<table class="table table-bordered table-hover table-striped">
				    <thead>
				       <tr>        
				        <th><span ng-if="inicio>0&&(actividadessf.length<10)" ng-click="paginar('izq')"  class="icon-arrow-left"></span> 
				        </th>
				        <th ng-click="ordenar('cuenta')" >Nombre</th>
				        <th ng-click="ordenar('nombre')">Lugar</th>
				        <th>Eliminar</th>
				    </thead>
				    <tbody ng-repeat="n in actividadessf">
				      <tr >
				        <td><a href="<?php echo base_url()?>home/actividad/{{n.idactividad}}">Agregar fechas</a></td>                                                            
				        <td class="cuenta">{{n.nombre}}</td>
				        <td class="nombre">{{n.lugar}}</td>
				        <td><button type="button" class="btn btn-danger" ng-click="deleteactividad(n.idactividad)" ><i class="fa fa-times"></i><span class="icon-bin"></span></button> </td>   
				      </tr>
				    </tbody>
				    <tbody ng-if="vencidos.length<=0">
				     <tr>
				        <td colspan="8" align="center">
				          No hay resultados
				      </td>
				     </tr> 
				    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

 var app=angular.module("actividad",[]);    
        app.controller("actcontroller",function ($scope,$http){
        		$scope.get_actividadess=function(){
                $http({url:'<?php echo base_url()?>home/actividadessfecha',method:'GET'}).success(function(result){ 
                         $scope.actividadessf=result.actividadesf;
                         console.log(result);
                        }).error(function(result){            
                             console.log(result);
                      });
     
            }
     $scope.deleteactividad=function(id){
		 alertify.confirm("<p>¿Deseas eliminar este registro?<br><br>", function (e) {
	        if (e) {
	        	window.location.replace('<?=base_url()?>home/evento/borrar/'+id);
	        }
	    }); 
	}
          $scope.get_actividadess();   
        });
        $( document ).ready(function() {
            	$('#act').fadeIn(100);

        });
</script>
