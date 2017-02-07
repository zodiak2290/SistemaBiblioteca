app.factory("miHttp", function($http,$q){
        var datos={url:"",method:"",data:""};
        var interfaz = {
            setData:function(urlServidor,metodo,params){
                datos.url=urlServidor;
                datos.method=metodo;
                datos.data=params;
                //console.log(urlServidor);
            },
            getDatos: function(){
              var defered = $q.defer();
              var promise = defered.promise; 
              var config = datos
                $http(
                  config
                  ).success(function(result){
                        defered.resolve(result);
                  }).error(function(result){
                       defered.reject(result);
                    });
                  return promise;
            }
        }
        return interfaz;
});