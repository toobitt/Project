$(document).ready(function(){  
	
	 
	//全选
	select_all = function(obj,type)
	{
		var arr;
		arr = $(".checks:checkbox");
		var hid = $("#sendStr_"+type).val();
		$(arr).each(function(){
			$(this).attr("checked",$(obj).attr("checked"));
			if($(this).attr("checked"))
			{
				hid += $(this).val() + ',';
			}
		});
		$("#sendStr_"+type).val(hid);
	};
	
	addThis = function(obj,type)
	{
		var hid = $("#sendStr_"+type).val(); 
		hid += $(obj).val() + ',';
		$("#sendStr_"+type).val(hid);
	};
		
	//批量删除
	deleteMore = function(userid,type)
	{
		var hid = $("#sendStr_"+type).val(); 
		 
		if(!hid)
		{
			alert('要删除至少选择一条评论！');
		}
		else
		{ 
			var divBox = '<div style="text-align: center; background-color: white; padding: 5px;"><p style="background:#fff;color: rgb(102, 102, 102);margin-top:5px;font-weight:bold;line-height:18px;margin-bottom:2px;">此操作不可删除，确定删除这些回复吗？</p><br><input type="button" value="确定" name="confirm" onclick="del('+type+');" style=" margin-right:10px;background-color: rgb(108, 187, 74); padding: 3px ; border: 0px none; color: rgb(255, 255, 255);top:20%;"><input type="button" value="取消" name="canel" onclick="closeSpan();" style="  background-color: rgb(108, 187, 74); padding: 3px ; border: 0px none; color: rgb(255, 255, 255);"></div>';
			//$("#sendStr").val(hid); 
			dialog('提示',"text:"+divBox,"200px","auto","floatBox");
		} 
		
	};
	//批量删除
	del = function(type)
	{
		$.ajax({
			url:"all_comment.php",
			type:"POST",
			dataType:"html",
			cache:false,
			data:{
				a:'del_more',
				comment_ids:$("#sendStr_"+type).val(),
				type:type
			},
			success:function(json){
				closeSpan();  
				location.href = "all_comment.php?t="+type;
			},
			error:function(){
				alert('Ajax Request Error!');
			}  
		});
		closeSpan();
	};
	//删除单个评论
	deleteComment = function(cid,type)
	{
		
		var dd = '<div id="box_'+cid+'" style="text-align: center; background-color: white; padding: 5px;"><p style="background:#fff;color: rgb(102, 102, 102);margin-top:5px;font-weight:bold;line-height:18px;margin-bottom:2px;">确定删除该条评论吗?</p><br><input type="button" value="确定" name="confirm" onclick="delComment('+cid+','+type+');" style=" margin-right:10px;background-color: rgb(108, 187, 74); padding: 3px ; border: 0px none; color: rgb(255, 255, 255);top:20%;"><input type="button" value="取消" name="canel" onclick="closeSpan();" style="  background-color: rgb(108, 187, 74); padding: 3px ; border: 0px none; color: rgb(255, 255, 255);"></div>';
		dialog('提示',"text:"+dd,"200px","auto","floatBox");  
	};
	delComment = function(cid,type)
	{
		
		$.ajax({
			url:"all_comment.php",
			type:"POST",
			dataType:"html",
			cache:false,
			data:{
				a:"del_comment",
				commentid:cid
			},
			success:function(json){
				closeSpan();
				$("#co_"+cid+"_"+type).remove();
			},
			error:function(){
				alert("Ajax Request Error!");
			}
		});
		closeSpan();
	};  
	replyComment = function(commid,type){
		//var userid = $("#rp_"+commid+'_'+type).val().split("_")[0];
		var username = $("#rp_"+commid+'_'+type).val().split("_")[1]; 
		var replyTextArea = '<div class="comment-content clear" id="comm_div_'+commid+'_'+type+'"><span class="triangle">&nbsp;</span><dl id="status_item_"'+commid+'><dt><span onclick="closeComm('+commid+');"></span></dt>';
		replyTextArea +='<dd id="text_'+commid+'" class="text">	<input type="text" name="comm_'+commid+'_'+type+'"  id="reply_'+commid+'_'+type+'" class="txt" value="回复@'+username+':"><input type="button" onclick="replyAction('+commid+','+type+')" value="我来评论" name="comm_sub">';
		replyTextArea +='</dd></dl></div>';
		if($("#comm_div_"+commid+'_'+type).length <= 0)
		{
			$("#speak_"+commid+'_'+type).parent().append(replyTextArea);
			$("input[name=comm_"+commid+"_"+type+"]").focus();
		}
		else
		{
			$("#comm_div_"+commid+'_'+type).remove();
		}
	};
	replyAction = function(commid,type)
	{
		$.ajax({
			url:"all_comment.php",
			type:"POST",
			dataType:'html',
			cache:false,
			timeout: TIME_OUT,
			data:{
				a:'repComment',
				commentid:commid,
				statusid:$("input[name=myself]").val().split('_')[2],
				text:$("#reply_"+commid+'_'+type).val()
			},
			success:function(json){
				//alert(json);
				dialog('提示',"text:回复成功！","200px","auto","floatBox");
				$("#comm_div_"+commid+'_'+type).remove();
				
			},
			error:function(){
				alert("Ajax Request Error!");
			}
		});
		
		$("#comm_div_"+commid+'_'+type).remove();
	}
	
});