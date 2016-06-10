<?php $correcto = $this->session->flashdata('correcto');
      if ($correcto) 
      {
      ?>
      <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <span id="registroCorrecto" ><?= $correcto ?></span>
        <label style="color:red">:Recordar bien estos datos</label>
      </div>
      <?php
      }
?>