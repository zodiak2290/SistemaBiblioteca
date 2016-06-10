<?php if ($rol==8){?>
<div   ng-app="appBiblio" ng-controller="restorecontroller" layout="column" flex layout-fill ng-cloak>
  <md-toolbar md-scroll-shrink>
    <div class="md-toolbar-tools">Respaldo</div>
  </md-toolbar>
 <md-content layout-padding >
  <md-tabs md-dynamic-height md-border-bottom>
    <md-tab label="Realizar respaldo"  >
    <link rel="stylesheet" href="<?php echo site_url(); ?>css/libros/carga.css" type="text/css" />
                  <div class="col-lg-8 container" id="divcarga" style="margin-left:40%; margin-top:10%;" hidden>
                        <div  class="pull-left windows8 row"style="width:25%;height: 40px;" >
                            <div class="wBall" id="wBall_1" style="width:35%;height: 50px;">
                            <div class="wInnerBall">
                            </div>
                            </div>
                            <div class="wBall" id="wBall_2" style="width:35%;height: 50px;">
                            <div class="wInnerBall">
                            </div>
                            </div>
                            <div class="wBall" id="wBall_3" style="width:35%;height: 50px;">
                            <div class="wInnerBall">
                            </div>
                            </div>
                            <div class="wBall" id="wBall_4" style="width:35%;height: 50px;">
                            <div class="wInnerBall">
                            </div>
                            </div>
                            <div class="wBall" id="wBall_5" style="width:35%;height: 50px;">
                            <div class="wInnerBall">
                            </div>
                            </div>
                        </div>
                        <div class="row">Espere un momento..</div>
                    </div>
    <div class="panel panel-default" id="panel">
      <div class="panel-body" align="center">                 
            <button class="btn btn-info icon-database" ng-click="realizar_backup()">
            Realizar copia de seguridad
            </button>
            <a type="button"  class="btn btn-success" href="<?php echo site_url(); ?>home/mirespaldo">Descargar copia</a>
      </div>
    </div>
  
      <md-content class="md-padding">
         <?php $attributes = array('name' => 'form');?>
        <?=form_open(base_url()."backup_database/editkey",$attributes)?>
          <md-input-container class="md-block">
            <label>Usuario</label>
            <input  ng-minlength="5" md-maxlength="25"  name="usuario" ng-model="usuario" required id="usuario">
          <span  class="warning" ng-show="!form.usuario.$pristine && form.usuario.$error.required">Campo requerido.</span>
          <span  class="warning" ng-show="!form.usuario.$pristine && form.usuario.$error.minlength">Campo entre 3 y 25 caracteres.</span>
          </md-input-container>
           <md-input-container class="md-block" ng-model="pass">
            <label>Contraseña</label>
            <input  ng-minlength="5"  name="pass" type="password" required ng-model="pass" id="pass">
            <span  class="warning" ng-show="!form.pass.$pristine && form.pass.$error.required">Campo requerido.</span>
            <span class="warning" ng-show="!form.pass.$pristine && form.pass.$error.minlength">Contraseña de almenos 5 caracteres.</span>
          </md-input-container>
           <md-input-container class="md-block" ng-model="passconf">
            <label>Repetir contraseña </label>
            <input  ng-minlength="5" required  type="password" name="passconf" ng-model="passconf" id="passconf">
            <span class="warning" ng-show="!form.passconf.$pristine && form.pass.$viewValue!=form.passconf.$viewValue">Las contraseñas no coinciden.</span>
            <span class="warning" ng-show="!form.passconf.$pristine && form.passconf.$error.required">Campo requerido.</span>
          </md-input-container>
          <section layout="row" layout-sm="column" layout-align="center center" layout-wrap >
          <div ng-if="pass==passconf">
          <md-button aria-label="search"  class="md-raised" ng-disabled="!form.$valid" type="submit" >    
                Guardar
          </md-button>
          </div>
          </section>
        </form>
      </md-content>  
    </md-tab>
  </md-content>
</div>
  <!-- Angular Material Library -->
  <script type="text/javascript">
angularRoutingApp.controller("restorecontroller",function($scope,miService,confighttpFactori){
    $scope.realizar_backup=function(){
        alertify.confirm("<p>¿Deseas hacer una copia de seguridad de la base de datos?<br><br>", function (e) {
        if (e){
            $("#panel").hide();
            $("#divcarga").show();
            confighttpFactori.setData('<?=base_url()?>home/respaldar','GET',[]);
            iniciar();
            //window.location.replace('<?=base_url()?>home/respaldar');
        }
      }) 
    }
    function iniciar(){
      miService.getAll()
      .then(function (result) {
        $("#panel").show();
        $("#divcarga").hide();
        alertify.log(result.mensaje);
        console.log(result);
      }).catch(function (message) {
        alertify.error("No fue posible conectarse al servidor");
      });
    }
});

</script>
<style type="text/css">
  .warning {
    width: 100%;
  background: rgba(255,255,0,0.5);
}
.md-toolbar-tools{
  background: rgba(112,75,35,1);
}
</style>
<?php }?>