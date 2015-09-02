<div id="allmap" style="width:{$hg_data['width']}px;height:{$hg_data['height']}px;overflow:hidden; display: inline-block;margin-left: 33px; "></div>
<!--<div class="m2o-item clear">
<label class="title"></label>
<span>经度:&nbsp;<input type="text" name="{$hg_name}" id="{$hg_name}" value="{$hg_data['longitude']}" size="35" /></span>
<span>纬度:&nbsp;<input type="text" name="$hg_value" id="$hg_value" value="{$hg_data['latitude']}" size="35" /></span>
</div>-->

<script type="text/javascript">
window.initializeMap = function () { 
	var map = new BMap.Map("allmap");
	map.centerAndZoom("{$hg_data['areaname']}",{$hg_data['zoomsize']});
	map.addControl(new BMap.NavigationControl()); 
	map.addControl(new BMap.ScaleControl());                   
	map.addControl(new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP]}));    

	var i = 1;
	map.addEventListener("tilesloaded",function(){
		if(i==1)
		{
			var lng = {$hg_data['longitude']};
			var lat = {$hg_data['latitude']};
			if(lng)
			{
				var point = new BMap.Point(lng,lat);
			}
			else
			{
				var point =  new BMap.Point(map.getCenter().lng,map.getCenter().lat);	
			}
			map.centerAndZoom(point, 13);
			var marker = new BMap.Marker(point);
			map.addOverlay(marker);
			var drag = {$hg_data['is_drag']};
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
};

function show_address_info(e)
{
	var obj = eval('('+e+')');
	console.log( obj );
	$('.specific-address').val(obj.address);
}
</script>