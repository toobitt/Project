{template:head}
{code}
	
	foreach($formdata as $k=>$v)
	{
		$$k = $v;
	}
	if($id)
	{
		$optext="重新发送";
		$a="update";
	}
	else
	{
		$optext="发送";
		$a="create";
	}
	$receiver_type = $receiver_type ? $receiver_type : 4;
	
{/code}
<script type="text/javascript">
	
	function hg_show_type(val,type)
	{
		if(type)
		{
			$("#receiver_val").hide();
			$("#receiver_4").show();
		}
		else
		{
			$("#receiver_4").hide();
			$("#receiver_val").show();
			$("input[name='receiver_value']").attr('value',val);
		}
	}
</script>
{css:calendar}
{css:ad_style}
{js:ad}
{js:jquery.multiselect.min}

<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}通知</h2>

<script>

</script>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">选择应用：</span>
			{code}
			$app_source = array(
				'class' => 'down_list i',
				'show' => 'app_shows_',
				'width' => 100,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			);
			
			$app_val = $app_id ? $app_id : '';
			
			foreach($appendApp as $k => $v)
			{
				$arr[$v['id']] = $v['name'];
				$default = $app_val ? $app_val : $v['id'];
			}
			{/code}
			{template:form/search_source,app_val,$default,$arr,$app_source}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">通知标题：</span><input type="text" value='{$title}' name='title' class="title">
			<font class="important">不填默认显示应用名称</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">通知内容：</span><textarea name="notice">{$content}</textarea>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">推送对象：</span>
			
			<input type="checkbox" value='ios' name='platform[]' {if $platform ==='IOS'}checked{/if}>IOS
			<input type="checkbox" value='android' name='platform[]'  style="margin-left: 20px;" {if $platform == 'android'}checked{/if}>安卓
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">推送方式：</span>
			
			<input type="radio" checked="checked" value='4' name='receiver_type' onclick="hg_show_type('',1);" {if $receiver_type =='4'}checked{/if}>广播(所有人)
			<input type="radio" value='3' name='receiver_type' onclick="hg_show_type('输入设备标签');" style="margin-left: 10px;" {if $receiver_type =='3'}checked{/if}>设备标签(Tag)
			<input type="radio" value='2' name='receiver_type' onclick="hg_show_type('输入设备别名');" style="margin-left: 10px;" {if $receiver_type =='2'}checked{/if}>设备别名(Alias)
			<input type="radio" value='1' name='receiver_type' onclick="hg_show_type('输入Android设备 IMEI。建议只在测试时使用。');" style="margin-left: 10px;" {if $receiver_type =='1'}checked{/if}>单个设备
			
		</div>
		<div class="form_ul_div clear" id="receiver_val" {if $receiver_type == 4 }style="display: none;"{/if}>
			<input type="text" name="receiver_value" value="{$receiver_value}" size='70' style="margin-left: 85px;margin-top:5px;">
		</div>
		<div class="form_ul_div clear" id="receiver_4" {if $receiver_type !=4 } style="display: none;"{/if}>
			<span id="send_all" style="margin-left:85px;">这意味着这个应用程序的所有已注册的用户都会接收消息。</span>
		</div>
	</li>
	
</ul>

{if $a == 'update'}
	<font class="important">重新发送的通知将覆盖上一条通知</font>
	<input type="hidden" name="sendno" value="{$sendno}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
{/if}
<input type="hidden" name="a" value="{$a}" />

<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}通知" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2 style="display:none;"><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
	<div id="client-info-list"></div>
</div>
</div>
{template:foot}