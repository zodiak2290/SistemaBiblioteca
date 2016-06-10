<?php $contador=0; 
$arrfechas=array();$totalas=0;$totaln=0;$totalj=0;$totala=0;
foreach ($datos as $row){    
    $img=base_url()."images/".$row->imagen;
    ?>
    <?php 
            $para=$row->dirigidoa;
            $titulo=$row->nombre;
            $descripcion=$row->descripcion;
            $fechapublicacion=$row->fechapublicacion;
            $imagen=$row->imagen;
            $id=$row->idactividad;
            $cuentaempleado=$row->cuentaempleado;
            $horainicio=$row->horainicio;
            $horafin=$row->horafin;
            $buton="Actualizar";        
          $div='<div></div>'; $lugar=$row->lugar;
          if(isset($row->asistencia)){
              $asis="si";
              $arrfechas[$contador]['dia']=$row->dia;//$arrfechas[$contador]['lugar']=$row->lugar;
              $arrfechas[$contador]['total']=$row->asistencia;
              $arrfechas[$contador]['ac_id']=$row->idactividadesdia;
              $totalas=$totalas+$arrfechas[$contador]['total'];
          }else{
            $arrfechas[$contador]['ac_id']=$row->idregistroactividadte;
              $arrfechas[$contador]['dia']=$row->dia;
              $arrfechas[$contador]['totaln']=$row->ninos;
              $totaln=$totaln+$arrfechas[$contador]['totaln'];
              $arrfechas[$contador]['totalj']=$row->jovenes;
              $totalj=$totalj+$arrfechas[$contador]['totalj'];
              $arrfechas[$contador]['totala']=$row->adultos;
              $totala=$totala+$arrfechas[$contador]['totala'];
          }
          $contador+=1;
  }
 ?>
<div ng-app="actividad" ng-controller="actividadcontroller" ng-cloak>
<div class="modal fade " id="myModal" align="center" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">   
    <div class="row modal-content">
      <div class="modal-header" >
    <div class="row" style=" marin-top:1%;margin-left:10%;">
      <div class="col-lg-12">
          <span><?php echo validation_errors(); ?></span>
          <?=form_open_multipart("actividad/camabiandoimagen")?>
                  <input type="hidden"  value='<?php echo $id ?>' id="id" name="id" >
                  <input type="hidden" value='<?php echo $imagen?>' id='imagen' name='imagen'>    
                  <div class="form-group" id="cargar">
                          <input type="file"  class="form-control"  placeholder="Imagen *"  id="userfile" name="userfile" >
                          <p class="help-block text-danger"></p>
                  </div>    
                           
                  <input type="hidden" id="descripcion" name="descripcion" value="<?php echo $descripcion?>">
                  <div id="success"></div>
                  <button type="submit" class="btn btn-xl">Cambiar imagen</button>
          <?=form_close()?>
      </div>
    </div> 
    </div>
  </div>
</div>        
</div>
  <md-content class="md-padding" layout-xs="column" layout="row" layout-align="center center">
    <div flex-xs flex-gt-xs="90" layout="column">
    <?php $correcto = $this->session->flashdata('correcto');
              if ($correcto) 
              {
              ?>
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="registroCorrecto" ><?= $correcto ?></span>
              </div>
              <?php
              }
        ?>
      <md-card>
        <md-card-header>
          <md-card-header-text>
            <span class="md-title"><?php echo mb_convert_case($titulo, MB_CASE_TITLE, "UTF-8");?></span>
              <div class="edicion" hidden>
                <md-input-container class="md-block">
                  <label>Título</label>
                  <input  ng-minlength="5" md-maxlength="65"  name="titulo" ng-model="titulo">
                  <div ng-messages="form.titulo.$error">
                    <div ng-message="minlength">Título entre 5 y 65 caracteres.</div>
                    <div ng-message="md-maxlength">Título entre 5 y 65 caracteres.</div>
                  </div>
                </md-input-container>
              <md-button ng-click="guardar('nombre')" class="md-raised md-primary">Guardar</md-button>
              </div>
            <span class="md-subhead">Publicado el: <?php echo $fechapublicacion;?> por: <?php echo $cuentaempleado;?></span>
          </md-card-header-text>
        </md-card-header>
        <md-card-title>
