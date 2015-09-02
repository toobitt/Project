$(document).ready(function(){
	show_share = function(type,page_url)
	{
		var ttitle,vedio_url;
		if(!video_title)
		{
			if(!video_address)
			{
				var oobj = $("#tvie_flash_players").children();
				var t_tvie,t_val;
				for(var i=0;i<oobj.length;i++)
				{
					if($(oobj[i]).attr("name") == 'flashvars')
					{
						t_val = $(oobj[i]).val().split('&');
					}
				}
				
				for(var j=0;j<t_val.length;j++)
				{
					var tmp = t_val[j].split('=');
					if(tmp[0] == 'fileurl')
					{
						vedio_url = tmp[1];
					}
				}
			}
			else
			{
				vedio_url = video_address;
			}	
			
			ttitle = $("#vedio_name_container").html();
			
		}
		else
		{
			ttitle = video_title;
			vedio_url = video_address;
		}	
		$.ajax({
			url:"doshare.php",
			type:"POST",
			dataType:"html",
			cache:false,
			timeout: TIME_OUT,
			data:{
				a:"show",
				title:ttitle,
				type:type,
				url:page_url,
				vedio_addr:vedio_url,
				ajax:1
			},
			success:function(json){
				close_status_share();
				close_program_one();
				var obj = new Function("return" + json)();
				eval(obj.callback);
			},
			error:function(){}
		});
	}
	
	hg_html_share_comm = function(html)
	{
		$('#share_container').html(html);
		$('#share_container').show();
	}

	
	
	if($("#Box").html()!=null)	
	{
		var param = {
				video_id:0,
				type:0,
				url:""
		}
		//发布点滴|对话
		var re = new AlertBox("Box"),locks = false;
		function lockup(e){ e.preventDefault(); };
		function lockout(e){ e.stopPropagation(); };
		re.onShow = function(){
			var obj = document.getElementById('status');
			var numE = $("#status").val().length;
			cursor('status',numE,numE);
			if ( locks ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		re.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("BoxClose").onclick = function(){ re.close(); }	
		countChar = function(){  
			$("#counter").html(140 - $("#status").val().length);
			if($("#counter").html()<1){
					$("#counter").html("<b style='color:red'>0</b>");
					$("#status").val($("#status").val().substring(0,140));
				}
		}
		box_close = function(){ re.close();}
		pubUserStatus = function(){		
		    if ($("#status").val() != "") 
		    {
		    	if(param.video_id && param.url )
	    		{
		    		var media_ids = param.video_id?param.video_id:"";
		    		$.ajax({
	    	            url: "doshare.php",
	    	            type: 'POST',
	    	            dataType: 'html',
	    	   			timeout: TIME_OUT,
	    	   			cache: false,
	    	            data: {
	            			status: $("#status").val(),
	    		        	a: "update",
	    		        	source:"点滴",
	    	        		type:param.type,
	    	        		media_id:media_ids
	    		        	},
	    	            error: function() {
	    		        		tipsport('网络有误！');
	    		        		
	    	            },
	    	            success: function(json) { 
	    		            var obj = new Function("return" + json)();
	    					if(obj=='false')
	    					{
	    						location.href = SNS_UCENTER + "login.php";
	    					}
	    					else
	    					{
	    						if(!obj.total)
	    						{
	    							$("#Box .lightbox_middle").html('<img src="'+RESOURCE_DIR+'img/140250.png"/>');
				            		setTimeout("box_close();",1000);
				            		$("#status_share").html('<a href="javascript:void(0);">分享成功</a>');
	    						}
	    						else
	    						{	
	    							re.close();
	    							tipsport('不要太贪心,发一次就够了!');							
	    						}
	    					}        	
	    	            }
	    	        });
	    		}
		    }
		    else
	    	{	
		    	$("#status").css('background-color','rgb(255, 200, 200)')
		    	setTimeout("$('#status').css('background-color','rgb(255, 255, 255)')",800);    
	    	}
		}		
		open_share = function(){
			if(re.center){
				re.center = true;
				locks = true;
			} else {
				re.center = true;
				locks = true;
			}
			re.show();	
		}
		
		clear_med = function(){
			param.video_id = 0;
			param.type = 0;
			param.url = "";
		}
		show_status_share = function(id)
		{
			$.ajax({
				url:"doshare.php",
				type:"POST",
				dataType:"html",
				cache:false,
				timeout: TIME_OUT,
				data:{
					a:"show_video",
					id:id
				},
				error:function(){tipsport('网络有误！');},
				success:function(urls) {
	            	if(urls)
	            		{
		            		$.ajax({
		            			url:"doshare.php",
		            			type:"POST",
		            			dataType:"html",
		            			cache:false,
		            			timeout: TIME_OUT,
		            			data:{
		            				a:"uploadvideo",
		            				url:urls
		            			},
		            			error:function(){tipsport('网络有误！');clear_med();},
		            			success:function(json) {
					            	if(json!="null")
					        		{
						            	var obj = new Function("return" + json)();
						            	if(param.video_id != (obj.id + ","))
					            		{
						            		param.video_id += obj.id + ",";
					            		}
						            	param.type += obj.type + ",";
						            	param.url = obj.url;
						            	$("#status").val(param.url);
	    								open_share();
					            	}
					            	else
					            	{
					            		tipsport('分享有误，请联系管理员！');
					            	}
		            			}
		            		});	
	            		}
	            		else
	            		{
	            			tipsport('上传有误！');
	            		}
				}
			});
		}
	}
	
	reset_programe = function(){
		$("#p_name").val('');
		$("#p_brief").val('');
	}
	
	close_share = function()
	{
		$("#share_container").html('');
		$("#share_container").hide();
	};
	
	close_status_share = function()
	{
		$("#share_status").html('');
		$("#share_status").hide();
	}
	
	copyToClipboard = function(iid)
	{
		var d = $("#"+iid).val(); 
		var ll = d.length;
		
		if(window.clipboardData)
		{
			window.clipboardData.setData('text', d);
			current_code = window.clipboardData.getData("text");
			alert('复制成功!');
		}
		else
		{ 
			var obj = document.getElementById(iid); 
			if(obj.createTextRange)
			{//IE浏览器
				
			    var range = obj.createTextRange();
			    range.moveEnd("character",numE);
			    range.moveStart("character",numS);
			    range.select();
			}else{//非IE浏览器 
				obj.setSelectionRange(0,ll);
				obj.focus();
			}
			alert('当前浏览器不支持复制功能，请按住Ctrl+C键进行复制');
		}
	}
	
	show_more_share = function(){}
});
