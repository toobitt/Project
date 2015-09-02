                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:750px;">
							    <!--<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&table_name={$v['table']}" style="margin-top:-2px;"><em class="b3" ></em></a>-->
								<a class="fl"  style="width:120px;"><em>{$v['app_bundle']}</em></a>
								<a class="fl"  style="width:120px;"><em>{$v['module_bundle']}</em></a>
								<a class="fl"  style="width:120px;"><em>{$v['cid']}</em></a>
								<a class="fl"  style="width:120px;">{$v['ip']}</a>
								<a class="fl"  style="width:120px;">{$v['access_time']}</a>
					  </span>
					  <span class="title overflow"  style="cursor:pointer;">
							<a><span id="title_{$v['id']}">{$v['refer_url']}</span></a>
					  </span>
                </li>