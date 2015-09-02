{template:./head}
{css:manage}
<script type="text/javascript">
//<![CDATA[
function change_state(state)
{
	var uid = [];
	$('input[name="selectMember[]"]').each(function() {
		if ($(this).attr('checked'))
		{
			uid.push($(this).val());
		}
	});
	var url = 'manage.php?a=change_state&uid=' + uid.join(",") + '&state=' + state + '&group_id=' + {$_INPUT['group_id']};
	{if isset($_INPUT['pp'])}
	url += '&pp='+{$_INPUT['pp']};
	{/if}
	location.href = url;
}
//]]>
</script>
<div class="gong_main">
	<div class="g_main_box">
		<div class="g_main_box_l">
			<ul>
				{if $level}
				<li><a href="manage.php?group_id={$group_id}">设置</a></li>
				{/if}
				<li><a style="background:#fd8c02; color:#fff;">成员</a></li>
				{if $level}
				<li><a href="manage.php?a=notice&group_id={$group_id}">公告</a></li>
				{/if}
			</ul>
		</div>
		<div class="g_main_box_r">
			<a href="group.php?group_id={$group_id}">返回讨论区首页</a>
		</div>
	</div>

	<div class="g_main_con1">
		<div class="g_main_left_line">
			<div class="g_main_right_line">
				<div class="chen_admin_box">
					<h3>管理员</h3>
					<ul>
						{if $creater}
						<li><a href="member.php?uid={$creater['user_id']}" class="chen_imgpic"><img src="{if is_string($creater['avatar'])}{$creater['avatar']}{else}{$creater['avatar']['host']}{$creater['avatar']['dir']}99x96/{$creater['avatar']['filepath']}{$creater['avatar']['filename']}{/if}" width="99" height="96" /></a>
							<div class="chen_admin">
								<p style="border-bottom: 1px dashed #ccc;">
									ID:<span style="color: #ff8b00;">{$creater['user_name']}</span>
								</p>
								<p>创建者</p>
							</div>
						</li>
						{/if}
						{if $administor}
						{foreach $administor as $v}
						<li class="chen_pic"><a href="member.php?uid={$v['user_id']}" class="chen_imgpic"><img src="{if is_string($v['avatar'])}{$v['avatar']}{else}{$v['avatar']['host']}{$v['avatar']['dir']}99x96/{$v['avatar']['filepath']}{$v['avatar']['filename']}{/if}" width="99" height="96" /></a>
							<div class="chen_admin">
								<p style="border-bottom: 1px dashed #ccc;">
									ID:<span style="color: #ff8b00;">{$v['user_name']}</span>
								</p>
								{if $level}
								<p><a href="#">任命为创建者</a></p>
								<p><a href="#">降为普通成员</a></p>
								<p><a href="#">加入黑名单</a></p>
								{/if}
							</div>
						</li>
						{/foreach}
						{/if}
					</ul>
				</div>

				<div class="main">
					<div class="menu">
						<span><a {if $status == 1}class="hover"{else}href="manage.php?a=member&group_id={$_INPUT['group_id']}&state=1"{/if}>已审核成员</a>{if $level}&nbsp;|&nbsp;<a {if $status == 0}class="hover"{else}href="manage.php?a=member&group_id={$_INPUT['group_id']}&state=0"{/if}>待审核成员</a>{/if}</span>
					</div>

					<div class="content">
						{if $members}
						<ul>
							{foreach $members as $v}
							<li><a href="member.php?uid={$v['user_id']}"><img src="{if is_string($v['avatar'])}{$v['avatar']}{else}{$v['avatar']['host']}{$v['avatar']['dir']}99x96/{$v['avatar']['filepath']}{$v['avatar']['filename']}{/if}" width="99" height="96" /></a>
								<p>{if $level}<input name="selectMember[]" type="checkbox" value="{$v['user_id']}" />{/if} {$v['user_name']}</p>								
							</li> {/foreach}
						</ul>
						{else}
						<p class="no_member">暂没有成员</p>
						{/if}
					</div>
					{if $level}
					<div style="padding: 10px;">
						<span class="audit">{$pagelink}</span><a href="javascript:;">全选/不选</a>&nbsp;|&nbsp;<a
							href="javascript:;" onclick="change_state(1)">审核</a>&nbsp;|&nbsp;<a
							href="javascript:;" onclick="change_state(0)">打回</a>&nbsp;|&nbsp;<a
							href="javascript:;">任命为创建者</a>&nbsp;|&nbsp;<a href="javascript:;">升为管理员</a>&nbsp;|&nbsp;<a
							href="javascript:;">加入黑名单</a>
					</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>
{template:./footer}