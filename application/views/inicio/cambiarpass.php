<?php
        if(isset($usuario)&&isset($token)){

?>
<div class="row">
    <div class="box">
    <div class="col-lg-12 text-center">
    <?php echo form_open('validaremail/cambiarpass'); ?>
      <div class="panel-heading"><strong>Restaurar contraseña</strong> </div>
      <div class="panel-body">
       <p></p>
       <div class="form-group col-lg-4">
        <label for="password"> Nueva contraseña </label>
        <input type="password" class="form-control" id="password1" name="password1" required>
       </div>
       <div class="form-group col-lg-4">
        <label for="password2"> Confirmar contraseña </label>
        <input type="password" class="form-control" id="password2" name="password2" required>
       </div>
       <input type="hidden" name="token" value="<?php echo $token ?>">
       <input type="hidden" name="idusuario" value="<?php echo $usuario ?>">
       <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Cambiar contraseña" >
       </div>
     </div>
    <?php   echo form_close();     ?>
   </div>
   </div>
</div> <!-- /container -->
  <style type="text/css">
  #cam{
    margin-left: 10%;
    width: 80%;
    position: relative;
  }
</style>
<?php
   }else{
    redirect(base_url().'restablecer', 'refresh');    
   }


?>