<md-card-title-text>
            <span class="md-subhead">
              <div class="col-lg-12" id="cuerpo"> 
                <div class="form-group ">
                                <?php if(strcmp($para,"hd")==0){
                                $var="Niños";
                                }elseif (strcmp($para,"et")==0) {
                                    $var="Jovenes";
                                }elseif (strcmp($para,"md")==0) {
                                    $var="Adultos";
                                }elseif (strcmp($para,"te")==0) {
                                    $var="Todas las edades";
                                }  ?>
                  <p> 
                    <strong>Lugar:</strong> <?php echo  mb_convert_case($lugar, MB_CASE_TITLE, "UTF-8");;?>
                      <div class="edicion" hidden>
                        <md-input-container class="md-block">
                          <label>Lugar</label>
                          <input  ng-minlength="5" md-maxlength="45"  name="lugar" ng-model="lugar">
                          <div ng-messages="form.lugar.$error">
                            <div ng-message="minlength">Lugar entre 5 y 45 caracteres.</div>
                            <div ng-message="md-maxlength">Lugar entre 5 y 45 caracteres.</div>
                          </div>
                        </md-input-container>
                      <md-button class="md-raised md-primary" ng-click="guardar('lugar')">Guardar</md-button>
                      </div>
                  </p>              
                  <p>
                    <strong>Hora:</strong> <?php echo $horainicio;?>-<?php echo $horafin;?>
                  </p>
                  <p><strong>Dirigido a:</strong>  <?php echo $var; ?></p>
                  <?php if(strcmp($para,"te")!=0){ ?>
                  
                  <div class="edicion" hidden>
                    <select id="dirigido" ng-model="dirigidoa">
                      <option  ng-selected='true' value="hd">Niños</option>
                      <option value="et">Jóvenes</option>
                      <option value="md">Adultos</option>
                      <option value="te">Todas las edades</option>
                      </select>
                     <md-button class="md-raised md-primary" ng-click="guardar('dirigidoa')">Guardar</md-button> 
                  </div>
                  <?php } ?>
                </div>
              <p align="justify">
                    <?php echo  mb_convert_case($descripcion, MB_CASE_TITLE, "UTF-8");;?>  
                    <div class="col-lg-12 edicion" hidden style="margin-top:10px;">
                    <textarea id="ced"  class="form-control" ng-model="descripcion" style="max-width:100%" cols="100" ng-model="desc" maxlength="1000"  >                   
                      </textarea>
                      {{descripcion.length}}
                      <md-button class="md-raised md-primary" ng-click="guardar('descripcion')">Guardar</md-button>
                    </div>      
              </p>
              </div>
            </span>
          </md-card-title-text>
          <md-card-title-media>
            <div class="md-media-lg card-media">
              <img class="img-responsive container img-thumbnail" src="<?= $img ?>" alt="">
            </div>
          </md-card-title-media>
        </md-card-title>
        <md-card-title>
        </md-card-title>
        <md-card-actions layout="column" layout-align="start center" >
          <md-card-icon-actions>
            <a href="<?php echo base_url(); ?>home/actividades" >
              <md-button class="md-raised"><SPAN class="icon-undo2"> </SPAN></md-button>
            </a>&nbsp;&nbsp;&nbsp;
            <a data-toggle="modal" href="#myModal" class="edit" >
              <md-button class="md-raised md-primary">Cambiar imagen</md-button>
            </a>&nbsp;&nbsp;&nbsp;
            <a href="<?php echo base_url()?>home/actividad/<?php echo $id ?>">
              <md-button class="md-raised">Agregar fecha</md-button>
            </a>&nbsp;&nbsp;&nbsp;
            <md-button class="md-raised md-accent" ng-click="mostrareditar()">Editar</md-button>&nbsp;&nbsp;&nbsp; 
            <md-button class="md-raised md-warn" ng-click="deleteactividad()">Eliminar</md-button>
          </md-card-icon-actions>
        </md-card-actions>
        <md-card-content>
          <p><table class="table table-condensed table-bordered table-striped table-responsive">
              <thead>
                <tr>
                  <th colspan="5" align="center">Día</th>
                  <?php if(isset($asis)){?>
                   <th>Asistentes</th>
                  <?php }else{?>
                    <td>Niños</td><td>Jóvenes</td><td>Adultos</td>
                  <?php }?>
                </tr>
              </thead>
              <tbody>
        <?php   foreach ($arrfechas as $row){  ?>
            <tr id="fil<?php echo $row['ac_id'] ?>">  
              <td colspan="5">
              <?php echo $row['dia'];
                  if($row['dia']>date('Y-m-d')){ ?>
                    <span class="icon-bin text-muted medium pull-center" ng-click="eliminar('<?php echo $row['ac_id'] ?>')"></span>
                  <?php }  ?>
              </td>           
              <?php if(isset($row['total'])){ ?>
              <td align="center">
                <?php  if($row['dia']<date('Y-m-d')||$row['dia']<=date('Y-m-d')&&$horafin<date("H:i:s")) {?>
                  <span id="valorreal<?php echo $row['ac_id'] ?>"><?php echo $row['total']; ?> </span>  
                <?php ?>
                  <span class="icon-pencil2 text-muted medium pull-right" ng-click="mostrar('<?php echo $row['ac_id'] ?>')"></span>
                  <span id="valoreditado<?php echo $row['ac_id'] ?>" hidden></span>
                  <input hidden type="number" min="0"  max="500" placeholder="Agregar asistencia" size="15" style="font-size:9px;" id="<?php echo $row['ac_id'] ?>" ng-keyup="agregar($event,'<?php echo $row['ac_id'] ?>')"> 
                  <?php  }else{ 
                      echo $row['total']; 
                      }?>
              </td>
                <?php }else{ 
                    if($row['dia']<=date('Y-m-d')&&strtotime($horafin)<strtotime(date("H:i"))){ ?>
              <td>
                <span id="valorreal<?php echo $row['ac_id'] ?>n"><?php echo $row['totaln'];?></span>
                <span class="icon-pencil2 text-muted medium pull-right" ng-click="mostrar('<?php echo $row['ac_id'] ?>n')"></span>
                <span id="valoreditado<?php echo $row['ac_id'] ?>n" hidden></span>
                <input hidden type="number" min="0"  max="500" placeholder="Niños" size="15" style="font-size:9px;" id="<?php echo $row['ac_id'] ?>n" ng-keyup="agregar($event,'<?php echo $row['ac_id'] ?>n','ninos')">                    
              </td>
              <td>
                <span id="valorreal<?php echo $row['ac_id'] ?>j"><?php echo $row['totalj'];?></span>
                <span class="icon-pencil2 text-muted medium pull-right" ng-click="mostrar('<?php echo $row['ac_id'] ?>j')"></span>
                <span id="valoreditado<?php echo $row['ac_id'] ?>j" hidden></span>
                <input hidden type="number" min="0"  max="500" placeholder="Jovenes" size="15" style="font-size:9px;" id="<?php echo $row['ac_id'] ?>j" ng-keyup="agregar($event,'<?php echo $row['ac_id'] ?>j','jovenes')">                    
              </td> 
              <td>
                <span id="valorreal<?php echo $row['ac_id'] ?>a"><?php echo $row['totala'];?></span>
                <span class="icon-pencil2 text-muted medium pull-right" ng-click="mostrar('<?php echo $row['ac_id'] ?>a')"></span>
                <span id="valoreditado<?php echo $row['ac_id'] ?>a" hidden></span>
                <input hidden type="number" min="0"  max="500" placeholder="Adultos" size="15" style="font-size:9px;" id="<?php echo $row['ac_id'] ?>a" ng-keyup="agregar($event,'<?php echo $row['ac_id'] ?>a','adultos')">                    
              </td>     
                    <?php  }else{ ?> 
                        <td ><span><?php echo $row['totaln'];?></span></td>
                        <td ><span><?php echo $row['totalj'];?></span></td> 
                        <td ><span><?php echo $row['totala'];?></span></td>  
                     <?php }?>
            </tr>                     
                <?php }?>
           <?php }?>
           <?php if(isset($asis)){?>
               <tr><td colspan="5"></td><td align="center">Total: <span id="totalas">{{totalas}}</span> </td>
              <?php }else{?>
                <tr><td colspan="5"></td>
                <td align="center">Total: <span id="totaln">{{totaln }}</span></td>
                 <td align="center">Total: <span id="totalj">{{totalj}}</span></td>
                 <td align="center">Total: <span id="totala">{{totala}}</span></td>
              <?php }?>
              </tr>
              <tbody>
            </table>
          </p>
        </md-card-content>
      </md-card>
    </div>
  </md-content>
