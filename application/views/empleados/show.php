<?php 
    foreach ($datos as $row){  
    if($rol=='3'){
    $hea="Grupo";
    $varselec="<select ng-model='grupot' id='idgrupot' name='idgrupot'>";
    $this->load->database(); 
    $grupos=$this->db->query("select idgrupo,namegrupo from grupos;");
    $idgrupo=$row->idgrupo;
     foreach ($grupos->result() as $grupo){
            if($idgrupo==$grupo->idgrupo)
              {$selg="ng-selected='true'";
          }else{
            $selg="";
          }

            $varselec=$varselec."<option ".$selg." id='".$grupo->idgrupo."' value='".$grupo->idgrupo."'>".$grupo->namegrupo." </option>";
     }
     $varselec=$varselec."</select>";
     $route="usuario"; 

      $namegrupo=$row->namegrupo;
      $ocupacion=$row->ocupacion;
      $idescuela=$row->idescuela;
      $nameescuela=$row->nombre;
       $vigente=$row->vigente;
     
$varesc="<select ng-model='idescuelat' id='idescuelat' name='idescuelat' >";
    $this->load->database(); 
    $escuelas=$this->db->query("select idescuela,nombre from escuelas order by nombre;");
     foreach ($escuelas->result() as $escuela){
      $sel="";
        if($idescuela==$escuela->idescuela){$sel="ng-selected='true'";}
        
            $varesc=$varesc."<option ".$sel."  id='".$escuela->idescuela."' value='".$escuela->idescuela."'>".ucwords(strtolower($escuela->nombre))." </option>";
          
     }
     $varesc=$varesc."</select>";

    } 
            $id=$row->curp;
            $name=$row->pnombre;
            $app=$row->apPat;
            $apm=$row->apMat;
            $direccion=$row->direccion;
            $cp=$row->cp;
            $cuentaempleado=$row->cuentausuario;
            $email=$row->email; 
            $fecha=$row->fechaNaci;
            $time = strtotime($fecha);  
            $telefono=$row->telefono;
            $cel=$row->tel2;
            $nombreaval=$row->nombreaval;
            $emailaval=$row->emailaval;
            $telefonoaval=$row->telefonoaval;
          $fecha = date("Y-m-d", $time);
          $roluse=$idgrupo;
}
?> 
<div class="wrapper" ng-app="setting" id="usuario" hidden>
<div class="row col-lg-12" align="center" style="font-size:12px;" >
<?php $this->load->view('empleados/menuusuarios');?> 
<div class="col-lg-12"  ng-controller="setcontroller" style="padding:10px">
<div ng-init="urlemail='<?php echo base_url()?>home/empleados/email/json'"></div>
<div ng-init="urlcuenta='<?php echo base_url()?>home/empleados/cuenta/json'"></div>
<div ng-init="url='<?php echo base_url()?>home/empleado/editar'"></div>
<div ng-init="idusuario='<?php echo $id ?>'"></div>

                    <div class="panel-body col-lg-8"  id="info" >
                      <button class="btn btn-info" id="verpdf">Reporte </button>
                      <button class="btn btn-info" id="verpreferencia">Preferencias de usuario </button>
                      <button class="btn btn-info" id="vermultas">Multas  </button>                   
                        <ul class="list-group" style="font-size:14px"> </br>
                            <li class="col-lg-12 list-group-item " align="left">
                              
                      <div class="col-lg-4"><strong>Nombre</strong></div>
                                <div class="col-lg-4"><span><?php echo ucwords(strtolower($app." ".$apm." ".$name))?></span>
                                </div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('pnombre')">Editar</a>
                                </div>                                          
                      <div id="pnombre" class="panel-body list-group-item-info hidden col-lg-12" align="center" >

                        <em style="font-size:12px;"><strong>Nombre(s): </strong></em><input style="font-size:10px;"  ng-keyup="funcioname($event,'pnombre')" id="pnombret" />
                        <p></p> 
                        <em style="font-size:12px;"><strong>Apellido paterno: </strong></em><input style="font-size:10px;" type="text" ng-keyup="funcioname($event,'apPat')" placeholder=" Opcional" id="apPatt">
                        <p></p>
                        <em style="font-size:12px;"><strong>Apellido materno: </strong></em><input style="font-size:10px;" type="text" ng-keyup="funcioname($event,'apMat')"   placeholder=" Opcional" id="apMatt">
                        <p></p>
                        <span>Presiona enter para enviar</span>
                      </div>
                            </li>
                            <li class="col-lg-12 list-group-item" align="left">
                                 <div class="col-lg-4"><strong>Cuenta usuario</strong></div>
                                 <div class="col-lg-4">
                                    <span ><?php echo $cuentaempleado ?> </span>
                                 </div>
                            </li>
                            <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Email</strong></div>
                                <div class="col-lg-4"><span id="emailspan"> <?php echo $email ?></span></div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('email')">Editar</a>
                      
                                </div>
                                <div id="email" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong>Email: </strong></em>
                                  <input  ng-model="email" ng-change="validar(0)" style="font-size:10px;" id="emailt" placeholder=" algo@example.com"  >
                                  <span >{{emailvalido}}.</span>
                                  <p></p>
                                  <button id="buto" ng-click="guardar('email')">Aceptar</button>
                                </div>
                            </li>
                             <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Dirección</strong></div>
                                <div class="col-lg-4"><span id="direccionspan"><?php echo ucwords(strtolower($direccion))?></span></div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('direccion')">Editar</a>
                      </div>
                                <div id="direccion" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong>Dirección: </strong></em>
                                  <input style="font-size:10px;" ng-model="direccion" id="direcciont" type="text">
                                  <p></p>
                                  <button ng-click="guardar('direccion')">Aceptar</button>
                                </div>
                            </li>
                             <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Código postal</strong></div>
                                <div class="col-lg-4"> <span id="cpspan"><?php echo $cp?></span></div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('cp')">Editar</a>
                      </div>
                                <div id="cp" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong>Código Postal: </strong></em><input ng-model="cp" style="font-size:10px;" id="cpt" type="number">
                                  <p></p>
                                  <button ng-click="guardar('cp')">Aceptar</button>
                                </div>
                            </li>
                             <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Edad</strong></div>
                                <div class="col-lg-4"> <span><?php echo (int)(((((strtotime(date('Y-m-d'))-strtotime($fecha))/365)/24)/60)/60) ?> años
                                 </span></div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('fechaNaci')">Editar</a>

                              </div>
                                <div id="fechaNaci" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong>Fecha de nacimiento: </strong></em><input style="font-size:13px;" id="fechaNacit" ng-model="fecha" type="date">
                                  <p></p>
                                  <button ng-click="guardar('fechaNaci')">Aceptar</button>
                                </div>
                            </li>
                          <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Ocupación</strong></div>
                                <div class="col-lg-4"> 
                                <span id="ocupacionspan"><?php echo $ocupacion;?></span>
                          </div>
                          <div class="col-lg-4"><a ng-click="mostrarocultar('ocupacion')">Editar</a>
                          </div>
                                <div id="ocupacion" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:10px;"><strong>Ocupación:</strong></em>
                                  <input ng-model="ocupacion" style="font-size:10px;" id="ocupaciont" type="text">
                                  <p></p>
                                  <button ng-click="guardar('ocupacion')">Aceptar</button>
                                </div>
                          </li>
                          <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Teléfono</strong></div>
                                <div class="col-lg-4"> 
                                <span id="telefonospan"><?php echo $telefono;?></span>
                          </div>
                          <div class="col-lg-4"><a ng-click="mostrarocultar('telefono')">Editar</a>
                          </div>
                                <div id="telefono" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:10px;"><strong>Nuevo:</strong></em>
                                  <input ng-model="telefono" style="font-size:10px;" id="telefonot" type="text">
                                  <p></p>
                                  <button ng-click="guardar('telefono')">Aceptar</button>
                                </div>
                          </li>
                         
                         <!--<li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong><?php echo $hea;?></strong></div>
                          
                                <div class="col-lg-4"> 
                          <span><?php echo $namegrupo;?></span>
   
                          </div>
                          <div class="col-lg-4"><a ng-click="mostrarocultar('idgrupo')">Editar</a>
                          </div>
                                <div id="idgrupo" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong><?php echo $hea?>:</strong></em>
                                  <?php echo $varselec;?>
                                  <p></p>
                                  <button ng-click="guardarid('idgrupo')">Aceptar</button>
                                </div>
                          </li>-->
                          <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Escuela</strong></div>
                                <div class="col-lg-4"> 
                        
                          <span><?php echo ucwords(strtolower($nameescuela));?></span>
            
                          </div>
                          <div class="col-lg-4"><a ng-click="mostrarocultar('idescuela')">Editar</a>
                          </div>
                                <div id="idescuela" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong><?php echo $hea?>:</strong></em>
                                  <?php echo $varesc;?>
                                  <p></p>
                                  <button ng-click="guardarid('idescuela')">Aceptar</button>
                                </div>
                          </li>
                          <li class="col-lg-12 list-group-item" align="center">
                              <div class="col-lg-12"><strong>Datos aval</strong></div>
                          </li>
                          <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Nombre </strong></div>
                                <div class="col-lg-4"> <span><?php echo ucwords(strtolower($nombreaval));?></span></div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('nombreaval')">Editar</a>
                               </div>
                                <div id="nombreaval" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong>Nombre :</strong></em>
                                  <input ng-model="nombreaval" style="font-size:10px;" id="nombreavalt" type="text">
                                  <p></p>
                                  <button ng-click="guardar('nombreaval')">Aceptar</button>
                                </div>
                            </li>
                            <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Email</strong></div>
                                <div class="col-lg-4"> <span><?php echo $emailaval;?></span></div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('emailaval')">Editar</a>
                               </div>
                                <div id="emailaval" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong>Email:</strong></em>
                                  <input ng-model="emailaval" style="font-size:10px;" id="emailavalt" type="text">
                                  <p></p>
                                  <button ng-click="guardar('emailaval')">Aceptar</button>
                                </div>
                            </li>
                            <li class="col-lg-12 list-group-item" align="left">
                                <div class="col-lg-4"><strong>Teléfono</strong></div>
                                <div class="col-lg-4"> <span><?php echo $telefonoaval;?></span></div>
                                <div class="col-lg-4"><a ng-click="mostrarocultar('telefonoaval')">Editar</a>
                               </div>
                                <div id="telefonoaval" class="panel-body list-group-item-info hidden col-lg-12" align="center">
                                  <em style="font-size:14px;"><strong>Telefono:</strong></em>
                                  <input ng-model="telefonoaval" style="font-size:10px;" id="telefonoavalt" type="text">
                                  <p></p>
                                  <button ng-click="guardar('telefonoaval')">Aceptar</button>
                                </div>
                            </li>
                            <li>
                              <div id="divcar" class="pull-left">
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
                            </li>
                        </ul>
                    
                    </div> 
                    <div class="alert alert-warning " ng-if="mensaje.length>0">
                      {{mensaje}}
                    </div>

                    <br/>                   
                    <div class="panel panel-default col-lg-4" ng-controller="donacontroller" hidden id="panelpref">
                      <br/>
                        <div ng-init="enviar('<?php echo base_url()?>home/usuario/preferencias/<?php echo $cuentaempleado ?>/E','morris-donut-chart','Externos')">
                            <button class="btn btn-success" ng-click="enviar('<?php echo base_url()?>home/usuario/preferencias/<?php echo $cuentaempleado ?>/E','morris-donut-chart','Externos')">Externos</button>
                            <button class="btn btn-success" ng-click="enviar('<?php echo base_url()?>home/usuario/preferencias/<?php echo $cuentaempleado ?>/I','morris-donut-chart','Internos')">Internos</button>
                        </div>  </br>
                        <div class="panel-heading">
                              <em><strong>Préstamos totales {{interexter}}:</strong>{{total}}</em>
                        </div>      
                        <div class="panel panel-body" id="es">
                          <div class="panel-body" >
                              <div class="row" id="morris-donut-chart"></div>
                          </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <div class="panel panel-defaul col-lg-4" hidden id="panelpdf">
                              <div class="span6" style="float: right">
                                      <a class="btn btn-success" id="botonframe" >
                                        <span class="icon-enlarge" id="icono">
                                      </a>

                                    <!--<iframe class="preview-pane"  src="http://docs.google.com/gview?url=http://victorpimentel.com/stuff/rubik.pdf&embedded=true" style="width:100%; height:375px;" frameborder="0"></iframe> --> 
                                      <iframe class="preview-pane" align="center" type="application/pdf" id="frame"  width="120%" height="350" frameborder="1" scrolling="yes" zoom: '1';></iframe>
                              </div>
                    </div>
                    <div class="panel panel-default col-lg-4" ng-controller="donacontroller" hidden id="panelmultas">  
                        <div class="panel-heading">
                              <em><strong> Multas:</strong>{{totalmultas}}</em>
                        </div>      
                        <div class="panel panel-body" id="es">
                          <div class="panel-body" >
                                <table class="table table-condensed table-bordered table-striped table-hover">
                                    <thead>
                                      <tr>
                                        <th>N° Multas </th>
                                        <th>Monto Total</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <td id="textmult"></td>
                                      <td id="textmonto"></td>
                                    </tbody>
                                </table>
                          </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    {{mensajemultas}} 
                </div>
                  <?php 
                    $hoy=date("Y-m-d H:i:s");
                    if(strtotime($vigente)<strtotime($hoy)){?>
                    <div class="alert alert-danger col-lg-12" >
                    <a onclick='href="<?php echo base_url(); ?>home/usuario/renovar/<?php echo $id ?>"' class="btn btn-info">Renovar vigencia</a>
                      
                    </div>
                 <?php }?>
                  </div>

  </div>               
