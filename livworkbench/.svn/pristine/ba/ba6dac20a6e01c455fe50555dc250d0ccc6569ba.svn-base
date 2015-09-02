function initialize(){
	if($('#allmap').length){
		initNearest();
	}
	if($('#websitemap').length){
		initWebsite();
	}
}
function loadScript(){
	var script = document.createElement('script');
	script.src="http://api.map.baidu.com/api?v=2.0&ak=6Oh4fxbugIpI6LMtKk6cHYPf&callback=initialize";
	document.body.appendChild(script);
}

function initNearest(){
	var map = new BMap.Map('allmap');
	var myCity = new BMap.LocalCity();
	myCity.get(get_current_city);
	function get_current_city(result)
	{
		map.centerAndZoom(result.name,11);
	}
}

function initWebsite(){
	var map = new BMap.Map('websitemap');
	var myCity = new BMap.LocalCity();
	myCity.get(get_current_city);
	function get_current_city(result)
	{
		map.centerAndZoom(result.name,11);
	}
	var driving = new BMap.DrivingRoute(map, {renderOptions:{map:map, autoVirewport: true}});
	driving.search("景明佳园", "安德门");
}
window.onload = loadScript;


