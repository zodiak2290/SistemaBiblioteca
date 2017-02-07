app.directive('navbarCollapse', [function () {
	return {    
		restrict: 'E',
		transclude: true,
		templateUrl : '/js/appAng/directivas/index/navbarCollapse/navCollapse.html',
    	replace: true,
	};
}]); 
