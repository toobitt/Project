$(document).ready(function(){
	//$('#windows').click(function() { 
      //  $.blockUI({ message: $('#loginForm') }); 
 
      //  setTimeout($.unblockUI, 2000); 
   // });
	/*弹出开始开始*/
	$("#windows").click(function(){
		$.tipsWindow({
			___title:"视频标题",
			___content:"iframe:html/iframe.html",
			___width:"635",
			___height:"600",
			___drag:"___boxTitle",
			___showbg:true
		});
	});
	$("#search_id").focus(function(){
		$("#search").addClass("search_width")
		
		});
	$("#search_id").blur(function(){
		$("#search").removeClass("search_width")
		
		});	
	
	/*弹出结束*/
		check = function(e){
		id = $(e).attr('id');
		$("#"+id+"_1").slideToggle();
	}
		check_windows = function(id,total){
				for(i=1;i<=total;i++)
				{
					if(i!=id)
					{
						$("#content_"+i).slideUp();	
					}	
				}
			}
		
		check_menu = function(id,total){
			
			$("#content_"+id).slideToggle();
			for(i=1;i<=total;i++)
			{
				if(i!=id)
				{
					$("#content_"+i).slideUp();	
				}	
			}
		}
		 $("#transcoding_id").mousemove(function(){
			 
			$("#transcoding_show").show();
			
		});
		$("#transcoding_id").mouseleave(function(){
			 
			$("#transcoding_show").hide();
			
		});
		$("#colonm_id").mousemove(function(){
			 
			$("#colonm_show").show();
			
		});
		$("#colonm_id").mouseleave(function(){
			 
			$("#colonm_show").hide();
			
		});
		
}
);