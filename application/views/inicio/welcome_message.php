<?php 
    $query = $this->db->query("select imagen,nombre 
FROM actividades
join actividadesdia on actividades.idactividad=actividadesdia.actividad_id
and actividad_id is not null
where year(actividadesdia.dia)=year(now())
and month(actividadesdia.dia)=month(now())
and dia>date_sub( now(),interval 1 day)
group by idactividad
      union all
SELECT imagen,nombre
FROM actividades
join registroactividadte on actividades.idactividad=registroactividadte.actividadid
where year(registroactividadte.dia)=year(now())
and actividadid is not null
and month(registroactividadte.dia)=month(now())
and dia>date_sub( now(),interval 1 day)  
group by idactividad       
limit 10
    ");
    $resultado=$query->result();
    $total= (isset($resultado)) ? count($resultado) :0;
 ?> 
<div class="row">

<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>css/agency.css" />
    <div class="box">
      <div class="col-lg-12 text-center">
          <div class="col-lg-2">
            <p ><a class="twitter-timeline" href="https://twitter.com/" data-widget-id="588792569858887680">Tweets.</a></p>
          </div>
          <div id="carousel-example-generic" class="carousel slide col-lg-7" style="margin-left:7px;">
              <!-- Indicators -->
              <ol class="carousel-indicators hidden-xs">
              <?php $cont=0; 
                  while($cont<$total){
                    $varclas="";
                    if($cont==0){
                      $varclas="class='active'";
                      } ?>
                  <li data-target="#carousel-example-generic" data-slide-to="<?php echo $cont; ?>" "<?php echo $varclas ?>"></li>
              <?php $cont+=1; }?>
                  
              </ol>

              <!-- Wrapper for slides -->
              <div class="carousel-inner" >
                  <?php $contador=0;
                  if($resultado){
                   foreach ($resultado as $row){
                     $img="images/".$row->imagen;?>
                     <div class="item <?php if($contador==0){ echo 'active';} ?>" style="height:350px" >
                      <img  src="<?= $img; ?>" alt="image1" class="img-rounded img-full img-responsive" height="350px" />
                    </div>
                    <?php 
                      $contador+=1;
                    }
                  } 
                  ?>
              </div>

              <!-- Controls -->
              <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                  <span class="icon-prev"></span>
              </a>
              <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                  <span class="icon-next"></span>
              </a>
          </div>
          <div class="col-lg-2">
            <?php $this->load->view('inicio/calendario'); ?>
          </div>

      </div>
    </div>
</div>
<?php $this->load->view('inicio/sliderimage'); ?>
<?php $this->load->view('inicio/novedades'); ?>
