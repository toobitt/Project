                 <li class="clear"  id="r_{$v['team_id']}"    name="{$v['team_id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[team_id]}', 'click', 'cur');"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['team_id']}" tle="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:850px;">
	                    		<a class="fl overflow" style="width:400px;" >{$v['introduction']}</a>      
	                    		{code}
	                    			$v['pub_time'] = date('Y-m-d H:i:s',$v['pub_time']);
	                    		{/code} 	
	                    		<a class="fl" style="width:90px;">{$v['type_name']}</a>
	                    		<a class="fl" style="width:90px;">{$v['category_name']}</a>    
	                    		{code}
	                    			switch($v['state'])
	                    			{
	                    				case 0:
	                    					$status = '未审核';
	                    					$state = 0;
	                    					break;
	                    				case 1:
	                    					$status = "已审核";
	                    					$state = 1;
	                    					break;
	                    				case 2:
	                    					$status = "关闭";
	                    					$state = 0;
	                    					break;
	                    				default:
	                    					$status = '未审核';
	                    					$state = 0;
	                    					break;
	                    			}
	                    		{/code} 
	                    		<a class="fl" style="width:90px;"><span class="news-status-button" style="cursor:pointer;" _id="{$v['team_id']}" _state="{$v['state']}" id="statusLabelOf{$v['team_id']}">{$status}</span></a>                
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
							{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['team_name']}"><span id="title_{$v['team_id']}">{$v['team_name']}</span></a>
					   </span>
                 </li>