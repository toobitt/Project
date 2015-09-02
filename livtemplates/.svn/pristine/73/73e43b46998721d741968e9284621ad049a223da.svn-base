window.onload = function(){initialize();}
var map,geocoder;
var userAddress;
var point,markers;

function initialize()
 {
	//如果用户location存在，就将地图的中心点设置为用户的location，否则就取设定好的中心点
	if(userAddress)
	{
		queryAddress(userAddress);
	}
	else
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
 };

 
//根据地址查询经纬度,
 function queryAddress(addr)
 { 
	if(addr){
		var local = new BMap.LocalSearch(map); 
		local.search(addr);  
		local.getResults(); 
		local.setSearchCompleteCallback(function(searchResult){
				var poi = searchResult.getPoi(0);
				if(!poi)
				{
					//alert("很抱歉，没有在附近找到<b>" + addr + '</b>，请检查地名是否正确');
					var centerP = MAP_CENTER_POINT;
	 				var mm = centerP.split("X");
	 				var p = new BMap.Point(mm[1],mm[0]);
	 			    createMap(p,13);
	 			    document.getElementById("g_lat").value = mm[0];
				  	document.getElementById("g_lng").value = mm[1];
				}
				else
				{  
					var pp = new BMap.Point(poi.point.lng,poi.point.lat); 
					createMap(pp,13); 
	        		document.getElementById("g_lat").value = poi.point.lat;
	        		document.getElementById("g_lng").value = poi.point.lng;
				} 
		});
	}

 }; 
 //创建地图
 function createMap(point,zoomsize)
 {
	 map = new BMap.Map('map_canvas');                                                                                                                                                                                             
	 map.centerAndZoom(point,13);//设定地图的中心点和坐标并将地图显示在地图容器中
     window.map = map;//将map变量存储在全局
     map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
     map.enableScrollWheelZoom();//启用地图滚轮放大缩小
	      
     //向地图中添加缩放控件
 	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
 	map.addControl(ctrl_nav);
		
 	//向地图中添加比例尺控件
 	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
 	map.addControl(ctrl_sca);
	 if(!map_op_type)
	 {
		  //当在地图单击时加入创建一个新的标注
		 map.addEventListener('click',function(e){
			 map.clearOverlays();
			 getThisLocation(e.point,0);
		  });
	  }
	  else
	  { 
	  	 
	  }
	 
	 if(this_act == 1)
	 {
		 var mm = new BMap.Marker(point);
		 mm.enableDragging();
		 mm.addEventListener('dragend',function(e){
			 var pp = new BMap.Point(e.point.lng,e.point.lat);
			 getThisLocation(pp,1);
		 }); 
		 map.addOverlay(mm);
	 }
	 
 };

//根据经纬度返回该地的地名（地址）
 function getThisLocation(point,type)
 {
	 var myGeo = new BMap.Geocoder();  
	 // 根据坐标得到地址描述  
	myGeo.getLocation(point, function(result){ 
		 if (result){  
	     if(!type){ 
	    	 showMarkers(result.point.lat,result.point.lng,result.address);
	     }else{
	    	 showMarkers(result.point.lat,result.point.lng,result.address);
	         saveData(result.point.lat + 'X' + result.point.lng,result.address,0);
	     }
	   } 
	 }); 
	  


 };
 
 
 //向地图中添加新标注
 function showMarkers(latitude,longitude,description)
 {
	map.clearOverlays();
	document.getElementById("g_lat").value = '';
 	document.getElementById("g_lng").value = '';
	// var marker = new GMarker(new GLatLng(latitude, longitude),{draggable:true});
 	var marker = new BMap.Marker(new BMap.Point(longitude,latitude));
 	marker.enableDragging();//
 	var divHtml = '<div style="width:220px;"><span id="show_desc_' + latitude + "X" + longitude+'" style="width:220px;height:25px;overflow:hidden;display:block;">'+description+'</span>';
 	divHtml += '<hr style="border:solid 1px #cccccc" /><div style="color:#0000cc;cursor:pointer;text-align:right;text-decoration:underline" onclick="cancle_mar('+latitude+','+longitude+')">取消标注</div>';
 	var infoWindow = new BMap.InfoWindow(divHtml,{width:232});
 	marker.addEventListener('mouseover',function(){
 		marker.openInfoWindow(infoWindow,marker.getPosition());
 	});
 	marker.addEventListener('dragstart',function(){
 		marker.closeInfoWindow();
 	});  
	 
 	marker.addEventListener('dragend', function(e) {
		deleteMarker(latitude+"X"+longitude); 
		getThisLocation(e.point,1);
	//	map.removeOverlay(this);//此处待定

    } );
	map.addOverlay(marker);
	document.getElementById("g_lat").value = latitude;
	document.getElementById("g_lng").value = longitude;
	document.getElementById("g_addr").value = description;
 };
function cancle_mar(lat,lng)
{
	var pp = new BMap.Point(lng,lat);
	map.removeOverlay(new BMap.Marker(pp));
	deleteMarker(lat + 'X' + lng);
}
function deleteMarker(latlng)
{
	 latlng = latlng.split("X");
	 document.getElementById("g_lat").value = latlng[0];
	 document.getElementById("g_lng").value = latlng[1];
};
function saveData(latlng,user_description,type)
{
	var ll = latlng.split("X");
	map.closeInfoWindow();
	showMarkers(ll[0],ll[1],user_description);
	document.getElementById("g_lat").value = ll[0];
	document.getElementById("g_lng").value = ll[1];
	document.getElementById("g_addr").value = user_description;
}
//地图与所选的城市进行联动
function changeCenterPoint(code)
{
	if(!document.getElementById("g_lat").value && !document.getElementById("g_lng").value){
		var gname = document.getElementById("province");
		var  addr;
		var cur_index = gname.selectedIndex;
		addr = gname[cur_index].text;
		addr = addr.replace('--','');
		var n_name = document.getElementById("gname").value;
		var mm = '浙江省杭州市' + addr + n_name; 
		queryAddress(mm);
	}
}
//地图与填入的讨论区名称进行联动
function changePoint()
{
	if(!document.getElementById("g_lat").value && !document.getElementById("g_lng").value){
		var gname = document.getElementById("gname").value;
		queryAddress('浙江省杭州市' + gname);
	}
}

 

 