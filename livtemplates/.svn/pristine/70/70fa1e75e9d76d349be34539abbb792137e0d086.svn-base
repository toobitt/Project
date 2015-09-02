<div id="allmap" style="width:{$hg_data['width']}px;height:{$hg_data['height']}px;overflow:hidden;margin:0;"></div>
<div class="form_ul_div clear">
<span style="margin-left:70px;">经度:&nbsp;<input type="text" name="{$hg_name}" id="{$hg_name}" value="{$hg_data['longitude']}" size="35" /></span>
<span>纬度:&nbsp;<input type="text" name="$hg_value" id="$hg_value" value="{$hg_data['latitude']}" size="35" /></span>
</div>

<script type="text/javascript">
window.initializeMap = function () { 
	var map = new BMap.Map("allmap");
	var city_name = "{$hg_data['areaname']}";
	var zoomsize  = {$hg_data['zoomsize']};
	var lat		  = {$hg_data['latitude']};
	var lng		  = {$hg_data['longitude']};
	var drag 	  = {$hg_data['is_drag']};
	var flag = 0;
	var point;
	if(lat && lng)
	{
		point = new BMap.Point(lng,lat);
		map.centerAndZoom(point, zoomsize);
		flag = 1;
	}
	else
	{
		if(city_name)
		{
			map.centerAndZoom(city_name,zoomsize);
		}
		else
		{
			var myCity = new BMap.LocalCity();
			myCity.get(get_current_city);
		}
	}
	map.addControl(new BMap.NavigationControl()); 
	map.addControl(new BMap.ScaleControl());                   
	map.addControl(new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP]}));
	
	//地图加载好绑定事件
	var i = 1;
	map.addEventListener("tilesloaded",function(){
		if(i==1)
		{
			if(!flag)
			{
				point =  new BMap.Point(map.getCenter().lng,map.getCenter().lat);
			}
			var marker = new BMap.Marker(point);
			map.addOverlay(marker);
			if(drag)
			{
				marker.enableDragging();
			}
			marker.addEventListener("dragend",showInfo);
		}
		i++;
	});
	
	function showInfo(e)
	{
		var longitude = "{$hg_name}"; 
		var latitude = "$hg_value";
		$('#'+ longitude).val(e.point.lng);
		$('#'+ latitude).val(e.point.lat);
		var url = "run.php?mid="+gMid+"&a=get_address_by_xy&location="+e.point.lat+","+e.point.lng;
		hg_ajax_post(url);
	}

	function get_current_city(result)
	{
		map.centerAndZoom(result.name,zoomsize);
	}
};

function show_address_info(e)
{
	var obj = eval('('+e+')');
	$('#detailed_address').val(obj.address);
}

if (!window.BMap) 
{
	$('<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4&callback=initializeMap"><\/script>').appendTo('body');
} 
else 
{
	window.initializeMap();
}
</script>