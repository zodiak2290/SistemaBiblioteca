<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Inicio</title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css" type="text/css" media="screen"/> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/alertify.core.css" type="text/css" media="screen"/> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/alertify.default.css" type="text/css" media="screen"/> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/angular/angular-material.min.css" type="text/css" media="screen"/> 
    <link rel="stylesheet" href="<?php echo site_url(); ?>css/libros/carga.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo site_url(); ?>css/simple-sidebar.css" type="text/css" media="screen"/>  
    <link rel="stylesheet" href="<?php echo site_url(); ?>css/inicio/fonts.css" type="text/css" />
    <!-- Custom CSS -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <script src="<?php echo base_url()?>js/jquery.min.js"></script>
        <script src="<?php echo base_url()?>js/angular.min.js"></script>
        <script src="<?php echo base_url()?>js/angular/angular-material.min.js"></script>
        <script src="<?php echo base_url()?>js/angular/angular-aria.min.js"></script>
        <script src="<?php echo base_url()?>js/angular/angular-animate.min.js"></script>
        <script src="<?php echo base_url()?>js/angular/angular-messages.min.js"></script>
        <script src="<?php echo base_url()?>js/alertify.js"></script>
        <script src="<?php echo base_url(); ?>js/servicioangular.js"></script>
        <!--Para mostrarlib --> 
        <script src="<?php echo base_url(); ?>js/scriptdatos/editardatos.js"></script>
        <script src="<?php echo base_url();?>js/dist/raphael-min.js"></script>
        <script src="<?php echo base_url();?>js/morris.min.js"></script>  
        <script src="<?php echo base_url(); ?>js/scriptdatos/ejemplares.js"></script>
        <!-- mostralibfin -->
        <script  language="javascript" type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <?php if($rol==1){ ?>
        <!--  CSS -->
        <link href="<?php echo site_url();?>css/sb-admin-2.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url();?>css/datepicker.css">
        <!--  JS -->
        <script src="<?php echo site_url();?>js/dist/jquery.min.js"></script>
        <script src="<?php echo site_url();?>js/Chart.min.js"></script>
        <script src="<?php echo base_url()?>js/datepicker.js"></script>  
        <script src="<?php echo site_url(); ?>js/bootstrap-datepicker.es.js"></script>
        <script src="<?php echo site_url(); ?>js/scriptdatos/inicialadmin.js"></script>
        <?php }elseif ($rol==3){ ?>
            <script src="<?php echo site_url();?>js/dist/jquery.min.js"></script>
            <script src="<?php echo site_url();?>js/Chart.min.js"></script> 
            <script src="<?php echo base_url()?>js/datepicker.js"></script>  
            <script src="<?php echo site_url(); ?>js/bootstrap-datepicker.es.js"></script>
            <script src="<?php echo site_url(); ?>js/scriptdatos/inicialadmin.js"></script>
            <link href="<?php echo site_url();?>css/sb-admin-2.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo base_url();?>css/datepicker.css">
        <?php }elseif($rol==2){ ?>
            <script src="<?php echo site_url();?>js/Chart.min.js"></script>
        <?php }elseif($rol==4){?>
            <link rel="stylesheet" href="<?php echo base_url()?>css/calendar.min.css">
            <link rel="stylesheet" href="<?php echo base_url()?>components/bootstrap3/css/bootstrap-theme.min.css">
              <script type="text/javascript" src="<?php echo base_url()?>components/underscore/underscore-min.js"></script>
             <script  src="<?php echo base_url()?>js/calndarjs/calendar.js"></script>
            <link rel="stylesheet" href="<?php echo base_url(); ?>css/w2ui-fields-1.0.min.css" type="text/css" media="screen"/> 
         <script src="<?php echo base_url()?>js/w2ui-fields-1.0.min.js"></script>
        <?php }?>
