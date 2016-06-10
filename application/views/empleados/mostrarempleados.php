<div id="page-wrapper" class="col-md-12" hidden>
<?php $this->load->view('empleados/menuusuarios');?> 
<div class="page-header">
  <div class="row">
     </div>
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
</div>

    <div id="mainBody">
      <div>
        <div class="table-responsive col-lg-10" ng-app="appBiblio" ng-controller="evencontroller">

          <div align="center">
            <ul class="pagination ">
            <?php
              /* Se imprimen los números de página */           
              echo $this->pagination->create_links();
            ?>
            </ul>
          </div>
<table class="table table-condensed table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>
      </th>
      <th>Nombre</th>
      <th>Cuenta</th>
      <th>Email</th>
      <th>Fecha de registro</th>
      <th colspan="2">   </th>
    </tr>
  </thead>
  <tbody>
    <?php if($datos)
           {
            foreach ($datos as $row){
            if(!($row->cuentausuario==$cuentaempleado)){
    ?>
      <tr <?php if(isset($row->iduserbloqueados)){?>
            class="danger" 
              <?php } ?>>
        <td><a href="<?php echo base_url(); ?>home/usuario/ver/<?php echo $row->curp;?>" ><span class="icon-eye"></span></a>
        </td>
        <td><?php echo utf8_encode(ucwords(strtolower(utf8_decode($row->pnombre))));?></td>
        <td><?php echo $row->cuentausuario;?></td>
      <?php if($rol=='3'){?>
      <td><?php echo $row->email?></td>
      <?php }?>
          <td><?php echo $row->created_at;?></td> 
          <td>
          <?php if(isset($row->iduserbloqueado)){?>
                <a ng-click="bloquear('<?php echo $row->cuentausuario;?>')" ><span id="es<?php echo $row->cuentausuario ?>" class="icon-unlocked"></span></a> 
              <?php }else{?>
               <a ng-click="bloquear('<?php echo $row->cuentausuario;?>')" ><span id="es<?php echo $row->cuentausuario ?>" class="icon-lock"></span></a>       
          <?php } ?>
          </td>
        <td>
           <a ng-click="verificar('<?php echo $row->cuentausuario;?>','<?php echo $row->curp;?>')"><span class="icon-bin"></a></td>    
      </tr>

    <?php }
    }
    }else{?>
    <td colspan="6" align="center">No hay resultados </td>
    <?php } ?>
  </tbody>
</table>
          </div> 
      </div> 
    </div>
</div>

<style type="text/css">
    .navev{
      display: flex;
      list-style: none;
      justify-content: center; 
    }
    a{
      text-decoration: none;
    }
</style>
<script type="text/javascript">
$( document ).ready(function() {
            $("#page-wrapper").show();
             $('#listar').attr("class","active");
});
angularRoutingApp.controller("evencontroller",function ($scope,miService,confighttpFactori){
  $scope.idpe="";
  $scope.verificar=function(cuenta,curp){
    alertify.confirm("<p>¿Eliminar a este usuario?<br>"+cuenta+"<br>", function (e) {
        if (e) {
          window.location.replace('<?=base_url()?>home/usuario/borrar/'+cuenta);
        }
    }); 
    $scope.idpe=cuenta;
  }
  $scope.bloquear=function(s){
    $scope.obs="";
    $scope.idpe=s;
    if(($("#es"+s)).attr('class')=='icon-lock'){
        alertify.prompt("Bloquear a:"+s, function (e, str) {
          if (e) {
            $scope.obs=str;
            bloquearusuario();
          }
        }, "Motivo del bloqueo");
      }else if(($("#es"+s)).attr('class')=='icon-unlocked'){
          alertify.confirm("<p>¿Desbloquear a este usuario?<br>"+s+"<br>", function (e) {
          if (e) {
            window.location.replace('<?php echo base_url(); ?>home/usuario/desbloqueo/'+s);
          }
          }); 
        }
    }
    bloquearusuario=function(){
      confighttpFactori.setData('<?php echo base_url()?>home/usuario/bloqueo',
                  'POST',{cuenta:$scope.idpe,obser:$scope.obs});
      miService.getAll()
      .then(function (result){
          $scope.bloqueo=result.mensaje;
          if(result.exito){
             alertify.success(result.mensaje);
             $('#es'+$scope.idpe).attr("class","icon-unlocked");
          }
      }).catch(function (message) {
          alertify.error("No fue posible conectarse al servidor");
      });
      }
    });

 
</script>