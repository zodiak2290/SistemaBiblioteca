<?php $arrmeses=array(0=>'',1=>'Enero',2=>'Febrero',3=>'Marzo',4=>"Abril",
                        5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",
                        9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre");
?>
<div class="col-lg-12"  id="destab1">
        <div class="panel panel-default col-lg-10">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Visitas
                <div class="pull-right">
                    <div class="btn-group ">
                        <select hidden  id="veraniorea" class="selectfecha">
                            <?php $con=2012;
                                while($con<date("Y")+1){ ?>
                                <option  value="<?php echo $con ?>"><?php echo $con ?></option>
                            <?php $con++;} ?>
                        </select>
                    </div>
                    <div class="btn-group">                                  
                        <select hidden id="vermesrea" class="selectfecha"> 
                            <?php foreach ($arrmeses as $value =>$mes ) 
                            { $var="";
                               if($value!=0){ ?>
                              <option value="<?php echo $value ?>"><?php echo $mes ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="btn-group hidden" id="verdiarea" >
                        <input  type="text" placeholder="Dia"  id="diarea" class="diachange">
                    </div>
                    <div class="btn-group">
                        <button class="btn-info" ng-click="cargardatos('<?php echo base_url()?>home/estadisticasvisitas/','graficavisistasral','area','rea')" >Ver</button>
                    </div>
                    <div class="btn-group">
                        <div>
                            <button id="menu2" type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                Opciones
                                <span class="caret"></span>
                            </button>
                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="menu2">
                            <li ng-click="periodocom('dia','rea')" class="hidden"><a>Día</a>
                            </li>
                            <li ng-click="periodocom('mes','rea')"><a>Mes</a>
                            </li>
                            <li ng-click="periodocom('anio','rea')"><a>Año</a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="graficavisistasral"></div>
            </div>
            <!-- /.panel-body -->
        </div>

        <!-- /.panel -->
        
    </div>