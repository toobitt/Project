                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" tle="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:450px;">
	                    		<a class="fl" style="width:90px;">{$v['column_id']}</a>     
	                    		<a class="fl" style="width:90px;">{$v['source']}</a>           
	                    		{code}
	                    			$v['pub_time'] = date('Y-m-d H:i:s',$v['pubtime']);
	                    		{/code} 	           
								<a class="tjr"  style="width:120px;">{$v['pub_time']}</a>
								<a class="fl" style="width:100px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a> 
					   </span>
					   <span class="title overflow"  style="cursor:pointer;">
					   		{code}
					   			$img = '';
					   			switch($v['source'])
					   			{
					   				case "team":
					   					$img = $v['data']['team_logo']['url'];
					   					break;
					   				case "topic":
					   					if($v['data']['topic_type'] == 'pic')
					   					{
					   						$img = $v['data']['data'][0]['img_info']['url'];
					   					}
					   					else
					   					{
					   						$img = $v['data']['data']['img'];
					   					}
					   					break;
					   				case "user":
					   					if($v['data']['avatar']['host'])
					   					{
					   						$img = $v['data']['avatar']['host'] . $v['data']['avatar']['dir'] . '40x30/' . $v['data']['filepath'] . $v['data']['filename']; 
					   					}
					   					break;
					   				case "action":
					   					$img = $v['data']['action_img']['url'];
					   					break;
					   				default:
					   					$img = "";
					   					
					   			}
					   		{/code}
							{if $img}<img src="{$img}" width="40" height="30"/>{/if}<a title="{$v['title']}"><span id="title_{$v['id']}">{$v['title']}</span></a>
					   </span>
                 </li>