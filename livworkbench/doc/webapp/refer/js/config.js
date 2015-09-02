define('config', function( require, exports, modules ){
	var base = './js/json/',
		baseOptions = {
			'cpflist' : 'cpflist.json',
			'cpfresult' : 'cpfresult.json',
		};
	function getUrl( tpl ){			//url*连接
		return base + baseOptions[ tpl ];
	}
	
	var refer = [
		{name : 'water', title : "水费"},
		{name : 'energy', title : "电费"},
		{name : 'gas', title : "燃气费"},
		{name : 'cpf', title : "公积金", src : "./cpf.html"},
		{name : 'phone', title : "固话宽带"},
		{name : 'traffic', title : "交通违章"}
	]
	
	exports.getUrl = getUrl;
	exports.refer = refer;
});
