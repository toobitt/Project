$(document).ready(function(){	
	var logos = "";
	var logo_n = "";
	
	//网台上传logo
	endUploads = function(json){
		if(json != 'null' && json)
		{
			var obj = new Function("return" + json)();
			
			if(!obj)
			{
				tipsport('尺寸不能小于235*235');
				logo_exit();
			}
			else
			{
				if(obj.logo !="")
				{
					var src = obj.small;	
					if(obj.id)
					{
						$("#new_logo").attr('src',src);
						$("#sta_id").val(obj.id);
					}
					else
					{
						$("#new_logo").attr('src',src);
					}
					$("#logo_o").attr("value",obj.logo);
					logos = obj.logo;
				}
				$("#files").val("");
				$("#get_logo").attr('style','display:inline');
				$("#set_logo").attr('style','display:none');
			}
		}
		else
		{
			tipsport('上传有误！');
			logo_exit();
		}
		$("#station_bt").removeAttr("disabled");
		
		
	}
	
	uploads = function (){
		logo_n = $("#new_logo").attr('src');
		$("#new_logo").attr('src',RESOURCE_DIR+'img/loading.gif');
		$("#new_logo").attr('style','display:inline-block;');
		$("#form1").submit();
		$("#station_bt").attr("disabled","disable");
	}
	
	stationSubmmit = function(){
		if($("#web_station_name").val()=='')
		{
			$("#station_name_tip").show();
		}
		else
		{
			$("#station_name_tip").hide();
			$.ajax({
	            url: re_back,
	            type: 'POST',
	            dataType: 'html',
	   			timeout: TIME_OUT,
	   			cache: false,
	            data: {web_station_name:$('#web_station_name').val(),
					brief:$('#brief').val(),
	        		logo:logos,
	        		tags:$('#tags').val(),
	        		sta_id:$("#sta_id").attr("value"),
	        		logo_o:$("#logo_o").attr("value"),
		        	a: 'changeProgram'
		        	},
	            error: function() {
		        		tipsport('网络延迟！');
	            },
	            success: function(json) {
	            	var obj = new Function("return" + json)();
	            	tipsport('频道设置成功！');
	            	$("#sta_id").attr("value",obj.id);
	            	$("#logo_o").attr("value",obj.logo);
	            	var set_station_name = $("#web_station_name").val();
	            	var set_tags = $("#tags").val();
	        		var set_brief = $("#brief").val();
	        		var set_logo = $("#sta_logo").attr('src');
	        		$("#get_station_name").html(set_station_name);
	        		$("#get_brief").html(set_brief);
	        		$("#get_tags").html(set_tags);
//	        		$("#get_logo img").attr('src',RESOURCE_DIR+'img/loading.gif');
//	        		$("#get_logo img").attr('src',set_logo);
//	        		$("#get_logo img").attr('style','');
	        		$("#set_station").attr('style','display:none');
	        		$("#get_station").attr('style','display:block');	
	            }
	        });	
		}		
	}
	
		$("#web_station_name").focus(function(){
			if($("#web_station_name").val()=='')
			{
				$("#station_name_tip").show();
			}
			else
			{
				$("#station_name_tip").hide();
			}
		});
		$("#web_station_name").blur(function(){
			if($("#web_station_name").val()=='')
			{
				$("#station_name_tip").show();
			}
			else
			{
				$("#station_name_tip").hide();
			}			
		});
	
	stationEdit = function(){
		var get_station_name = $("#get_station_name").html();
		var get_tags = $("#get_tags").html();
		var get_brief = $("#get_brief").html();
		$("#web_station_name").val(get_station_name);
		$("#tags").val(get_tags);
		$("#brief").val(get_brief);
		$("#set_station").attr('style','display:inline-block');
		$("#get_station").attr('style','display:none');	
	}
	
	logoEdit = function(){
		/*var get_logo = $("#get_logo img").attr('src');
		$("#sta_logo").attr('style','display:inline-block');
		$("#sta_logo").attr('src',get_logo);	
		$("#set_logo").attr('style','display:inline-block');
		$("#get_logo").attr('style','display:none');*/
	}
	
	logo_exit = function(){
	//	logo_n = $("#new_logo").attr('src');
		$("#new_logo").attr('src',logo_n);
	}
	
	add_program_out =  function(vid,sid,toff){
		if(!vid)
		{
			tipsport('视频已不存在');
		}
		else
		{
			if(!sid)
			{
				tipsport('请先创建频道！');
			}
			else
			{
				starttime = ($("#start_time").val()==0)?0:(parseInt($("#start_time").val())+gap);
				total = starttime + parseInt(toff);
				
				if(total>(3600*24))
				{
					tipsport('您的日程已满！');
				}
				else
				{
					endtime = encode_time(total);
					$("#s_time").val(toff_time(parseInt(starttime),parseInt(total)));
				}
				$.ajax({
					url:"my_program.php",
					type:"POST",
					dataType:"html",
					cache:false,
					timeout: TIME_OUT,
					data:{
						a:"show_one_html",
						video_id:vid,
						sta_id:sid,
						video_name:$("#vedio_name_container").html(),
						video_brief:$("#video_briefs").val(),
						toff:toff
					},
					success:function(obj){
						close_status_share();
						close_share();
						$('#program_list').html(obj);
						$('#program_list').show();
					},
					error:function(){}
				});
			}
		}
	}
	
	
	close_program_one = function()
	{
		$("#program_list").html('');
		$("#program_list").hide();
	}
	
	add_program_one = function(){
		if($("#p_name").val()=='')
		{
			$("#p_name_tip").show();
		}
		else
		{
			gap = 10;
			starttime = ($("#start_time").val()==0)?0:(parseInt($("#start_time").val())+gap);
			endtime = parseInt(starttime) + parseInt($("#v_toff").val());
			$.ajax({
		        url: "my_program.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "create",
					sta_id: $("#s_id").val(),
					video_id: $("#v_id").val(),
					program_name: $("#p_name").val(),
					brief: $("#p_brief").val(),
					start_time: parseInt(starttime),
					end_time: parseInt(endtime)	
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json)
		        	{
		        		$("#program_bt").html('<a href="javascript:void(0);">编单成功</a>');
		        		close_program_one();
		        	}
		        	else
		        	{
		        		tipsport('编单失败');
		        	}	
		        		
		        }
			});
		}
	}
	
	if($("#idBox").html()!=null)	
	{
		//间隔时间
		var gap = 10;
		var ab = new AlertBox("idBox"), lock = false;
		function lockup(e){ e.preventDefault(); }
		function lockout(e){ e.stopPropagation(); }
		ab.onShow = function(){			
			if ( lock ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		ab.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("idBoxClose").onclick = function(){ ab.close(); }
		add_program = function(vid,sid,toff){
			if(!vid&&!sid&&!toff)
			{
				if(ab.center){
						ab.center = true;
						lock = true;
					} else {
						ab.center = true;
						lock = true;
					}
				ab.show();
			}
			else
			{
				if(!sid)
				{
					ab.close();
					tipsport('请先创建频道！');
				}
				else
				{
					$("#v_name").val($("#v_"+vid).html());
					$("#p_name").val($("#v_"+vid).html());
					$("#v_id").val(vid);
					$("#s_id").val(sid);
					$("#v_toff").val(toff);
					
					starttime = ($("#start_time").val()==0)?0:(parseInt($("#start_time").val())+gap);
					total = starttime + parseInt(toff);
					
					if(total>(3600*24))
					{
						tipsport('您的日程已满！');
					}
					else
					{
						endtime = encode_time(total);
						$("#s_time").val(toff_time(parseInt(starttime),parseInt(total)));
					}
				}
			}
		}
		
		$$("add_program_bt").onclick = function(){
			starttime = ($("#start_time").val()==0)?0:(parseInt($("#start_time").val())+gap);
			endtime = parseInt(starttime) + parseInt($("#v_toff").val());
			if($("#p_name").val()=='')
			{
				$("#p_name_tip").show();
			}
			else
			{
				$("#p_name_tip").hide();
				$.ajax({
			        url: "my_program.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "create",
						sta_id: $("#s_id").val(),
						video_id: $("#v_id").val(),
						program_name: $("#p_name").val(),
						brief: $("#p_brief").val(),
						start_time: parseInt(starttime),
						end_time: parseInt(endtime)	
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	var obj = new Function("return" + json)();
			        	$("#start_time").val(endtime);
			        	$("#end_time").val(endtime);	
						li = '<li class="menu"><div class="program_name_title">名称</div>'+
							'<div class="program_time_title">时长</div>'+
						'<div class="program_manage_title">管理</div></li>';
			        	$.each(obj,function(k,v){
			        		li +='<li id="p_show_'+v.id+'" onmouseover="edit_del('+v.id+',1);" onmouseout="edit_del('+v.id+',0);">'+
							'<div class="program_name"><span></span><a target="_blank" title="'+v.programe_name+'" id="program_name_'+v.id+'" href="station_play.php?sta_id='+v.sta_id+'#'+v.id+'">'+v.programe_name.substr(0,10)+'</a></div>'+
							'<div class="program_time"><img src="'+RESOURCE_DIR+'img/play_default.png"/>'+toff_time(v.start_time,v.end_time)+'</div>'+
							'<div class="program_manage" id="p_'+v.id+'">'+
							'<a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',1);">编辑</a>'+
							'<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',2);">删除</a>';
							if(k!=0)
							{
								li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',3);">上移</a>';
							}
							if(k!=(obj.length-1))
							{
								li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',4);">下移</a>';
							}
							li +='</div></li><li id="p_edit_'+v.id+'" style="display:none"><ul><li><input id="p_name_'+v.id+'" type="text" value="'+v.programe_name+'"/><span id="p_name_tip_'+v.id+'" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>'+
								'<li><textarea id="p_brief_'+v.id+'" rows="3" cols="21">'+v.brief+'</textarea></li>'+
								'<li><input type="button" value="提交" id="eidt_bt_'+v.id+'"/><input type="button" value="取消" onclick="program_back('+v.sta_id+','+v.id+')"/></li></ul></li>';
			        	});
			        	$("#program_list").html(li);  	
			        	ab.close();
			    		tipsport('新增成功！');
			    		$("#p_name").val('');
						$("#p_brief").val('');
			        }
			    });
			}
		}
		
		$("#reset_program").click(function(){
			$("#s_time").val('');
			$("#v_name").val('');
			$("#p_name").val('');
			$("#p_brief").val('');
		});
		
		$("#p_name").focus(function(){
			if($("#p_name").val()=='')
			{
				$("#p_name_tip").show();
			}
			else
			{
				$("#p_name_tip").hide();
			}
		});
		$("#p_name").blur(function(){
			if($("#p_name").val()=='')
			{
				$("#p_name_tip").show();
			}
			else
			{
				$("#p_name_tip").hide();
			}			
		});
		
		//视频列表的ajax操作-------------start
		page_show = function(e,type){
			str = e.id;
			strs=str.split("_"); //字符分割 
			pp = strs[strs.length-1];
			title = "";
			if($("#video_search"))
			{
				title = $("#video_search").val();			
			}
			$.ajax({
		        url: "my_program.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "videolist",
					type:type,
					title:title,
					pp: pp
		        	},
		        error: function() {
		        		ab.close();
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json)
		        	{
		        		$("#video_list").html(json);
		        	}
		        	else
		        	{
		        		ab.close();
		        		tipsport('网络延迟！');
		        	}
		        }
		    });
			
		}
		
		search_video = function(e)
		{
			str = e.id;
			strs=str.split("_"); //字符分割 
			pp = strs[strs.length-1];
			title = "";
			if($("#video_search"))
			{
				title = $("#video_search").val();			
			}
			$.ajax({
		        url: "my_program.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "search_video",
					title:title,
					pp:pp
		        	},
		        error: function() {
		        		ab.close();
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json)
		        	{
		        		$("#video_list").html(json);
		        	}
		        	else
		        	{
		        		ab.close();
		        		tipsport('网络延迟！');
		        	}
		        }
		    });
		}
		
		tab_video = function(type){
			$.ajax({
		        url: "my_program.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "videolist",
					type:type
		        	},
		        error: function() {
		        		ab.close();
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json)
		        	{
		        		$("#video_list").html(json);
		        	}
		        	else
		        	{
		        		ab.close();
		        		tipsport('网络延迟！');
		        	}
		        }
		    });
		}
		
		//视频列表的ajax操作-------------end
	}

	edit_del = function(id,type){
		/*
		if(type==1)
		{
			$('#p_'+id).show();
		}
		if(type==0)
		{
			$('#p_'+id).hide();
		}*/
	}
	
	

	
	
	
	
	//专辑操作------------------------start
	 var param = {
			 show_album_html:'',	//保存专辑列表的临时html
			 album_name:'',			//专辑名（新建/修改）
			 album_brief:'',		//专辑简介（新建/修改）
			 album_sort:'', 		//专辑类型ID（新建/修改）
			 upload_video:'',		//我上传的视频id（选择视频）
			 favorite_video:'',		//我收藏的视频id（选择视频）
			 search_video:'',		//我检索的视频id（选择视频）
			 album_video:'',		//视频ID
			 album_video_n:'',		//编辑时用于比较的数据
			 album_id:'',			//专辑ID（临时存储）
			 album_total:'',		//视频总数
			 name:''				//检索关键字
	 };
	
	clear_album = function(){
		param = {
				 album_name:'',
				 album_brief:'',
				 album_sort:'',
				 upload_video:'',
				 favorite_video:'',
				 search_video:'',
				 album_video:'',
				 album_video_n:'',//编辑时用于比较的数据
				 album_id:'',
				 album_total:'',
				 name:''
		 }
	}
	
	album_mouse = function(id,type)
	{
		if(id)
		{
			obj = $("#album_ma_"+id);
			objs = $("#album_na_"+id);
			switch(type)
			{
				case 0:
						objs.hide();
						obj.show();
					break;
				case 1:
						objs.show();
						obj.hide();
					break;
				default:
					break;
			}
		}
	}
	
	 
	create_album = function(state){
//		alert(param.album_html);
		if(state)
		{
			clear_album();
		}	
		
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "show_html",
				type:2
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
        		param.show_album_html = json;
        		$.ajax({
        	        url: "my_album.php",
        	        type: 'POST',
        	        dataType: 'html',
        			timeout: TIME_OUT,
        			cache: false,
        	        data: {
        				a: "show_html",
        				album_name:param.album_name,
        				album_brief:param.album_brief,
        				album_sort:param.album_sort,
        				type:1
        	        	},
        	        error: function() {
        	        		tipsport('网络延迟！');
        	        },
        	        success: function(json) {
        	        		$("#album_info").html(json);
        	        }
        	    });
	        }
	    });
	}
	
	return_album = function()
	{
//		param.create_html = $("#album_info").html();
		param.album_name = $("#album_name").val();
		param.album_brief = $("#album_brief").val();
		param.album_sort = $("input[name='album_sort[]']:checked").val();
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "show_html",
				type:2
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        		$("#album_info").html(json);
	        }
	    });
	}
	
	select_video = function()
	{
		param.album_name = $("#album_name").val();
		param.album_brief = $("#album_brief").val();
		param.album_sort = $("input[name='album_sort[]']:checked").val();
		if(!$("#album_name").val())
		{
			tipsport('标题不能为空！');
		}
		else
		{
//			param.create_html = $("#album_info").html();
			$.ajax({
		        url: "my_album.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "show_html",
					upload_video:param.upload_video,
					favorite_video:param.favorite_video,
					search_video:param.search_video,
					name:param.name,
					type:3
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
//		        	alert(json);
		        		$("#album_info").html(json);
		        }
		    });
		}
	}
	
	//这里的修改是指创建时候的修改
	edit_album = function()
	{
		param.name = $("#album_video").val()?$("#album_video").val():'';
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "show_html",
				album_name:param.album_name,
				album_brief:param.album_brief,
				album_sort:param.album_sort,
				type:1
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        		$("#album_info").html(json);
	        }
	    });
	}
	
	
	
	album_page_show = function(e,type)
	{
		str = e.id;
		strs=str.split("_"); //字符分割 
		pp = strs[strs.length-1];
		var page_video = '';
		param.name = $("#album_video").val()?$("#album_video").val():'';
		param.album_id = $("#album_id").val();
		switch(type)
		{
			case 1:
				page_video = param.upload_video;
				break;
			case 2:
				page_video = param.favorite_video;
				break;
			case 3:
				page_video = param.search_video;
				break;
			case 4:
				page_video = param.album_video;
				break;
			case 5:
				page_video = "";
				break;
			default:
				break;
		}
		
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "video_list",
				type:type,
				page_video:page_video,
				name:param.name,
				album_id:param.album_id,
				pp: pp
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        	if(json)
	        	{
	        		switch(type)
	        		{
	        			case 1:
	    	        		$("#upload_list").html(json);
	        				break;
	        			case 2:
	    	        		$("#favorite_list").html(json);
	        				break;
	        			case 3:
	    	        		$("#search_list").html(json);
	        				break;
	        			case 4:
	    	        		$("#album_info").html(json);
	        				break;
	        			case 5:
	    	        		$("#album_list").html(json);
	        				break;
	        			default:
	        				break;
	        		}
	        	}
	        	else
	        	{
	        		tipsport('网络延迟！');
	        	}
	        }
	    });
	}
	
	select_upload = function()
	{
		$("#upload_list").toggle();
	}
	
	select_favorite = function()
	{
		$("#favorite_list").toggle();
	}
	
	check_list = function(e,type){
		id = e.value+",";
		switch(type)
		{
			case 1:
				if(e.checked)
				{
					param.upload_video +=id;
				}
				else
				{
					param.upload_video = param.upload_video.replace(id,'');
				}	
				break;
			case 2:
				if(e.checked)
				{
					param.favorite_video +=id;
				}
				else
				{
					param.favorite_video = param.favorite_video.replace(id,'');
				}	
				break;
			case 3:
				if(e.checked)
				{
					param.search_video +=id;
				}
				else
				{
					param.search_video = param.search_video.replace(id,'');
				}	
				break;
			case 4:
				
				if(e.checked)
				{
					param.album_video +=id;
					if(param.album_video)
					{
						$("input[name='get']").attr("disabled",false);
					}
				}
				else
				{
					param.album_video = param.album_video.replace(id,'');
					if(!param.album_video)
					{
						$("input[name='get']").attr("disabled",true);
					}
				}	
				break;
			default:
				break;
		}	
		
	}
	
	search_album_video = function(){
		name = $("#album_video").val();
		if(!name)
		{
			tipsport('关键词不能为空！');
		}
		else
		{
			$.ajax({
		        url: "my_album.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "search_video",
					name: name
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	$("#search_result").html(json);	
		        }
		    });	
		}
	}
	
	album_reset = function(){
		$("#album_name").val('');
		$("#album_brief").val('');
		$("#r_1").attr('checked','true');
		
	}
	
	album_bt = function(album_id){
		var video_id = param.upload_video+param.favorite_video+param.search_video;
		if(!video_id)
		{
			tipsport('请选择视频');
		}
		else
		{
			if(!album_id)
			{
				$.ajax({
			        url: "my_album.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "create_album",
						name: param.album_name,
						brief: param.album_brief,
						sort_id: param.album_sort,
						video_id: video_id
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	if(json)
			        	{
			        		$("#album_info").html(json);
			        	}
			        	else
			        	{
			        		tipsport('创建失败！');
			        		location.href="my_album.php";
			        	}	
			        }
			    });	
			}
			else
			{
				$.ajax({
			        url: "my_album.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "edit_album",
						name: param.album_name,
						brief: param.album_brief,
						sort_id: param.album_sort,
						album_id: album_id,
						video_id: video_id
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	if(json)
			        	{
			        		$("#album_info").html(json);
			        	}
			        	else
			        	{
			        		tipsport('创建失败！');
			        		location.href="my_album.php";
			        	}	
			        }
			    });	
			}
			
		}
		
	}
	
	manage_album_video = function(id){
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "get_album_video",
				album_video: param.album_video,
				album_id: id
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        	if(json)
	        	{
	        		$("#album_info").html(json);
	        	}
	        }
	    });	
	}
	
	add_album_video = function(){
		clear_album();
		param.album_id = $("#album_id").val();
		param.album_name = $("#album_name").val();
		param.album_brief = $("#album_brief").val();
		param.album_sort = $("#album_sort").val();
		param.album_total = $("#album_total").val();
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "show_html",
				album_name:param.album_name,
				album_total:param.album_total,
				album_id:param.album_id,
				type:4
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        		$("#album_info").html(json);
	        }
	    });
	}
	
	edit_album_cover = function(video_id,album_id){
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "edit_album_cover",
				album_id:album_id,
				video_id:video_id
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        	if(json)
        		{
	        		tipsport('设置成功！');
        		}
	        	else
	        	{
	        		tipsport('设置失败！');
	        	}	
	        }
	    });
	}

	del_album_video = function(id){
		if(confirm('确定将此记录移除?'))
		{
			if(!id)
			{
				id = param.album_video;
				id = id.slice(0,id.length-1);
			}
			param.album_id = $("#album_id").val();
			$.ajax({
		        url: "my_album.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "del_album_video",
					album_id:param.album_id,
					id:id
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json)
	        		{
		        		tipsport('移除成功！');
		        		param.album_video = "";
		        		manage_album_video(param.album_id);
	        		}
		        	else
		        	{
		        		tipsport('移除失败！');
		        	}	
		        }
		    });
		}
	}
	
	if($("#albumVideo").html()!=null)	
	{
		var al = new AlertBox("albumVideo"), lock = false;
		function lockup(e){ e.preventDefault(); }
		function lockout(e){ e.stopPropagation(); }
		al.onShow = function(){			
			if ( lock ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		al.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("AlbumClose").onclick = function(){ al.close(); }
		
		move_album_show = function(id){
			if(al.center){
				al.center = true;
				lock = true;
			}
			else {
				al.center = true;
				lock = true;
			}
			al.show();
		}
		
		move_album_video = function(e){
			if(confirm('确定将此记录转移?'))
			{
				album_id = $("#album_id").val();
				$.ajax({
			        url: "my_album.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "move_album_video",
						album_id:album_id,
						album_id_n:e.value,
						video_id:param.album_video.slice(0,param.album_video.length-1)
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	if(json)
		        		{
			        		al.close();
			        		tipsport('移除成功！');
			        		param.album_video = "";
			        		$("input[name='alb']:checked").attr("checked",false);
			        		manage_album_video(album_id);
		        		}
			        	else
			        	{
			        		tipsport('移除失败！');
			        	}	
			        }
			    });
			}
		}
	}
	
	
	
	
	edit_album_info = function(id){
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "get_album_info",
				album_id:id
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        	var obj = new Function("return" + json)();
	        	if(obj.id)
        		{
	        		clear_album();
	        		param.album_id = obj.id;
	        		param.album_name = obj.name;
	        		param.album_brief = obj.brief;
	        		param.album_sort = obj.sort_id;
	        		param.album_total = obj.total;
	        		$.ajax({
				        url: "my_album.php",
				        type: 'POST',
				        dataType: 'html',
						timeout: TIME_OUT,
						cache: false,
				        data: {
							a: "show_html",
							album_name: obj.name,
							album_brief: obj.brief,
							album_sort: obj.sort_id,
							type:5
				        	},
				        error: function() {
				        		tipsport('网络延迟！');
				        },
				        success: function(json) {
				        	if(json)
				        	{
				        		$("#album_info").html(json);
				        	}
				        	else
				        	{
				        		tipsport('创建失败！');
				        		location.href="my_album.php";
				        	}	
				        }
				    });	
        		}
	        }
	    });
	}
	
	
	edit_album_video = function(){
		param.album_name = $("#album_name").val();
		param.album_brief = $("#album_brief").val();
		param.album_sort = $("input[name='album_sort[]']:checked").val();
		$.ajax({
	        url: "my_album.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "show_html",
				album_name:param.album_name,
				album_total:param.album_total,
				album_id:param.album_id,
				type:4
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        		$("#album_info").html(json);
	        }
	    });
	}
	
	del_album = function(id){
		if(confirm('确定将此记录删除?'))
		{
			$.ajax({
		        url: "my_album.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "del_album",
					album_id:id
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json)
	        		{
		        		$("#album_info").html(json);
		        		tipsport('移除成功！');
	        		}
		        	else
		        	{
		        		tipsport('移除失败！');
		        	}	
		        }
		    });
		}
	}
	
	//专辑操作------------------------end
	program_back = function(sta_id,id,type){
		$p_show = $("#p_show_"+id);
		$p_edit = $("#p_edit_"+id);
		$p_show.show();
		$p_edit.hide();
	}
	
	
		
	program = function(sta_id,id,type){
		$p_show = $("#p_show_"+id);
		$p_edit = $("#p_edit_"+id);
		$eidt_bt = $("#eidt_bt_"+id);
		switch(type)
		{
			case 1://edit
				$p_show.hide();
				$p_edit.show();
				$("#p_name_"+id).focus(function(){
					if($("#p_name_"+id).val()=='')
					{
						$("#p_name_tip_"+id).show();
					}
					else
					{
						$("#p_name_tip_"+id).hide();
					}	
				});
				$("#p_name_"+id).blur(function(){
					if($("#p_name_"+id).val()=='')
					{
						$("#p_name_tip_"+id).show();
					}
					else
					{
						$("#p_name_tip_"+id).hide();
					}	
				});
				$eidt_bt.click(function(){
					if($("#p_name_"+id).val()=='')
					{
						$("#p_name_tip_"+id).show();
					}
					else
					{
						$.ajax({
					        url: "my_program.php",
					        type: 'POST',
					        dataType: 'html',
							timeout: TIME_OUT,
							cache: false,
					        data: {
								a: "edit",
								program_id: id,
								program_name: $("#p_name_"+id).val(),
								brief: $("#p_brief_"+id).val()
					        	},
					        error: function() {
					        		tipsport('网络延迟！');
					        },
					        success: function(json) {
					        	var obj = new Function("return" + json)();
					        	if(obj.id != "")
				        		{
					        		$("#program_name_"+id).html(obj.program_name);
					        		$("#p_name_"+id).val(obj.program_name);
									$("#p_brief_"+id).val(obj.brief);
					        		$p_show.show();
									$p_edit.hide();
				        		}
					        	else
					        	{
					        		tipsport('更新有误！');
					        	}
					        }
					    });	
					}
				});
				break;
			case 2://del
				tipsport('正在删除中...');
				$.ajax({
			        url: "my_program.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "delete",
						program_id: id,
						sta_id: sta_id,
						gap:gap
			        	},
			        error: function() {
			        		tipsport('删除有误！');
			        },
			        success: function(json) {
			        	tips.close();
						li = '<li class="menu"><div class="program_name_title">名称</div>'+
							'<div class="program_time_title">时长</div>'+
						'<div class="program_manage_title">管理</div></li>';
			        	if(json == 'null')
		        		{
			        		li += '<li id="default_list">默认分类</li>';
		        		}
			        	else
			        	{
				        	var obj = new Function("return" + json)();
				        	$.each(obj,function(k,v){
				        		li +='<li id="p_show_'+v.id+'" onmouseover="edit_del('+v.id+',1);" onmouseout="edit_del('+v.id+',0);">'+
								'<div class="program_name"><span></span><a target="_blank" title="'+v.programe_name+'" id="program_name_'+v.id+'" href="station_play.php?sta_id='+v.sta_id+'#'+v.id+'">'+v.programe_name.substr(0,10)+'</a></div>'+
								'<div class="program_time"><img src="'+RESOURCE_DIR+'img/play_default.png"/>'+toff_time(v.start_time,v.end_time)+'</div>'+
								'<div class="program_manage" id="p_'+v.id+'">'+
								'<a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',1);">编辑</a>'+
								'<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',2);">删除</a>';
								if(k!=0)
								{
									li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',3);">上移</a>';
								}
								if(k!=(obj.length-1))
								{
									li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',4);">下移</a>';
								}
								li +='</div></li><li id="p_edit_'+v.id+'" style="display:none"><ul><li><input id="p_name_'+v.id+'" type="text" value="'+v.programe_name+'"/><span id="p_name_tip_'+v.id+'" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>'+
									'<li><textarea id="p_brief_'+v.id+'" rows="3" cols="21">'+v.brief+'</textarea></li>'+
									'<li><input type="button" value="提交" id="eidt_bt_'+v.id+'"/><input type="button" value="取消" onclick="program_back('+v.sta_id+','+v.id+')"/></li></ul></li>';
				        	});
			        	}
			        	$("#program_list").html(li);
			        }
			    });	
				break;
			case 3://up
