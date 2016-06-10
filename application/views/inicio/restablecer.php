<div class="row">
    <div class="box">
    <div class="col-lg-12 text-center">
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
    <?php echo form_open('validaremail'); ?>
      <div align="center" id="centro" hidden>
        <div class="panel-heading"> Restaurar contraseña </div>
        <div class="panel-body">
          <div class="form-group">
            <label for="email"> Ingresa tu correo electrónico </label>
            <input style="width:70%"  type="email" id="email" class="form-control" name="email" required>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" id="rec" value="Recuperar contraseña" >
          </div>
        </div>
      </div>
    <?php   echo form_close();   ?>
    </div>
</div>
</div>

<script src="<?php echo site_url(); ?>js/jquery.js"></script> 
<script type="text/javascript">  
 $( document ).ready(function() {
              $('#centro').fadeIn(1700);
        });

</script>


