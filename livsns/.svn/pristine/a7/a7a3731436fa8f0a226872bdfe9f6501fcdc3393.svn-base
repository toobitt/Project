$(document).ready(function (){	
	var pic_id = '';
	var pic_url = '';
	var video_id = '';
	var video_url = '';
	var type = "";
	/*清空媒体信息*/
	clear_media = function(){
		 pic_id = '';
		 pic_url = '';
		 video_id = '';
		 video_url = '';
	}
	
	clear_menu = function(){
		$("#video_upload").hide();
		$("#video_load").hide();
		$("#video_tip").hide();
		$("#video_act").hide();
		$("#uploadimg").hide();
		$("#showimg").hide();
		$("#face").hide();
		$("#uploadvideo").hide();
		$("#img-s").attr('src','');
		$("#publish_to_groups").html('');
		$("#p_t_groups").attr("checked",false);
	}
	/*首页发布点滴*/
	pushStatus = function() {
	    if ($("#status").val() != "") 
	    {
	    	$("#themeBut").attr("class","unclick");
	    	$("#themeBut").attr('disabled',"true");
	    	var media_ids = pic_id != ''?(video_id != ''?(video_id + pic_id):pic_id):(video_id != ''?video_id:"");
	    	var group_ids = $("#sel_gp_name").val();
	        $.ajax({
	            url: "dispose.php",
	            type: 'POST',
	            dataType: 'html',
	   			timeout: TIME_OUT,
	   			cache: false,
	            data: {status: $("#status").val(),
		        	a: "update",
		        	source:$("#source").val(),
	        		type:type,
	        		pic_id : pic_id,//新浪同步图片
	        		media_id : media_ids,
	        		group_id : group_ids
		        	},
	            error: function() {
		        	tipsport('网络延迟！');
		        	clear_media();
		        	clear_menu();
			    	$("#themeBut").attr("class","published");
			    	$("#themeBut").removeAttr("disabled");
	            },
	            success: function(json) {
		            var obj = new Function("return" + json)();
					if(obj=='false')
					{
						location.href = re_back_login;
					}
					else
					{
						if(!obj.total)
						{
							$("#status").val('');	
							$("#status").attr("style","background:url(" + RESOURCE_DIR + "img/140250.gif) no-repeat");
							clear_media();
							clear_menu();
					    	$("#themeBut").attr("class","published");
					    	$("#themeBut").removeAttr("disabled");
					    	new_status();
					    	parp.since_id = obj.id;
					    	$("#status_count").html(parseInt($("#status_count").html())+1);
						}
						else
						{	
							tipsport('不要太贪心,发一次就够了!');			
					    	$("#themeBut").attr("class","published");
					    	$("#themeBut").removeAttr("disabled");
						}
						
					}        	
	            }
	        });
	    }
	    else
    	{	
	    	$("#status").css('background-color','rgb(255, 200, 200)')
	    	setTimeout("$('#status').css('background-color','rgb(255, 255, 255)')",800);    
    	}
	}
	
	
//首页发布点滴的上传图片
	$("#publish_picture").click(function(){
		//show_op_div("uploadimg");
//		if(!$("#img-s").attr('src'))
		{
			$("#uploadvideo").hide();
			$("#face").hide();
			$("#uploadimg").show();
		}
	});
	uploads = function (){
		$("#uploadimg").hide();
		$("#loading").toggle();
		$("#form1").submit();
	}
	
	endUploads = function(json){
		if(json == 'size_error')
		{
			tipsport('图片大小不能超过2M');
			$("#loading").hide();
			$("#uploadimg").show();
		}
		else
		{
			$("#loading").toggle();
			var obj = new Function("return" + json)();	
			$("#img-s").attr("src",obj.middle);
			$("#imgurl").html(obj.url.substr(0,12)+"...  "+obj.url.substr(16,4));
			$("#showimg").show();
			if(!$("#status").val())
			{
				$("#status").val('分享图片');
			}
			pic_id = obj.id;
			pic_url = obj.url;
			type += obj.type + ",";
		}	
	}
	closedThis = function(id){ 
		$("#"+id).hide();
		$("#loading").hide();
	}
	filesTrigger = function(){
		$("#files").show();
	}
	show_img = function(){
		$("#showimg").show();
	}
	del_img = function(){
        $.ajax({
            url: "index.php",
            type: 'POST',
            dataType: 'html',
   			timeout: TIME_OUT,
   			cache: false,
            data: {
        		id: pic_id,
        		url: pic_url,
	        	a: 'deletepic'
	        	},
            error: function() {
	        		tipsport('网络延迟！');
            },
            success: function(json) {
            	var obj = new Function("return" + json)();
            	if(obj.id != null)
        		{
	            	$("#showimg").hide();
	    			if($("#status").val()=="分享图片")
	    			{
	    				$("#status").val('');
	    			}
	        		$("#img-s").attr('src','');
        		}
            }
        });	
	}
//增加视频
	$("#publish_video").click(function(){
		//show_op_div("uploadvideo");
			$("#video_upload").show();
			$("#video_load").hide();
			$("#video_tip").hide();
    		$("#video_act").hide();
			$("#uploadimg").hide();
			$("#showimg").hide();
			$("#face").hide();
			if($("#video_url").val()!="http://")
			{
				$("#video_url").val('http://');
			}
			$("#uploadvideo").show();
	});
	$("#video_url").focus(function(){
		if($("#video_url").val()=="http://")
		{
			$("#video_url").val('');
		}
	});
	$("#video_url").blur(function(){
		if($("#video_url").val()=="")
		{
			$("#video_url").val('http://');
		}
	});
	$("#video_act_del").click(function(){
		$("#status").val($("#status").val()+' '+$("#video_url").val());
		closedThis('uploadvideo');
	});
	$("#video_submit").click(function(){
		if($("#video_url").val()=="http://")
		{
			var video_url = '';
			$("#video_upload").hide();
    		$("#video_tip").show();
    		$("#video_act").show();	
		}
		else
		{
			$("#video_upload").hide();
			$("#video_load").show();
			var video_url = $("#video_url").val();
		}
        $.ajax({
            url: "index.php",
            type: 'POST',
            dataType: 'html',
   			timeout: TIME_OUT,
   			cache: false,
            data: {
        		url: video_url,
	        	a: 'uploadvideo'
	        	},
            error: function() {
	        		tipsport('网络延迟！');
            },
            success: function(json) {
            	if(json!="")
        		{
	            	var obj = new Function("return" + json)();
	            	$("#uploadvideo").hide();
	            	
	            	$("#video_upload").show();
	        		$("#video_load").hide();
	        		
	            	$("#status").val($("#status").val()+ " "+obj.url);
	            	video_id += obj.id + ",";
	            	type += obj.type + ",";
            	}
            	else
            	{
            		$("#video_tip").show();
            		$("#video_act").show();           		
            	}
            }
        });	
	});
	
//增加话题格式
	add_topic_rule = function(id){
		clear_menu();
		var obj = document.getElementById(id);
		var rules = '请在这里输入自定义话题';
		var val = obj.value;
		var numS = 1;
		var numE = -1;
		if(val=='')
		{
			obj.value = "#"+rules+"#";
			countChar();
		}
		else 
			{
				if(obj.value.indexOf(rules)==-1)
				{
					obj.value = val + "#"+rules+"#";	
					countChar();
				}
				numS = obj.value.indexOf(rules);	
				var dump = rules.length + numS;
				if(obj.value.length > dump)
				{
					numE = dump - obj.value.length;
				}
			}
		if(obj.createTextRange)
		{//IE浏览器
			
		    var range = obj.createTextRange();
		    range.moveEnd("character",numE);
		    range.moveStart("character",numS);
		    range.select();
		}else{//非IE浏览器
			obj.setSelectionRange(numS, numS + rules.length);
			obj.focus();
		}
	};
	
	
	var parp = ({
		since_id:0
	});
	get_new_status = function(since_id)
	{	
		if(parp.since_id < since_id)
		{
			parp.since_id = since_id;
		}
		else
		{
			since_id = parp.since_id;
		}	
		if(since_id)
		{
			$.ajax({
				url:"index.php",
				type: 'POST',
				dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
				data:
				{
					a:"new_status",
					since_id:since_id
				},
				error: function()
				{
//					alert('Ajax request error');
				},
				success: function(json)
				{  
					if(json && since_id < json)
					{
						$("#new_st").show();
						$("#new_img").hide();
						setTimeout('get_new_status('+json+')',3000);
					}
					else
					{
						setTimeout('get_new_status('+since_id+')',3000);
					}	
				}
			});
		}
	}; 
	
	new_status = function (){
		if(parp.since_id)
		{
			$.ajax({
				url:"index.php",
				type: 'POST',
				dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
				data:
				{
					a:"new_status_show",
					ajax:1,
					since_id:parp.since_id
				},
				error: function()
				{
//					alert('Ajax request error');
				},
				success: function(json)
				{  
					if(json)
					{
						var obj = new Function("return" + json)();
						eval(obj.callback);
					}
				}
			});
		}
	};

	hg_new_status = function(html)
	{
		if(html)
		{
			$("#list").prepend(html);
			$("#new_st").hide();
			$("#new_img").hide();
			$("#status").attr("style","background:none");
		}
	}
	
	
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
	showMyGroups = function()
	{
		
		$("#pub_to_g_btn").onClick = function(){pub.close();}; 
		if($("#p_t_groups").attr("checked") == true)
		{ 
			$("#p_t_gs_div").show();
			$.ajax({
				url:'get_groups.php',
				type:'POST',
				dataType:"html",
				cache:false,
				timeout: TIME_OUT,
				data:{
					a:"getMyGroups"
				},
				success:function(json){
					if(json != 0)
					{
						$("#p_t_gs_div").html(json); 
						if($("#publish_to_groups").html())
						{
							$("#sel_re_span").html($("#publish_to_groups").html());
						}
					}
					pub.show();
				},
				error:function(){
					tipsport('网络延迟！');
				}
			});
		}		
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
					alert('您只能选择' + PUBLISH_TO_MULTI_GROUPS + '个讨论区');
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
		if(!$('#publish_to_groups').html())
		{
			$("#p_t_groups").attr("checked",false);
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
		var children_g = $("#sel_results").children();
		if(children_g.length == 1)
		{
			alert('尚未选择任何讨论区!');
		}
		else
		{
			var n_html = $("#sel_re_span").html();
			$('#publish_to_groups').html(n_html);
			$("#sel_re_span").html('');
			pub.close();
		}
		 
	}
	
	//取消
	cancle_choice = function()
	{
		pub.close();
		$("#p_t_groups").attr("checked","false");
	};
	
});