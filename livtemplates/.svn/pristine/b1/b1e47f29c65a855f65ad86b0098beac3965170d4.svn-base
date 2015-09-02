var this_click=0;
var curr_type=-1;
var father_group = "";
function show_tips(op_type)
{
	if(!op_type){
		var dd = document.getElementById("groups_tips_");
		if(dd.style.display == "none")
		{
//			document.getElementById(obj).className = "popmov"; 
			document.getElementById("groups_tips_").style.display = "inline";
//			document.getElementById("map_canvas").style.width = 615 + "px";
			//map.checkResize();
		}
		else
		{
			document.getElementById("groups_tips_").style.display = "none";
//			document.getElementById(obj).className = "popmov_hid"; 
//			document.getElementById("map_canvas").style.width = 960 + "px";
			//map.checkResize();
		}
	}else{
//		document.getElementById(obj).className = "popmov"; 
		document.getElementById("groups_tips_").style.display = "inline";
	}
}

//创建地盘
function xajax_group_create(){return xajax.call("group_create", arguments, 1);} 
function create_group(input)
{
	 
		xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
		xajax_group_create(xajax.getFormValues(input));
	 
}

//搜索区域
function xajax_search_similar_group(){return xajax.call("search_similar_group", arguments, 1);} 
function search_similar_group(group_n,type,event)
{
 
	if(arguments.length == 2)
	{
		
		if(group_n.length > 0){
			document.getElementById("result_list_group").style.display="block";
			xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
			xajax_search_similar_group(group_n,type);
		}else{
			if(document.getElementById("result_list_group").style.display == "block")
			{
				document.getElementById("result_list_group").style.display = "none";
			}
		}
	}else if(typeof event != "undefined"){
		if(event.keyCode == 10 || event.keyCode == 13)
		{
			query_user_addr();
		}
		else
		{
			search_similar_group(group_n,type);
		}
		
	}
}

//点击“搜索”搜索地名
function query_user_addr()
{
	var val = document.getElementById('user_query_addr').value;
	if(!val)
	{
		alert('请输入地址进行查询！');
		document.getElementById('user_query_addr').focus();
	}
	else
	{
		if(NOW_MOUDLE == 0){
			map.clearOverlays();
		}
		search_similar_group(val,1); 
	}
}




//搜索区域
function xajax_search_map(){return xajax.call("search_map", arguments, 1);} 
function search_map(type_id,group_id,gnames)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_search_map(type_id,group_id,gnames);
}

//新版首页检索
function change_group_map()
{
	var group_id = document.getElementById("group_region").value;
	var type_id = document.getElementById("group_type").value;
	var gnames = document.getElementById("user_query_addr").value;
	if(gnames == '请输入地盘名称')
	{
		gnames = '';
	}
	if(type_id == -1)
	{
		group_id = "";
		var l = rec_groups.length; 
		var sp = '';
		for(var i=0;i<l;i++)
		{
			if(rec_groups[i]>0)
			{
				group_id += sp + rec_groups[i];
				sp = ',';
			}
		}
	}
	if(NOW_MOUDLE == 0){
			map.clearOverlays();
	}
	search_map(type_id,group_id,gnames);
}


