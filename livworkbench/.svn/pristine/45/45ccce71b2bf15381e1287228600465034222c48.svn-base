function loadScript(){
	var script = document.createElement('script');
	script.src="http://api.map.baidu.com/api?v=2.0&ak=6Oh4fxbugIpI6LMtKk6cHYPf&callback=initialize";
	document.body.appendChild(script);
}

function initialize(){
	var map = new BMap.Map("getloc-map");          
	var point = new BMap.Point(116.404, 39.915);
	map.centerAndZoom(point, 15);
}

window.onload = loadScript;