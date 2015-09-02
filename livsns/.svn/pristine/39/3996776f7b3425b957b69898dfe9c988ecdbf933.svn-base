$(document).ready(function(){	
	if($("#Box").html()!=null)	
	{
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
		box_close = function(){re.close();}
		pubUserStatus = function(){		
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
			        	source:$("#source").val()
			        	},
		            error: function() {
		                alert('Ajax request error');
		            },
		            success: function(json) {
		            	var obj = new Function("return" + json)();
						if(obj=='false')
						{
							re.close();
							location.href = re_back_login;
						}
						else
						{
							if(!obj.total)
							{
								$("#Box .lightbox_middle").html('<img src="'+RESOURCE_DIR+'img/140250.png"/>');
								setTimeout("box_close();",1000);
								location.href = re_back;
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
		    else
	    	{	
		    	$("#status").css('background-color','rgb(255, 200, 200)')
		    	setTimeout("$('#status').css('background-color','rgb(255, 255, 255)')",800);    
	    	}
		}		
		OpenReleased = function(q){
			$("#status").val('');
			if(q)
			{
				var	user = '@'+ q +' ';
				$("#status").val(user);
			}
			if(re.center){
				re.center = true;
				locks = true;
			} else {
				re.center = true;
				locks = true;
			}
			re.show();	
		}
	}
	
			//关注话题的删除与添加
			del_Topic_Follow = function(id,e){
				var q = $("#topic_"+id).html();
				$.ajax({
					url: 'dispose.php',
			        type: 'POST',
			        dataType: 'html',
						timeout: TIME_OUT,
						cache: false,
			        data: {topic: q,
			        	a: "delTopicFollow"
			        	},
			        error: function() {
			            alert('Ajax request error');
			        },
			        success: function(json) {
			        	 var obj = new Function("return"+json)();
			        	 var num = $("#liv_topic_follow_num").html();
			             if(json)
			             {
			                $(e).parent().remove();
			                $("#liv_topic_follow_num").html(parseInt(num)-1);
			             }
			             else
			             {
								alert('删除失败！');
			             }           	
			        }
			    });	
			}
			var tb = $("#topicbox");
			tb.attr('style','display:none;');
			topicBoxClose = function(){
				$("#topic").val('');
				$('#topic_dd_about').html('请添加想关注的话题');
				tb.attr('style','display:none;');
				}
			add_Topic_Follow = function(){
				tb.attr('style','display:block;');
				$("#topic").focus();
			}
			addTopic = function(){
				var q = $('#topic').val();
				$.ajax({
					url: 'dispose.php',
			        type: 'POST',
			        dataType: 'html',
						timeout: TIME_OUT,
						cache: false,
			        data: {topic: q,
			        	a: "addTopicFollow"
			        	},
			        error: function() {
			            alert('Ajax request error');
			        },
			        success: function(json) {
			            var obj = new Function("return" + json)();
			            var num = $("#liv_topic_follow_num").html();
			            if(json)
			            {
							if(obj=='null')
							{
								$('#topic_dd_about').html('<span style="color:red;">请输入话题</span>');
							}
							else
							{
								if(obj=='false')
								{
									$('#topic_dd_about').html('<span style="color:red;">你已经添加该话题</span>');
								}
								else
								{
									$("#addtopicfollow").before('<li class="topic_li" onmouseover="this.className=' + '\'topic_li_hover\'' + '" onmouseout="this.className=' + '\'topic_li\'' + '"><a href="k.php?q=' + q + '">' + q + '</a><a class="close" href="javascript:void(0);" onclick="del_Topic_Follow(\'' + obj.topic_id + '\',this)"></a><div class="hidden" id="topic_'+ obj.topic_id +'">'+ q +'</div></li>');
					                $("#liv_topic_follow_num").html(parseInt(num) + 1);
					                topicBoxClose();
								}
							}
			            }
			            else
			            {
							alert('关注失败！');
			            }
			        }
			    });	
			}
			
			countChar = function(){  
				$("#counter").html(140 - $("#status").val().length);
				if($("#counter").html()<1){
						$("#counter").html("<b style='color:red'>0</b>");
						$("#status").val($("#status").val().substring(0,140));
					}
			}
			countCharF = function(){  
				$("#counterF").html(140 - $("#statusF").val().length);
				if($("#counterF").html()<1){
						$("#counterF").html("<b style='color:red'>0</b>");
						$("#statusF").val($("#statusF").val().substring(0,140));
					}
			}
			
		//删除一条博客信息
		destroy_blog= function(status_id)
		{	
			$("#idBox2"+status_id).html('<img style="padding-top:30px;" src="res/img/loading_page.gif"/>删除中...');
			$.ajax({
				url: "dispose.php",
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
					tipsport('网络延迟！');
				},
				success: function(json)
				{	
					var nodeAncestor=document.getElementById("fa"+status_id).parentNode.parentNode.parentNode;			
					var ancestorParent = nodeAncestor.parentNode;
					ancestorParent.removeChild(nodeAncestor);
					$("#status_count").html(parseInt($("#status_count").html())-1);
				}
			});
		}
		
		unfshowd = function(status_id)
		{
			$("#idBox2"+status_id).remove();  
			var html = ' <div id="idBox2'+status_id+'" class="del_box"><div class="del_tip">确定删除这条点滴吗？</div>' +
			'<div style="padding-top:20px;" >' +
			'	<a href="javascript:void(0);" onclick="destroy_blog('+status_id+')"><img src="res/img/true.jpg"></a>' +
					'&nbsp;&nbsp;<a href="javascript:void(0);" onclick="unclose('+status_id+')"><img src="res/img/false.jpg"></a>' +
							'</div></div>';
			$("#fa"+status_id).append(html);
			$("#idBox2"+status_id).fadeIn(5000);
			//unfavorites(status_id);
		}		
			
		if($("#idBox").html()!=null)
		{
			var ab = new AlertBox("idBox"), lock = false;
			function lockup(e){ e.preventDefault(); }
			function lockout(e){ e.stopPropagation(); }
			ab.onShow = function(){
				$("#counterF").html(140 - $("#statusF").val().length);
				numS = $("#statusF").val().length;
				cursor('statusF',0,0);				
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
			OpenForward = function(id,uid){			
				$("#temporary").val(id);
				$("#title").html($("#t_"+id).html());
				$("#statusF").val($("#f_"+id).html());
				$("#avatar").attr('src','avatar.php?user_id='+ uid +'&a=get_avatar');
				if(ab.center){
					ab.center = true;
					lock = true;
				} else {
					ab.center = true;
					lock = true;
				}
				ab.show();
			}
			$$("idBoxClose").onclick = function(){ ab.close(); }
			idbox_close = function(){ab.close(); }
			$$("ModForward").onclick = function(){
				$.ajax({
		            url: "dispose.php",
		            type: 'POST',
		            dataType: 'html',
		   			timeout: TIME_OUT,
		   			cache: false,
		            data: {status: $("#statusF").val(),
			        	a: "update",
			        	source:$("#source").val(),
			        	status_id:$("#temporary").val()
			        	},
		            error: function() {
		                alert('Ajax request error');
		            },
		            success: function(json) {
		            	var obj = new Function("return" + json)();
						if(obj == 'false')
						{
							location.href = SNS_UCENTER + 'login.php';
						}
						else
						{
							if(!obj.total)
							{
								$("#idBox .lightbox_middle").html('<img src="'+RESOURCE_DIR+'img/140250.png"/>');
								setTimeout("idbox_close();",1000);
								$("#status_count").html(parseInt($("#status_count").html())+1);
								location.href = re_back;
							}
							else
							{
								ab.close();
								tipsport('不要太贪心,发一次就够了!');	
							}
						}
		            }
		        });	
			}
		}

});