var map,geocoder; 
var point,markers; 
	//地图初始化
	function initialize()
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
	     	createMap(p,13);
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
						alert("很抱歉，没有在附近找到<b>" + addr + '</b>，请检查地名是否正确');
						var centerP = MAP_CENTER_POINT;
		 				var mm = centerP.split("X");
		 				var p = new BMap.Point(mm[1],mm[0]);
		 			    createMap(p,13);
					}
					else
					{  
						var pp = new BMap.Point(poi.point.lng,poi.point.lat);
						createMap(pp,13,1); 
						document.getElementById("result_list_group").style.display = "none"; 
					} 
			});
		}

	 };
 
	 //创建地图
	function createMap(point,zoomsize,create_type)
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
    	
    	//点击绿色箭头（当前位置坐标），创建讨论区
		if(create_type==1)
		{ 
			getThisLocation(new BMap.Point(point.lng,point.lat),1);
		}
		  
		map.addEventListener( 'dragend',function(){ 
			if(NOW_MOUDLE == 1){
				show_surrounding(0);
			}else{ 
				show_surrounding(curr_type);
			}
		});
		map.addEventListener('click',function(e){ 
			  if(NOW_MOUDLE == 0){ 
//				  document.getElementById("map_canvas").style.width=615+"px"; 
				  getThisLocation(new BMap.Point(e.point.lng,e.point.lat),0);
			  }
		   
	 });
		  
		  show_surrounding(-1);
	}; 
	
	 //根据经纬度返回该地的地名（地址） 
	function getThisLocation(pp,type)
	{  
		var myGeo = new BMap.Geocoder();  
		 // 根据坐标得到地址描述  
		myGeo.getLocation(pp, function(result){    
			 
			 if (result){  
		     if(!type){ 
		    	 document.getElementById('marker_create').style.display = "block";  
				 show_tips(1);
				 document.getElementById('nologin_g_addr').value = result.address;
			     document.getElementById('area_result_div').innerHTML = '地址：'+result.address;
			     document.getElementById('area_result_div').style.color = "#00A1E9";
			     document.getElementById('nologin_g_lat').value = result.point.lat;
			     document.getElementById('nologin_g_lng').value = result.point.lng; 
		     }else{
		    	 
		    	 show_tips(1);
				 var place = result.address;
				 var n_lat = result.point.lat;
				 var n_lng = result.point.lng;  
			     document.getElementById('nologin_g_addr').value = place;
			     document.getElementById('area_result_div').innerHTML = '地址：'+place;
			     document.getElementById('nologin_g_lat').value = n_lat;
			     document.getElementById('nologin_g_lng').value = n_lng;
			     mark_result(new BMap.Point(n_lng,n_lat)); 
		     }
		   } 
		 }); 
			
	};
 
	
	function mark_result(point)
	{ 
	    var this_marker = new BMap.Marker(point);
	    
	    var addr = document.getElementById('nologin_g_addr').value;
	    var n_lat = document.getElementById('nologin_g_lat').value;
	    var n_lng = document.getElementById('nologin_g_lng').value;  
	    var return_form = '<span>'+addr+'</span><hr/><form action="?m=group&a=create_my_g" name="create_g_form"><input type="hidden" name="group_addr" value="'+addr+'" /><input type="hidden" name="lat" value="'+n_lat+'" /><input type="hidden" name="lng" value="'+n_lng+'" />';
	    return_form +=	'<input type="hidden" name="m" value="group" /><input type="hidden" name="a" value="create_my_g" /><input  type="submit" name="group_sub" value="" style="border:none;cursor:pointer;height: 21px;width: 55px;margin-left:5px;float:right;margin-top:5px;background:url('+livime_images_url+'uhome/images/cjdb.jpg) no-repeat scroll 0 0;" /></form>';
	    
	    var infoWindow = new BMap.InfoWindow(return_form,{width:232});
	    
		this_marker.addEventListener('click',function(overlay){ 
			this_marker.openInfoWindow(infoWindow,point);
		   }); 
		map.centerAndZoom(point,13);
		//createMap(point,13);
	    map.addOverlay(this_marker);
	    this_marker.openInfoWindow(infoWindow,point);
	}
	/**
	 *显示当前地图容器内存在的讨论区
	 * @return
	 */
function show_surrounding()
{
	var bounds = map.getBounds();
	var southWest = bounds.getSouthWest();
	var northEast = bounds.getNorthEast();
	var minPointLat = southWest.lat;
	var minPointLng = southWest.lng;
	var maxPointLat = northEast.lat;
	var maxPointLng = northEast.lng; 
	var group_ids='';
	var type_id=0;
	if(NOW_MOUDLE == 0){
		switch(arguments[0])
		{
			case -1:
				curr_type = type_id = arguments[0];
				var l = rec_groups.length;
				var sp = '';
				for(var i=0;i<l;i++)
				{
					if(rec_groups[i]>0)
					{
						group_ids += sp + rec_groups[i];
						sp = ',';
					}
				}
				break;
			case 0:
				curr_type = type_id = 0;
				if(father_group)
				{
					group_ids = father_group;
				}
				else
				{
					group_ids = '';
				}
				break;
			default:
				curr_type = type_id = arguments[0];
				if(father_group)
				{
					group_ids = father_group;
				}
				else
				{
					group_ids = '';
				}
				break;
		}
		showSurrounding(minPointLat,minPointLng,maxPointLat,maxPointLng,group_ids,type_id);
	}
	else
	{
		showSurrounding(minPointLat,minPointLng,maxPointLat,maxPointLng,0,0);
	}
  
}
function xajax_showSurrounding(){return xajax.call("showSurrounding", arguments, 1);}

