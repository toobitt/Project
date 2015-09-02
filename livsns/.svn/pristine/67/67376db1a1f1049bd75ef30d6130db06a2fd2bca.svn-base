var nowid = 'livehere_gid';
var map,geocoder; 
var point,markers;
$(document).ready(function (){


	//地图初始化
	initialize = function()
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
		    var p = new BMap.Point(mm[1],mm[0]);
	     	createMap(p,15);
	    }
	 };
	//根据地址查询经纬度
	 queryAddress = function(addr)
	  {
		 if(addr)
		 {
			var local = new BMap.LocalSearch(map); 
			local.search(addr);  
			local.getResults(); 
			local.setSearchCompleteCallback(function(searchResult){
					var poi = searchResult.getPoi(0);
					if(!poi)
					{
						alert("很抱歉，没有在附近找到 " + addr + ' ，请检查地名是否正确');
						var centerP = MAP_CENTER_POINT;
		 				var mm = centerP.split("X");
		 				var p = new BMap.Point(mm[1],mm[0]);
		 			    createMap(p,15);
					}
					else
					{  
						var pp = new BMap.Point(poi.point.lng,poi.point.lat);
						createMap(pp,15,1);  
					} 
			});
		}
	 };

	 //创建地图
	createMap = function(point,zoomsize)
	{
		map = new BMap.Map('map_canvas');                                                                                                                                                                                             
		map.centerAndZoom(point,zoomsize);//设定地图的中心点和坐标并将地图显示在地图容器中
        window.map = map;//将map变量存储在全局 
        map.enableScrollWheelZoom();//启用地图滚轮放大缩小
	      
        //向地图中添加缩放控件
    	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
    	map.addControl(ctrl_nav);
		
    	//向地图中添加比例尺控件
    	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
    	map.addControl(ctrl_sca);
		
		  //after dragging show groups around current point
    	map.addEventListener('dragend',function(){ 
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
		var minPointLat = southWest.lat;
		var minPointLng = southWest.lng;
		var maxPointLat = northEast.lat;
		var maxPointLng = northEast.lng; 
		showSurrounding(minPointLat,minPointLng,maxPointLat,maxPointLng);
	  
	};

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
		var pp = new BMap.Point(longitude,latitude);
		var hg_marker = new HG_Marker(pp,description,group_id,{"index":group_id,"nowid":nowid}); 
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
 
HG_Marker.prototype = new BMap.Overlay();
HG_Marker.prototype.initialize = function(map) { 
	curr_id = this._clickParamer["nowid"]; 
	var d_lat = this._latlng.lat;
	var d_lng = this._latlng.lng;
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
	var ch = document.getElementById("g_"+this._gid); 
	if(!ch)
	{
		map.getPanes().markerPane.appendChild(markerHtml);
	}  
    this._map = map;  
    markerHtml.onclick = function() { 
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
    }; 
    markerHtml.onmouseover = function() { 
    	markerHtml.style.display="block";
    	markerHtml.style.zIndex = 99;
    };
    markerHtml.onmouseout = function() { 
    	markerHtml.style.display="block";
    	markerHtml.style.zIndex = 9;
    };
    this._markerHtml = markerHtml; 
    return markerHtml;
};
HG_Marker.prototype.remove = function() {
    this._markerHtml.parentNode.removeChild(this._markerHtml); 
};
HG_Marker.prototype.copy = function() {
  //  return new HG_Marker(this._latlng,this._markerContent,this._gid,this._clickParamer);
};
HG_Marker.prototype.draw = function() { 
	var p = this._map.pointToOverlayPixel(this._latlng); 
	this._markerHtml.style.left = (p.x) - 10 + "px";
	this._markerHtml.style.top = (p.y) - 12 + "px"; 
	
	var old_id = this._markerHtml.id.split('_')[1];
	if(document.getElementById("overlay" + old_id))
	{
		document.getElementById("overlay" + old_id).style.left = (p.x) -170 + "px";
		document.getElementById("overlay" + old_id).style.top = (p.y) -227 + "px";
	} 
};
 
HG_Marker.prototype.getMarkerHtml = function() {
  //  return this._markerHtml;
}
HG_Marker.prototype.show = function(){  
   if (this._markerHtml){  
	   this._markerHtml.style.display = "";  
   }  
 }
 

