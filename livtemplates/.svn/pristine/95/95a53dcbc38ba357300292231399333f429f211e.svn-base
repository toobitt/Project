<li class="clear"  id="r_{$v['id']}" name="{$v['team_id']}" orderid="{$v['order_id']}" onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
    <span class="right"  style="width:720px;">
   			<a class="fl" style="width:400px;">{$v['title']}</a>
    		<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a> 
    		<a class="fl" style="width:35px;" onclick="hg_showEditIndex('{$v['id']}');">编辑</a>         
			<a class="tjr"  style="width:120px;"><em>{code} echo date('Y-m-d H:i:s',$v['update_time']){/code}</em></a>
   </span>
   <span class="title overflow"  style="cursor:pointer;">
   		{code}
   			$header_img = '';
   			if(!empty($v['host']))
   			{
   				$header_img = $v['host'] . $v['dir'] .'40x30/'. $v['filepath'] . $v['filename'];
   			}
   		{/code}
		{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['name']}"><span id="title_{$v['id']}">{$v['name']}</span></a>
   </span>
</li>