<script type="text/javascript">
function mostrarocultar(elem){
  if($(elem).is(':visible')){
      $(elem).fadeOut(600);
  }else{
      $(elem).fadeIn(600);
  }
}
$("#verpreferencia").click(function(){
  $("#panelpdf").fadeOut(100);
  $("#panelmultas").fadeOut(100);
  mostrarocultar("#panelpref");
});
$("#vermultas").click(function(){
    $("#panelpdf").fadeOut(100);
    $("#panelpref").fadeOut(100);
    traer_multas();
});
$("#verpdf").click(function(){
    crearpdf();
    $("#panelpref").fadeOut(100);
    $("#panelmultas").fadeOut(100);
      mostrarocultar("#panelpdf");
   
});
$("#botonframe").click(function(){
    if($("#info").is(':visible')){
      $("#icono").removeClass('icon-enlarge').addClass("icon-shrink");
      $("#info").slideUp(400,function(){
          $("#info").removeClass('col-lg-8');
          $("#frame").animate({height: "500px"}, 500).animate({width:"320%"},500);
      });
    }else{
      $("#icono").removeClass('icon-shrink').addClass("icon-enlarge");
      $("#info").stop(true,false).removeAttr('style').addClass('col-lg-8', {duration:500});
     
      $("#frame").animate({height: "350px"}, 500).animate({width:"120%"},500);      
    }
});
  var cuentau="<?php echo $cuentaempleado; ?>";
  var multas=[]; 
function traer_multas(){
      $.ajax({
      url:  "<?php echo base_url()?>home/multasmonto",
      type: 'POST',
      data: {cuenta: cuentau},
      success: function(resultado)
      {
        if(resultado.existe){
          $("#textmult").text(resultado.datos[0].total);
          $("#textmonto").text(resultado.datos[0].monto);
          mostrarocultar("#panelmultas");  
        }else{
          alertify.success("Este usuario no tiene multas registradas");
        }
      }
    });
}
function crearpdf(){
  $.ajax({
      url:  "<?php echo base_url()?>home/usuario/reporte",
      type: 'POST',
      data: {cuenta: cuentau},
      success: function(resultado)
      {
          $('.preview-pane').attr('src', '<?php echo base_url()?>/pdf/reporte'+cuentau+'.pdf');
      }
    });
}

 $( document ).ready(function() {
              $('#usuario').show();
        });
</script>
