<div id="nuevoem" class="col-lg-12 col-centered" ng-app="setting" ng-controller="setcontroller" hidden>
<div ng-init="urlemail='<?php echo base_url()?>home/empleados/email/json'"></div>
<div ng-init="urlcuenta='<?php echo base_url()?>home/empleados/cuenta/json'"></div>
            <div class="row">
                <div class="col-lg-10 text-center">
    <?php $this->load->view('empleados/menuusuarios');?> 
                </div>
            </div>
            <?php 
            $query = $this->db->query("select max(cuentausuario) as maximo FROM usuariobiblio;");
                                $resultado=$query->result()[0];
                                foreach ($resultado as $row) {
                                     $cuenta=$row+1;
                                 }
                                 $cuenta=$cuenta."";
                                 $cuenta=str_pad($cuenta,6,"0",STR_PAD_LEFT);
                $idin="b";
                $varh3="<h3>Grupo</h3>";
            $varselec="<select id='grupo' name='grupo'>";
            $this->load->database(); 
            $grupos=$this->db->query("select idgrupo,namegrupo from grupos order by namegrupo;");
             foreach ($grupos->result() as $grupo){
                    $varselec=$varselec."<option id='".$grupo->idgrupo."' value='".$grupo->idgrupo."'selected>".$grupo->namegrupo."</option>";
             }
             $varselec=$varselec."</select>";  
                
                $selectesc="<select id='escuela' name='escuela' class='form-control'>";
                $escuelas=$this->db->query("select idescuela,nombre from escuelas order by nombre"); 
                foreach ($escuelas->result() as $escuela) {
                    $selectesc=$selectesc."<option id='".$escuela->idescuela."' value='".$escuela->idescuela."'>".$escuela->nombre."</option>";
                }
                $selectesc=$selectesc."</select>";
                if (@$error) 
                {
            ?>
                    <div class="alert alert-danger">
                         <?= $error ?>
                     </div>
            <?php
                 }                     
            ?>  

            <div class="row">
                <div class="col-lg-10">
                <?php $fecha = date("YmdHis");?>
                <?php $d=rand();?>
                <?php  $id=$idin.$fecha.$d;?>           
                <?php if(validation_errors()){?>
                        <div class="alert alert-danger">
                           <span><?php echo validation_errors(); ?></span>
                        </div>
                <?php }?>  
          <?php $attributes = array('name' => 'userForm'); ?>        
					<?=  form_open(base_url()."users/agregar",$attributes)?>
                        <div class="row">
                            <div class="col-md-12">
                              <div class="form-group col-md-6">
                                  <input type="text" placeholder="Curp *"  class="form-control" id="curp" name="curp" maxlength="18"  ng-model="curp"  ng-keydown="validaCURP($event)" ng-change="validando(1)" value="<?php echo set_value('curp'); ?>" required>
                                  <span class="bar"></span>
                                  <label>Curp * <span>{{mensajecurp}}</span></label>
                              </div>
                              <div class="form-group col-md-6">
                                  <div ng-show="userForm.name.$dirty && userForm.name.$error.required" class="text-danger help-block">Campo obligatorio</div>
                                  <div ng-show="userForm.name.$dirty && userForm.name.$error.pattern" class="text-danger help-block">Formato no válido</div>
                                  <input  type="text" placeholder="Nombre(s)"id="name" name="name" ng-model="name" class="form-control"  value="<?php echo set_value('name'); ?>" required ng-pattern="/^[a-zA-Zñáéíóú\s]{1,37}$/">
                                  <span class="bar"></span>
                                  <label>Nombre(s)</label>
                              </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <div ng-show="userForm.apepat.$dirty && userForm.apepat.$error.pattern" class="text-danger help-block">Formato no válido</div>
                                        <input type="text"  class="form-control"  id="apepat" ng-model="apepat" name="apepat" value="<?php echo set_value('apepat'); ?>" ng-pattern="/^[a-zA-Zñáéíóú]{1,37}$/" >
                                          <span class="bar"></span>
                                          <label>Apellido paterno </label>
                                    </div>   
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <div ng-show="userForm.apemat.$dirty && userForm.apemat.$error.pattern" class="text-danger help-block">Formato no válido</div>
                                        <input type="text"  class="form-control"  id="apemat" name="apemat" ng-model="apemat" value="<?php echo set_value('apemat'); ?>" ng-pattern="/^[a-zA-Zñáéíóú]{1,37}$/">
          
                                          <span class="bar"></span>
                                          <label>Apellido materno </label>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-12">
                              <div class="col-md-6">   
                                <div class="form-group">
                                    <div ng-show="userForm.email.$dirty && userForm.email.$error.email" class="text-danger help-block">Email no válido</div>
                                    <input type="email" ng-model="email" ng-change="validar(1)" class="form-control" placeholder="Email"  id="email" name="email" value="<?php echo set_value('email'); ?>" >
                                    <span class="bar"></span>
                                    <label>Email  <p class="help-block text-danger"> <span >{{emailvalido}}.</span></p></label>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <input type="text"  class="form-control"   id="direc" name="direc" value="<?php echo set_value('direc'); ?>" >
                                  <span class="bar"></span>
                                  <label>Dirección</label>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6">    
                                    <div class="form-group">
                                        <input id="tel" name="tel" class="form-control" type="tel"  value="<?php echo set_value('tel'); ?>">
                                        <span class="bar"></span>
                                        <label>Teléfono</label>
                                    </div>
                                </div>
                                <div class="col-md-6">    
                                    <div class="form-group">
                                        <input id="tel2" name="tel2" class="form-control" type="tel" value="<?php echo set_value('tel2'); ?>">
                                        <span class="bar"></span>
                                        <label>Teléfono de casa</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="number"  class="form-control" min="0" id="cp" name="cp" value="<?php echo set_value('cp'); ?>" >
                                        <span class="bar"></span>
                                          <label>Código postal</label>
                                    </div>  
                                </div>
                                <div class="col-md-6">    
                                    <div class="form-group">
                                        <input type="text" class="form-control"  id="ocupacion" name="ocupacion" value="<?php echo set_value('ocupacion'); ?>" >
                                        <span class="bar"></span>
                                          <label>Ocupación</label>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-12">                            
                                <div class="col-md-6">    
                                    <div class="form-group">
                                        <label>Fecha de nacimiento</label>
                                        <input type="date" class="form-control" id="fechan" name="fechan" required data-validation-required-message="Ingrese fecha de nacimiento">
                                        <span class="bar"></span>
                                          <label>Fecha nacimiento * 11-01-1991</label>
                                    </div>
                                </div> 
                                <div class="col-md-6" align="center">    
                                    <div class="form-group ">
                                     <?php echo $selectesc;?>
                                    </div>  
                                </div>   
                            </div>
                            <div class="col-md-12"> 
                            </div>                          
                        </div>   
                        <div class="col-md-6 panel panel-default" align="center">  
                            <div class="form-group">
                                 <strong>Cuenta usuario:</strong>
                                 <span class="container"><strong><?php echo $cuenta ?></strong></span>
                                <input  type="hidden" min="0"  ng-model="cuenta" ng-change="validar(1)" ng-keydown="validaCURP($event)" class="form-control" value="<?php echo $cuenta ?>" placeholder="Cuenta *"  id="cuenta" name="cuenta" required >
                            </div>
                            <div class="form-group">
                                 <div ng-show="userForm.password.$dirty && userForm.password.$error.required" class="text-danger help-block">Campo requerido</div>
                                <input type="password"  ng-model="password" class="form-control" placeholder="Contraseña *"  id="password" name="password" required >
                                <span class="bar"></span>
                                <label>Contraseña</label>                                
                            </div>
                            <div class="form-group">
                                 <div ng-show="userForm.passconf.$dirty && userForm.passconf.$error.required" class="text-danger help-block">Campo requerido</div>
                                <input type="password" ng-model="passconf" class="form-control" placeholder="Confirmar contraseña *"  id="passconf" name="passconf" required >
                                <span class="bar"></span>
                                <label>Confirmar contraseña</label>
                            </div>
                                <!--<div class="form-group">
                                <?php  echo $varh3;?>
                                 <?php echo $varselec;?>
                                </div>-->
                        </div>
                        <div class="col-md-6 panel panel-default" align="center">
                                    <strong>Datos aval</strong>
                                <div class="form-group">
                                    <div ng-show="userForm.nombreaval.$dirty && userForm.nombreaval.$error.pattern" class="text-danger help-block">Formato no vàlido</div>
                                    <input type="text"  ng-model="nombreaval" class="form-control" placeholder="Nombre Aval *"  id="nombreaval" name="nombreaval" value="<?php echo set_value('nombreaval'); ?>" ng-pattern="/^[a-zA-Zñáéíóú]{1,180}$/" >
                                    <span class="bar"></span>
                                </div>
                                <div class="form-group">
                                    <div ng-show="userForm.emailaval.$dirty && userForm.emailaval.$error.email" class="text-danger help-block">Email no válido</div>
                                    <input type="email" ng-model="emailaval" class="form-control" placeholder="Email Aval *" id="emailaval" name="emailaval" value="<?php echo set_value('emailaval'); ?>" >
                                    <span class="bar"></span>
                                </div>
                                <div class="form-group">
                                    <input type="text"  class="form-control" placeholder="Teléfono Aval *"  id="telefonoaval" name="telefonoaval"  value="<?php echo set_value('telefonoaval'); ?>">
                                    <span class="bar"></span>
                                </div>
                        </div>
                        <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button id="buto" type="submit" class="btn btn-xl btn-info" ng-disabled="!userForm.$valid">Aceptar</button>
                                <button id="butoc" class="btn btn-xl" onclick="window.location.reload()">Cancelar</button>
                            </div>        
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
            <?php $correcto = $this->session->flashdata('correcto');
                if ($correcto) 
                {
                ?>
                <div class="alert alert-success">
                   <span id="registroCorrecto"><?= $correcto ?></span>
                </div>
                <?php
                }
            ?>
