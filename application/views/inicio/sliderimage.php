
<link rel="stylesheet" href="<?php echo site_url();?>css/jquery-ui.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>css/elastislide.css" />
<div class="row">
  <div class="box">
    <div class="col-lg-12 text-center">          
      <div id="cs" class="main pull-right"  width="100%" height="300px">
        <ul id="carousel" class="elastislide-list">
           <li  class="elemento">
            <a href="#"><img src="<?php echo site_url();?>images/logo/enlace1.png" class=" img-responsive" width="100%" height="300px"/>
            </a>
            </li>
            <li  class="elemento">
            <a href="#"><img src="<?php echo site_url();?>images/logo/enlace2.png" class=" img-responsive" width="100%" height="300px"/>
            </a>
            </li>
            <li  class="elemento">
            <a href="#"><img src="<?php echo site_url();?>images/logo/enlace3.png" class=" img-responsive" width="100%" height="300px"/>
            </a>
            </li>
        </ul>    
      </div>                   
    </div>
  </div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo site_url();?>js/modernizr.custom.46884.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo site_url();?>js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo site_url();?>js/jquery.elastislide.js"></script>
    <script  type="text/javascript">
      $('#carousel' ).elastislide();
    </script>

<style type="text/css">
#cs{
  width: 100%;
}

</style>
<style type="text/css">
  #divBodynov{
    margin-top:50px;
    padding: 0;
    width: 100%;
  }
  .elemento{
    width: 100%;
    margin-right: 1px;
    margin-bottom: 15px;
  }
  .elemento p{
    padding: 10px;
    font-size: 13px;
  }
  .elemento a img{
    width: 100%;
    height: 200px;
  }
@media screen and (max-width:400px ){
  #cs{
  width: 100%;
}
.elemento a img{
  width: 100%;
    height: 100px;
  }
}
</style>

