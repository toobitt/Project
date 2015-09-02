<li class="clear"  id="r_{$v['id']}" style="margin-top:0;height:36px;padding:1px 0;" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
	<span class="left">		
		<a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>	
	</span>
	<span style="width:516px;height:36px;" class="right">
		<a class="zt" style="margin-left: 120px;width:120px;">{$v['appname']}</a>
		<a id="cz" style="width:25px;position:relative;margin-left: 24px;" title="操作" class="cz"> <em class="b4"></em>
			<span style="display: none;z-index:999;width: 200px;" class="show_opration_button">
				<span onclick="hg_memberDel({$v['id']});"  style="margin:4px 2px 0 0;" class="button_2">删除</span>
			</span>
		</a>
		<a class="zt"><em><span style="display:inline-block;"></span></em></a>
		<a class="tjr">
			<em>{$v['user_name']}</em>
			<span>{$v['create_time']}</span>
		</a>
	</span>
	<span class="title overflow" style="cursor:pointer;font-size:14px;line-height:1.8;">		
		<a href="###">
			<span id="sort_name_86" style="color:#333;padding-right:10px;">{$v['title']}</span>
		</a>
	</span>
</li>