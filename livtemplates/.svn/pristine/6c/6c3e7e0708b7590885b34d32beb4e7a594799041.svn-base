<li class="clear"  id="row_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left" style="width:120px" >
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
			<input type="checkbox" name="userlist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  />
		</a>	
			{code}
				$ori_avatar = '';
				$low_avatar = '';
				if($v['avatar'])
				{
					$ori_avatar = $v['avatar']['host'].$v['avatar']['dir'].$v['avatar']['filepath'].$v['avatar']['filename'];
					$low_avatar = $v['avatar']['host'].$v['avatar']['dir'].'40x30/'.$v['avatar']['filepath'].$v['avatar']['filename'];
				}
			{/code}	
			<a class="fl" style="width:50px" href="{$ori_avatar}">
				<img alt="" src="{$low_avatar}" >
			</a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
		<a class="fl"><input type="button" value="加入访谈" class="button_4"  onclick="parent.hg_addUser({$v['id']},{$v['interview_id']})" /></a>    
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a  id="interview_title_{$v['id']}">{$v['nick_name']}</a>
	</span>
</li> 
