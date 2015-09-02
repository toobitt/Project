<li class="clear"  id="r_{$v['id']}"  name="{$v['id']}"  orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left" id = "pic_{$v['id']}">
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  />
		</a>
		{code}
				$ori_avatar = '';
				$low_avatar = '';
				if($v['avatar']['host'])
				{
					$ori_avatar = $v['avatar']['host'].$v['avatar']['dir'].$v['avatar']['filepath'].$v['avatar']['filename'];
					$low_avatar = $v['avatar']['host'].$v['avatar']['dir'].'40x30/'.$v['avatar']['filepath'].$v['avatar']['filename'];
				}
		{/code}	
			<a class="fl" style="width:50px" href="{$ori_avatar}">
				<img alt="" src="{$low_avatar}" onclick="show_pic({$v['id']})"/>
			</a>
	</span>
	
	<span class="right"  style="width:800px">
		<a style="width:35%;" id="text_{$v['id']}" onclick="hg_show_sel({$v['id']},this.innerHTML)">{if $v['group']}{$interview_user_list[0]['group'][$v['group']]}{else}暂无分组{/if}</a>	
		<a style="width:35%;display:none"  id="showsel_{$v['id']}">
			<select id="sel_{$v['id']}" onchange="hg_onchange_showtext({$v['id']},this.value)" onblur="hg_onblur_showtext({$v['id']})">
			{foreach  $interview_user_list[0]['group'] as $key => $value}
					{if $key!=-1}
					<option value="{$key}" {if $key == $v['group']} selected="selected" {/if}>{$value}</option>
					{/if}
			{/foreach}
			</select>
		</a>	
		<a style="width:35%" id="auth_{$v['id']}">{if $_configs['roles'][$v['authority']]}{$_configs['roles'][$v['authority']]}{else}暂无角色{/if}</a> 
		<span>
		<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">
			<i class="hg_icons del_icon"></i>
		</a>
		</span>
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a  id="interview_title_{$v['id']}"><span class="m2o-common-title">{$v['name']}</span></a>
	</span>
</li> 
