<li class="clear"  id="r_{$v['appid']}" style="margin-top:0;height:36px;padding:1px 0;" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
	<span class="left">		
		<a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['appid']}" title="{$v['appid']}" /></a>	
	</span>
	<span style="width:510px;height:36px;" class="right">
		<a class="zt" href="{$v['url']}" target="_blank" title="{$v['url']}">{$v['url']}</a>
		<a class="zt" style="margin-left: 183px;width:80px;">
			<span style="display:inline-block;color:gray;" id="status_{$v['appid']}"></span>
			<script id="link_{$v['appid']}" testlink="./run.php?mid={$_INPUT['mid']}&a=ping&url={$v['url']}&id={$v['appid']}"></script>
			<script>apps[{$i}] = "{$v['appid']}";</script>
		</a>
		<a id="cz" style="width:25px;position:relative;margin-left: 56px;" title="操作" class="cz"> <em class="b4"></em>
			<span style="display: none;z-index:999;width: 200px;" class="show_opration_button">
				<span onclick="window.location.href = './run.php?mid={$_INPUT['mid']}&a=form&id={$v['appid']}&infrm=1'"  style="margin:4px 2px 0 0;" class="button_2">编辑</span>
				<span onclick="hg_appDel({$v['appid']});"  style="margin:4px 2px 0 0;" class="button_2">删除</span>
			</span>
		</a>
	</span>
	<span class="title overflow" style="cursor:pointer;font-size:14px;line-height:1.8;">		
		<a href="###">
			<span id="sort_name_86" style="color:#333;padding-right:10px;">{$v['name']}</span>
		</a>
	</span>
</li>