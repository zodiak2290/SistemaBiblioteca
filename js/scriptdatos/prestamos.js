angularRoutingApp.controller("prestamoscontroller",function($scope,miService,confighttpFactori){
    $scope.url="";
    $scope.urlpr="";
    $scope.cuenta="";
    $scope.nadqui="";
    $scope.resultados=[];
    var buscando=false;
    $scope.get_prestamos=function(){
      data={inicio:$scope.inicio,cantidad:$scope.limite}; 
      confighttpFactori.setData($scope.url,'POST',data);
      //console.log(data);
      llamadaalservidor(); 
    }
    function llamadaalservidor(){
      miService.getAll()
      .then(function (result) {
          $scope.resultados=result.resultado;
          //console.log(result.resultado);
      }).catch(function (message) {
        alertify.error("No fue posible conectarse al servidor");
      }).finally(function(){
          $("#cargando").hide();
          $("#tabla").show();
      });
    }
    $scope.espacio=function(e){
      if(e.keyCode==32){
          event.returnValue = false;
      }
    }
    $scope.buscar=function(evento,tipo){
      if(evento.keyCode==13){
         if(tipo=='n'&&$scope.nadqui>0){
            confighttpFactori.setData($scope.url,'POST',{nadqui:$scope.nadqui});
            llamadaalservidor();
         }else if(tipo=='c'&&$scope.cuenta>-1){
            confighttpFactori.setData($scope.url,'POST',{cuenta:$scope.cuenta});
            llamadaalservidor();
         }
      }else if(evento.keyCode==32){

      }else{
        if(!buscando){
          $('#press').fadeIn(50);
          $('#press').fadeOut(700);
          buscando=true;
        }else{
          buscando=false;
        }
      }
    }
    $scope.init=function(){
      $scope.inicio=0;$scope.limite=10;
      $scope.get_prestamos()
    }
    $scope.ordenar=function(t){
      $scope.campo=t;
      orden();
    }
    $scope.paginar=function(dir){
      console.log("yes");
      $("#cargando").show();
      $("#tabla").hide();
      if(dir=="izq"){
        $scope.inicio=Number($scope.inicio)-Number($scope.limite);
        $scope.get_prestamos();
      }else if(dir=="der"){
        $scope.inicio=Number($scope.inicio)+Number($scope.limite);
        $scope.get_prestamos();
      }
    }
    $scope.cargaprestamos=function(){
      $scope.inicio=0
      $scope.get_prestamos();
    }
    function orden(){
        $scope.orden=$scope.orden==false?true:false;
    }
    $scope.infoven=function(cuenta,nadqui){
      $scope.resultados.forEach(function(elem,posicion){
        if(elem['cuenta']==cuenta&&elem['nadqui']==nadqui){  
          $scope.datosvencimiento=elem;
        }
      });
    }
    $scope.prestar=function(indice,folio){
     alertify.log("Espere un momento..");
      $("#pres"+indice).attr("disabled",true);
      var idr=folio;
      datos={foli:idr};
      confighttpFactori.setData($scope.urlpr,'POST',datos);
      prestar_reserva(indice);  
    }
    function prestar_reserva(indice){
      miService.getAll()
      .then(function (result) {
          if(result.exito){
            alertify.success(result.mensaje);
          }else{
            $("#pres"+indice).attr("disabled",false);
            alertify.error(result.mensaje);
          }
      }).catch(function (message) {
        alertify.error("No fue posible conectarse al servidor");
      });
      $scope.get_prestamos();  
    }
    $scope.init();        
});