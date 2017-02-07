app.directive('sectionAge', [function () {
	return {
		transclude: true,      
		restrict: 'E',
		templateUrl : '/js/appAng/directivas/index/sectionAge/sectionAge.html',
    	replace: true,
    	scope:{
    		namemenu: '=namemenu',
    		clases: '=clases'
    	}
	};
}]); 
