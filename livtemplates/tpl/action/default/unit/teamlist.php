                 <li class="clear"  id="r_{$v['team_id']}"    name="{$v['team_id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[team_id]}', 'click', 'cur');"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:720px;">
	                    		<a class="fl overflow" style="width:400px;">{$v['introduction']}</a>      
	                    		{code}
	                    			$v['pub_time'] = date('Y-m-d H:i:s',$v['pub_time']);
	                    		{/code} 
	                    		<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&team_id={$v['team_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a> 	
	                    		<a class="fl" style="width:35px;" onclick="hg_showEditTeam('{$v['team_id']}','{$v['team_name']}');">编辑</a> 
	                    		<a class="fl" style="width:80px;" onclick="hg_showRecommond('{$v['team_id']}','{$v['team_name']}','team');">推送至</a>           
								<a class="tjr"  style="width:120px;"><em>{$v['creater_name']}</em><span>{$v['pub_time']}</span></a>
					   </span>
					   <span class="title overflow"  style="cursor:pointer;">
					   		{code}
					   			$header_img = '';
					   			if(!empty($v['team_logo']))
					   			{
					   				$header_img = $v['team_logo']['host'] . $v['team_logo']['dir'] .'40x30/'. $v['team_logo']['filepath'] . $v['team_logo']['filename'];
					   			}
					   		{/code}
							{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['team_name']}"><span id="title_{$v['id']}">{$v['team_name']}</span></a>
					   </span>
                 </li>