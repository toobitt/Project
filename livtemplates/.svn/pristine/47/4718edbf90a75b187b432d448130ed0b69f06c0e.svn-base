window.onload = function(){initialize();if(parseInt(now_uid,10)>0){setTimeout("getnotify()",3000);}}
var map,geocoder;
var userAddress;
var point,markers;

function initialize()
 {
	 if (GBrowserIsCompatible())
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
				    var p = new GLatLng(mm[0],mm[1]);
			     	createMap(p,13);
			    }
			    
			}
		}
 };

//根据地址查询经纬度
 function  queryAddress(addr)
 {
	 geocoder = new GClientGeocoder();
	 geocoder.getLatLng(
	          addr,
	        function(point){
        	  if(!point)
        	  {
        		  var centerP = MAP_CENTER_POINT;
				  var mm = centerP.split("X");
				  var p = new GLatLng(mm[0],mm[1]);
			      createMap(p,13);
			      document.getElementById("g_lat").value = mm[0];
			  	  document.getElementById("g_lng").value = mm[1];
        	  }
        	  else
        	  {  
        		  createMap(point,13); 
        		  document.getElementById("g_lat").value = point.lat();
        		  document.getElementById("g_lng").value = point.lng();
        	  }
	});
 }
 //创建地图
 function createMap(point,zoomsize)
 {
	 map = new GMap2(document.getElementById('map_canvas'));
	 // 给地图添加内置的控件，分别为： 平移及缩放控件（左上角）、比例尺控件（左下角） 
	  map.addControl(new GLargeMapControl());
	  map.addControl(new GScaleControl());//比例尺 

	  //设定地图中心
	  map.setCenter(point,zoomsize);
	  map.enableScrollWheelZoom(); //鼠标滚动缩放
	  map.disableDoubleClickZoom();//禁止双击放大
	 // alert(map_op_type);
	 if(!map_op_type)
	 {
		  //当在地图单击时加入创建一个新的标注
		  GEvent.addListener(map, 'click',function(overlay,point){
			 var xx = point.lat();
			 var yy = point.lng();
			 getThisLocation(xx,yy,0);
		  });
	  }
	  else
	  { 
	  	//当地图拖拽结束后重新显示周边的圈子
	   GEvent.addListener(map, 'dragend',function(){ 
		 show_surrounding();
	   });
	   GEvent.addListener(map, 'zoomend',function(){ 
			 show_surrounding();
		   });
	   show_surrounding(); 
	  }
	 
	 if(this_act == 1)
	 {
		 var mm = new GMarker(point,{draggable:true});
		 GEvent.addListener(mm, 'dragend',function(overlay,point){ 
			 getThisLocation(overlay.lat(),overlay.lng(),1);
		   });
		 map.addOverlay(mm);
	 }
	 
 };

//根据经纬度返回该地的地名（地址）
 function getThisLocation(lat,lng,type)
 {
	 geocoder = new GClientGeocoder();

	 if(type == 0)
	 {
		 geocoder.getLocations(lat+','+lng,addAddressToMap);
	 }
	 else
	 {
		 geocoder.getLocations(lat+','+lng,addAddrToMarker);
	 }


 };
	 //显示
 function addAddressToMap(response)
 {
	 if (!response || response.Status.code != 200) {
	        alert("对不起，不能解析这个地址");
	 } else {
	 var place = response.Placemark[0]; 
     var add = place.address;
     showMarkers(place.Point.coordinates[1],place.Point.coordinates[0],add); 
      }
 };
 function addAddrToMarker(response)
 {
	 if (!response || response.Status.code != 200) {
	        alert("对不起，不能解析这个地址");
	 } else {
	 var place = response.Placemark[0];
	 var pp = new GLatLng(place.Point.coordinates[1],place.Point.coordinates[0]);
     var add = place.address;
     showMarkers(place.Point.coordinates[1],place.Point.coordinates[0],add);
     saveData(place.Point.coordinates[1]+"X"+place.Point.coordinates[0],add,0);

      }
 };
 
 //向地图中添加新标注
 function showMarkers(latitude,longitude,description)
 {
	map.clearOverlays();
	document.getElementById("g_lat").value = '';
 	document.getElementById("g_lng").value = '';
	 var marker = new GMarker(new GLatLng(latitude, longitude),{draggable:true});
	var divHtml = document.createElement("div");
	divHtml.style.width = "220px";

	var ssp = document.createElement("span");
	ssp.id='show_desc_' + latitude + "X" + longitude;
	ssp.innerHTML = '<span id="desc_'+latitude + "X" + longitude+'">'+description+'</span>' ;
	ssp.style.width = "220px"; ssp.style.height = "25px"; ssp.style.overflow = "hidden";ssp.style.display = "block";  
	divHtml.appendChild(ssp);


	var hr = document.createElement('hr');
	hr.style.border = 'solid 1px #cccccc';
	divHtml.appendChild(hr);

	// 创建“删除”按钮
	var lnk = document.createElement('div');
	lnk.innerHTML = "取消标注";
	lnk.style.color = '#0000cc';
	lnk.style.cursor = 'pointer';
	lnk.style.textDecoration = 'underline';
	lnk.style.textAlign = "right";

	// 为“删除”按钮添加事件处理
	lnk.onclick =
		function() {
		  map.closeInfoWindow();
		  var latlng = marker.getLatLng();
		  map.removeOverlay(marker);
		  var newpoint = latlng.lat()+"X"+latlng.lng();
		  deleteMarker(newpoint);

		};
	divHtml.appendChild(lnk);

	GEvent.addListener(marker,'mouseover', function() {
	                  marker.openInfoWindowHtml(divHtml);
	              }
	       );

	GEvent.addListener(marker,'dragstart', function() {
        marker.closeInfoWindow();
    } );
	GEvent.addListener(marker,'dragend', function(overlay,point) {
		deleteMarker(latitude+"X"+longitude);
		getThisLocation(overlay.lat(),overlay.lng(),1);
		map.removeOverlay(this);//此处待定

    } );
	map.addOverlay(marker);
	document.getElementById("g_lat").value = latitude;
	document.getElementById("g_lng").value = longitude;
	document.getElementById("g_addr").value = description;
 };

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
/**
 * 显示某个圈子的周边圈子
 * map_center：当前圈子的中心点（如果创建圈子时标注过，就采用该标注点的经纬度
 * @return
 */
function show_surrounding(obj)
{ 
	
	var bounds = map.getBounds();
	var southWest = bounds.getSouthWest();
	var northEast = bounds.getNorthEast();
	var minPointLat = southWest.lat();
	var minPointLng = southWest.lng();
	var maxPointLat = northEast.lat();
	var maxPointLng = northEast.lng();
	document.getElementById("minPointLat").value =  minPointLat;
	document.getElementById("minPointLng").value =  minPointLng;
	document.getElementById("maxPointLat").value =  maxPointLat;
	document.getElementById("maxPointLng").value =  maxPointLng; 
	showSurrounding();
  
}

function create_group_infoWindow(latitude,longitude,description,group_id,t_tips)
{   
	 
	var this_group = document.getElementById("thisGroup").value; 
	var tips = '<p class="title"><span style="float:right;margin-right:5px;corlor:red;"><a href="javascript:void(0)"  >X</a></span><b><a href="?m=thread&group_id='+group_id+'">'+description+'</a></b></p>';
	tips += t_tips; 
	var hg_marker = new HG_Marker(new GLatLng(latitude, longitude),tips,description,group_id,this_group,{"index":group_id}); 
	map.addOverlay(hg_marker); 
}

 