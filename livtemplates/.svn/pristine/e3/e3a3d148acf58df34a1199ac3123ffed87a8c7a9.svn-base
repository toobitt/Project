<?php 
/* $Id: encode_form.php 5170 2011-12-02 08:05:20Z gengll $ */
?>
{template:head}
{css:ad_style}
<style>
	.ad_form .form_ul .error_tips{color:red;padding-left:5px;}
	.ad_form .form_ul .success_tips{color:green;padding-left:5px;}
</style>
<script type="text/javascript">
	var flag = true;
	function check_encode_form()
	{
		if(!flag || !$("#name").val() || !$("#ip").val())
		{
			return false;
		}

		var num = $("#stream_num").val();
		var outname = stream = port = "";
		for(i = 1;i <= num; i++)
		{
			if(!$("#out_name"+ i).val() && !$("#port"+ i).val())
			{
				$("#num"+ i).val(0);
				$("#stream"+ i).val(0);
			}
			else
			{
				if(outname && outname == $("#out_name"+ i).val() && $("#out_name"+ i).val())
				{
					$("#error_tips" + i).removeClass('success_tips');
					$("#error_tips" + i).addClass('error_tips');
					$("#error_tips" + i).html('流名称有重复');
					$("#error_tips" + i).fadeIn(1500);
					return false;
				}

				if(stream && stream == $("#stream"+ i).val() && $("#stream"+ i).val())
				{
					$("#error_tips" + i).removeClass('success_tips');
					$("#error_tips" + i).addClass('error_tips');
					$("#error_tips" + i).html('码流有重复');
					$("#error_tips" + i).fadeIn(1500);
					return false;
				}

				if(port && port == $("#port"+ i).val() && $("#port"+ i).val())
				{
					$("#error_tips" + i).removeClass('success_tips');
					$("#error_tips" + i).addClass('error_tips');
					$("#error_tips" + i).html('端口号有重复');
					$("#error_tips" + i).fadeIn(1500);
					return false;
				}
				outname = $("#out_name"+ i).val();
				stream = $("#stream"+ i).val();
				port = $("#port"+ i).val();
			}

			if(!$("#out_name"+i).val() && !$("#port"+i).val())
			{
				$("#error_tips" + i).removeClass('error_tips');
				$("#error_tips" + i).removeClass('success_tips');
				$("#error_tips" + i).html('');
				return true;
			}
			else if(!$("#out_name"+i).val() || !$("#port"+i).val())
			{
				$("#error_tips" + i).removeClass('success_tips');
				$("#error_tips" + i).addClass('error_tips');
				$("#error_tips" + i).html('不能有空值！');
				$("#error_tips" + i).fadeIn(1500);
				return false;
			}
		}
		return true;
	}

	function check_value(e)
	{
		var name = $(e).attr('name');
		if($(e).val())
		{
			var str = '{'+ name + ':"' + $(e).val() + '"}';
			var data = strToJson(str);  
			$("#error_tips_" + name).removeClass('success_tips');
			$("#error_tips_" + name).addClass('error_tips');
			$("#error_tips_" + name).html('请求中...');
			$("#error_tips_" + name).fadeIn(1500);
			var url = './run.php?mid=' + $("#mid").val() + '&a=verify';
			if($("#id").val())
			{
				url += '&id=' + $("#id").val();
			}
			hg_request_to(url,data);
		}
		else
		{
			$("#error_tips_" + name).removeClass('success_tips');
			$("#error_tips_" + name).addClass('error_tips');
			$("#error_tips_" + name).html('不为空！');
			flag = false;
		}
	}

	function check_out_value(i)
	{
		if(!$("#out_name"+i).val() || !$("#port"+i).val())
		{
			$("#error_tips" + i).removeClass('success_tips');
			$("#error_tips" + i).addClass('error_tips');
			$("#error_tips" + i).html('不能有空值！');
			$("#error_tips" + i).fadeIn(1500);
			flag = false;
		}

		if($("#out_name"+i).val() && $("#stream"+i).val() && $("#port"+i).val())
		{
			$("#error_tips" + i).removeClass('error_tips');
			$("#error_tips" + i).addClass('success_tips');
			$("#error_tips" + i).html('ok');
			$("#error_tips" + i).fadeIn(1500);
			flag = true;
		}

		if(!$("#out_name"+i).val() && !$("#port"+i).val())
		{
			$("#error_tips" + i).removeClass('error_tips');
			$("#error_tips" + i).removeClass('success_tips');
			$("#error_tips" + i).html('');
			flag = true;
		}
	}

	function check_value_call(json)
	{
		var obj = jsonToObj(json);
		var name = obj.call;
		if(obj.error)
		{
			$("#error_tips_" + name).removeClass('success_tips');
			$("#error_tips_" + name).addClass('error_tips');
			$("#error_tips_" + name).html('已存在！');
			flag = false;
		}
		else
		{
			$("#error_tips_" + name).removeClass('error_tips');
			$("#error_tips_" + name).addClass('success_tips');
			$("#error_tips_" + name).html('可以使用');
			$("#error_tips_" + name).fadeIn(1500);
			flag = true;
		}
	}

	function strToJson(str)
	{
		var json = (new Function("return " + str))();  
		return json; 
	}

	function jsonToObj(json)
	{
		var obj = new Function("return" + json)();
		return obj; 
	}