</head>
<body>
<?php $sesion=$this->session->userdata('logged_in'); $rol=$sesion['rol']; ?> 
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav nav nav-tabs nav-stacked" id="menuVer">
            <?php if($rol!='4'){?>
                <li class="sidebar-brand" id="menuBrand">
                    <a href="<?php  echo base_url(); ?>home" style="color: #000;">
                        <span class="icon-home"> <em><?php  echo $sesion['cuentausuario']; ?>  </em></span> 
                    </a>
                </li>

            <?php }; ?>
                <li id="liperfil">
                    <a href="<?php  echo base_url(); ?>home/ajustes" ><span class="icon-cog "></span>  Perfil</a>
                </li>
            <?php if($rol=='8'){ ?>
               <li id="usuarios">
                    <a aria-expanded="false" class="collapsed" href="javascript:;" data-toggle="collapse" data-target="#demo"> <span class="icon-address-book"></span> Empleados</a>
                    <ul style="height: 0px;" aria-expanded="false" id="demo" class="collapse">
                        <li>
                            <a href="<?php  echo base_url(); ?>home/empleados/analistas">Analistas</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url(); ?>home/empleados/difusores">Difusión</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url(); ?>home/empleados/prestador">Préstamo</a>
                        </li>
                        <li>
                            <a href="<?php  echo base_url(); ?>home/empleados/encargadosala">Encargado de sala</a>
                        </li>
                        <li hidden>
                            <a href="<?php  echo base_url(); ?>home/empleados/recepcion">Recepción</a>
                        </li>
                    </ul>
                </li>
             <?php }; ?>    

              
            <?php if($rol=='4'){ ?>
                <li id="liactividades">
                    <a href="<?php echo base_url(); ?>home/actividades"><span class="icon-calendar"></span>  Actividades</a>
                </li>
            <!--
                <li>
                    <a href="<?php echo base_url(); ?>home/publicaciones"><span class="icon-feed"></span>  Publicarciones</a>
                </li>
            -->
                <li id="linovedades">
                    <a href="<?php echo base_url(); ?>home/novedades"><span class="icon-books"></span>  Novedades</a>
                </li>
                <li id="liactividadessf">
                    <a href="<?php echo base_url(); ?>home/actvidadessinfecha"><span class="icon-calendar"></span>  Actividades sin fecha</a>
                </li>
            <?php }; ?>
            <?php if($rol=='3'){ ?>                
                <li id="menuusuario">
                    <a href="<?php echo base_url(); ?>home/usuarios"><span class="icon-user"></span>  Usuarios</a>
                </li>
                <li id="menuprestamo">
                    <a   href="<?php echo base_url(); ?>home/prestamo"><span class="icon-book"></span>  Prestámos</a>
                </li>
             <!--  <li>
                    <a href="<?php echo base_url(); ?>home/grupos"><span class="icon-users"></span>  Grupos</a>
                </li> --> 
            <?php }; ?>
            <?php if($rol=='2'){ ?>                
                <li id="liaddlibro">
                    <a href="<?php echo base_url(); ?>home/fichas"><span class="icon-book"></span> Registrar libro</a>
                </li>
                <li id="liaddejem">
                    <a href="<?php echo base_url(); ?>home/libros"><span class="icon-plus"></span> Agregar ejemplar</a>
                </li>
                <li id="liejem">
                    <a href="<?php echo base_url(); ?>home/ejemplares"><span class="icon-book"></span>  Ejemplares</a>
                </li>
                <li id="licb">
                    <a href="<?php echo base_url(); ?>home/ejemplares/codes"><span class="icon-barcode"></span>  Código de barras</a>
                </li>
                <li id="libajas">
                    <a href="<?php echo base_url(); ?>home/ejemplares/bajas"><span class="icon-bin"></span> Bajas</a>
                </li>
            <?php }; ?>
            <?php if($rol=='6'){ ?>
                <li id="liuserat">
                   <a href="<?php echo base_url(); ?>home"><span class="icon-list2"></span>Usuarios atendidos </a>
                </li>
             <?php };?>
                 <li>
                    <a href="<?php echo base_url(); ?>"><span class="icon-"></span> Página principal</a>
                </li>    
                <li>
                    <a href="<?php echo base_url(); ?>home/logout"><span class="icon-exit"></span> Cerrar sesión</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"> 
                        <span class="icon-menu"></span></a>
                    </div>

                    <div id="content"> 
                        <div id="left">
                        <!-- Contenido enviado desde el home-->                                    
                            <?php if(isset($content)){
                                $this->load->view($content); 
                             }?> 
                            
                        
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->

    
    <!-- Menu Toggle Script -->
    <script language="javascript" type="text/javascript">
    $("#menuVer li").click(function(e){
        var idelem=$(this).attr("id");
        window.localStorage.setItem("presionado",idelem); 
    });
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");

    });
    $(document).ready(function(){
        $("#"+localStorage.getItem('presionado')).addClass("active")
        $("#"+localStorage.getItem('presionado')).addClass('resplandorverde');
    });
    </script>
</body>
</html>
<style type="text/css">
  .resplandorverde{   
      -moz-box-shadow: 0px 0px 30px #A3FF0F; 
      -webkit-box-shadow: 0px 0px 30px #A3FF0F; 
      box-shadow: 0px 0px 30px #A3FF0F;
      
      border: 1px solid #66ff00;
   }
body{
    background: white;
}
</style>
