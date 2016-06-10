
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">Visitanos</h2>
                    <hr>
                </div>
                <div class="col-md-8">
                    <!-- Embedded Google Map using an iframe - to select your location find it on Google maps and paste the link as the iframe src. If you want to use the Google Maps API instead then have at it! -->
                    mapa aqui 
                </div>
                <div class="col-md-4">
                    <p>Tels:
                        <strong>(52) xxx.xxx.xxx, xxx.xxx.xxx</strong>
                    </p>
                    <p>Abierto: 
                        <strong>Lunes a viernes, de 7:00 a 20:00 horas</strong>
                      
                    </p>
                    <p>Dirección:
                        <strong>Ubicacionn aqui 
                            <br>Oaxaca, Oax</strong>
                          
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="row" >
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">Contacto
    
                    </h2>
                    <hr>
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
                    <?php
                         //apuntamos el action del formulario a la funcion del controlador
                         echo form_open(base_url().'validaremail/enviar');
                        ?>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>Nombre</label>
                                <?php
                                  $options = array(
                                   'name'=>'nombre',
                                   'value'=> '',//se inicializa en el controlador
                                   'maxlength'=> '200',
                                   'class'=>'form-control',
                                  );
                                 echo form_input($options);
                                 echo form_error('nombre', '<div class="error">', '</div>');
                                 ?>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Email</label>
                                                <?php
                                      $options = array(
                                       'name'=>'email',
                                       'value'=> '',//se inicializa en el controlador
                                       'maxlength'=> '200',
                                       'class'=>'form-control',
                                      );
                                     echo form_input($options);
                                     echo form_error('email', '<div class="error danger">', '</div>');
                                     ?>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Asunto</label><?php
                                $options = array(
                       'name'=>'asunto',
                       'value'=> '',//se inicializa en el controlador
                       'maxlength'=> '200',
                        'class'=>'form-control',
                     );
                     echo form_input($options);
                     echo form_error('asunto', '<div class="error">', '</div>');
                     ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-12">
                                <label>Mensaje</label>
                                <?php
                      echo form_error('mensaje', '<div class="error">', '</div>');
                     ?><br>
                     <?php
                     $options = array(
                       'name'=>'mensaje',
                       'value'=>'',
                       'id'=>'mensaje',
                       'class'=>'form-control',
                       'maxlength'=>'200'
                     );
                     echo form_textarea($options);
                     ?>
                            </div>
                            <div class="form-group col-lg-12">
                                <input type="hidden" name="save" value="contact">
                                <button type="submit" class="btn btn-default">Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>