$(document).ready(function (){	
	var pic_id = '';
	var pic_url = '';
	var video_id = '';
	var video_url = '';
	var type = "";
	/*首页发布点滴*/
	pushStatus = function() { 
	    if ($("#status").val() != "") 
	    {
	        $.ajax({
	            url: "dispose.php",
	            type: 'POST',
	            dataType: 'html',
	   			timeout: TIME_OUT,
	   			cache: false,
	            data: {status: $("#status").val(),
		        	a: "update",
		        	source:$("#source").val(),
	        		type:type
	        		
		        	},
	            error: function() {
	                alert('insex Ajax request error');
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
							var img_id = pic_id != ''?(video_id != ''?(video_id + pic_id):pic_id):(video_id != ''?video_id:"");
							 
							//点滴同步发送到讨论区
							var group_ids = $("#sel_gp_name").val();
							if(group_ids)
							{
								$.ajax({
									url:"dispose.php",
									type:"POST",
									dataType:"html",
									cache:false,
									timeout: TIME_OUT,
									data:{
										a:"pub_to_group",
										group_ids:group_ids,
										status_id:obj.id
									},
									success:function(json){ 
										json = new Function("return"+json)();
										if(json == 'false')
										{
											alert('同步发送错误！');
										}
									},
									error:function(){ 
									}
								});
							}
							
							if(img_id !="")
							{
								$.ajax({
						            url: re_back,
						            type: 'POST',
						            dataType: 'html',
						   			timeout: TIME_OUT,
						   			cache: false,
						            data: {
						        		media_id: img_id,
						        		status_id: obj.id,
							        	a: 'uploadpic'
							        	},
						            error: function() {
						                alert('Ajax request error');
						            },
						            success: function(json) {
						            	var obj = new Function("return" + json)();	
						            	if(obj.status_id)
					            		{
						            		$("#showimg").hide();
						            		
					            		}
						            }
						        });
								$("#status").attr("style","background:url(" + RESOURCE_DIR + "img/140250.gif) no-repeat");
								setTimeout("location.href = re_back",1000);
							}
							else
							{
								$("#status").attr("style","background:url(" + RESOURCE_DIR + "img/140250.gif) no-repeat");
								setTimeout("location.href = re_back",1000);
							}
							
							
						}
						else
						{	
							tipsport('不要太贪心,发一次就够了!');							
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
	
	//删除一条博客信息
	destroy_blog= function(status_id)
	{	
		$.ajax({
			url: "user.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data:
			{
				//传递用户参数	
				a:"destroy_blog",
				status_id:status_id
			},
			error: function()
			{
				alert('Ajax request error');
			},
			success: function(json)
			{	
				var nodeAncestor=document.getElementById("fa"+status_id).parentNode.parentNode.parentNode;			
				var ancestorParent = nodeAncestor.parentNode;
				ancestorParent.removeChild(nodeAncestor);
			}
		});
	}
	unfshowd = function(status_id)
	{
		$("#idBox2"+status_id).remove();  
		var html = " <div id=idBox2"+status_id+" style='width:208px; height:85px; line-height:85px;  position:absolute; left:0px; top:-90px;border:1px solid #CACACA; background-image:url(res/img/unblog.jpg); text-align:center'><div style='padding-top:10px;' ><a href='javascript:void(0);' onclick='destroy_blog("+status_id+")'><img src='res/img/true.jpg'></a>&nbsp;&nbsp;<a href='javascript:void(0);' onclick='unclose("+status_id+")'><img src='res/img/false.jpg'></a></div></div>";
		$("#fa"+status_id).append(html);
		$("#idBox2"+status_id).fadeIn(5000);	
		//unfavorites(status_id);
	}	
	
//首页发布点滴的上传图片
	$("#publish_picture").click(function(){
		//show_op_div("uploadimg");
		if(!$("#img-s").attr('src'))
		{
			$("#uploadvideo").hide();
			$("#uploadimg").show();
		}
	});
	uploads = function (){
		$("#uploadimg").hide();
		$("#loading").toggle();
		$("#form1").submit();
	}
	
	endUploads = function(json){
			$("#loading").toggle();
			var obj = new Function("return" + json)();	
			$("#img-s").attr("src",obj.middle);
			$("#imgurl").html(obj.url.substr(0,12)+"...  "+obj.url.substr(16,4));
			$("#showimg").show();
			if($("#status").val()=="")
			{
				$("#status").val('分享图片');
			}
			pic_id = obj.id;
			pic_url = obj.url;
			type += obj.type + ",";
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
                alert('Ajax request error');
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
			$("#video_tip").hide();
    		$("#video_act").hide();
			$("#uploadimg").hide();
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
		}
		else
		{
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
                alert('Ajax request error');
            },
            success: function(json) {
            	if(json!="")
        		{
	            	var obj = new Function("return" + json)();
	            	$("#uploadvideo").hide();
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
		//show_op_div("p_t_gs_div");
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
					}
					pub.show();
				},
				error:function(){
					alert('请求错误，请稍后重试');
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
		if(PUBLISH_TO_MULTI_GROUPS)
		{
			//允许发布到多个讨论区
			
			$("#sel_gp_name").val(vv);
			
			var ch = $("#sel_re_span").children();
			var fl = 0;
			$.each(ch,function(k){
				if($(ch[k]).attr("id") == "chk_gps_"+group_id){
					fl = 1;
				}
				else
				{
					fl = 0;
				}
			});
			if(fl == 0){
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
		else
		{
			//只能发布到一个讨论区
			$("#sel_gp_name").val(group_id);
			var re1 = $('<span></span>');
			re1.attr("id","chk_gps_"+group_id);
			re1.attr("className",'group_result'); 
			re1.html('<input type="checkbox" name="groups[]" value="'+group_id+'" id="chk_'+group_id+'" checked="checked" onclick="remove_groups('+group_id+')" />'+group_name);
			re1.onClick=function(){remove_groups(group_id);};
			re1.onMouseover = function(){this.css('background-color',"#ffa22e");};
			re1.onMouseout = function(){$(this).css('background-color',"#fff");};
			$("#sel_re_span").html(re1);
		}
 			
	};
	remove_groups = function(group_id)
	{
		if(!$("#chk_"+group_id).attr("checked")){
			$("#chk_gps_"+group_id).remove();
		}
		var sel_gp = $("#sel_gp_name").val();
		if(PUBLISH_TO_MULTI_GROUPS){
			sel_gp = sel_gp.replace(group_id+',','');
			
		}else{
			sel_gp = '';
		}
		$("#sel_gp_name").val(sel_gp);
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
	
});