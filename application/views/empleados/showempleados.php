<?php 
    $contador=0; foreach ($datos as $row) {   
            $name=$row->nombre;
           $cuentaempleado=$row->cuenta;
            $idem=$row->curp;
             $email=$row->email;
$contador+=1; }
?> 
<br/>
<div id="page-wrapper" class="col-md-8 text-center" ng-app="setting" ng-controller="setcontroller">
<div class="panel panel-default text-center" style="margin-top:5%; margin-left:25%;">
<div ng-init="url='<?php echo base_url()?>home/empleado/editar'"></div>
<div ng-init="idusuario='<?php echo $idem; ?>'"></div>
				  	<div class="panel-body" >                 
					    <ul class="list-group" style="margin-left:5%;">
					        <li class="col-lg-12 list-group-item " align="left">
			                    <div class="col-lg-6">
			                    	<strong>Nombre</strong>
			                    </div>
								<div class="col-lg-6">
									<span id="nombrespan"><?php echo ucwords(strtolower($name))?></span>
			                    </div>
			                    <?php if($rol==8){ ?>
			                    <div  class="col-lg-4">
			                        <a  class="btn" ng-click="mostrarocultar('pnombre')">Editar</a>
			                    </div>
			                    <?php } ?>  
			                    <div id="pnombre" class="panel-body list-group-item-info hidden col-md-12" align="center">
			                        <em style="font-size:10px;">
			                            <strong>Nombre:</strong>
			                        </em>
			                        <input  id="nombret" style="font-size:10px;" type="text">
			                        <button id="ss" ng-click="guardar('nombre')">Aceptar</button>
			                    </div>
					        </li>
					        <li class="col-lg-12 list-group-item" align="left">
					             <div class="col-lg-6"><strong>Nombre Usuario</strong></div>
					             <div class="col-lg-6"><span ><?php echo $cuentaempleado ?> </span></div>
					        </li>
                  <li class="col-lg-12 list-group-item " align="left">
                      <div class="col-lg-4"><strong>Email</strong></div>
                      <div class="col-lg-6"><span><?php echo $email?></span></div>
                  </li>
					    </ul>
				  	</div>
				  </div>
  </div>