//				tipsport('调整中...');
				$.ajax({
			        url: "my_program.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "move",
						program_id: id,
						sta_id: sta_id,
						action: 0,
						gap:gap
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	var obj = new Function("return" + json)();
			        	tips.close();
						li = '<li class="menu"><div class="program_name_title">名称</div>'+
							'<div class="program_time_title">时长</div>'+
						'<div class="program_manage_title">管理</div></li>';
			        	$.each(obj,function(k,v){
			        		li +='<li id="p_show_'+v.id+'" onmouseover="edit_del('+v.id+',1);" onmouseout="edit_del('+v.id+',0);">'+
							'<div class="program_name"><span></span><a target="_blank" title="'+v.programe_name+'" id="program_name_'+v.id+'" href="station_play.php?sta_id='+v.sta_id+'#'+v.id+'">'+v.programe_name.substr(0,10)+'</a></div>'+
							'<div class="program_time"><img src="'+RESOURCE_DIR+'img/play_default.png"/>'+toff_time(v.start_time,v.end_time)+'</div>'+
							'<div class="program_manage" id="p_'+v.id+'">'+
							'<a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',1);">编辑</a>'+
							'<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',2);">删除</a>';
							if(k!=0)
							{
								li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',3);">上移</a>';
							}
							if(k!=(obj.length-1))
							{
								li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',4);">下移</a>';
							}
							li +='</div></li><li id="p_edit_'+v.id+'" style="display:none"><ul><li><input id="p_name_'+v.id+'" type="text" value="'+v.programe_name+'"/><span id="p_name_tip_'+v.id+'" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>'+
								'<li><textarea id="p_brief_'+v.id+'" rows="3" cols="21">'+v.brief+'</textarea></li>'+
								'<li><input type="button" value="提交" id="eidt_bt_'+v.id+'"/><input type="button" value="取消" onclick="program_back('+v.sta_id+','+v.id+')"/></li></ul></li>';
			        	});
			        	$("#program_list").html(li);
			        }
			    });	
				break;
			case 4://down
