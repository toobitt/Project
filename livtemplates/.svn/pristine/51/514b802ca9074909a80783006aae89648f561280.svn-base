                 <li class="clear"   id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
						
	                        <span class="right"  style="width:69%;">
								<a class="fl overflow" style="width:10%;"><em><span>{$v['issue']}</span></em></a>
								<a class="fl"><em><span >{$v['type_name']}</span></em></a>
								<a class="tjr"><em>{$v['author']}</em><span>{if($v[time])}{$v['time']}{/if}</span></a>
								<div onclick="hg_message_reply({$v['id']},{$_type});">
								<a class="fl" style="width:10%;"><em><span >{$v['repcontent']}</span></em></a>
								</div>
								<a class="tjr"><em>{$v['poster']}</em><span>{if($v[post_time])}{$v['post_time']}{/if}</span></a>
								<a class="zt"><em><span >{if $v['state']}已审核{else}待审核{/if}</span></em></a>
								<a class="fb"  title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&type={$_type}&infrm=1"><em class="b2" ></em></a>
								<a class="fb" style="width:12%;" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;"><a>{$v['title']}</a></span>
						
                </li>