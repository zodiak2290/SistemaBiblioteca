<?php 
    $this->load->database(); 
    $query = $this->db->query("select * from datosbiblio;");
    $nombre="";
    $localidad="";
    $municipio="";
    $estado="";
    $encargado="";
    $email="";
    if($query->result()){
        foreach ($query->result() as $row){
              $nombre=$row->namebiblio;
              $localidad=$row->localidad;
              $municipio=$row->municipio;
              $estado=$row->estado;
              $encargado=$row->encargado;
              $email=$row->email;
        }
      }
    if($rol==8){
 ?>  
<div class="panel panel-default" ng-app="setting" ng-controller="setcontroller">
<div ng-init="url='<?php echo base_url()?>home/empleado/edit'"></div>
				  	<div class="panel-body"  >                 
					    <ul class="list-group" >
					        <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Nombre</strong></div>
					            <div class="col-lg-4"><span><?php   
                          echo ucwords(strtolower($nombre))?></span></div>
					            <div class="col-lg-4"><a ng-click="mostrarocultar('namebiblio')">Editar</a>                      
                      </div>
                      <div id="namebiblio" class="panel-body list-group-item-info hidden col-lg-12" align="center" >
                        <em style="font-size:10px;"><strong>Nombre:</strong></em><input style="font-size:10px;" id="namebibliot"  />
                        <p></p> 
                        <button ng-click="guardar('namebiblio')">Aceptar</button>
                      </div>
					        </li>
					        <li class="col-lg-12 list-group-item" align="left">
					             <div class="col-lg-4"><strong>Localidad</strong></div>
					             <div class="col-lg-4"><span id="localidadspan"><?php echo ucwords(strtolower($localidad)) ?> </span></div>
					             <div class="col-lg-4"><a ng-click="mostrarocultar('localidad')">Editar</a>
                       </div>
					            <div id="localidad" class="panel-body list-group-item-info hidden col-lg-12" align="center"  >
		                          <em style="font-size:10px;"><strong>Nuevo:</strong></em><input  id="localidadt"  style="font-size:10px;" type="text" >
		                          <p></p>
		                          <button id="buto2" ng-click="guardar('localidad')">Aceptar</button>
		                        </div>
					        </li>
                  <li class="col-lg-12 list-group-item" align="left">
                       <div class="col-lg-4"><strong>Municipio</strong></div>
                       <div class="col-lg-4"><span id="municipiosapan" ><?php echo ucwords(strtolower($municipio)) ?> </span></div>
                       <div class="col-lg-4"><a ng-click="mostrarocultar('municipio')">Editar</a>
                       </div>
                      <div id="municipio" class="panel-body list-group-item-info hidden col-lg-12" align="center"  >
                              <em style="font-size:10px;"><strong>Nuevo:</strong></em><input  id="municipiot"  style="font-size:10px;" type="text"  >
                              <p></p>
                              <button id="buto2" ng-click="guardar('municipio')">Aceptar</button>
                            </div>
                  </li>
                  <li class="col-lg-12 list-group-item" align="left">
                       <div class="col-lg-4"><strong>Estado</strong></div>
                       <div class="col-lg-4"><span id="estadospan"><?php echo ucwords(strtolower($estado)) ?> </span></div>
                       <div class="col-lg-4"><a ng-click="mostrarocultar('estado')">Editar</a>
                       </div>
                      <div id="estado" class="panel-body list-group-item-info hidden col-lg-12" align="center"  >
                              <em style="font-size:10px;"><strong>Nuevo:</strong></em><input  id="estadot"  style="font-size:10px;" type="text"  > 
                              <p></p>
                              <button id="buto2" ng-click="guardar('estado')">Aceptar</button>
                            </div>
                  </li>
                  <li class="col-lg-12 list-group-item" align="left">
                       <div class="col-lg-4"><strong>Encargado(a)</strong></div>
                       <div class="col-lg-4"><span id="encargadospan"><?php echo ucwords(strtolower($encargado)) ?> </span></div>
                       <div class="col-lg-4"><a ng-click="mostrarocultar('encargado')">Editar</a>
                       </div>
                      <div id="encargado" class="panel-body list-group-item-info hidden col-lg-12" align="center"  >
                              <em style="font-size:10px;"><strong>Nuevo:</strong></em><input  id="encargadot"  style="font-size:10px;" type="text"  > 
                              <p></p>
                              <button id="buto2" ng-click="guardar('encargado')">Aceptar</button>
                      </div>
                  </li>
                  <li class="col-lg-12 list-group-item" align="left">
                       <div class="col-lg-4"><strong>Email</strong></div>
                       <div class="col-lg-4"><span id="emailspan"><?php echo $email ?> </span></div>
                       <div class="col-lg-4"><a ng-click="mostrarocultar('email')">Editar</a>
                       </div>
                      <div id="email" class="panel-body list-group-item-info hidden col-lg-12" align="center"  >
                              <em style="font-size:10px;"><strong>Nuevo:</strong></em><input  id="emailt"  style="font-size:10px;" type="text"  > 
                              <p></p>
                              <button id="buto2" ng-click="guardar('email')">Aceptar</button>
                      </div>
                  </li>
                  <li class="col-lg-12 list-group-item" align="left">
                      <div class="col-lg-4"><strong >Contraseña</strong></div>
                      <div class="col-lg-4"><span>*********</span></div>
                      <div class="col-lg-4 pull-rigth"><a ng-click="mostrarocultar('pass')">Editar</a>
                      </div>
                      <div id="pass" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                              <em style="font-size:10px;"><strong>Contraseña:</strong></em>
                              <input id="passwordt" style="font-size:10px;" type="password" >  
                              <button id="butop" ng-click="guardar('password')">Aceptar</button> 
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
<?php }?>