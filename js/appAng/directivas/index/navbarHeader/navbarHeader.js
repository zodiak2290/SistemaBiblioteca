app.directive('navbarHeader', [function () {
	return {      
		restrict: 'E',
		templateUrl : '/js/appAng/directivas/index/navbarHeader/navbarHeader.html',
    	replace: true,
    	scope:{
    		titlenav: '=titlenav',
    		subtitlenav: '=subtitlenav'
    	},
	};
}]); 
