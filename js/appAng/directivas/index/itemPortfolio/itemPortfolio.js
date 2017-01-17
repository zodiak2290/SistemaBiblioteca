app.directive('itemPortfolio', [function () {       
	return {
		restrict: 'E',	
		templateUrl : '/js/appAng/directivas/index/itemPortfolio/itemPortfolio.html',
    	replace: true,
    	scope: {
    		iditem: '=iditem',
    	},
    	link: function(scope,iElement, iAttr){
    		console.log(iAttr.imagen);
    		scope.imagenItem=iAttr.imagen;
    	}
	};
}]);