<li class="clear"  id="r_{$v['rid']}"    name="{$v['rid']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[rid]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:560px;">
	                    		<a class="fl overflow" style="width:220px;display:block;height:100%;"> {$v['source_name']}</a>
	                   	        {if $v['state'] != 2}
	                    		<a class="fl" style="width:100px;">{$_configs['state'][$v['state']]}</a>
	                    		{else}
	                    		<a class="fl" style="width:50px;" href="./run.php?mid={$_INPUT['mid']}&a=update{$_ext_link}&rid={$v['rid']}" onclick="return hg_ajax_post(this, '处理', 1);">保留数据</a> 
	                    		<a class="fl" style="width:50px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&rid={$v['rid']}" onclick="return hg_ajax_post(this, '删除', 1);">删除举报</a> 
	                    		{/if}
								<a class="tjr"  style="width:100px;"><em>{$v['user_id']}</em><span>{$v['create_time']}</span></a>
					   </span>
					   <span class="title overflow"  style="cursor:pointer;">
					   		{code}
					   			if(!empty($v['source_img']))
					   			{
					   				$header_img = $v['source_img']['host'] . $v['source_img']['dir'] .'40x30/'. $v['source_img']['filepath'] . $v['source_img']['filename'];
					   			}
					   		{/code}
							{if $header_img}<img src="{$header_img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;" />{else}{/if}<a title="{$v['source_name']}"><span id="title_{$v['rid']}" class="m2o-common-title">{$v['source_name']}</span></a>
					   </span>
                 </li>