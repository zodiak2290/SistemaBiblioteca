
<div id="page-wrapper" hidden>
<?php $correcto = $this->session->flashdata('correcto');
                    if ($correcto) 
                    {
                    ?>
                    <div class="alert alert-success alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                       <span id="registroCorrecto" ><?= $correcto ?></span>
                    </div>
                    <?php
                    }
                ?>
<div >
  <div class="page-header">
    <div class="pull-left form-inline col-md-8" ng-app="calendar" ng-controller="calcontroller">
      <div class="btn-group" >
        <button ng-click="anio(-1)" class="btn btn-primary" data-calendar-nav="prev"><< Anterior</button>
        <button ng-click="anio(0)" class="btn btn-default" data-calendar-nav="today">Hoy</button>
        <button ng-click="anio(+1)" class="btn btn-primary" data-calendar-nav="next">Siguiente >></button>
      </div> 
      <div class="btn-group">
        <button id="banio" class="bperiodo btn btn-warning" data-calendar-view="year">Año</button>
        <button id="bmes" class="bperiodo btn btn-warning active" data-calendar-view="month">Mes</button>
        <button id="bsemana"  class="hidden bperiodo btn btn-warning" data-calendar-view="week">Semana</button>
        <button id="bdia"  class="bperiodo btn btn-warning" data-calendar-view="day">Dia</button>
      </div>
    </div>
	    <h3 id="texxto"></h3>
  </div>
  <div class="row">
    <div class="col-md-8">
      <div id="calendar"></div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <select id="first_day" class="form-control">
          <option value="2" selected="selected">Primer día de la semana: domingo</option>
          <option value="1" >Primer día de la semana: lunes</option>
        </select>
        <!--
        <label class="checkbox">
          <input type="checkbox" value="#events-modal" id="events-in-modal"> Abrir ventana  
        </label>
        <label class="checkbox">
          <input type="checkbox" id="format-12-hours"> Formato de 12 horas
        </label>
        <label class="checkbox">
          <input type="checkbox" id="show_wb" checked> Ver semana
        </label>
        --> </br>
        <div class="navev" >
        <?php if($rol==4){ ?>
            <a href="<?php echo base_url(); ?>home/evento"><button class='btn btn-info' >Registrar una nueva actividad</button></a>
        <?php }?>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div id="disqus_thread"></div>
  <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

  <div class="modal fade" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3 class="modal-title">Registro</h3>
        </div>
        <div class="modal-body" style="height: 400px;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
 
  

<style type="text/css">
    a{
      text-decoration: none;
    }
    #calendar{
      background: rgb(176,170,108);
    }
</style>
<script type="text/javascript">
 var app=angular.module("calendar",[]);    
        app.controller("calcontroller",function ($scope,$http){
          $scope.ano="";
          $scope.eventos=[];
          $(".bperiodo").click(function(e){
              if(e.target.id=='banio'){
              }else{
                $scope.ano=$scope.ano;
              }
          });
          $scope.anio=function(i){
            $scope.ano=Number(($('#texxto').text().split(" ")[1]))+i;
            $scope.url='<?php echo base_url()?>home/eventos/json/'+$scope.ano;
            $scope.get_eventos();
          }
          $scope.eventos=[];
        $scope.get_eventos=function(){
          console.log($scope.ano);
          if($scope.ano){
                $http({method:'GET',url:$scope.url
                  }).success(function(result){ 
                          if(result.success==1){
                              eventos=result.result;
                              cargaropciones(eventos);
                              console.log(eventos);
                            }else{
                              eventos=[];
                            }
                        }).error(function(result){
                                    
                      });
               }         
          }
      cargaropciones=function(eventos){
          $scope.opciones = {
              events_source:eventos,
              view: 'year',
              width: '75%',
              language: 'es-MX',
              tmpl_path: '<?php echo base_url()?>components/tmpls/',
              day: ""+$scope.ano+"-01-01",
              time_start: '08:00',
              time_end: '22:00',                 
                  onAfterViewLoad: function(view) {
                    $('.page-header h3').text(this.getTitle());
                    $('.btn-group button').removeClass('active');
                    $('button[data-calendar-view="' + view + '"]').addClass('active');
                  },
                  classes: {
                    months: {
                      general: 'label'
                    }
                  }
                };
        cargardatos();
          }
  cargardatos=function(){
           (function($) {  
            "use strict";
  var calendar = $('#calendar').calendar($scope.opciones);
  $('.btn-group button[data-calendar-nav]').each(function() {
    var $this = $(this);
    $this.click(function() {
      calendar.navigate($this.data('calendar-nav'));
    });
  });

  $('.btn-group button[data-calendar-view]').each(function() {
    var $this = $(this);
    $this.click(function() {
      calendar.view($this.data('calendar-view'));
    });
  });

  $('#first_day').change(function(){
    var value = $(this).val();
    value = value.length ? parseInt(value) : null;
    calendar.setOptions({first_day: value});
    calendar.view();
  });
  $('#events-in-modal').change(function(){
    var val = $(this).is(':checked') ? $(this).val() : null;
    calendar.setOptions({modal: val,
          modal_type:'iframe'});
  });
  $('#format-12-hours').change(function(){
    var val = $(this).is(':checked') ? true : false;
    calendar.setOptions({format12: val});
    calendar.view();
  });
  
  $('#show_wb').change(function(){
    var val = $(this).is(':checked') ? true : false;
    calendar.setOptions({weekbox: val});
    calendar.view();
  });
  $('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
    //e.preventDefault();
    //e.stopPropagation();
  });
}(jQuery));
}
 
$scope.init=function(){
  $("#page-wrapper").fadeIn(900);
  var fecha = new Date();
  $scope.ano = fecha.getFullYear();
  $scope.url='<?php echo base_url()?>home/eventos/json/'+$scope.ano;
}
            $scope.init();
             $scope.get_eventos();
            
        });
</script>
<style type="text/css">
 a[data-event-class='parpadea']{
  font-size:15px;
  font-family:helvetica;
  font-weight:bold;

}
 
a[data-event-class='parpadea']{
  animation-name: parpadeo;
  animation-duration: 1s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;

  -webkit-animation-name:parpadeo;
  -webkit-animation-duration: 1s;
  -webkit-animation-timing-function: linear;
  -webkit-animation-iteration-count: infinite;
}

@-moz-keyframes parpadeo{  
  0% { opacity: 2.0; color: red;}
  50% { opacity: 0.0; color: yellow;}
   100% { opacity: 2.0; color: red;}
}

@-webkit-keyframes parpadeo {  
  0% { opacity: 2.0; color: red;}
  50% { opacity: 0.0; color: yellow;}
   100% { opacity: 2.0; color: red;}
}

@keyframes parpadeo {  
  0% { opacity: 2.0; color: red;}
  50% { opacity: 0.0; color: yellow;}
   100% { opacity: 2.0; color: red;}
}

.cal-year-box [data-event-class='parpadea']{
  background: red;
}
</style>
<script type="text/javascript">
  var variable=$("span[data-cal-date]");
  console.log(variable);
</script>
