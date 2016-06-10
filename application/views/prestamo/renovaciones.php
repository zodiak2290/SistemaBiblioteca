<div id="accionlibro" hidden  layout-sm="column" layout-align="center center" layout-wrap>
	<md-whiteframe  class="md-whiteframe-10dp" flex-sm="50" flex-gt-sm="50" flex-gt-md="90" layout layout-align="center center">
	 	<md-content  class="md-padding">
		    <div class="panel panel-info"  >
		            <div class="panel-heading" >
		                N° de adquisición {{acionlibro.nadqui}}
		                <p></p>
		                <em>{{acionlibro.titulo|capitalize}}</em>
		            </div>
		            <div class="panel-body" align="center">
		            <a class="btn btn-info" ng-click="devolver()" data-toggle="modal" >Devolver</a>
		            <button class="btn btn-info " ng-click="renovar(acionlibro.nadqui)">Renovar</button>    
		            </div>
		    </div>
		</md-content>
	</md-whiteframe>
</div>