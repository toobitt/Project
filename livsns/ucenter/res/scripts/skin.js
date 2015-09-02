$(document).ready(function(){
	var cp;
	window.onload = function() { 
	    cp = new dhtmlXColorPicker(null, null, true, true);
	    cp.setImagePath("../ui/res/colorselector/imgs/");
	    cp.init();  
	    cp.linkTo("mainSdiv", "pageback");
	    cp.setOnSelectHandler(function(color) {
	        document.getElementById("mainSdiv").style.backgroundColor = color;
	    });
	};
	showCP = function(){
		cp.show();
	};
	showZdy = function(){
		$("#tZdy").css("display","block");
	};
	
	upload = function(obj){
		if($(obj).val())
		{
			$("#upload_process").toggle(); 
			$(upload_form).submit();
		}
		 
	};
 
	endUpload = function(json_str){ 
		$("#upload_process").toggle();
		json_str = new Function("return" + json_str)();
		$("#tips_img").attr("src",json_str.imgsrc);
		$("#result").html(json_str.success); 
	};
	saveSkin = function(){
		var thubnail_src = $("#tips_img").attr("src"); 
		var bg_pos;
		
		if($("#unuse_bg").hasClass("set_border"))
		{
			var bgcolor = document.getElementById("mainSdiv").style.backgroundColor;
			bg_pos = 'background:' + bgcolor + ';';
		}
		else
		{
			bg_pos = 'background:url(' + thubnail_src + ') ';
			
			//设定背景锁定效果 
			if( !$("input[name=lockbg]").attr("disabled"))
			{
				if($("input[name=lockbg]").attr("checked"))
				{
					bg_pos += ' fixed ';
				}
			}
				
			//设定平铺
			if(!$("#bg_repeat").attr("disabled"))
			{
				bg_pos += ' '+$("#bg_repeat").val() + ' ';
			}
			
			//设定对齐方式
			if(!($("#bg_alin").attr("disabled")))
			{
				bg_pos += ' ' + $("#bg_alin").val() + ';';
			}
		}
		//字体颜色的设置
		var arr = $(".colorSet");
		var color_str='{';
		$.each(arr,function(k,v){ 
			color_str += '"mainF_'+k+'":"'+$(v).val()+'",';
		});
		
		var ll = color_str.length;
		color_str = color_str.substring(0, parseInt(ll) - 1);
		color_str += '}'; 
		
		$.ajax({
			url:"mytemplate.php",
			type:"POST",
			dataType:"html",
			cache:false,
			data:{
				a:"savaUserChoice",
				color_set:color_str,
				bg_url:thubnail_src,
				bg_pos:bg_pos,
				styleid:$("#styleid").val()
			},
			success:function(json){
				alert(json);
				location.href = "index.php";
			},
			error:function(){
				alert('Ajax Request Error!');
			}
		});
	};
	changeBG = function(){
		var thubnail_src = $("#tips_img").attr("src"); 
		$(document.body).css("background","url("+thubnail_src+")");
	};
	
	setColor = function(){
		$("#zdy_l0").toggle();
		$("#pageback").toggle();
		$("#mainColor_div").toggle();
	};
	disableCh = function(obj){
		if($(obj).attr("id") == "unuse_bg")
		{
			$("#lockbg").attr("disabled",true);
			$("#bg_repeat").attr("disabled",true);
			$("#bg_alin").attr("disabled",true);
		}
	};
	$("#bg_repeat").onchange = function(){
		var bg = $(document.body).css("background");
		bg += " " + $("#bg_repeat").val(); 
		$(document.body).css("background-repeat:",bg);
	};
	changeClass = function(obj){ 
		if($(obj).attr("id") == "unuse_bg")
		{
			$("#use_bg").removeClass("set_border"); 
			$(obj).addClass("set_border");
		}
		else
		{
			$("#unuse_bg").removeClass("set_border"); 
			$(obj).addClass("set_border");
			$("#lockbg").attr("disabled",false);
			$("#bg_repeat").attr("disabled",false);
			$("#bg_alin").attr("disabled",false);
		}
	};
	cpinit = function(num){
 		
		var ncp_num;
 		
		ncp_num = new dhtmlXColorPicker(null,null,true,true); 
		ncp_num.setImagePath("../ui/res/colorselector/imgs/");
		ncp_num.init();
		ncp_num.linkTo("mainD_"+num, "cSli_"+num);
		ncp_num.setOnSelectHandler(function(color) {
		    $("#mainF_"+num).val(color); 
		    document.getElementById("mainD_"+num).style.backgroundColor = color;
		});
	};

});