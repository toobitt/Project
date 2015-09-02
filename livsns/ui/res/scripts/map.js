$(document).ready(function(){
	var map,geocoder;
	var userAddress; 
	var point;
	map = new GMap2(document.getElementById('map_canvas'));
	 initialize = function()
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
					
					var centerP = MAP_CENTER_POINT; 
				    var mm = centerP.split("X");
				    var p = new GLatLng(mm[0],mm[1]);
			     	createMap(p,13);
				    
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
				      createMap(p,13);
	        	  }
	        	  else
	        	  {
	        		  createMap(point,13); 
	        	  }
		});
	 }
	 createMap = function(point,zoomsize)
	 {  
	      // 给地图添加内置的控件，分别为： 平移及缩放控件（左上角）、比例尺控件（左下角）、缩略图控件（右下角）
		  map.addControl(new GLargeMapControl());
		  map.addControl(new GScaleControl());//比例尺
		  map.addControl(new GOverviewMapControl());
		  
		  //设定地图中心
		  map.setCenter(point,zoomsize); 
		  map.enableScrollWheelZoom(); //鼠标滚动缩放
		  map.disableDoubleClickZoom();//禁止双击放大
		  //当在地图单击时加入创建一个新的标注
		  GEvent.addListener(map, 'click',function(overlay,point){
			  getThisLocation(point.y,point.x,0);
		   
		  });
		
		  /*GEvent.addListener(map, 'click',function(overlay,point){
			queryAddress('江苏省南京市雨花台区');
		  });*/
		
		  //显示用户之前标注过的地点
	  if(markers)
		{	
			for(id in markers) {
				showMarkers(markers[id].latitude, markers[id].longitude, markers[id].name);    
            }

		}
	 }; 
	 
	 //根据经纬度返回该地的地名（地址）
	 getThisLocation = function(lat,lng,type)
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
	 addAddressToMap = function(response)
	 {
		 
		 if (!response || response.Status.code != 200) {
		        alert("对不起，不能解析这个地址");
		 } else {
		 var place = response.Placemark[0];
		 var pp = new GLatLng(place.Point.coordinates[1],place.Point.coordinates[0]);    
	     var add = place.address; 
         var mapinfo = '<div style="width:220px;height:auto"><span>当前位置：'+add+'</span><br/><span style="float:right;"><a href="javascript:void(0);" onclick="saveData(\''+place.Point.coordinates[1]+'X'+place.Point.coordinates[0]+'\',\''+add+'\');" >标注该地</a></span></div>';
         map.openInfoWindow(pp,mapinfo);
	      }
	 };
	 addAddrToMarker = function(response)
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
	 showMarkers = function(latitude,longitude,description)
	 {
		var marker = new GMarker(new GLatLng(latitude, longitude),{draggable:true});
		var divHtml = document.createElement("div");
		divHtml.style.width = "220px";
		
		var ssp = document.createElement("span");
		ssp.id='show_desc_' + latitude + "X" + longitude;
		ssp.innerHTML = '描述: ' + '<input type="text" style="border:0;font-size:12px;" id="desc_'+latitude + "X" + longitude+'" value="'+description+'"/>' ;
		ssp.style.width = "120px"; 
		divHtml.appendChild(ssp); 
		
		var ssp_r = document.createElement("span");
		ssp_r.id="modify_t";
		ssp_r.innerHTML = '<a href="javascript:void(0);">修改</a>';
		ssp_r.style.float = "right";
		ssp_r.style.color = '#0000cc';
		ssp_r.style.cursor = 'pointer';
		ssp_r.style.width="30px";
		ssp_r.onclick = function()
		{
			this.innerHTML = "<a href='javascript:void(0);' onclick=\"saveData('" + latitude + "X" + longitude + "','',1);\"/>保存</a>"; 
			document.getElementById('desc_'+latitude + 'X' + longitude).style.border = '1px #ccc solid';
			var obj = document.getElementById('desc_'+latitude + 'X' + longitude);
			if(obj.createTextRange)
			{//IE浏览器
				
			    var range = obj.createTextRange();
			    range.moveEnd("character",parseInt(description.length));
			    range.moveStart("character",0);
			    range.select();
			}else{//非IE浏览器
				obj.setSelectionRange(0, 0 + parseInt(description.length));
				obj.focus();
			}
		}
		divHtml.appendChild(ssp_r); 
		
		var hr = document.createElement('hr');
		hr.style.border = 'solid 1px #cccccc';
		divHtml.appendChild(hr); 
		
		// 创建“删除”按钮
		var lnk = document.createElement('div');
		lnk.innerHTML = "删除该地";
		lnk.style.color = '#0000cc';
		lnk.style.cursor = 'pointer';
		lnk.style.textDecoration = 'underline';
		lnk.style.textAlign = "right";
		  
		// 为“删除”按钮添加事件处理：调用 removePoint() 并重新计算距离
		lnk.onclick =
			function() {
			  map.closeInfoWindow();
			  var latlng = marker.getLatLng();
			  var newpoint = latlng.lat()+"X"+latlng.lng();
			  deleteMarker(newpoint);
  			
			  map.removeOverlay(marker);
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
	 };
 	 
	 deleteMarker = function (latlng)
	 {
		 $.ajax({
			url: "map.php",
            type: 'POST',
            dataType: 'html',
   			timeout: TIME_OUT,
   			cache: false,
			data: {
				latlng: latlng,
				a: "delUserLocation"
			},
			success: function(response) {  
				 
				//alert(response);
            },
            error: function() { 
             alert('Ajax error');
            }
			 
		 });
	 };
		saveData = function(latlng,user_description,type) 
		{ 
			var des,user_des;
			//type为1，用户修改了标注地的描述，存储用户修改的描述，为0只存储默认的地址
			if(type == 1)
 			{
				des = '';
				user_des = document.getElementById("desc_"+latlng).value; 
 			}
			else
			{
				des = user_description;
				user_des = '';
			}
// 			alert('des'+des);	
// 			alert('user_des'+user_des);
			$.ajax({ 
				url: "map.php",
	            type: 'POST',
	            dataType: 'html',
	   			timeout: TIME_OUT,
	   			cache: false,
				data: {
					user_location: latlng,
					a: "create",
					description:des,
					user_defined:user_des
				},
				success: function(json) { 
					var ll = latlng.split("X");
					if(!type)
					{
						map.closeInfoWindow();
						showMarkers(ll[0],ll[1],des);
					}
					else
					{
						json = new Function("return" + json)();
						if(json)
						{   
							document.getElementById("desc_" + latlng).blur();
							document.getElementById("desc_" + latlng).value = user_des;
							document.getElementById("desc_" + latlng).style.border = "none";
		 					document.getElementById("modify_t").innerHTML = '<a href="javascript:void(0);">修改</a>';
						}
					}
					
	            },
	            error: function() { 
	             alert('Ajax error');
	            }
	            
			});
		 
			//marker.closeInfoWindow();
	     
	    }
		

 

	 });
 
