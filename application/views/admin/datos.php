<?php 
    $contador=0; foreach ($datos as $row) {   
            $name=$row->nombre;
           $cuentaempleado=$row->cuenta;
           $email=$row->email;
 }
?> 
<div id="page-wrapper" class="col-md-10 text-center" ng-app="setting" ng-controller="setcontroller" ng-cloak>
<div class="panel panel-default" >
<div ng-init="url='<?php echo base_url()?>home/admin/editar'"></div>
<div ng-init="urlpass='<?php echo base_url()?>home/admin/editarpas'"></div>
<div ng-init="urlemail='<?php echo base_url()?>home/empleados/email/json'"></div>
<div ng-init="urlcuenta='<?php echo base_url()?>home/empleados/cuenta/json'"></div
            <div class="panel-body" >                 
              <ul class="list-group">
                  <li class="col-lg-12 list-group-item " align="left">
                    <div class="col-lg-4">
                        <strong>Nombre</strong>
                    </div>
                    <div class="col-lg-4">
                        <span id="nombrespan">
                            <?php echo ucwords(strtolower($name))?>
                        </span>
                    </div>
                    <?php if($rol==8){ ?>
                      <div  class="col-lg-4">
                          <a ng-click="mostrarocultar('nombre')">Editar</a>
                      </div>
                    <?php } ?>  
                    <div id="nombre" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                        <em style="font-size:10px;">
                            <strong>Nombre:</strong>
                        </em>
                        <input  id="nombret" style="font-size:10px;" type="text">
                        <button id="ss" ng-click="guardar('nombre')">Aceptar</button>
                    </div>
                  </li>
                  <li class="col-lg-12 list-group-item" align="left">
                       <div class="col-lg-4">
                            <strong>Cuenta Usuario</strong>
                        </div>
                        <div class="col-lg-4">
                            <span ><?php echo $cuentaempleado ?></span>
                        </div>
                        <div hidden class="col-lg-4">
                            <a ng-click="mostrarocultar('cuenta')">Editar</a>
                        </div>
                        <div id="cuenta" class="hidden col-lg-12" align="center">
                            <em style="font-size:10px;">
                                <strong>Nombre Usuario:</strong>
                            </em>
                            <input  id="cuenta" style="font-size:10px;" type="text">
                            <button id="buto2" ng-click="guardar('cuenta')">Aceptar</button>
                        </div>
                  </li>
                  <li class="col-lg-12 list-group-item " align="left">
                    <div class="col-lg-2">
                        <strong>Email</strong>
                    </div>
                    <div class="col-lg-6">
                        <span id="emailspan"><?php echo $email?></span>
                    </div>
                    <div class="col-lg-2">
                        <a ng-click="mostrarocultar('email')">Editar</a>                      
                    </div>
                    <div id="email" class="panel-body list-group-item-info hidden col-lg-12" align="center" >
                        <em style="font-size:10px;">
                            <strong>Email:</strong>
                        </em>
                        <input  ng-model="email" ng-change="validar(0)" style="font-size:10px;" id="emailt" placeholder="algo@example.com"  >
                        <span >{{emailvalido}}.</span>
                        <p></p>
                       <button id="buto" ng-click="guardar('email')">Aceptar</button>
                    </div>
                  </li>
                  <li class="col-lg-12 list-group-item" align="left">
                      <div class="col-lg-4"><strong >Contraseña</strong></div>
                      <div class="col-lg-4"><span>*********</span></div>
                      <div class="col-lg-4 pull-rigth">
                            <a ng-click="mostrarocultar('pass')">Cambiar</a>
                      </div>
                      <div id="pass" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                            <em style="font-size:10px;">
                                <strong>Contraseña actual:</strong>
                            </em>
                            <input ng-model="passac" style="font-size:10px;" type="password" id="passac">  
                              <p></p>
                              <em style="font-size:10px;"><strong>Nueva contraseña:</strong></em><input ng-model="passn" style="font-size:10px;" type="password" id="passn">
                              <p></p>
                              <em style="font-size:10px;"><strong>Confirmar contraseña:</strong></em><input ng-model="passc" style="font-size:10px;" type="password" id="passc"/>
                              <p></p>
                                  <button id="butop" ng-click="cambiarpass()">Aceptar</button> 
                            </div>
                  </li>
                  <li class="col-lg-12 list-group-item" align="center" >
                  <div id="carga" hidden>
                    <div  class="pull-rigth windows8" >
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
                            <em>Espere un momento..</em>
                      </div>                   
                  </li>
              </ul>
          
            </div>
          </div>
  </div>
<script src="<?php echo site_url(); ?>js/md5.js"></script>