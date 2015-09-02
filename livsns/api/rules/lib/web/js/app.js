angular.module('yun', []).run(['$rootScope', function($rootScope){
	$rootScope.nav = {
		user : {},				//用户信息
		setOpts : [{			//头部操作
			title : '退出账号',
			key : 'layout'
		}]
	};	
}]);
angular.element(document).ready(function() {
    angular.bootstrap(document, ['yun']);
});