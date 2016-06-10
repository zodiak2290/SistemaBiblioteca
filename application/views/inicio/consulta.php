
<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo site_url(); ?>css/libros/carga.css" type="text/css" />
<div class="row">
    <div class="box">
<div class="col-lg-12 text-center">
    <div id="mainBody"  ng-app="consulta" ng-controller="consultacontroller" ng-cloak>
      <div>
              <?php $attributes = array('id' => 'formulario'); 
              echo form_open(base_url().'buscar',$attributes)?>
              <div class="page-header" align="center">
                <input type="text" name="busqueda"  id="texto" ng-model="busqueda" />
                <select id="tipo" name="tipo">
                  <option name="c" value="1">Titulo</option>
                  <option name="c" value="2">Autor</option>
                  <option name="c" value="3">Materia</option>
                </select>
                <button id="buto" type="submit" class='btn btn-default btn-xs' >Buscar</button>
                  <span><?php if(isset($total)) echo $total; ?></span>
                          <div class="windows8 row"  style="width: 50%" hidden id="cargando" >
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
            </div>
            <?=form_close()?> 
           <!-- <button class='btn btn-info btn-xs'>Busqueda Avanzada</button>-->
            <div class="table-responsive">
                    <div align="center">
            <ul class="pagination">
            <?php
              /* Se imprimen los números de página */           
              echo $this->pagination->create_links();
            ?>
            </ul>
          </div>
                <table id="tabla" class="table table-striped" style="font-size:12px">
                  <thead id="cabecera">
                    <tr >                
                      <th>N°</th>
                      <th>Titulo</th>
                      <th>Autor</th>
                      <th>Clasificación</th>
                    </tr>
                  </thead>
                  <tbody id="body">
                    <?php
                    $contador=1;
                    if(isset($resultados)){
                      // Se imprimen los datos
                      if(count($resultados)>0){
                      foreach ($resultados as $libro) {
                    ?>
                     <tr id="row">               
                      <td><a href="<?php echo base_url()?>ejemplar/<?php echo $libro->nadqui?>"><?php echo $contador?></td>       
                        <td><?php echo utf8_encode(ucwords(strtolower(utf8_decode(explode("/",$libro->titulo)[0]))))?></td>
                        <td><?php echo utf8_encode(ucwords(strtolower(utf8_decode($libro->nameautor))))?></td>
                        <td><?php echo $libro->clasificacion?></td>
                      </tr>
                    <?php   $contador++; 
                  }
                }else{
                   ?>
                     <tr>               
                      <td colspan="5" style="text-align:center">Sin resultados</td>       
                      </tr>
                    <?php  
                }
                }
                ?>
              </tbody>
            </table>
          </div> 
      </div> 
    </div>
</div>
</div>
</div>
<script type="text/javascript">
var app=angular.module("consulta",[]);
app.controller("consultacontroller",function ($scope){
    var recognition;
    var recognizing = false;
    if (!('webkitSpeechRecognition' in window)) {
        alert("¡API no soportada!");
    } else {
        recognition = new webkitSpeechRecognition();
        recognition.lang = "es-VE";
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.onstart = function() {
            recognizing = true;
            console.log("empezando a eschucar");
        }
        recognition.onresult = function(event) {
         for (var i = event.resultIndex; i < event.results.length; i++) {
            if(event.results[i].isFinal)
                document.getElementById("texto").value += event.results[i][0].transcript;
              parabuscar();
            }
        }
        
        /*function parabuscar(){
          $("#tabla").hide();
          $("#cargando").show();
          $("#buto").click();
        }*/
        $("#buto").click(function(){
          $("#tabla").hide();
          $("#cargando").show();
          
          });
        recognition.onerror = function(event) {
        }
        recognition.onend = function() {
            recognizing = false;
            //document.getElementById("procesar").innerHTML = "Escuchar";
            console.log("terminó de eschucar, llegó a su fin");
        }
    }
    function procesar() {
        if (recognizing == false) {
            recognition.start();
            recognizing = true;
           // document.getElementById("procesar").innerHTML = "Detener";
        } else {
            recognition.stop();
            recognizing = false;
           // document.getElementById("procesar").innerHTML = "Escuchar";
        }
    }
      //procesar();   
});

  
</script>
<style type="text/css">
#tabla{
  -webkit-box-shadow: -30px 34px 30px rgba(199,154,45,.5); 
  -moz-box-shadow: -30px 34px 30px rgba(199,154,45,.5);
  box-shadow: -30px 34px 30px rgba(199,154,45,.5); 
  border-radius: 20px;
}
#tabla #body{
background: linear-gradient(270deg, #fff,#c2b59b);
background: -webkit-linear-gradient(left, #fff,#c2b59b);
background: -moz-linear-gradient(270deg, #fff,#c2b59b);
}
 #tabla  #cabecera{
  
 background: linear-gradient(270deg, #fff 5%,#c79a2d);
  background:-webkit-linear-gradient(270deg, #fff 5%,#c79a2d);
  background: -moz-linear-gradient(270deg, #fff 5%,#c79a2d);
}
</style>