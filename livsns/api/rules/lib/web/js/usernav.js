angular.module('yun')
	.directive('userhead', [ "$timeout", function( $timeout ){
		return {
			restrict : 'AE',
			scope : {
				options : '=',
			},
			template : 
				'<div class="navbar-user" ng-cloak>' +
					'<div class="userinfo" title="{{options.user.user_name}}">' + 
						'<span class="img-box">' + 
							'<img ng-if="options.user.img" ng-src="{{options.user.img}}" />' +
						'</span>' +
						'<span class="name overhidden">{{options.user.user_name}}</span>' +
						'<span class="icon icon-down transition">更多</span>' +
					'</div>' + 
					'<div class="usermenu">' + 
						'<ul>' + 
							'<li ng-repeat="item in options.setOpts"><a href="{{item.url}}">{{item.title}}</a></li>' +
						'</ul>' +
					'</div>' +
				'</div>',
			replace : true,
			link : function( scope, ele, attrs ){
				var opt = scope.options;
				if( opt.setOpts && $.isArray( opt.setOpts ) ){
					$.each(opt.setOpts, function( kk, vv ){
						vv.url = opt[vv.key + 'Url'];
					});
				}
				ele.on({
					mouseenter : function(){
						var timer = ele.data('timer');
						if( timer ){
							$timeout.cancel( timer );
						}
						ele.data('timer', $timeout(function(){
							ele.addClass( attrs.myHover );
						}, 150))
					},
					mouseleave : function(){
						var timer = ele.data('timer');
						if( timer ){
							$timeout.cancel( timer );
						}
						ele.data('timer', null)
						ele.removeClass( attrs.myHover );
					}
				});
			}
		}
	}]);
	
