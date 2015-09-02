                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" tle="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:300px;">
	                   		    <a class="fl" style="width:120px;" href="?mid={$_INPUT['mid']}&a=form&id={$v['id']}{$_ext_link}">编辑</a>  
	                    		<!--<a class="fl" style="width:120px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>       -->    
					   </span>
					   <span class="title overflow"  style="cursor:pointer;">
							<a title="{$v['title']}"><span id="title_{$v['id']}">{$v['name']}</span></a>
					   </span>
                 </li>