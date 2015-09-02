$("#program").click(function(){
				task_liveclose();
				$("#task").attr('class','');
				$("#program").attr('class','text-click');	
				$(".live").slideDown();
				
				
		});
		$("#task").click(function(){
				livclose();
				$("#task").attr('class','text-click');
				$(".task_live").slideDown();
		});
	function	livclose(){
				$(".live").slideUp();
				$("#magnifier").hide();
				$("#zoom").show();
				$("#program").attr('class','');
		
			}
			function	task_liveclose(){
				$(".task_live").slideUp();
				$("#magnifier").hide();
				$("#zoom").show();
				$("#task").attr('class','');
				$("#program").attr('class','');
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
	var i=0;


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