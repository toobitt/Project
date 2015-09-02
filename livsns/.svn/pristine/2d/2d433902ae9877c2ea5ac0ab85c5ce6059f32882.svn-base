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
								setTimeout("box_close()",1000);
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
				var q = $("#"+q).html();
				var	user = '@'+q+' ';
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
			idbox_close = function(){ ab.close(); }
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
						if(obj=='false')
						{
							location.href = re_back_login;
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
		
		/*网台关注*/
		add_concern = function (id,type,uid){
			if(!uid)
			{
				location.href="login.php";
			}
			else
			{
				$.ajax({
			        url: "dispose.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "create_concern",
						id: id,
						type: type,
						uid:uid
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	if(json == 'null')
			        	{
			        		location.href="login.php";
			        	}
			        	else
			        	{
				        	var obj = new Function("return" + json)();
				        	if(id)
	        				{
			        			if(obj.self)
					        	{
					        		tipsport('不能关注自己！');
					        	}
					        	else
				        		{
					        		if($("#collect_"+id).html())
				        			{
					        			li = '<a class="gz_back" href="' + SNS_UCENTER + '?user_id='+ uid +'"></a><a class="plays" href="'+SNS_VIDEO+'station_play.php?sta_id='+id+'"></a><a class="gz_del" href="javascript:void(0);" onclick="del_concern('+ obj.id +','+ id +','+ uid +',1);"></a>';
					        			$("#collect_"+id).html(li);
				        			}
					        		if($("#gz_"+id).html())
				        			{
					        			$("#gz_"+id).html('已关注');
				        			}
					        		var target = '#add_' + uid;
									if(obj.relation == 3)
									{
										$(target).html('<a class="mul-concern"></a>');				
									}

									if(obj.relation == 4)
									{
										$(target).html('<a class="been-concern"></a>');	
									}
					        		$('#deleteFriend').html('<a class="cancel-concern" href="javascript:void(0);" onclick="delFriend('+ uid +')"></a>');	
				        		}
	        				}
		        			else
		        			{
		        				tipsport('该用户未创建频道！');
		        			}
			        	}
			        }
			    });		
			}
		}
		
		del_concern = function (id,cid,uid,type){
			if(confirm('确定删除此关注?'))
			{
				$.ajax({
			        url: "dispose.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "del_collect",
						id: id,
						cid:cid,
						type: type,
						uid:uid
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	if(json == 'null')
			        	{
			        		tipsport('删除失败');
			        	}
			        	else
			        	{
				        	var obj = new Function("return" + json)();
				        	
				        	if($("#collect_" + cid).html())
			        		{
				        		li = '<a class="gz_back" href="' + SNS_UCENTER + '?user_id='+ uid +'"></a><a class="plays" href="'+SNS_VIDEO+'station_play.php?sta_id='+id+'"></a><a class="gz_get" href="javascript:void(0);" onclick="add_concern('+ cid +',1,'+ uid +');"></a>';
				        		$("#collect_" + cid).html(li);
			        		}

							var target = '#add_' + uid;
				        	$(target).html('<a class="concern" href="javascript:void(0);" onclick="addFriends('+ uid +' , '+ obj.relation +')"></a>');
							$('#deleteFriend').empty(); 
							if($("#collect_" + obj.cid).html())
			        		{
				        		li = '<a class="gz_back" href="' + SNS_UCENTER + '?user_id='+ uid +'"></a><a class="plays" href="'+SNS_VIDEO+'station_play.php?sta_id='+ obj.cid +'"></a><a class="gz_get" href="javascript:void(0);" onclick="add_concern('+ obj.cid +',1,'+ id +');"></a>';
				        		$("#collect_" + obj.cid).html(li);
			        		}
			        	}
			        }
			    });
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
});