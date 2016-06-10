
var app=angular.module("setting",[]); 
        //llamamos al metodo que pasa como parametro la aplicacion
        // este metodo nos permitira hacer peticiones al servidor usando el servicio 
        //llamado Servidor
servicio_comunicacion_al_servidor(app);
 app.controller("donacontroller",function ($scope,Servidor){
     $scope.enviar=function(url,elemento,interex){
          Servidor.query(url,'GET').success(function(result){ 
                $scope.datos=[];
                $scope.total=0;
                $scope.interexter=interex;
                if(result.length>0){
                  result.forEach(function(elem,posicion){
                    $scope.datos.push({label:elem.clasificacion,value:elem.total});
                    $scope.total=$scope.total+Number(elem.total);
                  });
                  $scope.graficar('morris-donut-chart',$scope.datos);
                }
          }).error(function(result){
                     console.log(result);
              });
      }
      $scope.ver_prcent=function(){
        datos=[];
      $scope.datos.forEach(function(elem){
        console.log(elem);
         datos.push({label:elem.label,value:Math.floor((elem.value*100)/$scope.total)});
      });
      console.log(datos);
        $scope.graficar('morris-donut-chart',datos);
      }
      $scope.graficar=function(element,datos){
        document.getElementById(element).innerHTML='';
          Morris.Donut({
                      element: element,
                      data: datos,
                      resize: true
                  });
      }
      });
