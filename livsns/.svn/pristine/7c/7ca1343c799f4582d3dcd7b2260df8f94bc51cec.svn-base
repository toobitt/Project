var nowid = 'livehere_gid';
var map,geocoder; 
var point,markers;
$(document).ready(function (){


	//地图初始化
	initialize = function()
	{ 
		if (GBrowserIsCompatible())
			{
				var centerP = MAP_CENTER_POINT;
				var group_name = '浙江省杭州市';
			    var mm = centerP.split("X");
			    if(mm[0] == 0.0 || mm[1] == 0.0)
			    { 
			    	queryAddress(group_name);
			    }
			    else
			    {
				    var p = new GLatLng(mm[0],mm[1]);
			     	createMap(p,15);
			    }
			}
	 };
	//根据地址查询经纬度
	 queryAddress = function(addr)
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
	 			      createMap(p,15); 
	         	  }
	         	  else
	         	  {  
	         		  createMap(point,15);  
	         	  }
	 	});
	 };

	 //创建地图
	createMap = function(point,zoomsize)
	{
		 map = new GMap2(document.getElementById('map_canvas'));
		 // 给地图添加内置的控件，分别为： 平移及缩放控件（左上角）、比例尺控件（左下角）、缩略图控件（右下角）
		  map.addControl(new GLargeMapControl());
		  map.addControl(new GScaleControl());//比例尺
		  map.addControl(new GOverviewMapControl());

		  //设定地图中心
		  map.setCenter(point,zoomsize);
		  map.enableScrollWheelZoom(); //鼠标滚动缩放
		  map.disableDoubleClickZoom();//禁止双击放大
		
		  //after dragging show groups around current point
		  GEvent.addListener(map, 'dragend',function(){ 
				 show_surrounding(nowid);
			   });
		  show_surrounding(nowid);
	}; 
	//保存设置
	savaData = function()
	{
		$.ajax({
			url:"geoinfo.php",
			type:"POST",
			dataType:"html",
			cache:false,
			timeout:TIME_OUT,
			data:{
				a:"save_data",
				gname:$("#"+nowid+'_n').val(),
				gid:$("#"+nowid).val(),
				glat:$("#"+nowid+"_lat").val(),
				glng:$("#"+nowid+"_lng").val()
			},
			success:function(obj){
				alert(obj);
				document.location.href="";
			},
			error:function(){}
		});
	};
	/**
	 *显示当前地图容器内存在的讨论区
	 * @return
	 */
	show_surrounding = function()
	{  
		var bounds = map.getBounds();
		var southWest = bounds.getSouthWest();
		var northEast = bounds.getNorthEast();
		var minPointLat = southWest.lat();
		var minPointLng = southWest.lng();
		var maxPointLat = northEast.lat();
		var maxPointLng = northEast.lng(); 
		showSurrounding(minPointLat,minPointLng,maxPointLat,maxPointLng);
	  
	}

	showSurrounding = function(minlat,minlng,maxlat,maxlng)
	{
		 
		if(minlat && minlng && maxlat && maxlng)
		{
			$.ajax({
				url:"geoinfo.php",
				type:"POST",
				dataType:"html",
				cache:false,
				timeout:TIME_OUT,
				data:{
					a:"get_bounds",
					min_lat:minlat,
					min_lng:minlng,
					max_lat:maxlat,
					max_lng:maxlng
				},
				success:function(obj){
					//alert(obj); 
					if(obj){
						var json = new Function('return'+obj)();
						$.each(json,function(k){  
							create_group_infoWindow(json[k].lat,json[k].lng,json[k].name,json[k].group_id,nowid); 
						});
					}
				},
				error:function(){}
			});
		}
	};
	

	create_group_infoWindow = function(latitude,longitude,description,group_id,nowid)
	{     
		var hg_marker = new HG_Marker(new GLatLng(latitude, longitude),description,group_id,{"index":group_id,"nowid":nowid}); 
		map.addOverlay(hg_marker); 
	}
	
	
}); 

