$scope.listafechas=[];
$scope.agregardatos=function(){
    dia=$('#dia').val();
    var existe=false;
    $scope.listafechas.forEach(function(elem,posicion){
        if(Date.parse(elem.dia)==Date.parse(dia)){
             existe=true;
          }
    }); 
    if(existeFecha(dia)) {
        if(!existe){
          $scope.listafechas.push({dia:dia});
            alertify.success("Se agrego "+dia+" a la lista");
        }else{
          alertify.error("La fecha ya esta en la lista");
        }
      }else{
        alertify.error("Fecha no válida");
        $('#dia').val('');
      }
}
function existeFecha(fecha){
    var fechaf = fecha.split("-");
    var day = fechaf[0];
    var month = fechaf[1];
    var year = fechaf[2];
    var date = new Date(year,month,'0');
    if((day-0)>(date.getDate()-0)){
          return true;
    }else{
    return false;
  }
  $scope.eliminar=function(indice){
    $scope.listafechas.splice(indice,1);
  }
}
 $scope.subir=function(){
      alertify.log("Espere un momento...");
      $("#divfechas").fadeIn(100);
      $("#fechas").hide(100);
      $http({
        url:'<?php echo base_url()?>home/actividad/addgafechas',
        method:'POST',
        data:{id:$scope.idac,datos:$scope.listafechas}
      }).success(function(result){ 
          alertify.success(result.insert); 
          $scope.resultados= result;
          if(result.inserta==true){
              $scope.listafechas=[];
          }                             
        }).error(function(result){
            console.log(result);
          });
          $("#divfechas").hide(100);
          $("#fechas").show(100);                        
} 