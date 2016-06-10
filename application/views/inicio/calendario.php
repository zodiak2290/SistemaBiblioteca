<div id="calendario" class="calendario" >
  <div >
        <table class="table table-bordered table-hover table-striped">
                              <thead style="background: linear-gradient(180deg,#c79a2d,#fff);">
                                 <tr>         
                                  <th colspan="7" style="text-align:center">Actividades del mes</th>
                                </tr>
                              </thead>
                              <tbody >
                                <tr style="background: linear-gradient( 180deg,#fff,#c79a2d);">
                                  <td colspan="4">
                                  <?php $arrmeses= array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>"Abril",
                                              5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",
                                              9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre");
                                   echo $arrmeses[intval(date('m'))]?>
                                  </td>
                                  <td colspan="3">
                                    <?php echo date("Y")?>
                                  </td>
                                </tr>
                                <tr style="background: #c2b59b;">
                                  <td>D</td><td>L</td><td>M</td><td>M</td><td>J</td><td>V</td><td>S</td>
                                </tr>
                                <tr>
                                <?php 
                                $diasdelMes=date("d",mktime(0,0,0,date("m")+1,0,date("Y")));
                                $diaSemana=date("w",mktime(0,0,0,date("m"),1,date("Y")));
                                $limitador=$diasdelMes+$diaSemana;
                                 $cont=0;$contdias=0;
                                    while ($cont<=$limitador){
                                  if($cont<$diaSemana){?>
                <td></td>
              <?php }
              elseif($contdias<$diasdelMes){
                $hoy=date("d");
                if($contdias+1==$hoy){
                  $style="style='background: linear-gradient( 180deg, #c2b59b,#fff)';";}
                else{
                    $style="";
                } ?>
                <td class="acalendar" <?php echo $style; ?> >
                <?php if($contdias+1>=$hoy){ ?>
                  <a class="cla" style="color:#58595B" href="<?php  echo base_url();?>actividad/<?php echo $contdias+1;?>/" > 
                  <?php } $contdias=$contdias+1; echo $contdias;?></a>
                              </td>
                            <?php
                            if(($cont+1)%7==0){ ?>
                              </tr><tr>
                          <?php } } 
                            $cont++;
                            }
                                
                          ?>
                        </tr>
        </tbody>
      </table>
    </div>
</div>
<style type="text/css">
  .calendario{
    margin-top: 4px;
    font-size: 0.8em;
  }
</style>