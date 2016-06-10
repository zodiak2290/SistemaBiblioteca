
        <div id="page-wrapper" hidden  >   
            <div class="row">
                <!-- /.col-lg-12 -->
            </div></br>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span class="icon-user" style="font-size:500%"></span>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $totaluser; ?></div>
                                    <div>Usuarios</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <span class="icon-books" style="font-size:500%"></span>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $totalejemplares?></div>
                                    <div>Ejemplares</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" style="font-size:30%">
                        <?=form_open(base_url()."home/pdfs/reportar")?>
                            <td colspan="4">
                            <?php $arrmeses=array(0=>'',1=>'Enero',2=>'Febrero',3=>'Marzo',4=>"Abril",
                                                    5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",
                                                    9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre");
                            ?>
                            </td> 
                            <select id="anio" name="anio" class="form-control">
                                <?php $con=2012;
                                    while($con<date("Y")+1){ ?>
                                    <option  value="<?php echo $con ?>"><?php echo $con ?></option>
                                <?php $con++;} ?>
                            </select></br>
                            <select id="mes" name="mes" class="form-control"> 
                                <?php foreach ($arrmeses as $value =>$mes ) { 
                                    if($value!=0){    ?>
                                    <option value="<?php echo $value ?>"><?php echo $mes ?></option>
                                <?php }
                                } ?>
                            </select>   </br>                         
                             <button id="buto" type="submit" class="btn btn-xl btn-info"  >Imprimir reporte</button>
                             <?php echo form_close(); ?>
                    </div>
                </div>
            <!-- /.row -->
            <div class="row" ng-app="appBiblio" ng-controller="graficasController">
                <div class="col-lg-12" id="panelGrafica" ng-init="urlgraf='<?php echo base_url()?>'">
                        <DIV id="graficasAnio">    
                            <div class="btn-group col-lg-12">
                                <div class="btn-group col-lg-4">
                                        <select  ng-model="anioselect" class="form-control" ng-change="selectcambio()">
                                            <?php $con=2012;
                                                while($con<date("Y")){ ?>
                                                <option  value="<?php echo $con ?>"><?php echo $con ?></option>
                                            <?php $con++;} ?>
                                            <option style="display:none" value="">Año de Inicio</option>
                                        </select> 
                                </div> 
                                <div class="btn-group col-lg-4">
                                        <select  class="form-control" ng-model="aniofin" ng-options="n for n in aniosfin ">
                                             <option style="display:none" value="">Año de Fin</option>
                                        </select>
                                </div>
                                <button class="btn-group col-lg-4" ng-click="updatecanvas()">Actualizar</button>                  
                            </div>
                            <div class="col-lg-12">
                                <div class="page-header">
                                    <h1 align="center"><small>{{titulo}}</small></h1>
                                </div>
                            </div>  
                            <div class="col-lg-10">
                                <canvas id="grafica" style="heigt:200px width:100%" >
                                                                        
                                </canvas>
                            </div>

                            <div class="col-lg-2">
                                <ul id="etiquetas">
       
                                </ul>
                            </div>
                        </DIV>
                </div>
            </div>
            
            <!-- /.row -->
        </div> 

        <!-- /#page-wrapper -->
    <!-- jQuery -->

    <script>
     $( document ).ready(function() {
            $("#page-wrapper").show();
        });

    </script>
<style type="text/css">
    .fondooscuro{
        width: 5%;
        height: 5px;
        background: blue;
        border-radius: 55px;
        padding: 50px;
        -webkit-animation: waitanimation 2s infinite; /* Safari 4+ */
        -moz-animation:    waitanimation 2s infinite; /* Fx 5+ */
        -o-animation:      waitanimation 2s infinite; /* Opera 12+ */
        animation:         waitanimation 2s infinite; /* IE 10+, Fx 29+ */
    }
@-webkit-keyframes waitanimation {
   0%, 20%, 50%, 80%, 100% {
        -webkit-transform: translateY(0);}
    40% {-webkit-transform: translateY(-30px);
             background: green;}
    60% {-webkit-transform: translateY(-15px);}
}
</style>
