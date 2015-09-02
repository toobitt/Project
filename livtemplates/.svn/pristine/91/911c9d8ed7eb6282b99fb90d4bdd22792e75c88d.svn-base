<div class="map-box map-pop">
	<div class="map-title"><div class="title_pos">地图坐标</div>
		<div class="map-lude">
			<label>经度: </label><input type="text" name="{$hg_name}" id="{$hg_name}" value="{$hg_data['latitude']}" size="35"/>
			<label>纬度: </label><input type="text" name="$hg_value" id="$hg_value" value="{$hg_data['longitude']}" size="35" />
		</div>
		<span class="map-save">保存</span><span class="pop-close-button map-close"></span>
	</div>
	<div id="allmap"></div>
</div>

<script type="text/javascript">
function getLng( lat, lng ) { 
	var map = new BMap.Map("allmap");
	var city_name = "{$hg_data['areaname']}";
	var zoomsize  = {$hg_data['zoomsize']};
	var lat		  = lat || {$hg_data['latitude']};
	var lng		  = lng || {$hg_data['longitude']};
	var drag 	  = {$hg_data['is_drag']};
	var flag = 0;
	var point;
	if(lat && lng)
	{
		point = new BMap.Point(lat, lng);
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
	$('<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4&callback=getLng"><\/script>').appendTo('body');
} 
else 
{
	getLng();
}
</script>