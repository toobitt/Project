<?php
/* $Id: editpasswd.php 387 2011-07-26 05:31:22Z lijiaying $ */
?>
{template:head/head_register_login}
<script type="text/javascript">
//submit form
$(document).ready(function(){
	var $inp = $('td div input');
	var salt = "";
	$inp.bind('keydown', function (e){
		var key = e.which;		
		if (key == 13)
		{
			e.preventDefault();
	        var nxtIdx = $inp.index(this) + 1;
	        $("td div input:eq(" + $inp.index(this) + ")").blur();
	        $("td div input:eq(" + nxtIdx + ")").focus();
	        reg();
		}
	});
	$inp.bind('focus', function (){
		$("td div:eq(" + $inp.index(this) + ")").attr('class','biankuang_hover');
	});
	$inp.bind('blur', function (){
		$("td div:eq(" + $inp.index(this) + ")").attr('class','biankuang');
	});
	$('#submit01').click(function(){
		if(!$('#pwd0').val())
		{
			$('#info01').html('{$_lang['inputCurPwd']}');
		}
		else if(!$('#pwd1').val())		
		{
			$('#info02').html('{$_lang['inputNewPwd']}');
		}
		else if(!$('#pwd2').val())
		{
			$('#info03').html('{$_lang['difPwd']}');
		}
		else
		{
			$.ajax({
				url:'editpasswd.php',
				type:'post',
				timeout:5000,
				cache:false,
				data:{
					a:'updatePasswd',
					salt:salt,
					pwd10:$('#pwd0').val(),
					pwd1:$('#pwd1').val(),
					pwd2:$('#pwd2').val()
				},
				error:function(){alert('ajax request error');},
				success:function(json){
					var obj = new Function("return" + json)();
					if(obj)
					{
						tipsport('{$_lang['successTips']}');
					}
				}
			});
		}
	});

	$('#pwd0').focus(function(){
		if(!$('#pwd2').val())
		{
			$('#info01').html('{$_lang['inputCurPwd']}');
		}
	});
	$('#pwd0').blur(function(){
		var value = $('#pwd0').val();
			if(value=="")
				$('#info01').html('{$_lang['inputCurPwd']}');
			else if(value.length < 6)
				$('#info01').html('{$_lang['sixChar']}');
			else
				$.ajax({
					url:'editpasswd.php',
					type:'post',
					timeout:5000,
					cache:false,
					data:{
						a:'verifyPassword',
						pwd0:$('#pwd0').val()
					},
					error:function(){alert('ajax request error');},
					success:function(json){
						var obj = new Function("return" + json)();
						if(obj != "")
						{
							salt = obj;
							$('#info01').html('Ok');
						}
						else
						{
							$('#info01').html('{$_lang['errorPassword']}');
						}
					}
				});
		});
	
	$('#pwd1').focus(function(){
		if(!$('#pwd2').val())
		{
			$('#info02').html('{$_lang['inputNewPwd']}');
		}
	});	
	$('#pwd1').blur(function(){
		var value = $('#pwd1').val();
		if(value =="")
			$('#info02').text('{$_lang['inputNewPwd']}');
		else if(value.length < 6)
		{
			$('#info02').text('{$_lang['sixChar']}');
		}
		else
		{
			$('#info02').text('ok');
		}
	});

	$('#pwd2').focus(function(){
		if(!$('#pwd2').val())
		{
			$('#info03').html('{$_lang['difPwd']}');
		}
	});
	$('#pwd2').blur(function(){
		var pwd1 = $('#pwd1').val();
		var pwd2 = $('#pwd2').val();
		if(pwd1 == "" || pwd1!=pwd2)
		{
			$('#info03').text('{$_lang['difPwd']}');
		}
		else
		{
			$('#info03').text('ok');
		}
	});
	
});

</script>
<div class="content">
	<div class="content_top"></div>	
	<div class="content_middle clear"> 
	{template:unit/userset}
		<div class="edit-passwd">
			<table class="table-passwd">
				<tr>
					<td class="td-ft">{$_lang['curPwd']}：</td>
					<td class="td-sd">
						<div class="biankuang">
							<input id="pwd0" type="password"/>
						</div>
					</td>
					<td class="td-td" id="info01"></td>
				</tr>
				<tr>
					<td class="td-ft">{$_lang['newPwd']}：</td>
					<td class="td-sd">
						<div class="biankuang">
							<input type="password" id="pwd1"/>
						</div>	
					</td>
					<td id="info02" class="td-td"></td>
				</tr>
				<tr>
					<td class="td-ft">{$_lang['verifyPwd']}：</td>
					<td class="td-sd">
						<div class="biankuang">
							<input type="password" id="pwd2"/>
						</div>
					</td>
					<td id="info03" class="td-td"></td>
				</tr>
				<tr>
					<td></td>
					<td class="td-sd user_info_ok">
						<input type="button" value="" id="submit01">
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="content_bottom"></div>
</div>

{template:foot}