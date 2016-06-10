<?php 
    $this->load->database(); 
    $query = $this->db->query("select ejemplar.nadqui,imagen,descripcion,idnovedad,titulo
from novedades join ejemplar on novedades.nadqui=ejemplar.nadqui
join libros on idlibro=nficha
left join librosbloqueados as lb on lb.nadqui=ejemplar.nadqui
where lb.nadqui is null
order by ejemplar.nadqui desc
limit 15;
    ");
 ?> 
 <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/agency.css"  media="all" />
<div class="row">
    <div class="box">
      <div class="col-lg-12 text-center">
          <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Novedades</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 mySliderTabs">
                    <ul class="nav nav-tabs">
                     <?php $contador=0; 
                        foreach ($query->result() as $row){
                            $class="";
                        if($contador==0) {
                             $class="active";
                          }  
                        ?> 
                        <li class="<?php echo $class; ?>"><a data-toggle="tab" href="#<?php echo $contador;?>">
                            <?= substr(explode("/",utf8_encode(ucwords(strtolower(utf8_decode($row->titulo)))))[0],0, 7); ?></a></li>
                    <?php $contador+=1;}; ?>
                   </ul>
                    <div class="tab-content">
                     <?php $contador=0; 
                        foreach ($query->result() as $row){
                            $class="fade";
                            $img=base_url()."images/novedades/".$row->imagen;
                        if($contador==0) {
                             $class="fade in active";
                          }  
                        ?> 
                      <div id="<?php echo $contador; ?>" class="tab-pane <?php echo $class;?>">
                            <h3><?= utf8_encode(ucwords(strtolower(utf8_decode($row->titulo)))); ?></h3>
                            <div class="col-md-4" id="divimg<?php echo $row->idnovedad ?>">
                                <a href="<?php echo site_url();?>ejemplar/<?php echo $row->nadqui; ?>">
                                    <img class="img-thumbnail img-responsive "  style="width: 155px; height:180px;" src="<?= $img; ?>" alt="" >
                                </a>
                            </div>
                            <div class="col-md-8">
                                <p align="justify"><?php  echo utf8_encode(ucwords(strtolower(utf8_decode($row->descripcion))));  ?></p>    
                            </div>
                            
                      </div>
                      <?php $contador+=1;}; ?>
                </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
   $(".more").click(function(e) {
        if($('#complete'+e.target.id).is(':hidden')){
            $('#complete'+e.target.id).show(1000);
            $('#sumary'+e.target.id).hide();
            $('#'+e.target.id).text("Ocultar");

            $('#panel'+e.target.id).addClass('rcorners1');
        }else{
            $('#complete'+e.target.id).hide(1000);
            $('#sumary'+e.target.id).show();
            $('#'+e.target.id).text("Leer mas...");
            $('#panel'+e.target.id).removeClass('rcorners1');
        }
    }); 

</script>
<style type="text/css">
    .imagenes:hover {
  animation-duration: 1s;
  animation-name: slidein;
  -webkit-transform: scale(1.2);
    -moz-transform: scale(1.2);
    -o-transform: scale(1.2);
    -ms-transform: scale(1.2);
    transform: scale(1.2);
    -webkit-box-reflect: below 0px -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(.7, transparent), to(rgba(0,0,0,0.4)));
    -webkit-box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
    -moz-box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
    box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
}
.invertida:hover{
    margin-left: -50px;
    width: 250%;
}
.invernull:hover{
    width: 250%;
    margin-left: 50px;
}
@keyframes slidein {
    0%   {
        width: 100%;
        opacity: 3;
    }   
    25%  {
        opacity: 1;
    }
    50%  {

    }
    75%  {
        opacity: .9;
    }
    100% {
        opacity: 1;
        width: 200%;
    }
}
.rcorners1{
    margin-right: 1%;
    z-index:99999; 
    text-indent: 2em;
    /*background: rgba(255,255,255,0);*/ 
    text-align: justify;
    /* Propiedad a animar, si se desean animar todas las propiedades se puede utilizar all*/
    transition-property: all;
    /* Duracion de la transicion*/
    transition-duration: 3s;
    /* Curva de aceleración a utilizar */
    transition-timing-function: linear;
    /* Tiempo de espera antes de iniciar la transición */
    transition-delay: 0s;
    font-family: cursive;
    border-style: solid outset; 
    border-width: 5px;
    border-radius: 45px;
    background:rgba(199,154,45,.7);
}
</style>

