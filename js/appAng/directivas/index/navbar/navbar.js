app.directive('navBar', [function () {
	return {
		restrict: 'E',
		transclude: true,
		templateUrl : '/js/appAng/directivas/index/navbar/template.html',
    	replace: true,
	};
}]);