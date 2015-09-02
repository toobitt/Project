                 <li class="clear"  id="r_{$v['action_id']}"    name="{$v['action_id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:560px;">
	                    		<a class="fl overflow" style="width:220px;display:block;height:100%;"> {$v['slogan']}</a>
	                   	        {code}
	                    			$v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
	                    		{/code}
	                    		<!--<a class="fl" style="width:70px;" onclick="hg_showEditActivity('{$v['action_id']}','{$v['action_name']}');">编辑</a>   -->
	                    		<a class="fl" style="width:70px;");" href="./run.php?mid={$_INPUT['mid']}&a=op&state=0{$_ext_link}&action_id={$v['action_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
                                {if $v['isopen']}
                                <a class="fl" style="width:30px;" onclick="hg_showEditActivity('{$v['action_id']}','{$v['action_name']}');">编辑</a>
	                    		<a class="fl" style="width:40px;" onclick="hg_showRecommond('{$v['action_id']}','{$v['action_name']}','action');">推送至</a>
								{else}
								<a class="fl" style="width:30px;" >&nbsp;&nbsp;</a>
								<a class="fl" style="width:40px;" >&nbsp;&nbsp;&nbsp;</a>
								{/if}
								<a class="tjr"  style="width:120px;"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
					   </span>
					   <span class="title overflow"  style="cursor:pointer;">
					   		{code}
					   			if(!empty($v['action_img']))
					   			{
					   				$header_img = $v['action_img']['host'] . $v['action_img']['dir'] .'40x30/'. $v['action_img']['filepath'] . $v['action_img']['filename'];
					   			}
					   		{/code}
							{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['action_name']}"><span id="title_{$v['action_id']}">{$v['action_name']}</span></a>
					   </span>
                 </li>