 {code}
 	$image_resource = RESOURCE_URL;
 {/code}                
<li class="clear"  id="r_{$formdata['id']}"    name="{$formdata['id']}"   orderid="{$formdata['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$formdata['id']}" title="{$formdata['id']}"  /></a>
		<a class="slt" onclick="hg_open_tuji({$formdata['id']});"><img src="{if $formdata['cover_url']}{$formdata['cover_url']}{else}{$image_resource}hill.png{/if}"   width="40" height="30"   id="img_{$formdata['id']}"  title="点击查看该图集下的图片" />
		</a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');">
		<a class="fb"><em class="b2" onclick="hg_showAddTuJi({$formdata['id']});"></em></a>
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}"><em class="b3" ></em></a>
		<a class="fl"><em  class="overflow" id="tuji_sort_{$formdata['id']}">{$formdata['sort_name']}</em></a>
		<a class="zt" > <em><span class="zt_a" id="tuji_status_{$formdata['id']}">{$formdata['status']}</span></em></a>
		<a class="tjr"><em>{$formdata['user_name']}</em><span>{$formdata['create_time']}</span></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;" onclick="hg_show_opration_info({$formdata['id']})"><a  id="tuji_title_{$formdata['id']}">{$formdata['title']}</a></span>
</li>   