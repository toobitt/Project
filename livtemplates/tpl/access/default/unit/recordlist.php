                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:650px;">
								<a class="fl"  style="width:120px;"><em>{$v['app_bundle']}</em></a>
								<a class="fl"  style="width:120px;"><em>{$v['access_type']}</em></a>
								<a class="fl"  style="width:120px;"><em>{$v['app_name']}</em></a>
								<a class="fl"  style="width:120px;">{$v['ip']}</a>
								<a class="tjr"  style="width:120px;"><em>{$v['user_name']}</em><span>{$v['access_time']}</span></a>
					  </span>
					  <span class="title overflow"  style="cursor:pointer;">
							<a  href="#" title="{$v['url']}"><span id="title_{$v['id']}">{$v['refer_url']}</span></a>
					  </span>
                </li>