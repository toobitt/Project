                 <li class="clear"  id="r_{$v['topic_id']}"    name="{$v['topic_id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[topic_id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['topic_id']}" title="{$v['topic_id']}"  /></a>
						</span>
	                    <span class="right"  style="width:330px;">
	                    		{code}
	                    			$v['pub_time'] = date('Y-m-d H:i:s',$v['pub_time']);
	                    		{/code} 
	                    		<a class="fl" style="width:80px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&topic_id={$v['topic_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>   
	                    		<a class="fl" style="width:80px;" onclick="hg_showRecommond('{$v['topic_id']}','{$v['topic_type']}','topic');">推送至</a>    	           
								<a class="tjr"  style="width:120px;"><em>{$v['creater_name']}</em><span>{$v['pub_time']}</span></a>
					   </span>
					   <span class="title overflow"  style="cursor:pointer;">
					   		{code}
					   			$header_img = '';
								switch ($v['topic_type'])
								{
									case "pic":
										$header_img = $v['data'][0]['img_info']['url'];
										$v['subject'] = '图片';
										break;
									case "video":
										$header_img = $v['data']['img'];
										$v['subject'] = '视频';
										break;
									default:
										$header_img = '';
								}
					   		{/code}
							{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['subject']}"><span id="title_{$v['topic_id']}">{$v['subject']}</span></a>
					   </span>
                 </li>