         /*Servicio que hace llamado al servidor 
            @author alberto
            realiza peticion al servidor recibiendo como paramtro la url a consultar
            los datos a enviar(si existen) y el metodo get Post
            retorna los datos de respuesta
            */
function servicio_comunicacion_al_servidor(aplicacion){            
	aplicacion.service('Servidor',function($http){
	    this.query=function(url,datos,metodo){
	        return $http({method:metodo,url:url,data:datos});
	    }
	}); 
}
var angularRoutingApp = angular.module('appBiblio',['ngMaterial','ngMessages']).filter('capitalize', function() {
    return function(input, all){
    return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
});
angularRoutingApp.factory("confighttpFactori", function(){
        var datos={url:"",method:"",data:""};
        var interfaz = {
            setData:function(urlServidor,metodo,params){
                datos.url=urlServidor;
                datos.method=metodo;
                datos.data=params;
            },
            getDatos: function(){
              return datos;
            }
        }
        return interfaz;
});
angularRoutingApp.service('miService', function($http,$q,confighttpFactori){
        var resultado=false;
        function getAll(){
            var defered = $q.defer();
            var promise = defered.promise; 
            var datos=confighttpFactori.getDatos();
              $http({
                       url: datos.url,
                       method: datos.method,
                       data:datos.data  
                  }).success(function(result){
                      defered.resolve(result);
                }).error(function(result){
                     defered.reject(result)
                  });
                return promise;
        }
        return {
          getAll: getAll
        }
});