app.controller("setcontroller",function ($scope,Servidor){
    $scope.url=""; //url para editar datos inicializado en la vista
    $scope.urlpass=""; // url para editar pass inicializado en la vista
    $scope.urlpermisos=""; //url para editar permisos iniciaizado en la vista
    $scope.urlemail="";
    $scope.urlcuenta="";
    $scope.idusuario="";
    var emailcorrecto=false; //varibla de email correcto se inicializa false para deshabilidar boton
    var cuentacorrecta=false;//variable de cuenta correcta se inicializa false para deshabilitar boton
    var curpcorrecta=false;
    $scope.email="";//inicializamos scope email que ayudara a validar que la cuenta de correo sea valida y este disponible
    $scope.centa="";//inicializamos scope cuenta que ayudara a validar que la cuenta de usuario sea valida y este disponible
    $scope.guardarcambios=function(dato){
      url=$scope.urlpermisos;
        alertify.log("Espere un momento...");
        valo= $("#"+dato).is(':checked');
        $scope.datos={
            dato:dato,
            valor:valo
        };
      llamadaalservidor(url,$scope.datos,'POST','');  
    }
    $scope.funcioname=function(tecla,elem){
       if(tecla.keyCode==13){
          $scope.guardar(elem);
        }
    }    
    $scope.guardar=function(idelem){
      url=$scope.url;
      $("#carga").fadeIn(10);
      valo= $("#"+idelem+"t").val();
    	$scope.datos={
      	dato:idelem,
        valor:valo,
        idus:$scope.idusuario
      };
      llamadaalservidor(url,$scope.datos,'POST',idelem);                 
    }
    function llamadaalservidor(url,datos,metodo,idelem){
      Servidor.query(
          url,
          datos,
          metodo    
        ).success(function(result){
            if(result.correcto){
              $("#"+idelem+"span").text(datos.valor);
            }
            alertify.success(result.mensaje);
            setTimeout(recarga(idelem),5700000); 
        }).
        error(function(result){
            console.log(result);
        });
        
    }
    function recarga(idelem){
      $("#carga").fadeOut(500);
      $scope.mostrarocultar(idelem);
      //window.location.reload();
    }
    $scope.mostrarocultar=function(idelem){
      if($("#"+idelem).hasClass('hidden')){
         $("#"+idelem).removeClass( "hidden" );
      }else{
          $("#"+idelem).addClass( "hidden" );
      }
    }
    $scope.cambiarpass=function(){
        if(md5($scope.passc)==md5($scope.passn)){
             $("#carga").fadeIn(100);
             url=$scope.urlpass;
              $scope.datos=
              {
                nueva:$scope.passn,
                actual:$scope.passac,
                confi:$scope.passc
                }
                Servidor.query(
                        url,
                                $scope.datos,
                                'POST'
                                ).success(function(result){
                                  alertify.success(result.mensaje);  
                                }).error(function(result){
                                    console.log(result)
                                });
                             setTimeout(recarga('pass'),47000); 
        }else{
          alertify.error("Las contraseñas no coinciden");
        }
     }
     //recibe como parametro la variable que indicara si se requiere validar email y cuenta 
    $scope.validar=function(validar_cuenta_email){
        $("#buto").attr("disabled",true);
        if($scope.cuenta!=''){
            buscacuenta(validar_cuenta_email,$scope.urlcuenta);    
        }else{
            $scope.cuentavalida="";
        }
        if($scope.email!=''){
            buscaemail(validar_cuenta_email,$scope.urlemail,{email:$scope.email});
        }else{
            $scope.emailvalido="";    
        }
    }
    $scope.result=""
    buscaemail=function(validar_cuenta_email,url,datos){      
       Servidor.query(
                    url,
                    datos,
                    'POST').success(function(result){
                                if(result.existe!="Disponible"){
                                        emailcorrecto=false;
                                    }else{
                                        emailcorrecto=true;
                                    }
                                    $scope.emailvalido=result['existe'];
                                    verifi(validar_cuenta_email);
                                    
                    }).error(function(result){
                       
                    });

    }
        buscacuenta=function(validar_cuenta_email,url){
              Servidor.query(
               url,
               {cuenta:$scope.cuenta},
               'POST').success(function(result){
                if(result.existecuenta!="Disponible"){
                    cuentacorrecta=false;
                }else{
                    cuentacorrecta=true;
                }    
             $scope.cuentavalida=result['existecuenta']; 
              verifi(validar_cuenta_email);
              console.log(result);
            }).
              error(function(data, status, headers, config) {
            console.log(result);
          });
      }
      var valido=false;
      $scope.valido=false;
    verifi=function(validar_cuenta_email_curp){
      if(validar_cuenta_email_curp==3){
        valido=(emailcorrecto)&&(cuentacorrecta)&&(curpcorrecta);
      }else if(validar_cuenta_email_curp==2){
        valido=(emailcorrecto)&&(cuentacorrecta);
      }else if (validar_cuenta_email_curp==1){
        valido=emailcorrecto&&curpcorrecta;
      }else if(validar_cuenta_email_curp==0){
        valido=emailcorrecto;
      }
      $scope.valido=valido;
         if(!valido){
            $("#buto").attr("disabled",true);
          }else{
              $("#buto").removeAttr("disabled");
          } 
      
    };
     $scope.validaCURP=function(e) {
        if(e.keyCode==32){
            event.returnValue = false;
        }else if(e.keyCode==8){
            event.returnValue =true;
        } 
    }
$scope.validaCP=function(e) {
        if(e.keyCode==32){
            event.returnValue = false;
        }else if(e.keyCode==16){
      return false;
  }else if(e.keyCode==8){
            event.returnValue =true;
        }else{
     var keynum = window.event ? window.event.keyCode : e.which;
      event.returnValue =/\d/.test(String.fromCharCode(keynum));
  }   
    }
    $scope.validando=function (v){ 
        var paso = validateCURP(curp);
            if(!paso){
                $scope.mensajecurp="La CURP no es válida";
                 curpcorrecta=false;
            }else{
             $scope.mensajecurp="La CURP  es válida";
             curpcorrecta=true;
                //$('#fechan').val('1990-06-02');
            }
            verifi(v);
    }
    function validateCURP(curp) { 
      $scope.curp=$scope.curp.toUpperCase(); 
      var expreg = /^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/;
        return expreg.test($scope.curp);
    }
    $scope.guardarid=function(elem){
      if(elem=="idgrupo"){
           elem='idgrupo';
           valo=$('select#idgrupot').val();
      }else{          
          elem='idescuela';
          valo=$('select#idescuelat').val();           
        }
        datos={
            dato:elem,
            valor:valo,
            idus:$scope.idusuario
        }
        llamadaalservidor($scope.url,datos,'POST',elem);
    }


      $("#buto").attr("disabled",true);
     verifi(3);
});  