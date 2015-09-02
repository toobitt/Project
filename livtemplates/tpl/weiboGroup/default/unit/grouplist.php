                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:580px;">
								<a class="fl"  style="width:150px;" {if $v['href']}href="{$v['href']}"{/if} target="_blank"><em style="width:150px;">{$v['expired_time']}</em></a>
					  </span>
					  <span class="title overflow"  style="cursor:pointer;">
					  		{code}
					  			$img = $v['picurl']['host'] . $v['picurl']['dir'] ."100x75/". $v['picurl']['filepath'] . $v['picurl']['filename'];
					  		{/code}
							<a title="{$v['name']}" style="verticla-align:middle;"><img src="{$img}" style="vertical-align:middle;width:40px;height:30px;margin-right:10px;"/><span id="title_{$v['id']}">{$v['name']}</span></a>
					  </span>
                </li>