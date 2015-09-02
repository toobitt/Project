<!-- 
<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['collect_order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  />
		</a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  style="width:420px">
		<a class="fb"  onclick="return hg_ajax_post(this, '推荐', 0);" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$v['id']}">发布</a>
		<a class="fb"  onclick="hg_showAddCollect(true,{$v['id']});"><em class="b2" ></em></a>
		<a class="fb" href="javascript:void(0);"  title="删除"  onclick="return hg_deleteCollect({$v['id']})" ><em class="b3" ></em></a>
		<a class="fl" ><em style="color:#8197BE;"  id="sort_{$v['id']}" class="overflow">{$v['sort_name']}</em></a>
		<a class="fl"><em class="overflow" style="color:#8197BE;">{$v['admin_name']}</em></a>
		<a class="tjr">{$v['create_time']}</a>
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a href="./run.php?mid={$relate_module_id}&collect_id={$v['id']}{$_ext_link}"   onclick="return hg_look_videos(this);" ><span id="collect_name_{$v['id']}">{$v['collect_name']}{$v['starttime']}</span><strong  id="duration_{$v['id']}">{$v['count']}</strong></a>
	</span>
</li>   
-->
<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['collect_order_id']}" onclick="hg_row_interactive(this, 'click', 'cur');">
	<div class="common-list-left">
		<div class="common-list-item paixu">
			<div class="common-list-cell">
				<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
					<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  />
				</a>
			</div>
		</div>
	</div>
	<div class="common-list-right">
		<div class="common-list-item fabu">
			<div class="common-list-cell">
				<a class="fb"  onclick="return hg_ajax_post(this, '推荐', 0);" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$v['id']}">发布</a>
			</div>
		</div>
		<div class="common-list-item edit">
			<div class="common-list-cell">
				<a class="fb"  onclick="hg_showAddCollect(true,{$v['id']});"><em class="b2" ></em></a>
			</div>
		</div>
		<div class="common-list-item delete">
			<div class="common-list-cell">
				<a class="fb" href="javascript:void(0);"  title="删除"  onclick="return hg_deleteCollect({$v['id']})" ><em class="b3" ></em></a>
			</div>
		</div>
		<div class="common-list-item sort">
			<div class="common-list-cell">
				<em style="color:#8197BE;"  id="sort_{$v['id']}" class="overflow">{$v['sort_name']}</em>
			</div>
		</div>
		<div class="common-list-item ren">
			<div class="common-list-cell">
				<span class="name">{$v['admin_name']}</span><span class="time">{$v['create_time']}</span>
			</div>
		</div>
	</div>
	<div class="common-list-biaoti">
		<div class="common-list-item biaoti-transition">
			<div class="common-list-cell">
				<a href="./run.php?mid={$relate_module_id}&collect_id={$v['id']}{$_ext_link}"   onclick="return hg_look_videos(this);" ><span id="collect_name_{$v['id']}">{$v['collect_name']}{$v['starttime']}</span><strong  id="duration_{$v['id']}">{$v['count']}</strong></a>
			</div>
		</div>
	</div>
</li>