<div class="form-group">
    <label>Cuenta</label>
     <p class="text-danger help-block">{{cuentavalida}}</p>
    <input type="text"  ng-model="cuenta" ng-change="validar(3)" ng-keydown="validaCURP($event)" class="form-control" value="<?php echo set_value('cuenta'); ?>" placeholder="Cuenta *"  id="cuenta" name="cuenta" required >
</div>
 <div class="form-group">
    <label>Contraseña</label>
    <input type="password"  class="form-control" placeholder="Contraseña *"  id="password" name="password" required >
    <p class="help-block text-danger"></p>
</div>
<div class="form-group">
    <label>Confirmar contraseña</label>
    <input type="password"  class="form-control" placeholder="Confirmar contraseña *"  id="passconf" name="passconf" required >
    <p class="help-block text-danger"></p>
</div>
