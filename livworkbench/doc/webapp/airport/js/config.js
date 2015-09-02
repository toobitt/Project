(function(){
	var utils = {};
	utils.nav = [{ key : 'start_off', name : '出发', type : '出港'}, 
				{key : 'get_to', name : '到达', type : '进港'}, 
				{key : 'start_flight', name : '出港航班', type : '出港航班' }, 
				{key : 'get_flight', name : '进港航班', type : '进港航班'}, 
				{key : 'air_bus', name : '机场大巴', type : '机场大巴'}];
	
	utils.baseUrl = './AirPort.php';
	utils.externalUrl = 'http://10.0.2.45/livsns/api/mobile/data/ceshi/';
	
	var config = {
		start_flight : 'airport_out_info.php',
		get_flight : 'airport_in_info.php',
		air_bus : 'airport_bus_info.php'
	};
	
	utils.getUrl = function( key ){
		return utils.externalUrl + config[key];
	}
	
	utils.spinner = {
		show : function( target, opts ){
			if( $.spinner ){
				return;
			}
			target = target || $('body');
			opts = $.extend({
				lines : 12,
        		length : 4,
        		width : 2,
        		speed : 1.4,
        		radius : 6,
        		color : '#999'
			}, opts);
			$.spinner = new Spinner( opts ).spin( target[0] );
		},
		close : function(){
			if( $.spinner ){
				$.spinner.stop();
				delete $.spinner;
			}
		}
	};
	window.config = utils;
})();
