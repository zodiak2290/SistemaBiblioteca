<nav class="navbar navbar-default" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse">
      <span class="sr-only">Desplegar navegación</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
  <ul class="nav navbar-nav navbar-left">
      <BUTTON id="mnus">         
        <span class=" text-muted icon-menu"> </span> 
      </BUTTON>
  </ul>
    <ul id="menuusuario" class="nav navbar-nav navbar-right">
      <li class="dropdown"  ng-if="datosuser!=false">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Préstamos
              <span class="badge">{{datosuser.length}}</span>
              <b class="caret"></b>
          </a>
        <ul class="dropdown-menu dropdown-tasks">
           <p>
              <li ng-repeat="n in datosuser">
              <a>
                  <p>
                    <em>
                      <strong>Titulo:</strong>
                    </em>
                    <em>
                      {{n.titulo.split("/")[0]|capitalize}}
                    </em>
                  </p>
                  <p><em><strong>Fecha de Préstamo</strong></em>
                 <em>{{n.nombredia}}-{{n.dia}} de {{n.periodo}} del {{n.anio}}</em>
                 </p>
                  <p>
                      Renovación: {{n.contreno}}
                  </p>
                  <p>
                    Entrega:{{n.diaentrega}}-{{n.diaen}} de {{n.mesentrega}} del {{n.anioe}}
                  </p>
              <div class="progress active">
                <div  class="progress-bar progress-bar-{{n.barra}}  "role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 1em; width:{{n.porcentaje}}%" >
                </div>
              </div>
              </a>
            </li>
            </p>
            <li class="divider"></li>
        </ul>
      </li>
      <li class="dropdown" id="datuser" ng-if="reservas!=false">
        <a href="#" clas="dropdown-toggle" data-toggle="dropdown">
          Reservas <span class="badge">{{reservas.length}}</span>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
           <li ng-repeat="n in reservas">
                <a> 
                <p>Titulo: <em>{{n.titulo.split("/")[0]|capitalize}}</em></p>
                <p>Fecha de vencimiento:<em>{{n.diaven}}-{{n.dia}} de {{n.mesven}} del {{n.anio}}
                    Hora:{{n.fecha.split(" ")[1]}}</em>
                </p>
                <p style="font-family: cursive;">{{n.mensaje}}</p>
                <div id="delet{{n.idreserva}}" class="pull-right" ng-click="eliminareserv($index)">
                  <button class="btn btn-danger">
                    <span class=" text-muted small icon-cross" style="color:white" > 
                      <em>Cancelar Reserva</em>
                    </span> 
                  </button> 
                </div>
                </a>
            </li>
        </ul>
      </li> 
    </ul>
  </div>
</nav>
<script type="text/javascript">
      $("#mnus").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
