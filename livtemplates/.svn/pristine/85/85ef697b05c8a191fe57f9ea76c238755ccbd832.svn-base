                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                        <span class="right"  style="width:817px;">
								<a class="fl overflow" style="width:320px;" href="#"><em><span class="m2o-common-title">{$v['title']}</span></em></a>						
								<a class="zt" style="width:100px"><em><span >{if $v['state']}已审核{else}待审核{/if}</span></em></a>
								<a class="tjr" style="width:160px;"><em>{$v['user']}</em><span>{$v['create_time']}</span></a>
								<a class="fb"  title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" ></em></a>
								<a class="fb" style="width:107px;" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&infrm=1"><em class="b3" ></em></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;"><a>{$v['content']}</a></span>
                </li>