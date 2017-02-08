
app.controller('inicioCtrl', inicioCtrl);

app.$inject = ["$scope","miHttp"] 

function inicioCtrl($scope, miHttp){

	$scope.login = function(user){
		var data = {
			accion: 'login',
			user: user 
		};

		miHttp.setData('/loginandroid', 'POST', data)
		miHttp.getDatos()
	        .then(function (result){
	        	console.log(result);
	        	procesaResultado(result);

	        }).catch(function (message) {
	          
	        }).finally(function(){
	        	
	        });
	}

	function procesaResultado(result){
		if(result.alert == "success"){
			window.location = "/home";
		}else{
	    	$scope.mensaje = result.mensaje;
	   		$scope.alert = result.alert == "error" ? "danger" : result.alert;
	   	}
	}
}