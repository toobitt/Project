                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                        <span class="right"  style="width:781px;">
								<a class="fl overflow" href="./run.php?mid={$_INPUT['mid']}&type=allreply&contentid={$v['messageid']}&infrm=1" style="width:220px;"><em><span>{$v['content']}</span></em></a>
								<a class="fl"><em><span >{$v['groupname']}</span></em></a>
								<a class="fl"><em><span >{$v['contentid']}</span></em></a>
								<a class="fl"><em><span id="contribute_audit_{$v['id']}">{$v['state']}</span></em></a>
								<a class="tjr"><em>{$v['answerer']}</em><span>{$v['reply_time']}</span></a>
								<a class="fb"  title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" ></em></a>
								<a class="fb" style="width:107px;" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;"><a>{$v['content_reply']}</a></span>
                </li>