app.directive('headerAge', [function () {
	return {

		restrict: 'E',
		link: function (scope, iElement, iAttrs) {
			scope.imagen=iAttrs.img;
		},
		transclude: true,	
		templateUrl : '/js/appAng/directivas/index/headerAge/headerAge.html',
    	replace: true,
	};
}]);
