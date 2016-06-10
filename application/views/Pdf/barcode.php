<div id="page-wrapper" class="col-md-12">
<div class="page-header"> 
</div>

    <div id="mainBody">
    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i><?php if($datos){ echo " Códigos agregados"; }else{
                                  echo "No hay códigos para imprimir";}
                              ?>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <?php if($datos){  ?>
                                    <button id="menu1" type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                        Opciones
                                        <span class="caret"></span>
                                    </button>
                                    
                                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="menu1">
                                        <li><a href="<?php echo base_url(); ?>home/ejemplares/barcode">Imprimir</a>
                                        </li>
                                        <li ><a href="<?php echo base_url(); ?>home/ejemplares/barcodes/vaciar">Vaciar lista</a>
                                        </li>
                                    </ul>
                                   <?php }?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                         <div class="table-responsive col-lg-12" >      
                           <table class="table table-condensed table-bordered table-striped">
                              <tbody>
                                  <?php if($datos){
                                          $cont=0;?>
                                    <tr><?php foreach ($datos as $row){ ?>
                                              <td>
                                                 <a href="<?php echo base_url(); ?>home/ejemplares/barcodes/eliminar/<?php echo $row->dato?>">Eliminar-></a><?php echo $row->dato;?>
                                              </td>
                                            <?php
                                            if(($cont+1)%7==0){ ?>
                                              </tr><tr>
                                          <?php }  $cont++; }?>
                                        </tr>
                                <?php }; ?>
                                </tbody>
                              </table>
                          </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
      </div>
      </div>




