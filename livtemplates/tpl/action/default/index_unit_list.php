{if !$_INPUT['id']}
<li class="clear"  id="r_{$formdata['id']}" name="{$formdata['team_id']}" orderid="{$formdata['order_id']}" onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
    <span class="right"  style="width:720px;">
   			<a class="fl" style="width:400px;">{$formdata['title']}</a>
    		<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$formdata['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a> 
    		<a class="fl" style="width:35px;" onclick="hg_showEditIndex('{$formdata['id']}');">编辑</a>         
			<a class="tjr"  style="width:120px;"><em>{code} echo date('Y-m-d H:i:s',$formdata['update_time']){/code}</em></a>
   </span>
   <span class="title overflow"  style="cursor:pointer;">
   		{code}
   			$header_img = '';
   			if(!empty($formdata['host']))
   			{
   				$header_img = $formdata['host'] . $formdata['dir'] .'40x30/'. $formdata['filepath'] . $formdata['filename'];
   			}
   		{/code}
		{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$formdata['name']}"><span id="title_{$formdata['id']}">{$formdata['name']}</span></a>
   </span>
</li>
{else}
 <span class="right"  style="width:720px;">
   			<a class="fl" style="width:400px;">{$formdata['title']}</a>
    		<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$formdata['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a> 
    		<a class="fl" style="width:35px;" onclick="hg_showEditIndex('{$formdata['id']}');">编辑</a>         
			<a class="tjr"  style="width:120px;"><em>{code} echo date('Y-m-d H:i:s',$formdata['update_time']){/code}</em></a>
   </span>
   <span class="title overflow"  style="cursor:pointer;">
   		{code}
   			$header_img = '';
   			if(!empty($formdata['host']))
   			{
   				$header_img = $formdata['host'] . $formdata['dir'] .'40x30/'. $formdata['filepath'] . $formdata['filename'];
   			}
   		{/code}
		{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$formdata['name']}"><span id="title_{$formdata['id']}">{$formdata['name']}</span></a>
   </span>
{/if}