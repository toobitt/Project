<li style="margin-top:0;height:36px;padding:1px 0;"  id="r_{$v['id']}" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" class="clear">
	<span class="left">
		<a name="alist[]" class="lb">
			<input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]">
		</a>
	</span>
	<span style="width:455px;height:36px;" class="right">
		<a class="zt">{$v['relate_count']}</a>
		<a class="zt">美食</a>
		<a class="zt">10</a>
		<a id="cz" style="width:25px;position:relative;" title="操作" class="cz"> <em class="b4"></em>
			<span style="display: none;" class="show_opration_button">
				<span onclick="return hg_ajax_post(this, '合并话题', 1);" href="#" style="margin:4px 2px 0 0;" class="button_4">合并话题</span>
				<span onclick="return hg_ajax_post(this, '编辑', 1);" style="margin:4px 2px 0 0;" class="button_2">编辑</span>
				<span onclick="return hg_stateDel({$v['id']});" style="margin:4px 2px 0 0;" class="button_2">删除</span>
			</span>
		</a>
		<a class="zt">
			<em><span id="audit_{$v['id']}">{if !$v['status']}已审核{else}待审核{/if}</span></em> 
		</a>
		<a class="tjr">
			<em>{$v['members']['user']['username']}</em>
			<span>{$v['members']['create_time']}</span>
		</a>
	</span>
	<span style="cursor:pointer;" class="title overflow">
		<a href="{$v['link']}">
			<span class="m2o-common-title">
				#{$v['title']}#
			</span>
		</a>
	</span>
</li>