app.directive('inputGroup', [function () {
	return {
		restrict: 'E',
		controller:function($scope ){
			$scope.$watch('cambio',function(){
				$scope.model=$scope.cambio;
			})
		},
		transclude: false,	
		templateUrl : 'js/appAng/directivas/index/inputGroup/inputGroup.html',
    	replace: true,
    	scope:{model:"=igmodel"},
    	link: function(scope, iElement, iAttrs){
	    			scope.igicon=iAttrs.igicon;
	    			scope.igtype=iAttrs.igtype;
	    			scope.igid=iAttrs.igid;
	    			scope.igname=iAttrs.igname;
	    			scope.igplaceholder=iAttrs.igplaceholder;
	    			scope.igtitle=iAttrs.igtitle;
	    			scope.igrequired=iAttrs.igrequired;
	    }
	};
}]);
