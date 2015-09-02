  
var map;  
var g_lat,g_lng; 
function initialize()
{  
	if(map_op_type == 1)
	{
		var centerP = GROUP_CENTER_POINT;
		var group_name = GROUP_NAME;
	}else
	{
		var centerP = MAP_CENTER_POINT;
		var group_name = '浙江省杭州市';
	}
    var mm = centerP.split("X");
    if(mm[0] == 0.0 || mm[1] == 0.0)
    { 
    	queryAddress(group_name);
    }
    else
    {
    	var p = new BMap.Point(mm[1],mm[0]);
     	createMap(p,13);
    } 
}

//根据地址查询经纬度
 function  queryAddress(addr)
 {
	 if(addr){
			var local = new BMap.LocalSearch(map); 
			local.search(addr);  
			local.getResults(); 
			local.setSearchCompleteCallback(function(searchResult){
					var poi = searchResult.getPoi(0);
					if(!poi)
					{
						alert("很抱歉，没有在附近找到<b>" + addr + '</b>，请检查地名是否正确');
						var centerP = MAP_CENTER_POINT;
		 				var mm = centerP.split("X");
		 				var p = new BMap.Point(mm[1],mm[0]);
		 			    createMap(p,13);
					}
					else
					{  
						var pp = new BMap.Point(poi.point.lng,poi.point.lat);
						createMap(pp,13);
						//map.centerAndZoom(pp,map.getZoom());
						document.getElementById("g_lat").value = poi.point.lat;
			    		document.getElementById("g_lng").value = poi.point.lng; 
					} 
			});
		} 
 }
 //创建地图
 function createMap(point,zoomsize)
 {
	map = new BMap.Map('map_canvas');                                                                                                                                                                                             
	map.centerAndZoom(point,zoomsize);//设定地图的中心点和坐标并将地图显示在地图容器中
    window.map = map;//将map变量存储在全局 
    map.enableScrollWheelZoom();//启用地图滚轮放大缩小
    
    g_lat = point.lat;
    g_lng = point.lng;
    
    //向地图中添加缩放控件
	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
	map.addControl(ctrl_nav);
	
	//向地图中添加比例尺控件
	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
	map.addControl(ctrl_sca);
	
	var this_marker = new BMap.Marker(point); 
	map.addOverlay(this_marker);
  
	//当在地图单击时加入创建一个新的标注
	map.addEventListener('click', function(e) {
		g_lat = e.point.lat;
		g_lng = e.point.lng;
		document.getElementById("g_lat").value = e.point.lat;
	    document.getElementById("g_lng").value = e.point.lng;
		var pp = new BMap.Point(e.point.lng,e.point.lat);
		getThisLocation(pp);
	});
 };
 
//根据经纬度返回该地的地名（地址）
 function getThisLocation(point)
 {
	 var myGeo = new BMap.Geocoder();  
	 // 根据坐标得到地址描述  
	myGeo.getLocation(point, function(result){ 
		 if (result){ 
			 map.clearOverlays();
			 var marker = new BMap.Marker(point);
		 
//			 marker.enableDragging();
//			 marker.addEventListener('draggend',function(){
//				 
//			 });
			 map.addOverlay(marker);
			 
            document.getElementById('this_group_addr').innerHTML = document.getElementById('group_addr').value = result.address;
            var divHtml = '<div style="width:220px"><span style="width:120px" id="show_desc_' + point.lat + 'X' + point.lng + '">' + result.address + '</span>';
            divHtml += '<hr style="border:solid 1px #cccccc;" />';
            divHtml += '<div style="corlor:#0000cc;text-decoration:underline;text-align:right;cursor:pointer;" onclick="remove_this('+point.lat+','+point.lng+')">取消标注</div>' ;
            divHtml += '</div>';
            var infoWindow = new BMap.InfoWindow(divHtml,{width:222}); 
        	marker.openInfoWindow(infoWindow,result.point);
		 } 
	 }); 
   
 };
 
function remove_this(lat,lng)
{
	map.clearOverlays();
	document.getElementById("g_lat").value = lat;
    document.getElementById("g_lng").value = lng;
}
   

//地图与所选的城市进行联动（检索地名是否存在）
function changeCenterPoint(code)
{
	var gname = document.getElementById("province");
	var  addr;
	var cur_index = gname.selectedIndex;
	addr = gname[cur_index].text;
	addr = addr.replace('--','');
	var n_name = document.getElementById("gname").value;
	var mm = '浙江省杭州市' + addr + n_name;
	queryAddress(mm);
}

//地图与所选的城市进行选区联动（不检索地名是否存在）
function changeCenterPointNo(code)
{
	var n_name = "";
	var gname = document.getElementById("province");
		var  addr;
		var cur_index = gname.selectedIndex;
		addr = gname[cur_index].text;
		addr = addr.replace('--','');
		var mms = '浙江省杭州市' + addr + n_name;
		var local = new BMap.LocalSearch(map); 
		local.search(mms);  
		local.getResults(); 
		local.setSearchCompleteCallback(function(searchResult){
				var poi = searchResult.getPoi(0);
				if(!poi)
				{
				//	alert("很抱歉，没有在附近找到<b>" + mms + '</b>，请检查地名是否正确');
					var centerP = MAP_CENTER_POINT;
					var mm = centerP.split("X");
					var p = new BMap.Point(mm[1],mm[0]);
					createMap(p,13);
				}
				else
				{  
					var pp = new BMap.Point(poi.point.lng,poi.point.lat);
					createMap(pp,13,1); 
				//	document.getElementById("result_list_group").style.display = "none"; 
				} 
		});
}

//地图与填入的讨论区名称进行联动
function changePoint()
{
	var now_lat = document.getElementById('g_lat').value;
	var now_lng = document.getElementById('g_lng').value;
	if(g_lat == now_lat && g_lng == now_lng)
	{
	}
	else
	{
		var gname = document.getElementById("gname").value;
		queryAddress('浙江省杭州市' + gname);
	}
}
 