<li class="clear"  id="r_{$v['uid']}" style="margin-top:0;height:36px;padding:1px 0;" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
	<span class="left">		
		<a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['uid']}" title="{$v['uid']}" /></a>	
	</span>
	<span style="width:710px;height:36px;" class="right">
		<a class="zt" style="">{$v['email']}</a>
		<a class="zt" style="margin-left: 180px;width:120px;">{$v['regip']}</a>
		<a id="cz" style="width:25px;position:relative;margin-left: 24px;" title="操作" class="cz"> <em class="b4"></em>
			<span style="display: none;z-index:999;width: 200px;" class="show_opration_button">
				<!-- <span onclick="hg_userAudit({$v['uid']},{$v['status']});" id="userAudit_{$v['uid']}"  style="margin:4px 2px 0 0;" class="button_2">审核</span> -->
				<span onclick="window.location.href = './run.php?mid={$_INPUT['mid']}&a=form&id={$v['uid']}&uname={$v['username']}&infrm=1'"  style="margin:4px 2px 0 0;" class="button_2">编辑</span>
				<span onclick="hg_userDel({$v['uid']});"  style="margin:4px 2px 0 0;" class="button_2">删除</span>
			</span>
		</a>
		<!-- <a class="zt">
			<em><span id="audit_{$v['uid']}">{if $v['status']}已审核{else}待审核{/if}</span></em> 
		</a> -->
		<a class="zt"><em><span style="display:inline-block;"></span></em></a>
		<a class="tjr zt" style="padding:0;width:170px;">
			<span style="color:#7B7B7B;font-size:9px;line-height: 36px;">{$v['create_time']}</span>
		</a>
	</span>
	<span class="title overflow" style="cursor:pointer;font-size:14px;line-height:1.8;">		
		<a href="###">
			<span id="sort_name_86" style="color:#333;padding-right:10px;">
			<img style="display:block;float:left;margin-right:5px;width:36px;height:36px;" width="36" height="36" src="{$v['smallavatar']}" alt="">{$v['username']}</span>
		</a>
	</span>
</li>