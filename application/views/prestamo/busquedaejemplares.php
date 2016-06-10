 <md-whiteframe  class="md-whiteframe-10dp" flex-sm="50" flex-gt-sm="50" flex-gt-md="90" layout layout-align="center center">
    <md-content  class="md-padding">
        <div id="buslibro" hidden>
            <md-input-container class="md-block" flex-gt-sm>
                <label>Nº Adquisición</label>
                <input ng-model="nadqui" type="number" min="1"  />  
            </md-input-container>
            <div class="bottom-sheet-demo inset" layout="row" layout-sm="column" layout-align="space-around" >
                <md-button ng-click="buscarejem()"  flex="20" class="md-primary md-raised" >Agregar</md-button>
            </div> 
        </div>
            <div ng-messages="mensaje.length>0" >
                <div>{{mensaje}}</div>
            </div>
        <div ng-messages="true" >
            <div class="row col-lg-12" id="limite" hidden align="center" >
                   <div class="row" align="center">
                        <p><em style="font-family: cursive;">Límite de libros alcanzado</em></p>
                        <strong style="color:rgba(7,7,7,0.5)">El usuario ya no puede solicitar más Ejemplares</strong>
                    </div>
              <!--<button id="pres" ng-click="permiripres()">Permitir Préstamo</button>-->
            </div>
        </div>
         <div class="container col-lg-12" id="pres"  align="center"  >
              <em ng-if="mensajepres.length>0">{{mensajepres}}</em><br/>
              <em ng-if="mensajemulta.length>0">{{mensajemulta}}</em>
              <!--<button id="pres" ng-click="permiripres()">Permitir Préstamo</button>-->
        </div>
        <div id="divbususer" class="pull-left">
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
    </md-content>
 </md-whiteframe>