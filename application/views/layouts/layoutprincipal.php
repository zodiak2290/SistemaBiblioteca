<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php 
        $sesion=$this->session->userdata('logged_in');
        if(isset($sesion['rol'])){
            $rol=$sesion['rol'];
        }else{
            $rol="";
        }
        
    ?>
    <title>Biblioteca</title>
    <!-- Bootstrap Core CSS --> 
    <link rel='stylesheet' type='text/css' href="<?php echo site_url();?>css/bootstrap.min.css"  media="all" />
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>css/business-casual.css"  media="all" />
    <!-- Fonts -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url()?>js/angular.min.js"></script>
</head>
<body id="body" hidden>
    <div class="brand titulo" hidden>     
        <img style="max-width:100px; height:50px; margin-top: -7px;"
             src="<?php echo site_url(); ?>images/logo/oax.png">
            Biblioteca 
         <img style="max-width:100px; height:50px; margin-top: -7px;"
             src="<?php echo site_url(); ?>images/logo/looax.png">
    </div>
    <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container titulo">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"  data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
                <a class="navbar-brand titulo" href="<?php base_url() ;?>">Biblioteca Pública</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav menu">
                    <li><a href="<?php echo base_url() ?>">Inicio </a> </li>
                    <li><a  href="<?php echo base_url()?>biblioteca">Biblioteca</a></li>
                    <!--<li ><a href="<?php echo base_url()?>servicios" >Servicios</a></li>-->
                    <li><a href="<?php echo base_url()?>actividades">Actividades</a></li>
                    <li><a href="<?php echo base_url()?>consulta">Catálogo</a></li>
                    <li><a href="<?php echo base_url()?>contacto">Contacto</a></li>
                    <?php if($sesion&&$rol!=11){?>
                    <li><a href="<?php echo base_url(); ?>home/logout">Cerrar sesión</a></li>
                    <li><a href="<?php echo base_url(); ?>home">Mi cuenta</a></li>
                    <?php }else{?>
                    <li><a href="<?php echo base_url()?>login">Iniciar sesión</a></li>
                    <?php }?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <div class="container">
            <!--Vista enviada desde welcome -->
            <?php $this->load->view('inicio/'.$content); ?>
    </div>
    <!-- /.container -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Biblioteca &copy; </p>
                </div>
            </div>
        </div>
    </footer>
    <style type="text/css">
    .box{
        border-radius: 10px;
    }
    .menu li a{
        font-size: 12px;
        font-family: "HelveticaNeue-CondensedBold", "Helvetica Neue";
    }
    .container{
        font-size: 12px;
        font-family: "HelveticaNeue-CondensedBold", "Helvetica Neue";
        
    }
    @font-face {
    src: url('<?php echo site_url();?>css/tipogr/humnst777-bt-bold.woff');
    }
    .titulo{
         font-family: "Humnst777 Bold BT";
         font-size: 16px;
    }
    .nav li:hover{
        background: rgb(199,154,45);
    }
    .nav li a{
        font-family: 'Roboto', sans-serif;
        font-size: 12px;
    }
.navbar-default .navbar-nav>li>a {
    color: #fff;
}
    .brand{
        background: linear-gradient(90deg, #fff 25%,#c79a2d);
        background: -webkit-linear-gradient(90deg, #fff 25%,#c79a2d); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(90deg, #fff 25%,#c79a2d); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(90deg, #fff 25%,#c79a2d); /* For Firefox 3.6 to 15 */
    }
    .brand{
        background: rgba(255,255,255,0.6);
    }
    .navbar{
        background: rgba(112,75,35,1);
    }
    </style>
<script type="application/javascript" src="<?php echo site_url(); ?>js/jquery.min.js"></script>
<script type="application/javascript" src="<?php echo site_url(); ?>js/bootstrap.min.js"></script>
<script src="<?php echo site_url(); ?>js/angular/angular-animate.min.js"></script> 
<script src="<?php echo site_url(); ?>js/angular/angular-aria.min.js"></script> 
<script src="<?php echo site_url(); ?>js/angular/angular-material.min.js"></script> 
<script src="<?php echo site_url(); ?>js/angular/angular-messages.min.js"></script>   
    <!-- jQuery -->

    <!-- Script to Activate the Carousel -->
    <script language="JavaScript" type="application/javascript">
       $( document ).ready(function() {
            $("#body").fadeIn(100);
            $("#titulo").fadeIn(1000);

    $(window).scroll(function(){
        var barra = $(window).scrollTop();
        var posicion =  (barra * 0.04);
        
        $('body').css({
            'background-position': '0 -' + posicion + 'px'
        });
 
        });
 
});

    </script>
</body>
</html>
