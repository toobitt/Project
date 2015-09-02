                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:400px;">
	                    		<a class="fl" style="width:100px;" onclick="hg_showRecommond('{$v['id']}','{$v['nick_name']}','user');">推送至</a> 	           
								<a class="tjr"  style="width:120px;"><span>{$v['create_time']}</span></a>
					    </span>
					    <span class="title overflow"  style="cursor:pointer;">
					   		{code}
					   			if(!empty($v['team_logo']))
					   			{
					   				$header_img = $v['team_logo']['host'] . $v['team_logo']['dir'] .'40x30/'. $v['team_logo']['filepath'] . $v['team_logo']['filename'];
					   			}
					   		{/code}
							{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['nick_name']}"><span id="title_{$v['id']}">{$v['nick_name']}</span></a>
					    </span>
                 </li>