//				tipsport('调整中...');
				$.ajax({
			        url: "my_program.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "move",
						program_id: id,
						sta_id: sta_id,
						action: 1,
						gap:gap
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	var obj = new Function("return" + json)();
			        	tips.close();
						li = '<li class="menu"><div class="program_name_title">名称</div>'+
							'<div class="program_time_title">时长</div>'+
						'<div class="program_manage_title">管理</div></li>';
			        	$.each(obj,function(k,v){
			        		li +='<li id="p_show_'+v.id+'" onmouseover="edit_del('+v.id+',1);" onmouseout="edit_del('+v.id+',0);">'+
							'<div class="program_name"><span></span><a target="_blank" title="'+v.programe_name+'" id="program_name_'+v.id+'" href="station_play.php?sta_id='+v.sta_id+'#'+v.id+'">'+v.programe_name.substr(0,10)+'</a></div>'+
							'<div class="program_time"><img src="'+RESOURCE_DIR+'img/play_default.png"/>'+toff_time(v.start_time,v.end_time)+'</div>'+
							'<div class="program_manage" id="p_'+v.id+'">'+
							'<a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',1);">编辑</a>'+
							'<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',2);">删除</a>';
							if(k!=0)
							{
								li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',3);">上移</a>';
							}
							if(k!=(obj.length-1))
							{
								li += '<span>|</span><a href="javascript:void(0);" onclick="program('+v.sta_id+','+v.id+',4);">下移</a>';
							}
							li +='</div></li><li id="p_edit_'+v.id+'" style="display:none"><ul><li><input id="p_name_'+v.id+'" type="text" value="'+v.programe_name+'"/><span id="p_name_tip_'+v.id+'" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>'+
								'<li><textarea id="p_brief_'+v.id+'" rows="3" cols="21">'+v.brief+'</textarea></li>'+
								'<li><input type="button" value="提交" id="eidt_bt_'+v.id+'"/><input type="button" value="取消" onclick="program_back('+v.sta_id+','+v.id+')"/></li></ul></li>';
			        	});
			        	$("#program_list").html(li);
			        }
			    });
				break;
			default:
				break;
		
		}
		
	}
	
	function encode_time(total){
		var hou=min=sec=0;
		if(parseInt(total)>60)
		{
			if(parseInt(total)>3600)
			{
				hou = Math.floor(parseInt(total)/3600);
				mins = parseInt(total) - hou * 3600;
				min = Math.floor(parseInt(mins)/60);
				sec = parseInt(mins) - min * 60;
			}
			else
			{
				min = Math.floor(parseInt(total)/60);
				sec = parseInt(total) - min * 60;
			}
		}
		else
		{
			sec = parseInt(total);
		}
		hou = (hou<10)?'0'+hou:hou;
		min = (min<10)?'0'+min:min;
		sec = (sec<10)?'0'+sec:sec;
		return hou+':'+min;
	}
	
	function toff_time(start_time,end_time)
	{
		total = parseInt(end_time) - parseInt(start_time);
		var hou=min=sec=0;
		if(parseInt(total)>60)
		{
			if(parseInt(total)>3600)
			{
				hou = Math.floor(parseInt(total)/3600);
				mins = parseInt(total) - hou * 3600;
				min = Math.floor(parseInt(mins)/60);
				sec = parseInt(mins) - min * 60;
			}
			else
			{
				min = Math.floor(parseInt(total)/60);
				sec = parseInt(total) - min * 60;
			}
		}
		else
		{
			sec = parseInt(total);
		}
		hou = (hou<10)?'0'+hou:hou;
		min = (min<10)?'0'+min:min;
		sec = (sec<10)?'0'+sec:sec;
		ret = "";
		if(sec)
		{
			ret =sec+"秒";
			if(min)
			{
				ret =min+"分"+ret;
				if(hou)
				{
					if(hou<24)
					{
						ret =hou+"时"+ret;
					}
				}
			}
		}
		return ret;
	}
	
