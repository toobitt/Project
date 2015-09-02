<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left" style="width:120px" >
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  />
		</a>
		<div id="pic_{$v['id']}">
			<a class="fl" style="width:50px" href="{$v['ori_pic']}">
				<img alt="" src="{$v['pic']}"  onclick="show_pic({$v['id']})">
			</a>
		</div>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  style="width:800px">
		<a class="fl">{$v['file_type']}</a>
        <a class="fl">{$v['file_size']}</a>
        <a class="fl">{$v['show_pos']}</a>
        <a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
		<a class="fl"  id ="disable_{$v['id']}">
			{if ($v['cover_pic'] == $v['id'])}
				封面图片
			{else}
				{if $v['is_ban']}
					<span  style="color:#ff0000"  title="点击可解除禁用"  onclick="hg_pic_disable({$v['id']},{$v['is_ban']},{$v['interview_id']})">已禁用</span>
					{else}
					<span  title="点击可禁用图片" onclick="hg_pic_disable({$v['id']},{$v['is_ban']},{$v['interview_id']})"  style="color:green">未禁用</span>
				{/if}
				
			{/if}
		</a>
        <a class="fl" id ="cover_{$v['id']}">
        	{if($v['is_ban']==1)}
        		已禁用
        		{else}
        		{if ($v['cover_pic'] == $v['id'])}
        			<span title="点击取消封面"  onclick="hg_pic_cover({$v['id']},{$v['interview_id']})" style="color:#ff0000">取消封面</span>
        			{else}
        			<span title="点击设为封面" onclick="hg_pic_cover({$v['id']},{$v['interview_id']})" style="color:green">设为封面</span>
        		{/if}
        	{/if}
        
        </a>
        <span>
        	<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&interview_id={$_INPUT['interview_id']}&kid={$_INPUT['mid']}&infrm=1"><i class="hg_icons edit_icon"></i></a>
			<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><i class="hg_icons del_icon"></i></a>
        </span>      
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a  id="interview_title_{$v['id']}">
		<span class="m2o-common-title">{$v['name']}</span></a>
	</span>
</li> 
