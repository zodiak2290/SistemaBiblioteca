
angularRoutingApp.controller("ejemplarcontroller",function ($scope,miService,confighttpFactori,$mdSidenav){
   $scope.titulo="";$scope.autor="";$scope.isbn="";$scope.limitar=10;$scope.nadq=""; 
   $scope.close = function () {
      $mdSidenav('right').close()
        .then(function () {
          console.log("close RIGHT is done");
        });
    };
   $scope.enviar=function(url,elemento){
      document.getElementById(elemento).innerHTML='';
      confighttpFactori.setData(url,'POST',{nadqui:$scope.list_data.nadqui});      
      miService.getAll()
      .then(function (result) {
        recibirdatos(result.datos,elemento);
      }).catch(function (message) {
        alertify.error("No fue posible conectarse al servidor");
      });
  }
  recibirdatos =function(result,elemento){
    datos=[];
    for(attr in result){
      datos.push({y:attr,a:Number(result[attr]['I']),b:Number(result[attr]['E'])});
    }
    graficar(elemento,datos,'y',['a', 'b'],['Internos', 'Externos'],'auto',true,2);    
  }
  graficar=function(elemento,data,xkey,ykeys,labels,hidehover,ajuste,sizepoitn,tipografi){   
    config={element:elemento,data:data,xkey:xkey,ykeys:ykeys,labels:labels,pointSize:sizepoitn,hideHover:hidehover,resize:ajuste
        ,lineColors: ["blue", "green"]};  
            Morris.Line(config);
  }
  function llamadaalservidor(url,datos,metodo,idelem){
    confighttpFactori.setData(url,metodo,datos);
      miService.getAll()
      .then(function (result) {
        console.log(result);
            alertify.success(result.mensaje);
            if(result.datos){
              $scope.list_data= result['datos'];
            }
        }).catch(function (message) {
          alertify.error("No fue posible conectarse al servidor");
      });
  }
  $scope.mostrarche=false;         
 $scope.bloqdes=function(mostrar){
  if(mostrar){
       llamadaalservidor('<?php echo base_url()?>home/ejemplar/bloquear',
                          {nad:$scope.nadq},
                          'POST'
        );
    }
 }
  $scope.cargalibros=function(){
   document.getElementById('morris-donut-chart').innerHTML='';
    $("#bc").hide();
    $("#divbus").attr("class","windows8");
    $('#btnb').text('...');     
    llamadaalservidor('<?php echo base_url()?>home/libros/json',
          {nad:$scope.nadq},
          'POST'
    );
    $("#divbus").removeAttr("class","windows8");
    $('#btnb').text('Buscar');   
  } 
  $scope.estado=function(status){
      if(status){
         llamadaalservidor('<?php echo base_url()?>home/ejemplar/estado',
            {nad:$scope.nadq},
            'POST'
          );
      }
  }  
  $scope.criterio="";
            $scope.observaciones="";
  $scope.eliminar=function(){
          alertify.confirm("<strong style='color:red'>Advertencia:</strong>Esta seguro desea eliminar este ejemplar?.", function (e) {
          if (e) {
            
              $scope.toggleRight();
                      $scope.isOpenRight();
          }
      });
  }
  $scope.baja=function(t){
      baja=t;
      llamadaalservidor('<?php echo base_url()?>home/libros/baja',
          {nad:$scope.list_data.nadqui,baja:baja,criterio:$scope.criterio,obs:$scope.observaciones},
          'POST'
      );
      $scope.close();
      $scope.cargalibros();
  }
  $scope.addcb =function(){
    alertify.log("Espere un momento...")
    llamadaalservidor('<?php echo base_url()?>home/ejemplar/barcode',
        {nad:$scope.list_data.nadqui},
        'POST'
      );
      $('#bc').show(100);
  }
  $scope.editar=function(libro){
    alertify.confirm("<p>¿Desea editar este libro?<br>"+libro.titulo+"<br>", function (e) {
        if (e) {
            window.localStorage.setItem("idficha",libro.idficha); 
            window.location = "<?php echo base_url(); ?>home/libros";
        }
      }); 
      return false               
    }
    $scope.toggleRight = buildToggler('right');
    $scope.isOpenRight = function(){
            return $mdSidenav('right').isOpen();
    };
          function debounce(func, wait, context) {
              var timer;
              return function debounced() {
                var context = $scope,
                    args = Array.prototype.slice.call(arguments);
                $timeout.cancel(timer);
                timer = $timeout(function() {
                  timer = undefined;
                  func.apply(context, args);
                }, wait || 10);
              };
            }
              function buildDelayedToggler(navID) {
                return debounce(function() {
                  $mdSidenav(navID)
                    .toggle()
                    .then(function () {
                      console.log("toggle " + navID + " is done");
                    });
                }, 200);
              }
              function buildToggler(navID) {
                return function() {
                  $mdSidenav(navID)
                    .toggle()
                    .then(function () {
                      console.log("toggle " + navID + " is done");
                    });
                }
              }
});    