<li style="margin-top:0;height:36px;padding:1px 0;"  id="r_{$v['id']}" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" class="clear">
	<span class="left">
		<a name="alist[]" class="lb">
			<input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]">
		</a>
	</span>
	<span style="width:515px;height:36px;" class="right">
		<a class="zt overflow" style="width:225px;"><span style="color:#8197BE;">{$v['user']['username']}</span><span style="color:#7D7D7D;">: {code} echo strip_tags($v['status']['text']);{/code}</span></a>
		<a id="cz" style="width:25px;position:relative;" title="操作" class="cz"> <em class="b4"></em>
			<span style="display: none;" class="show_opration_button">
				<span onclick="hg_commentState({$v['id']},1);" style="margin:4px 2px 0 0;" class="button_2">审核</span>
				<span onclick="hg_commentState({$v['id']},0);" style="margin:4px 2px 0 0;" class="button_2">打回</span>
				<span onclick="hg_stateDel({$v['id']});" style="margin:4px 2px 0 0;" class="button_2">删除</span>
			</span>
		</a>
		<a class="zt">
			<em><span id="audit_{$v['id']}">{if !$v['state']}已审核{else}待审核{/if}</span></em> 
		</a>
		<a class="tjr zt" style="padding:0;width:140px;">
			<div>
				<img style="display:block;float:left;margin-right:5px;width:36px;height:36px;" width="36" height="36" src="{$v['user']['middle_avatar']}" alt="">
				<div>
					<span style="display:block;color:#8199BD;font-size:12px;">{$v['user']['username']}</span>
					<span style="color:#7B7B7B;font-size:9px;">{$v['comment_time']}</span>
				</div>
				
			</div>
		</a>
	</span>
	<span style="cursor:pointer;" class="title overflow">
		<a href="###">
			<span>
				{$v['content']}
			</span>
		</a>
	</span>
</li>