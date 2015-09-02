<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"  _id="{$v['id']}" order_id="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" >
			<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  />
		</a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');" style="width:350px">
		<a class="fb" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
		<a class="fl">{$v['name']}</a>
        <a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;" >
		<a  id="interview_title_{$v['id']}">{$v['content']}</a>
	</span>
</li> 
