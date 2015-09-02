{if $formdata}
	{foreach $formdata AS $k => $v}
		{code}
			$$k = $v;
		{/code}
	{/foreach}
{/if}
<style>
.edit_show{min-height:0;overflow-y:scroll;}
</style>
<script type="text/javascript">
  var tp_id = "{$member_id}";
  var vr = hg_get_cookie('module_info');
  var vs = hg_get_cookie('subject');
  var vi = hg_get_cookie('body');
  $(document).ready(function(){
	$('#module_info').css('display',vr?vr:'block');
	$('#subject').css('display',vs?vs:'block');
	$('#body').css('display',vi?vi:'block');
  });
</script>
<div class="info clear vider_s" id="vodplayer_{$member_id}">
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz" style="background: #EEEFF1;border: 0;">
	
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'module_info')"><span title="展开\收缩"></span>基本信息</h4>
	<div class="channel_info_box">
		<ul id="module_info" class="clear">
			<li><span>会员名：</span>{$member_name}</li>
			<li><span>状态：</span><span id="m_status_{$member_id}">{if $status}已审核{else}待审核{/if}</span></li>
			<li><span>黑名单次数：</span>{$blacklist['total']}</li>
			<li><span>总积分：</span>{$credits}</li>
			<li><span>用户组：</span>{$groupname}</li>
			<li><span>等级：</span>{$graname}</li>
			<li><span>所属类型：</span>{$type_name}</li>
			<li><span>注册来源：</span>{$appname}</li>
			<li><span>注册IP：</span>{$ip}</li>
			<li style="width: 190px;"><span>注册时间：</span>{$create_time}</li>
			<li><span>头像：</span>{if $avatar}<img width="35" height="35" src="{$avatar['host']}/{$avatar['dir']}/{$avatar['filepath']}/{$avatar['filename']}" />{/if}</li>
			<li style="width: 380px;"><span>手机：</span>{$mobile}</li>
			<li style="width: 380px;"><span>个性签名：</span>{$signature}</li>
		</ul>
	</div>
</div>
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;height:auto">
		<li class="ml_10">
			<a class="button_4" title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$member_id}&infrm=1">编辑</a>
		</li>
		<li class="ml_10">
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" title="删除" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$member_id}">删除</a>
		</li>
		<li class="ml_10">
			<a class="button_4" title="审核" href="javascript:;" onclick="hg_audit({$member_id}, 'm_status_');">审核</a>
		</li>
		<li class="ml_10">
			<a class="button_4 black-list" id="m_blacklist_{$member_id}" title="{if $blacklist['isblack']}点击取消黑名单{else}点击加入黑名单{/if}" data='{$blacklist['isblack']}' href="javascript:;" onclick="hg_blacklistset({$member_id}, 'm_blacklist_');">{if $blacklist['isblack']}取消黑名单{else}加入黑名单{/if}</a>
		</li>
	</ul>
</div>
{if !empty($bind)}
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'subject')"><span title="展开\收缩"></span>绑定信息</h4>
	<div class="channel_info_box" id="subject" style="display:none">
	{foreach $bind AS $v}
		<ul  class="clear">
			<li><span>会员ID：</span>{$v['platform_id']}</li>
			<li><span>昵称：</span>{$v['nick_name']}</li>
			<li><span>所属类型：</span>{$v['type_name']}</li>
			<li style="width: 190px;"><span>绑定时间：</span>{$v['bind_time']}</li>
			<li><span>头像：</span>{if $v['avatar_url']}<img width="35" height="35" src="{$v['avatar_url']}" />{/if}</li>
		</ul>
	{/foreach}
	</div>
</div>
{/if}
{if $extension}
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'body')"><span title="展开\收缩"></span>扩展信息</h4>
	<div class="channel_info_box">
		<ul id="body" class="clear">
		{foreach $extension AS $v}
			<li><span>{$v['name']}：</span>{$v['value']}</li>
		{/foreach}
		</ul>
	</div>
</div>
{/if}












