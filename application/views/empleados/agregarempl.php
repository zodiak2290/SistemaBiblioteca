<div id="nuevoem" class="wrapper" ng-app="setting" ng-controller="setcontroller" ng-cloak>
        <div ng-init="urlemail='<?php echo base_url()?>home/empleados/email/json'"></div>
        <div ng-init="urlcuenta='<?php echo base_url()?>home/empleados/cuenta/json'"></div>
            <div class="row">
                <div class="col-lg-8 text-center">
                    <h3 class="section-subheading text-muted">     
                        <em class="section-heading">Agregar empleado</em>
                </h3>
                </div>
            </div>              
            <div class="col-lg-12">
             <?php if(validation_errors()){?>
                        <div class="alert alert-danger">
                           <span><?php echo validation_errors(); ?></span>
                        </div>
                <?php }?>  
            <?=form_open(base_url()."empleado/agregar")?>
                <div class="row text-center">
                    <div class="col-md-12" align="center">
                    <div class="col-md-6" align="center">  
                        <div class="form-group">
                        <label>CURP</label>
                            <p class="help-block text-warning">{{mensajecurp}}</p>
                            <input type="text"  class="form-control" placeholder="Curp *" id="curp" name="curp" maxlength="18"   value="<?php echo set_value('curp'); ?>" ng-model="curp"  ng-keydown="validaCURP($event)" ng-change="validando(3)" required>
                        </div> 
                        <div class="form-group">
                            <label>Ingrese el nombre completo</label>
                              <input type="text" placeholder="Nombre"  id="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" required class="form-control">
                              <p class="help-block text-danger"></p>
                        </div>
                        <div class="form-groupr">
                            <label>Correo electrónico</label>
                            <input type="email" ng-model="email" ng-change="validar(3)" class="form-control" value="<?php echo set_value('email'); ?>" placeholder="Email"  id="email" name="email" >
                            <p class="help-block text-danger"> <span >{{emailvalido}}.</span></p>
                        </div>
                    </div>
                    <div class="col-md-6" align="center">        
                       <?php $this->load->view('empleados/partial_form_cuenta'); ?>
                        <div class="form-group">
                            <select id='rol' name='rol' class="form-control">
                                  <option id='2' value='2'>Procesos Técnicos</option>
                                  <option id='3' value='3'>Préstamo de libros</option>
                                  <option id='4' value='4' >Difusión</option>
                                  <option id='5' value='5' >Encargado de Sala</option>
                                  <option id='6' value='6' >Recepción</option>
                                <select>
                        </div>
                    </div>
                </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12">
                                <div id="success" align="center"></div>
                                <button id="buto" type="submit" class="btn btn-xl">Aceptar</button>
                            </div>
                </div>
            <?=form_close()?>
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