/**
 * 关灯，开灯
 */	
	lamps = function(id,type)
	{
		if(type)
		{
			la = '<a href="javascript:void(0);" onclick="lamps(2,0);">关灯</a>';
			re = /close/g;   
			ss = $("#lamp").attr('href');
			r = ss.replace(re,"open"); 
			$("#lamp").attr('href',r);
		}
		else
		{
			la = '<a href="javascript:void(0);" onclick="lamps(2,1);">开灯</a>';
			re = /open/g;   
			ss = $("#lamp").attr('href');
			r = ss.replace(re,"close"); 
			$("#lamp").attr('href',r);
		}
		if($$("#la_"+id).html())
		{
			$("#la_"+id).html(la);
		}
	}

	
	if($("#idVideo").html()!=null)	
	{
		var video = new AlertBox("idVideo"), lock = false;
		function lockup(e){ e.preventDefault(); }
		function lockout(e){ e.stopPropagation(); }
		video.onShow = function(){			
			if ( lock ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		video.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("idVideoClose").onclick = function(){ video.close(); }
		
		
		edit_video = function(id){
			/* 获取视频信息  */
			var title = $('#video_title_' + id).val();
			var tags = $('#video_tags_' + id).val();
			var copyright = $('#video_copyright_' + id).val();
			var sort = $('#video_sort_' + id).val();
			var brief = $('#video_brief_' + id).val();
			var schematic = $('#video_schematic_' + id).val();
						
			$('#edit_title').val(title);
			$('#edit_tag').val(tags);
			
			var cr = $("input[name='edit_copyright']");
			cr[copyright].checked = true; 
			
			var so = $("input[name='edit_sort']");
			
			for(i=1;i<(so.length+1);i++)
			{
				if($(so[i]).val() == sort)
				{
					so[i].checked = true;
				}
			}
			
			$('#edit_brief').text(brief);
			
			$('#edit_id').val(id);
			$("#schematic").val(schematic);
			$("#show_img").attr('src',schematic);
						
			if(video.center){
				video.center = true;
				lock = true;
			}
			else {
				video.center = true;
				lock = true;
			}
			video.show();
		}
		
		edit_video_image = function(){
			$("#video_ids").val( $('#edit_id').val());
			$("#form1").submit();	
			$("#show_img").attr('src',RESOURCE_DIR+'img/loading.gif');
			$("#show_img").show();
		}
		
		end_edit_image = function(json)
		{
			var obj = new Function("return" + json)();
			$("#show_img").attr('src',obj.normal);
			$("#video_img_"+obj.id).attr('src',obj.normal);
			$("#schematic").val(obj.ori);
		}
		
		edit_bt = function(){
			video.close();
			var video_title = $('#edit_title').val();
			var video_tag = $('#edit_tag').val();
			var video_copyright = $("input[name='edit_copyright']:checked").val();
			var video_sort = $("input[name='edit_sort']:checked").val();
			var video_brief = $('#edit_brief').text();
			var video_id = $('#edit_id').val();
			
			$.ajax({
				url: "my_video.php",
				type: 'POST',
				dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
				data: {a: "update",
		 video_title: video_title,
		   video_tag: video_tag,
	 video_copyright: video_copyright,
	 	  video_sort: video_sort,
		 video_brief: video_brief,
		    video_id: video_id
				},
				error: function(){
					tipsport('网络延迟！');
				},
				success: function(response){
					
					//alert(response);
					tipsport('OK');
				}	
				});			 
		}
	}
	
	if($("#previewVideo").html()!=null)	
	{
		var pre_video = new AlertBox("previewVideo"), lock = false;
		function lockup(e){ e.preventDefault(); }
		function lockout(e){ e.stopPropagation(); }
		pre_video.onShow = function(){			
			if ( lock ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		pre_video.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("VideoClose").onclick = function(){ pre_video.close(); }
		preview_video = function(id){
		    var width = 500;
		    var height = 350;
		    var autostart=true;
		    var dar_value=1.5;
		    var video_address = $('#video_flv_url_' + id).val();
		    var tvie_player = new TViePlayer(0, "flashdiv", width + "x" + height, "./res/swf/vod_player.swf", { mode: "SimpleVOD", 
				   fileurl: video_address , autostart:true , dar: dar_value});			
			if(pre_video.center){
				pre_video.center = true;
				lock = true;
			}
			else {
				pre_video.center = true;
				lock = true;
			}
			pre_video.show();
		}
	}
	
	
	/*tipsmessage*/
	if($("#tips").html()!=null)	
	{
		var tips = new AlertBox("tips"),locks = false;
		function lockup(e){ e.preventDefault(); };
		function lockout(e){ e.stopPropagation(); };
		tips.onShow = function(){
			if ( locks ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		tips.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("tipsCloses").onclick = function(){ tips.close(); }
		$$("tipsClose").onclick = function(){ tips.close(); }	
		tipsport = function(text){
			$("#tipscon").html(text);
			if(tips.center){
				tips.center = true;
				locks = true;
			} else {
				tips.center = true;
				locks = true;
			}
			tips.show();	
		}
	}
	
	if($("#pub_to_g").html())
	{
		var pub = new AlertBox("pub_to_g"), lock = true;
		function lockup(e){ e.preventDefault(); }
		function lockout(e){ e.stopPropagation(); }
		pub.onShow = function(){ 
			if ( lock ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		};
		pub.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		};
		//显示我关注的讨论区
		showMyGroups = function(id)
		{
			$("#pub_to_g_btn").onClick = function(){pub.close();}; 
			pub_close = function(){pub.close();};
				$("#p_t_gs_div").show();
				$.ajax({
					url:'get_groups.php',
					type:'POST',
					dataType:"html",
					cache:false,
					timeout: TIME_OUT,
					data:{
						a:"getMyGroups",
						title:$("#video_name_"+id).html(),
						id:id
					},
					success:function(json){
						if(json != 0)
						{
							$("#p_t_gs_div").html(json); 
						}
						pub.show();
					},
					error:function(){
						tipsport('网络延迟！');
					}
				});
			
			 
			
		};
		changeCss = function(obj)
		{  	
			if(!$(obj).hasClass('act_g'))
			{
				$(obj).addClass('act_g'); 
			}
			else
			{
				$(obj).removeClass('act_g'); 
			}
		};
		
		//选择讨论区
		choose_groups = function(group_id,group_name)
		{ 
			var vv = $("#sel_gp_name").val();
			if(PUBLISH_TO_MULTI_GROUPS > 0)
			{
				//允许发布到多个讨论区,配置的数目即是能够发布的最多的讨论区数目 
				$("#sel_gp_name").val(vv); 
				var ch = $("#sel_re_span").children();
				var fl = 0;
				$.each(ch,function(k){
					if($(ch[k]).attr("id") == "chk_gps_"+group_id){
						fl = fl + 1;
					}
					 
				});
				if(fl == 0){
					var sel_re = $("#sel_re_span").children(); 
					if(sel_re.length > PUBLISH_TO_MULTI_GROUPS - 1)
					{
						tipsport('您只能选择' + PUBLISH_TO_MULTI_GROUPS + '个讨论区');
					}
					else
					{
						var re1 = $('<span></span>');
						re1.attr("id","chk_gps_"+group_id);
						re1.attr("className",'group_result'); 
						re1.html('<input type="checkbox" name="groups[]" value="'+group_id+'" id="chk_'+group_id+'" checked="checked" onclick="remove_groups('+group_id+')" />'+group_name);
						re1.onClick=function(){remove_groups(group_id);};
						re1.onMouseover = function(){this.css('background-color',"#ffa22e");};
						re1.onMouseout = function(){$(this).css('background-color',"#fff");};
						$("#sel_re_span").append(re1);
						 
						vv += group_id+',';
						$("#sel_gp_name").val(vv);
					}
				}
			} 
	 			
		};
		remove_groups = function(group_id)
		{
			if(!$("#chk_"+group_id).attr("checked")){
				$("#chk_gps_"+group_id).remove();
			}
			var sel_gp = $("#sel_gp_name").val();
			if(PUBLISH_TO_MULTI_GROUPS > 0){
				sel_gp = sel_gp.replace(group_id+',','');
				$("#sel_gp_name").val(sel_gp);
			} 
			
		};
	 
		//确定选择
		confirm_groups = function()
		{
			var children_g = $("#sel_re_span").children();
			if(!children_g.length)
			{
				pub_close();
				tipsport('尚未选择任何讨论区!');
			}
			else
			{
				$.ajax({
					url:'my_video.php',
					type:'POST',
					dataType:"html",
					cache:false,
					timeout: TIME_OUT,
					data:{
						a:"add_threads",
						group_id:$("#sel_gp_name").val(),
						link:$("#video_info").val(),
						title:$("#video_name").val()
					},
					success:function(json){
						var obj = new Function("return" + json)();
						if(obj.thread_id)
						{
							$.ajax({
								url:'my_video.php',
								type:'POST',
								dataType:"html",
								cache:false,
								timeout: TIME_OUT,
								data:{
									a:"video_threads",
									video_id:$("#video_id").val()
								},
								success:function(json){
									var obj = new Function("return" + json)();
									if(obj)
									{
										$("#thr_"+obj).removeAttr("onclick");
										$("#thr_"+obj).html('已分享');
									}
								},
								error:function(){
									tipsport('网络延迟！');
								}
							});
						}
					},
					error:function(){
						tipsport('网络延迟！');
					}
				});
				
				var n_html = $("#sel_re_span").html();
				$('#publish_to_groups').append(n_html);
				pub.close();
			}
			 
		}
		
		//取消
		cancle_choice = function()
		{
			pub.close();
			$("#p_t_groups").attr("checked","false");
		};
	}
});