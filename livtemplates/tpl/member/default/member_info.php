{if $formdata}
	{foreach $formdata AS $k => $v}
		{code}
			$$k = $v;
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
  var tp_id = "{$id}";
  var vr = hg_get_cookie('member_register');
  var vs = hg_get_cookie('member_info');
  var vi = hg_get_cookie('member_contact');
  $(document).ready(function(){
	$('#member_register').css('display',vr?vr:'block');
	$('#member_info').css('display',vs?vs:'block');
	$('#member_contact').css('display',vi?vi:'block');
  });
</script>
<div class="info clear vider_s" id="vodplayer_{$id}">
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<img src="{$avatar_url}" style="width:100px;height:100px;" />
</div>
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;height:auto">
		<li class="ml_10">
			<a class="button_4" title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$id}&infrm=1">编辑</a>
		</li>
		<li class="ml_10">
			<a class="button_4" title="审核" href="javascript:;" onclick="hg_memberAudit({$id},{$status},'status','m_status_');">审核</a>
		</li>
		<li class="ml_10">
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" title="删除" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$id}">删除</a>
		</li>
		<li class="ml_10">
			<a class="button_4" title="头像审核" href="javascript:;" onclick="hg_memberAudit({$id},{$avatar},'avatar','m_avatar_');">头像审核</a>
		</li>
		<li><a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '发布', 0);">发布</a></li>
	</ul>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'member_register')"><span title="展开\收缩"></span>会员注册信息</h4>
	<div id="member_register" class="channel_info_box">
		<ul class="clear">
			<li><span>用户名：</span>{$member_name}</li>
			<li><span>昵称：{$nick_name}</span></li>
			<li><span>性别：</span>{if $sex == 1}男{elseif $sex == 2}女{else}保密{/if}</li>
			<li><span>邮箱：</span>{$email}</li>
			<li><span>手机：</span>{$mobile}</li>
			<li style="width: 180px;"><span>注册时间：</span>{$create_time}</li>
			<li><span>邮箱验证：</span>{if $is_email}已验证{else}未验证{/if}</li>
			<li><span>头像状态：</span><span id="m_avatar_{$id}">{if $avatar}已审核{else}待审核{/if}</span></li>
			<li><span>会员状态：</span><span id="m_status_{$id}">{if $status}已审核{else}待审核{/if}</span></li>
		</ul>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'member_info')"><span title="展开\收缩"></span>会员基本信息</h4>
	<div id="member_info" class="channel_info_box">
		<ul class="clear">
			<li><span>中文名：</span>{$cn_name}</li>
			<li><span>英文名：{$en_name}</span></li>
			<li><span>性别：</span>{if $mi_sex == 1}男{elseif $mi_sex == 2}女{else}保密{/if}</li>
			<li><span>生日：</span>{$birth}</li>
			<li><span>星座：</span>{if $constellation}{$constellation_name}{/if}</li>
			<li><span>血型：</span>{if $bloodtype}{$bloodtype_name}{/if}</li>
			<li><span>语言：</span>{$language}</li>
			<li style="display:inline-block;width:200px;"><span></span></li>
			<li><span>现居：</span>{$live_country} {$live_prov} {$live_city} {$live_dist}</li>
			<li><span>故乡：</span>{$home_country} {$home_prov} {$home_city} {$home_dist}</li>
			<li style="width:360px;"><span>自我描述：</span>{$introduce}</li>
		</ul>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'member_contact')"><span title="展开\收缩"></span>会员联系方式</h4>
	<div id="member_contact">
		<ul class="clear">
			<li><span>QQ：</span>{$qq_num}</li>
			<li><span>其他：</span></li>
			<li><span>手机：</span>{$mc_mobile}</li>
			<li><span>固定电话：</span>{$phone}</li>
			<li><span>邮箱：</span>{$mc_email}</li>
			<li><span>邮编：</span>{$zipcode}</li>
			<li style="display:inline-block;width:200px;"><span></span></li>
			<li style="width:360px;"><span>通信地址：</span>{$address_country} {$address_prov} {$address_city} {$address_dist} {$address}</li>
			<li style="width:360px;"><span>个人主页：</span>{$website}</li>
		</ul>
	</div>
</div>













