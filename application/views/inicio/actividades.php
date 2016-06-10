<div class="row">
    <div class="box">
<div class="col-lg-12 text-center">
      		<div id="mainBody">
          <div id="actividades" class="col-lg-12 text-center">
            <div id="container-main">
            <div align="center">
                  <ul class="pagination ">
                  <?php
                    /* Se imprimen los números de página */           
                    echo $this->pagination->create_links();
                  ?>
                  </ul>
            </div>
              <?php if(isset($acti)){
                              if(count($acti)>0&&$acti!=false){
                foreach ($acti as $a) {
               ?>
                <div class="accordion-container">
                    <a href="#" class="accordion-titulo"  style="font-size:12px"><?php echo utf8_encode(ucwords(strtolower(utf8_decode($a->nombre)))); ?><span  style="font-size:14px" class="toggle-icon"></span></a>
                    <div class="accordion-content col-lg-12" >
                        <div id="img" class="col-lg-3 imagen thumbnail" style="width:30%; heigth:150px;">
                          <img class="img-responsive container img-thumbnail"  src="<?php echo base_url()?>images/<?php echo $a->imagen; ?>" style="width:100%; heigth:150px; max-height:150px; max-width:100% ">
                        </div>
                        <div id="texto" class="col-lg-8" style="margin-left:6px;">
                          <p align="justify" style="font-size:12px"><?php echo $a->descripcion; ?>.</p>
                          <p align="justify"><em><?php echo ($a->periodo." ".$a->dia." de ".$a->mes);?></em></p>
                          <p align="justify"><?php echo $a->horainicio; ?>-<?php echo $a->horafin; ?>.</p>    
                       </div>
                    </div>  
                </div>
                <?php }
                          }
                 }?>
            </div>
          </div>
        </div>
      	</div>
  </div>
  </div>
<style type="text/css">
  #actividades{
    display: flex;
    width: 100%;  
  }
 
#container-main{
    width:100%;
    min-width:30%;
    max-width:95%;
    margin-left: 20px;
    margin-rigth: 20px;

}
 
#container-main h1{
    font-size: 40px;
    text-shadow:4px 4px 5px #16a085;
}
 
.accordion-container {
    width: 100%;
    margin: 0 0 20px;
    clear:both;
}
 
.accordion-titulo {
    position: relative;
    display: block;
    padding: 10px;
    font-size: 24px;
    font-weight: 300;
    background: #a97c50;
    color: #fff;
    text-decoration: none;
}
.accordion-titulo.open {
    background: #16a085;
    color: #58595B;
}
.accordion-titulo:hover {
    background: #c2b59b;
}
 
.accordion-titulo span.toggle-icon:before {
    content:"+";
}
 
.accordion-titulo.open span.toggle-icon:before {
    content:"-";
}
 
.accordion-titulo span.toggle-icon {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 38px;
    font-weight:bold;
}
 
.accordion-content {
    display: none;
    padding: 20px;
    overflow: auto;
}
 
.accordion-content p{
    margin:0;
}
 
.accordion-content img {
    display: block;
    float: left;
    margin: 0 15px 10px 0;
    width: 50%;
    height: auto;
}
 
 
@media (max-width: 800px) {
    .accordion-content {
        padding: 10px 0;
    }
    .accordion-container {
      width: 100%;
      margin: 0 0 10px;
      clear:both;
    }
}
</style>

<script src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript">
$(".accordion-titulo").click(function(){
   var contenido=$(this).next(".accordion-content");
   if(contenido.css("display")=="none"){ //open   
      contenido.slideDown(250);     
      $(this).addClass("open");
   }
   else{ //close    
      contenido.slideUp(250);
      $(this).removeClass("open");  
  }
              
});
</script>