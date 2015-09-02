<li {if $k == 0}class="li"{/if} id="r_{$v['id']}" name="{$v['id']}" onmouseover="hg_row_show(this, 'on', {$v['type']});" onmouseout="hg_row_show(this, 'out', {$v['type']});">
	<div class="c_t">
		<a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>
		<span class="platform"><img src="" /></span>
		<span class="member_name">{$v['member_name']}</span>
		<span class="_channel_name">{$v['channel_name']}</span>
	</div>
	<div name="c_m" class="c_m bg_{$v['type']}" onclick="hg_interactive_checkbox(this);">
		<span></span>
		<div class="con">{$v['content']}</div>
	</div>
	<div name="c_b" class="c_b bg_{$v['type']}">
		<div class="c_time">
			<span>{$v['create_time']}</span>
			<span name="status_dom">{if $v['status'] == 1}已审核{elseif $v['status'] == 2}被打回{else}待审核{/if}</span>
		</div>
		<div class="type" id="type_{$v['id']}">
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">
				删除
			</a>
			<a onclick="hg_audit({$v['id']}, 1);">审核</a>
			<a onclick="hg_audit({$v['id']}, 2);">打回</a>
			<a onclick="hg_interactive_type({$v['id']}, 1);" {if $v['type'] == 1}class="font_color"{/if} name="1">推送</a>
			<a onclick="hg_interactive_type({$v['id']}, 2);" {if $v['type'] == 2}class="font_color"{/if} name="2">推荐</a>
			<a onclick="hg_interactive_type({$v['id']}, 3);" {if $v['type'] == 3}class="font_color"{/if} name="3">警告</a>
			<a onclick="hg_interactive_type({$v['id']}, 4);" {if $v['type'] == 4}class="font_color"{/if} name="4">屏蔽</a>
		</div>
	</div>
</li>