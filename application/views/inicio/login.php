<div class="row">
    <div class="box">
<div class="col-lg-12 text-center">

<?php
    $username = array('id' => 'cuentausuario','name'=>'cuentausuario','class'=>'form-control', 'placeholder' => 'Cuenta de usuario' ,'required data-validation-required-message'=>"Ingrese su cuenta.");
    $password = array('id'=>'password_d','name' => 'password_d','class'=>'form-control', 'placeholder' => 'Contraseña','required data-validation-required-message'=>"Ingrese su contraseña.");
    ?>
<div class="center-block">
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
              <div class="col-md-12 col" >
                  <?php echo form_open('verifylogin'); ?>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                              <?=form_input($username)?><p class="help-block text-danger"><?=form_error('cuentausuario')?></p>       
                            </div>
                            <div class="form-group">
                              <?=form_password($password)?><p  class="help-block text-danger"><?=form_error('password')?></p>    
                            </div>
                           
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12 text-center">
                          <div id="success"></div>
                             <button type="submit" class="btn btn-info">Ingresar</button>
                          </div>
                          <div class="col-lg-12 text-center"> </br>
                            <a href="<?php echo base_url(); ?>restablecer">¿Olvidaste tu contraseña?</a>
                          </div>
                      </div>
              <?php if(isset($error)){?>
                 <div class="col-lg-12">
                        <div class="alert alert-danger col-lg-12" >
                           <span><?php echo $error; ?></span>
                        </div>
                        </div>
                <?php }?>    
                  </form>               
              </div>
 
</div>
</div>
</div>
<style type="text/css">
  .col{
    display: flex;
    justify-content: center;
  }
  .btn:hover{
    transform: scale(1.5,1);
  }
</style>
       
      