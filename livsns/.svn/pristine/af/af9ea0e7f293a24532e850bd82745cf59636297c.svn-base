$(document).ready(function (){		
	var q = $('#q').val();
	var re = new AlertBox("Box"),locks = false;
	function lockup(e){ e.preventDefault(); };
	function lockout(e){ e.stopPropagation(); };
	re.onShow = function(){
		$("#counter").html(140 - $("#status").val().length);
		numS = $("#status").val().length;
		cursor('status',numS,numS);	
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
						location.href = 'login.php';
					}
					else
					{
						$("#Box dd").html('<div class="pub-user-ok"> </div>');
						setTimeout(re.close(),2000);	
						location.href="k.php?q="+$("#q").val();					
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
	OpenReleaseds = function(){
		if(q)
		{
			var	user = '#'+q+'#';
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
	
	
	addTopicFollow = function(){
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
							$('#liv_topic_id').val(obj.topic_id);
							$("#delTopic").attr("style","display:inline");
			            	$("#addTopics").attr("style","display:none");
		                	$("#addtopicfollows li:last").after('<li class="topic_li" id="liv_topic_'+ obj.topic_id +'" onmouseover="this.className=' + '\'topic_li_hover\'' + '" onmouseout="this.className=' + '\'topic_li\'' + '"><a href="k.php?q=' + q + '">' + q + '</a><a class="close" href="javascript:void(0);" onclick="delTopicFollow()"></a></li>');
			                $("#liv_topic_follow_num").html(parseInt(num) + 1);
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
	delTopicFollow = function(){
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
                    var id = "#liv_topic_" + obj.topic_id;
                	$(id).remove();
                	if($('#liv_topic_id').val() == obj.topic_id)
                	{
	                	$("#addTopics").attr("style","display:inline");
						$("#delTopic").attr("style","display:none");
                	}
					$("#liv_topic_follow_num").html(parseInt(num)-1);
                 }
                 else
                 {
 					alert('删除失败！');
                 }           	
            }
        });			
	}
});