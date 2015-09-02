<li class="list_first clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">                 
    <span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
		<a class="fl" style="width:100px">{$v['id']}</a>
		<!--<a class="shareslt" href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['id']}">{$v['name']}{if $v['is_last']==0} >>{/if}</a>
		-->
		<a class="shareslt">{$v['name']}</a>
	</span>
    <span class="right" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');">
		<a style="width:50px" title="编辑" href="./run.php?mid={$_INPUT['mid']}&speid={$_INPUT['speid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
		<a style="width:50px" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</span>
	<span class="title overflow"  style="cursor:pointer;"><a></a></span>
</li>