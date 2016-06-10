</br>
<nav class="navbar navbar-default" role="navigation">
      <!-- El logotipo y el icono que despliega el menú se agrupan
           para mostrarlos mejor en los dispositivos móviles -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse"
                data-target=".navbar-ex1-collapse">
          <span class="sr-only">Desplegar navegación</span>
          <span class="icon-menu2"></span>
        </button>
       
      </div>
     
      <!-- Agrupar los enlaces de navegación, los formularios y cualquier
           otro elemento que se pueda ocultar al minimizar la barra -->
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <li id="agregar"><a href="<?php echo base_url(); ?>home/usuario">Agregar nuevo usuario</a></li>
          <li id="listar" ><a href="<?php echo base_url(); ?>home/usuarios">Listar usuarios</a></li>
          <li id="escuelas" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Escuelas <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo base_url(); ?>home/escuelas">Listar</a></li>
              <li><a href="#myModal"data-toggle="modal">Nueva</a></li>
            </ul>
          </li>
        </ul>
        <div class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input id="cuenta" type="number" min="0" class="form-control" placeholder="Ingrese cuenta de usuario">
          </div>
          <button id="buscar" class="btn btn-info">Buscar</button>
        </div>
      </div>
    </nav>
    <script type="text/javascript">
    $('#buscar').click(function(e){
     cuenta=$('#cuenta').val();
        if(cuenta.length>0){
          window.location.replace('<?=base_url()?>home/usuario/'+cuenta);
        }else{
          console.log("error");
        }
      
      });
    </script>
