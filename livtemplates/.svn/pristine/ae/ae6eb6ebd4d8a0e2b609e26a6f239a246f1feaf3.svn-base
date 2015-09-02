<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  />
		</a>
		
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');" style="width: 740px">
		{code}
			$system_img = $v['system_img']['host'].$v['system_img']['dir'].$v['system_img']['filepath'].$v['system_img']['filename'];
			$user_img = '';
			$bg_image = '';
			if($v['user_img'])
			{
				$user_img = $v['user_img']['host'].$v['user_img']['dir'].$v['user_img']['filepath'].$v['user_img']['filename'];
			}
			if($v['bg_image'])
			{
				$bg_image = $v['bg_image']['host'].$v['bg_image']['dir'].$v['bg_image']['filepath'].$v['bg_image']['filename'];
			}
		{/code}
		<a class="fl"><em><img src="{$system_img}"/></em></a>
		<a class="fl" style="width:180px;"><em id="user_img_{$v['id']}">{if $v['user_img']}<img src="{$user_img}" height="100px"/>{/if}</em></a>
		<a class="fl" style="width:180px;"><em id="bg_img_{$v['id']}">{if $v['bg_image']}<img src="{$bg_image}" height="100px"/>{/if}</em></a> 	
        <a class="tjr"><em>{$v['user_name']}</em><span>{$v['update_time']}</span></a>
        <span>
        	<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">更新</a>
        	
        </span>
	</span>
	<span class="title overflow"  style="cursor:pointer;height:100px">
		<a id="report_{$v['id']}">{$v['title']}</a>
	</span>
</li> 