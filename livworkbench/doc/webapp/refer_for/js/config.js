define('config', function( require, exports, modules ){
	var base = './gjj.php';
	var vertifyUrl = 'http://www.12329app.com/YBMAP/appapi40101.html?centerId=00076000&deviceType=1&currenVersion=1.0.4&channel=&buzType=5431';
	function getUrl( tpl ){			//url*连接
		return base + '?f=' + tpl;
	}
	
	var refer = []
	
	exports.getUrl = getUrl;
	exports.vertifyUrl = vertifyUrl;
});