</div>
<script type="text/javascript">
var app=angular.module("actividad",['ngMaterial']);
app.controller("actividadcontroller",function ($scope,$http){
 $scope.agregar=function(tecla,idact,tipo){ 
            if(tecla.keyCode==13){
                alertify.log("Espera un momento..");
              var patron = /^\d*$/;
              var dato=$("#"+idact).val(); 
              if(!isNaN(dato)&&patron.test(dato)){
                data={ida:idact,valor:dato,dir:tipo};
                $scope.agregarregistro('<?php echo base_url()?>home/actividad/registro',data);
                $('#valorreal'+idact).hide();
                $('#valoreditado'+idact).text($("#"+idact).val());
                $('#valoreditado'+idact).show();
                $('#'+idact).hide(100);
                actualizatotal(tipo,dato,$('#valorreal'+idact).text());
              }else{
                alertify.error("Inserta un número");
              }
            }
          }
$scope.totaln='<?php echo $totaln ?>';
$scope.totalj='<?php echo $totalj ?>';
$scope.totala='<?php echo $totala ?>';
$scope.totalas='<?php echo $totalas ?>';
actualizatotal=function(tipo,dato,real){
  if(tipo=="ninos"){
    $scope.totaln=$scope.totaln-real+Number(dato);
    alertify.log($("#totaln").text());
  }else if(tipo=="jovenes"){
    $scope.totalj=$scope.totalj-real+Number(dato);
  }else if(tipo=="adultos"){
    $scope.totala=$scope.totala-real+Number(dato);
  }else{
    $scope.totalas=$scope.totalas-real+Number(dato);
  }
}         
$scope.eliminar=function(id){
  alertify.confirm("<p>¿Deseas eliminar este registro?<br><br>", function (e) {
    if (e) {
      eliminar(id);
      $('#fil'+id).hide();
    }
  }); 
  return false
}
eliminar=function(id){
  alertify.log("Espera un momento");
  data={ida:id,idactiv:'<?php echo $id ?>'};
  $scope.agregarregistro('<?php echo base_url()?>home/actividaddia/eliminar',data);
}
$scope.agregarregistro=function(url,dat){
    $http({
    url:url,
     method:'POST',
    data:dat
    }).success(function(result){ 
      alertify.success(result);
    }); 
}
$scope.dirigidoa="hd";
$scope.guardar=function(id){
    if(id=='descripcion'){valor=$scope.descripcion;}
    else if(id=='nombre'){valor=$scope.titulo;}
    else if(id=='lugar'){valor=$scope.lugar;}
    else if(id='dirigidoa'){valor=$scope.dirigidoa;}
    console.log($scope.dirigidoa);
      //if($scope.descripcion.length>10){  
       
      //}else{
      //  alertify.error("Descripción demasiado corta")
      //}
    //}
  data={ida:'<?php echo $id?>',valor:valor,dato:id};
  $scope.agregarregistro('<?php echo base_url()?>home/actividad/edit',data);
}
$scope.mostrar=function(id){
    if($('#'+id).is(':visible')){
        $('#'+id).hide(100);
    }else{
      $('#'+id).show(100);
    }
}
$scope.deleteactividad=function(){
   alertify.confirm("<p>¿Deseas eliminar este registro?<br><br>", function (e) {
        if (e) {
            window.location.replace('<?=base_url()?>home/evento/borrar/<?php echo $id;?>');
        }
    }); 
}
$scope.mostrareditar=function(tecla,id){
  if($('.edicion').is(':visible')){
      $('.edicion').hide(100);
  }else{
      $('.edicion').show(100);
    } 
}
});
    $(".edit").click(function(){
          $(".row").css("display","block");
    }); 
function confirma(){
    if (confirm("¿Realmente desea eliminarlo?")){ 
        alert("El registro ha sido eliminado.") }
        else { 
        return false
    }
}
</script>
