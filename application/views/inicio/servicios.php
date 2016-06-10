
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" style="font-size:10px;">
                      <li role="presentation" ><a class="menuservicios" id="tab3" data-toggle="tab"  href="#">Consulta del catálogo bibliográfico online.</a></li>
                      <li role="presentation"><a class="menuservicios" id="tab4" data-toggle="tab" href="#">Acceso a servicios digitales.</a></li>
                      <li role="presentation"><a class="menuservicios" id="tab5" data-toggle="tab" href="#">Sala de usos múltiples "Luz Cordero de Galindo".</a></li>
                      <li role="presentation"><a class="menuservicios" id="tab6" data-toggle="tab" href="#">Orientación a usuarios.</a></li>
                      <li role="presentation"><a class="menuservicios" id="tab7" data-toggle="tab" href="#">Fotocopiado.</a></li>
                      <li role="presentation"><a class="menuservicios" id="tab8" data-toggle="tab" href="#">Visitas guiadas.</a></li>
                      <li role="presentation"><a class="menuservicios" id="tab9" data-toggle="tab" href="#">Guarda objetos.</a></li>
                      <li role="presentation" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                          Préstamos <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu"> 
                          <li ><a class="menuservicios" id="tab1" data-toggle="tab">Préstamo interno con estanteria abierta.</a></li>
                            <li><a class="menuservicios" id="tab2" data-toggle="tab">Préstamo a domicilio</a></li>                        </ul>
                      </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <div id="destab1" hidden>
                            <p align="justify">
                            Los préstamos a domicilio se tramitan mediante un registro que acredite al usuario como miembro de la biblioteca.  Los usuarios son responsables absolutos de todos los materiales que se registren en préstamo.<br/>
                            <br/>
                                Para hacer uso del préstamo a domicilio, los usuarios deberán obtener una credencial, la cual será expedida por la Biblioteca, y para esto se deben cumplir los siguientes requisitos:<br/>
                                <br/>
                                - Llenar la solicitud de registro. <br/>
                                - Proporcionar dos fotografías recientes tamaño infantil.<br/>
                                - Presentar un aval (no estudiante).<br/>
                                - Copia de identificación oficial tanto del estudiante como de su aval.<br/>
                                - Copia de comprobante de domicilio reciente (no más de dos meses de haber sido expedida) del solicitante y del aval.<br/>
                                - Copia de la credencial de la institución educativa de la que proviene el solicitante. <br/>
                                - El solicitante y su aval deberán firmar un pagaré por el valor de un  libro o un video en su caso.<br/>
                            </p>
                            TÉRMINOS Y CONDICIONES<br/>
                            <p align="justify">
                            - Una vez que sus datos sean confirmados, se le notificará vía telefónica la aceptación de la documentación y el día en que deberá presentarse para recoger su credencial.<br/>
                            - Puede ser aval toda persona solvente no menor de 18 años que se responsabilice de cumplir con las obligaciones que establece este cápítulo cuando el usuario no lo haga.<br/>
                            - En el caso de niños, uno de los padres puede fungir como aval.<br/>
                            - La vigencia de la credencial será de dos años.<br/>
                            - La credencial es personal e intransferible y el usuario se hará responsable del uso que se haga con ella.<br/>
                            - En caso de que el usuario extravíe su credencial, deberá dar aviso inmediatamente a la biblioteca a fin de evitar que otra persona lo utilice.<br/>
                            - En caso de pérdida o deterioro de la credencial, la Biblioteca expedirá un duplicado a petición del interesado, por una sola vez dentro del periodo de vigencia del registro (dos años).<br/>
                            - Tanto el usuario como su aval deberá notificar oportunamente a la biblioteca cualquier cambio de domicilio, teléfono, lugar de trabajo, escuela, etc.<br/>
                            - Las primeras tres ocasiones en que el usuario haga uso del servicio, sólo podrá llevarse en préstamo un libro. A partir de la cuarta ocasión, podrá obtener en préstamo más de tres libros simultáneamente.<br/>
                            - La duración máxima del préstamo a domicilio será de 5 días.<br/>
                            - Si el material no ha sido solicitado por otras personas y el usuario lo ha devuelto puntualmente, se podrá renovar el préstamo.<br/>
                            - En caso de que el libro que el usuario requiera se encuentre prestado, podrá solicitar el apartado del mismo a fin de que, en cuanto sea devuelto, se ponga a su disposición. El usuario deberá recoger el libro en la fecha que se le indique; de lo contrario se cancelará su apartado.<br/>
                            - El usuario deberá verificar las condiciones físicas de los materiales que ha obtenido en préstamo a domicilio, puesto que al recibirlos se hace responsable de cualquier desperfecto o daño que pudieran sufrir.<br/>
                            - El usuario está obligado a devolver en la fecha señalada los materiales obtenidos en préstamo a domicilio.<br/>       
                            </p>
                                El servicio se proporcionará a los usuarios que se registren en la Biblioteca, presentando su cédula de identificación (o credencial de préstamo a domicilio).<br/>
                        </div>
                        <div id="destab3" hidden>
                            <a href="<?php echo base_url()?>consulta"><img src="<?php echo base_url(); ?>images/buscar.png" width="121" height="30" /></a><br/>
                        </div> 
            
                </div>
            </div>
        </div>
        <script src="<?php echo site_url(); ?>js/jquery.js"></script>
        <script type="text/javascript">
                     $(".menuservicios").click(function(e){
        var tab="tab";
        for(var i=1;i<10;i++){
            if(tab+i==e.target.id){
                $('#des'+tab+i).slideDown(800);
            }else{
                $('#des'+tab+i).slideUp(1000);
            }
        }
        
        
     });
        </script>