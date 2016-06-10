<nav class="navbar navbar-default" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse">
      <span class="sr-only">Desplegar navegación</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav nav-tabs">
      <li id="nuevo"><a href="<?php echo base_url(); ?>home/fichas">Nuevo libro</a></li>
      <li id="archivo " class="hidden"><a href="<?php echo base_url(); ?>home/cargar_prome">Actualizar archivo</a></li>
      <li id="etiqueta"> <a href="<?php echo base_url(); ?>home/etiquetas">Buscar datos </a>
      </li>      
      <li id="" class="dropdown">
        <a  target="_blank" href="http://189.206.56.132/F/Q9435FX7M5LTTA74MBRES2Q6JK4N3U7YXSPDLM6EKQ2YX7DPQU-00805?func=find-b&request=Introduccion+A+La+Electricidad+&find_code=TIT&adjacent=N&local_base=&x=7&y=7&filter_code_1=WLN&filter_request_1=&filter_code_2=WYR&filter_request_2=&filter_code_3=WYR&filter_request_3=&filter_code_4=WFM&filter_request_4=&filter_code_5=WSL&filter_request_5=">Catalógo DGB
         </a>
      </li>
    </ul>
  </div>
</nav>
