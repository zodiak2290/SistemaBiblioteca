<div class="row">
  <div class="box">
    <div class="col-lg-12 text-center">
    <script src="<?php echo base_url()?>js/jquery.min.js"></script>
      <div class="col-md-12">
        <div id="mainBody">
          <div id="panel"  style="font-size:23px"></div>
          <div class="panel-body" style="font-size:13px">
            <div class="row">
              <div class="col-lg-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover table-striped" id="tabla" >
                    <thead style="background: linear-gradient(180deg,#c79a2d,#fff);"></thead>
                    <tbody ng-if="list_data.nadqui" class="small" id="body">
                      <tr style="background: linear-gradient(180deg,#fff,#c79a2d);">
                        <td colspan="5" align="center">
                          <strong>
                            Datos del Ejemplar
                          </strong>
                        </td>
                      </tr>
                      <tr>                                                           
                        <td colspan="3">
                          Titulo:
                          <span  class="pull-center">
                            <em>
                              <?php echo utf8_encode(ucwords(strtolower(utf8_decode($resultados['titulo']))))?>
                            </em>
                          </span>
                        </td>
                        <td>
                          Clasificación:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['clasificacion']?>
                            </em>
                          </span>
                        </td>
                        <td>
                          Autor:
                          <span  class="pull-center">
                            <em>
                              <?php echo utf8_encode(ucwords(strtolower(utf8_decode($resultados['nameautor']))))?>
                            </em>
                          </span>
                        </td> 
                      </tr>
                      <tr>
                        <td colspan="3" >
                          ISBN:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['isbn']?>
                            </em>
                          </span>
                        </td>
                        <td>
                          Idioma:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['ci']?>
                            </em>
                          </span>
                        </td>
                        <td>
                          Edición:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['edicion']?>
                            </em>
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          Editorial:
                          <span  class="pull-center">
                            <em>
                              <?php echo utf8_encode(ucwords(strtolower(utf8_decode($resultados['edit']))));?>
                            </em>
                          </span>
                        </td>
                        <td colspan="2">
                          Descr. Fisica:
                          <span  class="pull-center">
                            <em>
                              <?php echo utf8_encode(ucwords(strtolower(utf8_decode($resultados['des']))));?>
                            </em>
                          </span>
                        </td>
                        <td colspan="2">
                          Serie:
                          <span  class="pull-center">
                            <em>
                              <?php echo utf8_encode(ucwords(strtolower(utf8_decode($resultados['asiento']))));?>
                            </em>
                          </span>
                        </td>              
                      </tr>
                      <tr>
                        <td colspan="2">
                          Nota General:
                          <span  class="pull-center">
                            <em>
                              <?php echo utf8_encode(ucwords(strtolower(utf8_decode($resultados['nota']))));?>
                            </em>
                          </span>
                        </td>
                        <td colspan="4">
                          Contenido:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['cont']?>
                            </em>
                          </span>
                        </td>             
                      </tr>
                      <tr>
                        <td colspan="6">
                          Materia(s):
                          <span  class="pull-center">
                            <em>
                              <?php echo utf8_encode(ucwords(strtolower(utf8_decode($resultados['stema']))));?>
                            </em>
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          Ejemplar:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['ejemplar']?>
                            </em>
                          </span>
                        </td>
                        <td>
                          Tomo:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['tomo']?>
                            </em>
                          </span>
                        </td>
                        <td colspan="2">
                          Volumen:
                          <span  class="pull-center">
                            <em>
                              <?php echo $resultados['volumen']?>
                            </em>
                          </span>
                        </td>
                      </tr>
                      <tr >
                        <td colspan="2">
                          Estado:
                          <?php  if($resultados['status']==1){ $estado= "Disponible";
                                }elseif ($resultados['status']==2){$estado="En reparación";
                                }elseif ($resultados['status']==3){$estado="Prestámo";
                                }elseif($resultados['status']==4){$estado="Reservado";
                                }?>
                          <span  class="pull-center"><em><?php echo $estado ?></em></span>
                        </td>
                        <td colspan="2"><?php if($resultados['fechaentrega']){echo "Fecha de vencimiento: ". $resultados['fechaentrega']; }?>
                        </td>
                        <td>
                          Colección:
                         <?php if(mb_substr($resultados['clasificacion'],0,1)=='I' ){
                                                      echo "Infantil";
                                                    } else if (mb_substr($resultados['clasificacion'],0,1)=='C') {
                                                      echo "Consulta";
                                                    }else{
                                                      echo "General";
                                                    }
                                                    ?>                   
                        </td>
                      </tr>
                    <?php
                        if($resultados['numej']==0){ ?>
                      <tr>
                        <td >
                          Solo préstamos internos
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <!-- /.table-responsive -->
          </div>
        </div>
        <!-- /.row -->
      </div>
    <!-- /.panel-body -->
    </div>
  </div>
</div>
</div>
<?php if($resultados['status']!=1){?>
<div class="row">
    
  <div class="box">
  Sugerencias
  <?php  if(isset($sugerencias)){
                      if($sugerencias){?>
      <table id="tabla" class="table table-striped" style="font-size:12px">
                  <thead id="cabecera">
                    <tr >                
                      <th>N°</th>
                      <th>Titulo</th>
                      <th>Autor</th>
                      <th>Clasificación</th>
                    </tr>
                  </thead>
                  <tbody id="body">
                    <?php
                    $contador=1;
                    
                      // Se imprimen los datos
                      if(count($sugerencias)>0){
                      foreach ($sugerencias as $libro) {
                    ?>
                     <tr id="row">               
                      <td><a href="<?php echo base_url()?>ejemplar/<?php echo $libro->nad?>"><?php echo $contador?></td>       
                        <td><?php echo utf8_encode(ucwords(strtolower(utf8_decode(explode("/",$libro->titulo)[0]))))?></td>
                        <td><?php echo utf8_encode(ucwords(strtolower(utf8_decode($libro->nameautor))))?></td>
                        <td><?php echo $libro->clasificacion?></td>
                      </tr>
                    <?php   $contador++; 
                  }
                }
                ?>
              </tbody>
            </table>
            <?php }else{
                   ?>
                     <tr style="text-align:center">               
                      <td colspan="5" >:Sin resultados</td>       
                      </tr>
                    <?php  
                }
                }?>
  </div>
  <?php }?>
</div>

<style type="text/css">
  @media screen and (max-width:800px ){
  #panel{
    font-size: 13px;
  }
}
</style>
<script type="text/javascript">
   $( document ).ready(function() {
             
              $("#consulta").show();
              $('#divBodynov').hide();
              $('#mainBody').fadeIn(500);
              $("#art1izq").hide();
              
        });
</script>
<style type="text/css">
#tabla{
 
  border-radius: 20px;

}
#thead{
    background: linear-gradient( 180deg,#c79a2d,#fff);
 }
 #tabla  #cabecera{
  
 background: linear-gradient(270deg, #fff 5%,#c79a2d);
  background:-webkit-linear-gradient(270deg, #fff 5%,#c79a2d);
  background: -moz-linear-gradient(270deg, #fff 5%,#c79a2d);
}
</style>
<script type="text/javascript">
   $( document ).ready(function() {
              $('#page-wrapper').fadeIn(500);
        });
</script>