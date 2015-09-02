$(document).ready(function(){
			if ($('#zoom_green').length > 0)
			{
				$('#zoom_green').Draggable
				(
					{
						axis:'horizontally'
					}
				);
			}
			if ($('#zoom_yellow').length > 0)
			{
				$('#zoom_yellow').Draggable
				(
					{
						axis:'horizontally'
					}
				);
			}
			if ($('#box_1').length > 0)
			{
				$('#box_1').Draggable
				(
					{
						axis:'horizontally'
					}
				);
			}
			if ($('#box_2').length > 0)
			{
				$('#box_2').Draggable
				(
					{
						axis:'horizontally'
					}
				);
			}

	/*弹出开始开始*/
	$("#preview").click(function(){
		$.tipsWindow({
			___title:"视频标题",
			___content:"text:1213123",
			___width:"500",
			___height:"230",
			___drag:"___boxTitle",
			___showbg:true
		});
	});
	/*弹出结束*/
	/*放大图层开始*/
	$("#zoom_none").click(function(){
		$("#zoom_none").css("display","none")
		$("#zoom_show").css("display","none")
		$("#zoom_yellow").css("display","block")
		$("#zoom_green").css("display","block")
		$("#video").css("border","3px solid #000")
		
		});
	$("#zoom_green").dblclick(function(){
		$(".task_live").css("display","none")
		$(".live").css("display","none")
		$("#zoom_color").attr("class","green");
		$("#video").css("border","3px solid #348A0F")
		var X = $('#zoom_green').offset().left;
		X= X-12;
		$("#zoom_show").css({"display":"block","position":"absolute","left":X,"z-index":"100","border-radius":"3px"});
		$("#zoom_green").css("display","none")
		$("#zoom_yellow").css("display","block")
		$("#zoom_none").css("display","block")
		
							}
							);
	$("#zoom_yellow").dblclick(function(){
		$(".task_live").css("display","none")
		$(".live").css("display","none")
		$("#zoom_color").attr("class","yellow");
		$("#video").css("border","3px solid #FD7100")
		var X = $('#zoom_green').offset().left;
		var b = $('#zoom_yellow').offset().left;
		var v= $('#zoom_show').width();
		z = b - X - v;
		d=X+z;
		$("#zoom_show").css({"display":"block","position":"absolute","left":d,"z-index":"100","border-radius":"3px"});
		$("#zoom_yellow").css("display","none")
		$("#zoom_green").css("display","block")
		$("#zoom_none").show();
							}
							);						
	/*放大图层结束*/
		var t=0;
	/*拖拉效果*/
		$("#magnifier").hide()
		$("#program").click(function(){
				task_liveclose();
				$("#task").attr('class','button_4');
				$("#program").attr('class','button_4_cur');	
				$(".live").slideDown();
				
				
		});
		$("#task").click(function(){
				livclose();
				$(".task_live").slideDown();
		});
	function	livclose(){
				$(".live").slideUp();
				$("#task").attr('class','button_4_cur');
				$("#magnifier").hide();
				$("#zoom").show();
				$("#program").attr('class','button_4');
		
			}
			function	task_liveclose(){
				$(".task_live").slideUp();
				$("#task_2").attr('class','button_4_cur');
				$("#magnifier").hide();
				$("#zoom").show();
				$("#program").attr('class','button_4');
			}
		$("#liv_close").click(function(){
			livclose();
			});
			$("#liv_close_2").click(function(){
			task_liveclose();
			});
			$("#task").click(function(){
				livclose();
			});
		
	/*伸缩效果*/	 
	$("#column").hide();
	$(".add").hide();
	$("#info-img").hide();
	$("#left-colomn").hide();
		var i=0;
	$("#left_part").click(function(){
		livclose();
		$("#left-colomn").slideToggle();
		});

  	$("#show a").click(function(){
			if(i==0)
			{
				$("#edit").attr('class','edit_click');
				openall();
				i=1;
			}
			else
			{
				closeall();
			}
	  });
	 $("#show").mousemove(function(){
			 if(i==0)
			 {
				$("#edit").attr('class','edit_move');
			 }
			 else
			 {				
				openall();
			 }
		 }); 
	$("#show").mouseout(function(){
			if(i==0)
			 {
				closeall();
			 }
			 else
			 {
				openall();
			}
		 }); 
		 
	function  closeall(){
		i=0;
		$("#edit").attr('class','edit');
		$("#show").attr('class','show');
		$("#column").slideUp();
		$("#column_2").attr("id","column_1");
		setTimeout('$("#column_1").hide();',2000);
		  
		}
	function openall(){
			$(".add").slideUp();
			$("#info-img").slideUp();
			$("#column").slideDown();
			$("#show").attr('class','show_move');
			$("#column_1").show();
			$("#column_1").attr("id","column_2");
		}

		var c=0;
		$("#video").click(function(){
			if(c==0){
			$("#video").attr('class','video_click');
			c=1;
			}
			else
			{
			$("#video").attr('class','video');
			c=0;
				}
		});
	  $("#show").attr('class','show');
	  $("#mark").click(function(){
		  		  livclose();
	  $("#show").attr('class','show');
	  $(".add").slideToggle();
	  $("#info-img").slideUp();
	  $("#column").slideUp();
	  });
	  $(".info-bigimg").click(function(){
		  livclose();
	  $("#info-img").slideToggle();
	  $(".add").slideUp();
	  closeall();
	  });  
$("#add-button").click(function(){
	
	var count = document.getElementById('add-ul').getElementsByTagName('li').length;
	if(count >= 8)
	{
		}
	else
	{
		$("#add-ul").append('<li><a><img src="IMG/2.png" width="59" height="45" /><span class="start-time">18:10:22</span><span class="end-time">18:10:24</span></a></li>');
		}	
	});	
$("#addinfo-img").click(function(){
	
	var count = document.getElementById('add-img').getElementsByTagName('dd').length;
	if(count >= 9)
	{
		}
	else
	{
		$("#add-img").append('<dd><a name="flag" id="add-check-'+count+'" onclick="add_pic(this);"><img src="IMG/4.jpg" width="117" height="88" /></a></dd>');
		}	
	});
	
	var flag = false;
	
	add_pic = function(obj){
	
		
	var id='#'+obj.id;
	
	if(flag == false)
	{
		$(id).append('<span id="info-img-selected"></span>');
		flag = true;	
	}
	else
	{
		$('#info-img-selected').empty();
	}
	}	
});
/*IE6透明背景*/
if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style)
{
	DD_belatedPNG.fix('.mask-left');
	DD_belatedPNG.fix('.mask-right');
	DD_belatedPNG.fix('.axis em');
	DD_belatedPNG.fix('.close');
	DD_belatedPNG.fix('.edit_click');
	DD_belatedPNG.fix('#zoom_green');
	DD_belatedPNG.fix('#zoom_yellow');
}