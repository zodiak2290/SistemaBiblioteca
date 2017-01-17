app.directive('linavBar', [function () {
  	var controller = ['$scope', function ($scope) {
		$('#mainNav').affix({
			offset: {
			    top: 90
			}
		});
		$scope.desplaza = function ( $event){
    		id="#"+($event.target.id).split('-')[0];
			$('html, body').animate({
			    scrollTop: $(id).offset().top
			}, 1000 );
		};
    }];        
	return {
		restrict: 'E',	
		templateUrl : '/js/appAng/directivas/index/linavBar/template.html',
    	replace: true,
    	scope: {
    		liref: '=liref',
			liname: '=liname',
    	},
    	controller: controller,
    	link:
    		{	
    			pre: function(scope, iElement , iAttrs , controller){
    				iElement.hide();
	    		},
	    		post: function(scope, iElement , iAttrs , controller){
	    			iElement.fadeIn(1000);
	    		}
			}
	};
}]);