<script type="text/javascript">
 $( document ).ready(function() {
            $("#nuevoem").show();
            $('#agregar').attr("class","active");
        });   
</script>
<style type="text/css">

/* form starting stylings ------------------------------- */
.group            { 
  position:relative; 
  margin-bottom:45px; 
}
input               {
  font-size:18px;
  padding:10px 10px 10px 5px;
  display:block;
  width:300px;
  border:none;
  border-bottom:1px solid #757575;
}
input:focus         { outline:none; }

/* LABEL ======================================= */
label { 
    display: none;
}

/* active state */
input:focus ~ label, input:valid ~ label        {
  top:-20px;
  font-size:14px;
  color:#000;
  display: block;
}

/* BOTTOM BARS ================================= */
.bar    { position:relative; display:block; width:100%; }
.bar:before, .bar:after     {
  content:'';
  height:2px; 
  width:0;
  bottom:1px; 
  position:absolute;
  background:#c79a2d; 
  transition:0.2s ease all; 
  -moz-transition:0.2s ease all; 
  -webkit-transition:0.2s ease all;
}
.bar:before {
  left:50%;
}
.bar:after {
  right:50%; 
}

/* active state */
input:focus ~ .bar:before, input:focus ~ .bar:after {
  width:50%;

}

/* HIGHLIGHTER ================================== */
.highlight {
  position:absolute;
  height:60%; 
  width:100px; 
  top:25%; 
  left:0;
  pointer-events:none;
  opacity:0.5;
}

/* active state */
input:focus ~ .highlight {
  -webkit-animation:inputHighlighter 0.3s ease;
  -moz-animation:inputHighlighter 0.3s ease;
  animation:inputHighlighter 0.3s ease;
}

/* ANIMATIONS ================ */
@-webkit-keyframes inputHighlighter {
    from { background:#5264AE; }
  to    { width:0; background:transparent; }
}
@-moz-keyframes inputHighlighter {
    from { background:#5264AE; }
  to    { width:0; background:transparent; }
}
@keyframes inputHighlighter {
    from { background:#5264AE; }
  to    { width:0; background:transparent; }
}
</style>