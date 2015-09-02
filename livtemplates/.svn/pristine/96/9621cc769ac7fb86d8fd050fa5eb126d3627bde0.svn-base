<li class="clear"  id="r_{$v['id']}" style="margin-top:0;height:36px;padding:1px 0;" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
	<span class="left">		
		<a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a>	
	</span>
	<span style="width:410px;height:36px;" class="right">
		<a class="zt">{$v['reply_count']}</a>
		<a class="zt">{$v['comment_count']}</a>
		<a id="cz" style="width:25px;position:relative;" title="操作" class="cz"> <em class="b4"></em>
			<span style="display: none;z-index:999;" class="show_opration_button">
				<span onclick="hg_stateAudit({$v['id']},{$v['status']});" id="stateAudit_{$v['id']}"  style="margin:4px 2px 0 0;" class="button_2">审核</span>
				<span onclick="hg_statePublish({$v['id']});" id="statePublish_{$v['id']}"  style="margin:4px 2px 0 0;" class="button_2">发布</span>
				<span onclick="hg_stateDel({$v['id']});"  style="margin:4px 2px 0 0;" class="button_2">删除</span>
			</span>
		</a>
		<a class="zt">
			<em><span id="audit_{$v['id']}">{if $v['audit']}已审核{else}待审核{/if}</span></em> 
		</a>
		<a class="tjr zt" style="padding:0;width:170px;">
			<div>
				<img style="display:block;float:left;margin-right:5px;width:36px;height:36px;" width="36" height="36" src="{$v['user']['middle_avatar']}" alt="">
				<div>
					<span style="display:block;color:#8199BD;font-size:12px;">{$v['user']['username']}</span>
					<span style="color:#7B7B7B;font-size:9px;">{$v['create_at']}</span>
				</div>
				
			</div>
		</a>
	</span>
	<span class="title overflow" style="cursor:pointer;font-size:14px;line-height:1.8;">		
		<a href="###">
			<span id="sort_name_86" style="color:#333;padding-right:10px;">{$v['text']}</span>
		</a>
	</span>
</li>