function showSurrounding(minPointLat,minPointLng,maxPointLat,maxPointLng,group_ids,type_id)
{
	if(document.getElementById("group_region"))
	{
		var group_id_s = document.getElementById("group_region").value;
		var type_id_s = document.getElementById("group_type").value;
		var gnames = document.getElementById("user_query_addr").value;
		if(gnames == '请输入地盘名称')
		{
			gnames = '';
		}
		if(gnames || group_id_s>0)
		{
			if(NOW_MOUDLE == 0){
				map.clearOverlays();
			}
			if(type_id_s == -1)
			{
				search_map(type_id_s,group_ids,gnames);
			}
			else
			{
				search_map(type_id_s,group_id_s,gnames);
			}
		}
		else
		{
			xajaxRequestUri = 'groups.php?a=group_ajax';
			xajax_showSurrounding(minPointLat,minPointLng,maxPointLat,maxPointLng,group_ids,type_id);
		}
	}
	else
	{
		xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
		xajax_showSurrounding(minPointLat,minPointLng,maxPointLat,maxPointLng,group_ids,type_id);
	}
}



function create_group_infoWindow(latitude,longitude,description,group_id,tips,is_adm)
{    
	var pp = new BMap.Point(longitude,latitude);  
	var hg_marker = new HG_Marker(pp,description,group_id,tips,is_adm,{"index":group_id});
	 
	map.addOverlay(hg_marker); 
}
function close_marker(group_id)
{
	this_click = 1;
	var dd = document.getElementById('overlay'+group_id);
	if(dd)
	{
		document.getElementById('overlay'+group_id).style.display = 'none';
	}
}
function show_group_type()
{
	if(document.getElementById("group_type_ul").style.display == "none")
	{
		document.getElementById("group_type_ul").style.display="block";
	}
	else
	{
		document.getElementById("group_type_ul").style.display = "none";
	}
}
 
function change_group_type()
{
	var group_id = document.getElementById("group_region").value;
	var type_id = document.getElementById("group_type").value;
	document.getElementById("group_type_ul").style.display="block";
	document.getElementById("group_type_ul_img").style.display="block";
	document.getElementById("group_type_ul_u").style.display="none";
	if(NOW_MOUDLE == 0){
		if(type_id != curr_type)
		{
			map.clearOverlays();
		}
	}
	if(parseInt(group_id))
	{
		father_group = group_id;
	//	location.href="?m=thread&group_id="+group_id;
	}
	else
	{
		father_group = "";
	//	location.href="";
	}
	show_surrounding(type_id);
}
function change_city_type()
{
	var group_id = document.getElementById("group_region").value;
	var type_id = document.getElementById("group_type").value;
	document.getElementById("group_type_ul").style.display="block";
	document.getElementById("group_type_ul_img").style.display="block";
	document.getElementById("group_type_ul_u").style.display="none";
	if(NOW_MOUDLE == 0){
		if(type_id != curr_type)
		{
			map.clearOverlays();
		}
	}
	if(parseInt(group_id))
	{
		father_group = group_id;
	//	location.href="?m=thread&group_id="+group_id;
	}
	else
	{
		father_group = "";
	//	location.href="";
	}
	show_surrounding(type_id);
}

var curr_id,is_adm;
var timeHandler= new Array(); 
function HG_Marker(latlng, markerContent,g_id,tips,is_adm,option)
{  
	this._latlng = latlng; 
    this._markerContent = markerContent;
    this._gid = g_id; 
	this._clickParamer = option;
	this._tips = tips;
	this._isadm = is_adm;
}
 