</script>
<!--<pre>
{code}
	print_r($formdata);
{/code}
</pre>-->
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
	<div class="ad_middle">
	<form name="editform" id="editform" action="" method="post" class="ad_form h_l" onsubmit="return check_encode_form();">
		<h2>{$optext}编码器</h2>
		<ul class="form_ul">
			<li class="i"><span>编码器名称：</span><input id="name" type="text" name="name" value="{$name}" onblur="check_value(this);"/><font class="important">*</font><span id="error_tips_name" class="error_tips"></span></li>
			<li class="i"><span>IP地址：</span><input id="ip" type="text" name="ip" value="{$ip}" onblur="check_value(this);"/><font class="important">*</font><span id="error_tips_ip" class="error_tips"></span></li>
			<li class="i"><span>是否启用：</span><input type="radio" name="is_used" value="1" {if $is_used} checked {/if}/>是<input type="radio" name="is_used" value="0" {if !$is_used} checked {/if}/>否</li>
			<li>
				<ul class="stream_ul" id="stream_list">
				{code}
					$num = $num ? $num : count($_configs['stream_port']);
					for($i=1 ;$i <= $num;$i++)
					{
						switch($i)
						{
							case 1:
								$defalut_stream = 200;
								break;
							case 2:
								$defalut_stream = 500;
								break;
							case 3:
								$defalut_stream = 800;
								break;
							case 4:
								$defalut_stream = 1000;
								break;
							default:
								break;
						}
						$stream[$i]['stream'] = $stream[$i]['stream'] ? $stream[$i]['stream'] : $defalut_stream;
				{/code}
					<li id="list_{$i}" class="i">
						<span>通道{$i}：</span><input type="hidden" id="num{$i}" name="num[{$i}]" value="{$i}"/><input type="hidden" name="out_id[{$i}]" id="out_id{$i}" value="{$stream[$i]['id']}" onblur="check_out_value({$i})" onfocus="check_out_value({$i})"/><font class="important">*</font>
						<span>流名称：</span><input type="text" id="out_name{$i}" name="out_name[{$i}]" value="{$stream[$i]['name']}" onblur="check_out_value({$i})" onfocus="check_out_value({$i})"/><font class="important">*</font>
						<span>码流：</span><input style="width:100px;" type="text" id="stream{$i}" name="stream[{$i}]" value="{$stream[$i]['stream']}" onblur="check_out_value({$i})"/><font class="important">*</font>
						<span>端口号：</span><input style="width:40px;" type="text" id="port{$i}" name="port[{$i}]" value="{$stream[$i]['port']}" onfocus="check_out_value({$i})" onblur="check_out_value({$i})"/><span id="error_tips{$i}" class="error_tips"></span>
					</li>
				{code}
					}
				{/code}
				</ul>
			</li>
		</ul>		
	<input type="hidden" id="stream_num" name="stream_num" value="{$num}" />
	<input type="hidden" name="a" value="{$action}" />
	{if $action == 'update'}
	<input type="hidden" name="id" id="id" value="{$id}" />
	{/if}
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<input type="hidden" name="mid" id="mid" value="{$_INPUT['mid']}" />
	
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_2" />
	</form>
	</div>
	<div class="right_version">
		<h2>返回前一页</h2>
	</div>
{template:foot}