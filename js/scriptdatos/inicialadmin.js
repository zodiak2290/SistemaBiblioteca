    angularRoutingApp.controller("graficasController",function ($scope,miService,confighttpFactori){
        $scope.enviar=function(url,datos){
            $("#grafica").addClass('fondooscuro');
            confighttpFactori.setData(url,'POST',datos); 
            miService.getAll().then(function (result) { 
                console.log(result);
                set_Config(result);
            }).catch(function (message) {
                alertify.error("No fue posible conectarse al servidor");
            }).finally(function(){
                $("#grafica").removeClass('fondooscuro');
            });
        }
        $scope.init=function(){
            $scope.enviar('home/biblioteca/prestamos');
        }

        function set_Config(datos){
            if(window.myBar){
                window.myBar.destroy();
            }
            var ctx = document.getElementById("grafica").getContext("2d");
            window.myBar = new Chart(ctx).Bar(datos, {
                responsive : true, 
                onAnimationComplete: function(){
                    $ ("#etiquetas").empty();
                    $.each(datos.datasets, function( index, value ){

                        $("#etiquetas").append("<li style=color:"+value['highlightStroke']+">"+value['label']+"</li>");
                    });
                    
                }
            });
            window.myBar.generateLegend();
        }
        $scope.selectcambio=function(){
            var fecha = new Date();
            var ano = fecha.getFullYear();
            $scope.aniosfin=Array.range($scope.anioselect,ano);
        }
        $scope.updatecanvas=function(){
            window.myBar.destroy();

            $scope.anioinicio=$scope.anioselect?$scope.anioselect:2012;
            datos={rango:{anioinicio:$scope.anioselect,aniofin:$scope.aniofin}};
            setTitulo();
            $scope.enviar('home/biblioteca/prestamos',datos);
        }
        $("#grafica").click(function(e) {
            $("#etiquetas").empty();
            window.myBar.destroy();
            if(!$scope.anioselecionado){
                var activeBars = window.myBar.getBarsAtEvent(e);
                //console.log(activeBars[0]);
                    if($("#graficasAnio").is(':visible')){
                        $("#graficasAnio").animate({height:"0"},{
                                duration: 500,
                                complete: function(){
                                        anioelegido=activeBars[0]['label'];
                                        $scope.anioselecionado=true;
                                        $scope.titulo="Préstamos realizados en "+anioelegido;
                                        $scope.enviar('home/biblioteca/prestamos',{anio:anioelegido});                   
                                }
                              });
                    }
            }else{
                $scope.init();
                $scope.anioselecionado=false;
            }
        });
        Array.range= function(a, b){
            arr=[];
            a=parseInt(a)+1;
            for (var i = a; i < b+1; i++) {
                arr.push(i);
            };
            return arr;
        }
        $scope.anioinicio=2012;
        var fecha = new Date();
        $scope.aniofin= fecha.getFullYear();
        $scope.anioselecionado=false;
        $scope.init();

        function setTitulo(){ 
            $scope.titulo="Préstamos realizados de "+$scope.anioinicio+" a "+$scope.aniofin;
        }
        setTitulo();
    });