HG_Marker.prototype = new BMap.Overlay();
HG_Marker.prototype.initialize = function(map) {  
	var d_latlng = this._latlng; 
	var tt = this._tips;
	is_adm = this._isadm;
	var markerHtml;
	curr_id = this._gid;
	var n_id = this._clickParamer["nowid"];
    markerHtml = document.createElement('div'); 
    markerHtml.id = 'g_'+curr_id;  
    markerHtml.style.position = "absolute";
    markerHtml.style.color="white"; 
	markerHtml.style.cursor="pointer";  
	markerHtml.style.fontSize="12px"; 
	markerHtml.style.height="20px";  
	markerHtml.style.padding="0 1px";
	markerHtml.style.minWidth="104px";
	//markerHtml.style.textAlign="center";
	
	var m_head = document.createElement("span");
	m_head.style.background='url('+livime_images_url+'map/marker_head.gif) no-repeat scroll 1px 0 transparent';
	m_head.style.height="20px";
	m_head.style.width="30px ";
	m_head.style.display="inline-block";
	m_head.style.styleFloat="left";
	m_head.style.cssFloat="left";
	markerHtml.appendChild(m_head);
	
	var m_middle = document.createElement("span");
	m_middle.style.background='#ff8601 repeat scroll 1px 0';
	m_middle.style.height="16px"; 
	m_middle.style.marginTop="2px";
	m_middle.style.styleFloat="left";
	m_middle.style.cssFloat="left";
	m_middle.style.paddingRight="3px";
	m_middle.innerHTML = this._markerContent;
	markerHtml.appendChild(m_middle);
	
// 	
	var markerTip = document.createElement("div");
	markerTip.id='overlay' + this._gid; 
    markerTip.innerHTML = this._tips;
    markerTip.style.position = "absolute"; 
    markerTip.style.display = "block"; 
    
    
    markerTip.onmouseout = function(){
    	timeHandler[markerTip.id] = setTimeout(function(){ 
		    markerTip.style.display = "none";  
    	},100);  
    };
    markerTip.onmouseover = function() {
    	try{
    		clearTimeout(timeHandler[markerTip.id]); 
    	}catch(e){}
    }
    	
	//避免重复append div
	var ch = document.getElementById("g_"+this._gid); 
	if(!ch)
	{
		map.getPanes().markerPane.appendChild(markerHtml);
	}
 
    this._map = map;  
    markerHtml.onclick = function() {  
    	 
        var _id = markerHtml.id.split('_')[1];
        var g_ = document.getElementById("overlay"+_id);
        if(!g_)
        {
        	var p_ = map.pointToOverlayPixel(d_latlng); 
        	markerTip.style.left = (p_.x) -170 + "px";
        	markerTip.style.top = (p_.y) -227 + "px";
        	map.getPanes().floatPane.appendChild(markerTip); 
        	markerTip.style.dispaly = "block";
        }
        else
        {
        	var p_ = map.pointToOverlayPixel(d_latlng); 
        	g_.style.left = (p_.x) -170 + "px";
        	g_.style.top = (p_.y) -227 + "px";
        	document.getElementById("overlay"+_id).style.display = "block";
        }
    }; 
    markerHtml.onmouseout = function() {
    	timeHandler['overlay'.curr_id] = setTimeout(function(){ 
    		try{
    			document.getElementById('overlay'.curr_id).style.display = "none";  
    		}catch(e){
    			
    		};  
    		
    	},100); 
        
    }; 

    this._markerHtml = markerHtml;
    //this._markerTip = markerTip; 
    return markerHtml;
};
HG_Marker.prototype.remove = function() {
	if(this._markerHtml.parentNode)
	{
		this._markerHtml.parentNode.removeChild(this._markerHtml);
	}
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

//判断当前讨论区是否是用户已关注过的讨论区
function js_in_array(needle)
{   
	for(i in my_groups)
	{
		 
		if(eval(my_groups[i].group_id) == needle)
		{
			return needle;
		}
	}
	return false;
}

this_click = 0;