var _index = 1;  
var curr_id,gid,last_id=0;
function HG_Marker(latlng, markerContent,g_id,option)
{ 
    this._latlng = latlng; 
    this._markerContent = markerContent;
    this._gid = g_id; 
	this._clickParamer = option; 
}
 
HG_Marker.prototype = new GOverlay();
HG_Marker.prototype.initialize = function(map_) { 
	curr_id = this._clickParamer["nowid"]; 
	var d_lat = this._latlng.lat();
	var d_lng = this._latlng.lng();
	var markerHtml; 
    markerHtml = document.createElement('div');
    markerHtml.setAttribute("index", this._clickParamer["index"]);
    markerHtml.id = 'g_'+this._gid; 
    markerHtml.innerHTML = '<span id="gspan'+this._gid+'" style="display:inline-block;height: 14px; overflow: hidden; padding-top: 3px;width: 70px;cursor:pointer;" titile="'+this._markerContent+'">' + this._markerContent + '</span>'; 
    markerHtml.style.position = "absolute";
    markerHtml.style.height="34px";
    markerHtml.style.color="#fff";
    markerHtml.style.textAlign="center";
    markerHtml.style.paddingTop="2px";
    markerHtml.style.fontSize="12px";
    //如果用户设置过当前位置，那么就将当前位置的class 设置为marker_this
    var current_default = document.getElementById(nowid).value;
    if(current_default == this._gid)
    { 
    	//markerHtml.setAttribute("class", "maker_this");
    	last_id = markerHtml.id;
    	markerHtml.style.background='url('+RESOURCE_DIR+'img/map/marker_this.gif) no-repeat scroll 0 0 transparent';
    }
    else
    {
    	//markerHtml.setAttribute("class", "maker_normal");
    	markerHtml.style.background='url('+RESOURCE_DIR+'img/map/marker_n.gif) no-repeat scroll 0 0 transparent';
    }
     
	markerHtml.style.width="70px"; 
    map_.getPane(G_MAP_MARKER_PANE).appendChild(markerHtml);  
    this._map = map;  
    GEvent.addDomListener(markerHtml, "click", function() { 
    	if(curr_id == null || curr_id == "undefined" || !curr_id)
    	{
    		alert("还未选择标注类型，单击上边对应的按钮来选择");
    	}
    	else
    	{ 
    		//返回讨论区的名称和id and lat,lng
    		var gid = markerHtml.id.split("_")[1]; 
    		document.getElementById(curr_id+'_n').value=document.getElementById("gspan"+gid).innerHTML;
    		document.getElementById(curr_id).value = markerHtml.id.split("_")[1];
    		document.getElementById(curr_id+'_lat').value = d_lat;
    		document.getElementById(curr_id+'_lng').value = d_lng;
    		if(markerHtml.id != last_id)
    		{
    			markerHtml.style.background='url('+RESOURCE_DIR+'img/map/marker_this.gif) no-repeat scroll 0 0 transparent';
    			if(last_id)
    			{
    				document.getElementById(last_id).style.background='url('+RESOURCE_DIR+'img/map/marker_n.gif) no-repeat scroll 0 0 transparent';
    			}
    			last_id = markerHtml.id;
    		}
    	}
    }); 
    GEvent.addDomListener(markerHtml, "mouseover", function() { 
    	markerHtml.style.display="block";
    	markerHtml.style.zIndex = 99;
    });
    GEvent.addDomListener(markerHtml, "mouseout", function() { 
    	markerHtml.style.display="block";
    	markerHtml.style.zIndex = 9;
    });
    this._markerHtml = markerHtml; 
};
HG_Marker.prototype.remove = function() {
    this._markerHtml.parentNode.removeChild(this._markerHtml); 
};
HG_Marker.prototype.copy = function() {
    return new (this._latlng,this._markerContent,this._gid,this._clickParamer);
};
HG_Marker.prototype.redraw = function(force) {
    if (force)
    {
        var p = this._map.fromLatLngToDivPixel(this._latlng);
        this._markerHtml.style.left = (p.x) + "px";
        this._markerHtml.style.top = (p.y) - 25 + "px"; 
    }
};
 
HG_Marker.prototype.getMarkerHtml = function() {
    return this._markerHtml;
}
 
