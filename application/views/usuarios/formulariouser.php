<md-content layout-padding>
      N° de resultados:  <select class="form-control" ng-model="limitar" ng-change="cargalibros()">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="25">30</option>
                                  </select>
    <form name="userForm">
      	<md-input-container class="md-block" flex-gt-sm>
        	<label>Titulo</label>
            <input type="text" name="titulo" md-maxlength="150" ng-model="titulo"  ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,255}$/">  
	        <div ng-messages="userForm.titulo.$dirty &&userForm.titulo.$error" ng-if="!showHints">
	          	<div ng-message="md-maxlength">El titulo debe de ser menor de 150 caracteres.</div>
	          	<div ng-message="pattern">Formato no válido</div>
	        </div>
      	</md-input-container>
      	<md-input-container class="md-block" flex-gt-sm>
        	<label>Autor</label>
            <input type="text" name="autor" md-maxlength="50" ng-model="autor"  ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,50}$/">  
	        <div ng-messages="userForm.autor.$dirty &&userForm.autor.$error" ng-if="!showHints">
	          	<div ng-message="md-maxlength">El autor debe de ser menor de 50 caracteres.</div>
	          	<div ng-message="pattern">Formato no válido</div>
	        </div>
      	</md-input-container>
      	<md-input-container class="md-block" flex-gt-sm>
        	<label>Materia</label>
            <input type="text" name="materia" md-maxlength="50" ng-model="materia" ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,50}$/">  
	        <div ng-messages="userForm.materia.$dirty &&userForm.materia.$error" ng-if="!showHints">
	          	<div ng-message="md-maxlength">La materia debe de ser menor de 50 caracteres.</div>
	          	<div ng-message="pattern">Formato no válido</div>
	        </div>
      	</md-input-container>
      	<md-input-container class="md-block" flex-gt-sm>
        	<label>Editorial</label>
            <input type="text" name="editorial" md-maxlength="50" ng-model="editorial" ng-pattern="/^[A-Za-z ñáéíóú?0-9_.\-:,;]{4,50}$/">  
	        <div ng-messages="userForm.editorial.$dirty &&userForm.editorial.$error" ng-if="!showHints">
	          	<div ng-message="md-maxlength">La editorial debe de ser menor de 50 caracteres.</div>
	          	<div ng-message="pattern">Formato no válido</div>
	        </div>
      	</md-input-container>
	    <div class="bottom-sheet-demo inset" layout="row" layout-sm="column" layout-align="space-around" >
	      	<md-button ng-click="cargalibros()" ng-disabled="!userForm.$valid||!buscando" flex="20" class="md-primary md-raised" >Buscar</md-button>
	    </div> 
    </form>
</md-content>


