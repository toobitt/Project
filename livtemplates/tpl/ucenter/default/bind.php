<?php

/* $Id: bind.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>

{template:head}
<script type="text/javascript">

$(document).ready(function (){

	
    /* 绑定微博   */
	setBind = function(type)
	{	
		
		var form_id = '#bind_form_' + type;
			
		$.ajax({
			url: "bind.php",
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
				a:'get_bind_state',
				type:type  				 	
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){

				if(response == 0) /*未开通绑定*/
				{
					$(form_id).submit();
				}
			}
			});			 		
	};

	/* 同步  */
	addSyn = function(state)
	{
		if($('#syn').attr('checked') == true)
		{
			var state = 1;
		}
		else
		{
			var state = 0;
		}
				
		$.ajax({
			url: "bind.php",
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
			a:'syn',
			state:state
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){
								
			}
			});
	};
	

	/* 取消绑定  */
	bindDestroy = function()
	{
		
		$.ajax({
			url: "bind.php",
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
			a:'destroy'
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){

				$('#state').html('成功解除!');
				$('#destroy').empty();
				$('#tongbu').empty(); 								
			}
			});
	};			
});


</script>

<div class="content clear">
	<div class="content_top"></div>	
	<div class="content_middle lin_con clear"> 

		
            {template:unit/userset}
							
			<p>选择你所要绑定的微博:</p>
		
			<form id="bind_form_{code} echo 1;{/code}" action="bind.php" action="post" style="float:left;display:inline">
				<input type="hidden" name="a" value="bind" />
				<span style="display:inline-block;border:1px solid silver; margin:30px 40px; padding:3px;"><a href="javascript:void(0);" onclick="setBind(1);"><img  src="<?php echo RESOURCE_DIR; ?>img/sina-logo.png" /></a></span>											
			</form>
		
			
			{if !$is_bind}
			<div class="state""></div>
			{else}
				<div style="padding:50px 20px;float:left;display:inline-block">	
				
				{if $bind_info['is_bind'] == 1}
					<span style="color:gray;" id="state">已绑定</span>
					<span id="destroy"><a style="font-size:14px;color:#0164CC;" href="javascript:void(0);" onclick="bindDestroy();">解除绑定</a></span>											
				{/if}	
					
				{if $bind_info['state'] == 1}
				<span style="margin-left:10px;" id="tongbu"><input id="syn" type="checkbox" onclick="addSyn(1);" name="state" value="1" checked="checked" />同步到新浪微博</span>
				{else}
				<span style="margin-left:10px;" id="tongbu"><input id="syn" type="checkbox" onclick="addSyn(0);" name="state" value="0" />同步到新浪微博</span>
				{/if}
				</div>
			{/if}
				<div class="bind_con clear">

			     <p class="notice">使用说明：</p>
			     <p>(1) 绑定新浪微博后，我们将不会记录你的用户名和密码！</p>
			     <p>(2) 当你设置同步到新浪微博时，你发送的微博将同步发送到新浪微博。删除微博时，同步发送到新浪上的微博一并删除！</p>	
			     <p>(3) 遵循新浪微博接口使用说明，该应用不可设置多个账号绑定新浪同一账号！</p>			
				</div>
			</div>				
			<div class="content_bottom"></div>			
	</div>
	
{template:foot}