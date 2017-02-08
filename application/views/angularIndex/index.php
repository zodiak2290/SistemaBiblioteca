<!DOCTYPE html>
<html lang="es">
  <head>
    <title> Biblioteca</title>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap/newage/new-age.min.css">
  </head>
  <body ng-app="biblioteca" id="page-top">
    <?php 
      $login = false;
      if(isset($_SESSION['logged_in'])) {
        $login =  true;
      } ?>
    <div ng-controller="inicioCtrl " ng-cloak>
      <nav-bar>
          <!-- Brand and toggle get grouped for better mobile display -->
        <navbar-header titlenav="'Menu'" subtitlenav="'Biblioteca'"> </navbar-header>
          <!-- Collect the nav links, forms, and other content for toggling -->
        <navbar-collapse id="bs-example-navbar-collapse-1">
          <linav-bar liref="'biblioteca'" liname="'Conocenos'"></linav-bar>
          <linav-bar liref="'actividades'" liname="'Actividades'"></linav-bar>
          <linav-bar liref="'servicios'" liname="'Servicios'"></linav-bar>
          <linav-bar liref="'consulta'" liname="'Consulta'"></linav-bar>  
          <linav-bar liref="'contacto'" liname="'Contacto'"></linav-bar>
          
          <?php if(!$login){ ?>
              <linav-bar liref="'login'" liname="'Iniciar Sesión'"></linav-bar> 
          <?php }else{ ?>
              <li>
                <a href="<?php echo base_url(); ?>home"> Inicio</a>
              </li>
              <li>
                <a href="<?php echo base_url(); ?>home/logout"> Cerrar sesión</a>
              </li>
          <?php }  ?>          
        </navbar-collapse> 
      </nav-bar>
      <header-age data-img="images/inicio/demo.jpg">
        <div class="header-content-inner">
            <h1>Biblioteca</h1>
        </div>        
      </header-age>
      <section-age namemenu="'biblioteca'" clases="'download bg-primary text-center'"> 
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <p align="justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe nostrum ullam eveniet pariatur voluptates odit, fuga atque ea nobis sit soluta odio, adipisci quas excepturi maxime quae totam ducimus consectetur?</p>
                    <p align="justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius praesentium recusandae illo eaque architecto error, repellendus iusto reprehenderit, doloribus, minus sunt. Numquam at quae voluptatum in officia voluptas voluptatibus, minus!</p>
                    <p align="justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum molestiae debitis nobis, quod sapiente qui voluptatum, placeat magni repudiandae accusantium fugit quas labore non rerum possimus, corrupti enim modi! Et.</p>
                </div>
            </div>
        </div>
      </section-age>
      <section-age namemenu="'actividades'" clases="'features'"> 
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Actividades</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <item-portfolio iditem="'portfolioModal1'" data-imagen="http://lorempixel.com/250/200/sports/1"> </item-portfolio>
                <item-portfolio iditem="'portfolioModal1'" data-imagen="http://lorempixel.com/250/200/sports/2"> </item-portfolio>
                <item-portfolio iditem="'portfolioModal1'" data-imagen="http://lorempixel.com/250/200/sports/3"> </item-portfolio>
                <item-portfolio iditem="'portfolioModal1'" data-imagen="http://lorempixel.com/250/200/sports/4"> </item-portfolio>
                <item-portfolio iditem="'portfolioModal1'" data-imagen="http://lorempixel.com/250/200/sports/5"> </item-portfolio>
                <item-portfolio iditem="'portfolioModal1'" data-imagen="http://lorempixel.com/250/200/sports/6"> </item-portfolio>
            </div>
        </div>    
      </section-age>
      <section-age namemenu="'servicios'" clases="'download bg-primary text-center'">
            <div class="row">
                Algunos servicios por aqui
            </div> 
      </section-age>
      <section-age namemenu="'consulta'" clases="'features'">
            <div class="row">
              <div class="col-lg-8">
                <input type="text" placeholder="Que estas buscando?" class="form-control">
              </div>
              <div class="col-lg-4 text-center">
                <button class="btn btn-primary btn-lg btn-block">Buscar</button>
              </div>
            </div> 
      </section-age>
      <section-age namemenu="'contacto'" clases="'download bg-primary text-center'">
            <div class="row">
                <div class="col-lg-6">
                  <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d15256.452015769972!2d-96.71194105000001!3d17.0671243!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2smx!4v1484679516434" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
                <div class="col-lg-6">
                  <h2>Direccion</h2>
                  <address>
                    <strong>Animas trujano</strong>
                    carretera pt angel <br>
                    S/N <br>
                    <abbr title="Phone">Cel:</abbr> 9511942111
                  </address>
                </div>
            </div> 
      </section-age>
      <section-age namemenu="'login'" clases="'text-center'">
        <panel-login mensaje="mensaje" alert="alert" mensaje2="mensaje2" mensaje3="mensaje3">
          <form id="form1" name="form1" method="post" novalidate="">
            <div class="col-lg-12 text-center"  style="margin-bottom: 4px;">
                <input-group  data-igicon="user" 
                              data-igtype="text" 
                              data-igid="cuenta"
                              data-igname="cuenta"
                              data-igplaceholder="Email"
                              data-igtitle="Correo electónico"
                              data-igrequired="true" 
                              igmodel="user.email">
                </input-group>
                <input-group  data-igicon="lock" 
                              data-igtype="password" 
                              data-igid="password"
                              data-igname="password"
                              data-igplaceholder="Contraseña"
                              data-igtitle="Contraseña"
                              data-igrequired="true"
                              igmodel="user.pass"
                              > 
                </input-group>
              <div class="form-group">
                <button id="button" name="button"  type="submit" class="btn btn-info btn-xl form-control" ng-click="login(user)">Ingresar</button>
              </div>
            </div>
          </form>
          <div class="col-lg-12 text-center">
              <a href="" ng-click="cambiar(2)" class="forgot">Olvido su contraseña ?</a>&nbsp;|&nbsp;<a href="" ng-click="cambiar(1)" > Registrarse </a>
          </div>
        </panel-login>      
      </section-age>  
    </div>

    <script src="js/appAng/jquery.min.js"></script>
    <script src="js/appAng/bootstrap/bootstrap.min.js"></script>  
    <script src="js/appAng/angular.min.js"></script>
      
    <script src="js/appAng/app.js"></script>
    
    <!-- Fatorias -->
    <script src="js/appAng/factories/http.js"></script>
    
    <!-- Controlador -->
    <script src="js/appAng/controllers/loginControl.js"></script>
    
    <!--Directivas -->
    <script src="js/appAng/directivas/index/navbar/navbar.js"></script>
    <script src="js/appAng/directivas/index/navbarHeader/navbarHeader.js"></script>
    <script src="js/appAng/directivas/index/navbarCollapse/navbarCollapse.js"></script>
    <script src="js/appAng/directivas/index/linavBar/linavBar.js"></script>
    <script src="js/appAng/directivas/index/headerAge/headerAge.js"></script>
    <script src="js/appAng/directivas/index/sectionAge/sectionAge.js"></script>
    <script src="js/appAng/directivas/index/itemPortfolio/itemPortfolio.js"></script>
    <script src="js/appAng/directivas/index/panelLogin/panelLogin.js"></script>
    <script src="js/appAng/directivas/index/inputGroup/inputGroup.js"></script>
  </body>
</html>