app.directive('panelLogin', [function () {
	return {
		restrict: 'E',
		transclude: true,	
		templateUrl : 'js/appAng/directivas/index/panelLogin/panelLogin.html',
    	replace: true,
        scope:{
            mensaje:'=mensaje',
            alert:'=alert'
        },
        compile: function(tElement, tAttrs, transclude){
        	return {
        		pre : function (scope, iElement, iAttrs) {
        			var caja=$("#caja", iElement);
        			caja.css(
        					{
                            "background":"rgba(225, 255, 255, 0.1)",
        					"width": "50%; ",
        					"margin": "auto",
        					"position": "relative",
        					"top": "10px",
        					"left": "0",
        					"bottom": "0", 
        					"right": "0",
        					"height": "300px",
        					"min-width": "200px",
        					"max-width": "400px" ,
        					}	
        				);

        		}
        	}
        }
	};
}]);
