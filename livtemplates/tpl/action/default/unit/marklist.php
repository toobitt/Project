                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" tle="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:350px;">
	                    		<a class="fl" style="width:100px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>  
	                    		<a class="fl" style="width:100px;">{$_configs['cat'][$v['kind_name']]}</a>  
	                    		{code}
	                    			$v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
	                    		{/code}    	
	                    		<a class="fl" style="width:120px;">{$v['create_time']}</a>                
					   </span>
					   <span class="title overflow"  style="cursor:pointer;">
							<a title="{$v['mark_name']}"><span id="title_{$v['id']}">{$v['mark_name']}</span></a>
					   </span>
                 </li>