{if $formdata}
	{foreach $formdata AS $k => $v}
		{code}
			$$k = $v;
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
  var tp_id = "{$id}";
  var vr = hg_get_cookie('module_info');
  var vs = hg_get_cookie('subject');
  var vi = hg_get_cookie('body');
  $(document).ready(function(){
	$('#module_info').css('display',vr?vr:'block');
	$('#subject').css('display',vs?vs:'block');
	$('#body').css('display',vi?vi:'block');
  });
</script>
<div class="info clear vider_s" id="vodplayer_{$id}">
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz" style="background: #EEEFF1;border: 0;">
	
</div>

<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'module_info')"><span title="展开\收缩"></span>基本信息</h4>
	<div class="channel_info_box">
		<ul id="module_info" class="clear">
			<li style="width: 380px;"><span>发送配置名：</span>{$name}</li>
			<li style="width: 380px;"><span>邮件来源地址：</span>{$emailsend}</li>
			<li style="width: 380px;"><span>邮件发送方式：</span>{$emailtype}</li>
			<li style="width: 380px;"><span>SMTP 服务器：</span>{$smtphost}</li>
			<li style="width: 380px;"><span>端口：</span>{$smtpport}</li>
			<li style="width: 380px;"><span>SMTP 服务器身份验证：</span>{if $smtpauth}是{else}否{/if}</li>
			<li style="width: 380px;"><span>SMTP 服务器 SSL：</span>{$usessl}</li>
			<li style="width: 380px;"><span>发信人名：</span>{$fromname}</li>
			<li style="width: 380px;"><span>SMTP 发件人邮件地址：</span>{$smtpuser}</li>
			<li style="width: 380px;"><span>状态：</span><span id="m_status_{$id}">{if $status}已审核{else}待审核{/if}</span></li>
			<li style="width: 380px;"><span>配置邮件头尾：</span>{if $is_head_foot}是{else}否{/if}</li>
			<li style="width: 380px;"><span>创建人：</span>{$user_name}</li>
			<li style="width: 380px;"><span>创建时间：</span>{$create_time}</li>
		</ul>
	</div>
</div>
<!--
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'subject')"><span title="展开\收缩"></span>发送标题</h4>
	<div class="channel_info_box">
		<ul id="subject" class="clear">
			<li style="width: 380px;">{$subject}</li>
		</ul>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'body')"><span title="展开\收缩"></span>发送内容</h4>
	<div class="channel_info_box">
		<ul id="body" class="clear">
			<li style="width: 380px;">{$body}</li>
		</ul>
	</div>
</div>
-->
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;height:auto">
		<li class="ml_10">
			<a class="button_4" title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$id}&infrm=1">编辑</a>
		</li>
		<li class="ml_10">
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" title="删除" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$id}">删除</a>
		</li>
		<li class="ml_10">
			<a class="button_4" title="审核" href="javascript:;" onclick="hg_emailSettingsAudit({$id},{$status},'status','m_status_');">审核</a>
		</li>
	</ul>
</div>













