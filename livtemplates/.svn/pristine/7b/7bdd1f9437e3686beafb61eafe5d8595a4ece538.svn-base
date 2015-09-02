<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}" _id="{$v['id']}"  order_id="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left" style="width:120px" >
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  />
		</a>
		<div id="pic_{$v['id']}">
			<a class="fl" href="{$v['avatar']}">
				<img src="{$v['avatar']}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" onclick="show_pic({$v['id']})">
			</a>
		</div>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  style="width:770px">
        <a class="fb" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
		<a class="fl">{$v['plat_name']}</a>
        <a class="fl">{$v['type_name']}</a>	
		<a class="fl" id="status_{$v['id']}">{if $v['audit']}已审核{else}未审核{/if}</a>
        {if $v['can_access']}
        <a class="fl" style="width: 115px">{$v['expired_time']}</a>
        {else}
        <a class="fl" href="#" style="color:red;" onclick="hg_reset_auth({$v['id']});">授权已过期,点击此处重新授权</a>
        {/if}
		<a class="fl" onclick="push_queue({$v['id']});"><em id="queue_{$v['id']}">获取报料</em></a>        
		<a class="tjr"><em>{$v['user_name']}</em><span>{$v['update_time']}</span></a>   
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a  id="interview_title_{$v['id']}">{$v['nickname']}</a>
	</span>
</li> 