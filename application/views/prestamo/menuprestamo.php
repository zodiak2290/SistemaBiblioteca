</br>
<nav class="navbar navbar-default" role="navigation">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/libros/carga.css">
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
    <ul class="nav nav-tabs"></br>
		   <li id="nuevo"><a href="<?php echo base_url();?>home/prestamo">Nuevo préstamo</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          Préstamos <b class="caret"></b>
        </a>
        <ul class="dropdown-menu" >
          <li id="vencido" ><a href="<?php echo base_url();?>home/prestamos/vencidos">Préstamos vencidos</a></li>
          <li id="enprestamo"><a href="<?php echo base_url(); ?>home/prestados">Préstamos vigentes</a></li>
        </ul>
     
      </li>
	  <li id="reserva"><a href="<?php echo base_url();?>home/reservas">Reservas</a></li>
    </ul>
    <div class="navbar-form navbar-left" role="search" hidden id="buscar">
      <div class="form-group">
        <input id="inpuser" type="number" min="0" ng-model="cuenta" placeholder=" Cuenta"  />
      </div>
      <button id="nu" ng-click="nuevouser()" class="btn btn-success">Nuevo usuario</button>
      <button id="btnb" ng-click="buscaruser()" class="btn btn-info">Buscar</button>
    </div>
    <ul id="menuusuario" class="nav navbar-nav navbar-right" >
      <li style="margin-right:10px;">
        <STRONG ng-if="datosuser.length>0">Usuario({{cuenta}}):</STRONG>
          <em>
            {{datosuser[0]['apPat']|capitalize}} {{datosuser[0].apMat|capitalize}} 
            {{datosuser[0].pnombre|capitalize}} {{datosuser[0].snombre|capitalize}} :
          </em>
      </li>
      <li class="dropdown" id="datuser" >
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              Multas
              <span class="badge" ng-if="multas!=false&& datosuser.length>0">{{multas.length}}</span>
              <span class="badge" ng-if="multas==false&& datosuser.length>0">0</span>
              <span class="badge" ng-if="multas!=false&& datosuser.length==0">?</span>
              <b class="caret"></b>
          </a>
        <ul class="dropdown-menu" ng-if="datosuser.length>0 && multas!=false">
           <li ng-repeat="m in multas" >
              <a><em>Fecha:</em>{{m.fechamulta}}
              <em>Monto:</em>{{m.total}}
              <em>Retraso:</em>{{m.retraso}} dia(s)
              <button class="btn btn-info" ng-click="saldar($index)">Saldar</button>
              </a>
            </li>
        </ul>
      </li>
      <li class="dropdown" id="datuser" >
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              Bloqueado
              <span class="badge" ng-if="bloqueado!=false && datosuser.length>0"><span class="icon-lock"></span></span>
              <span class="badge" ng-if="bloqueado==false"><span class="icon-unlocked"></span>
              <span class="badge" ng-if="bloqueado!=false"></span>
          </a>
        <ul class="dropdown-menu" >
           <li ng-repeat="m in bloqueado">
              <a  data-toggle="modal" href="#myModal" type="button" >
                <span id="es" class="icon-unlocked">
                  <em>Motivo:</em>{{m.observaciones}}
                </span>
                <button class="btn btn-info" ng-click="bloquear(m.cuentauser)">Desbloquear</button>
              </a>
            </li>
        </ul>
      </li> 
    </ul>
  </div>
</nav>
