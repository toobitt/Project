                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
							<a class="slt" ><img src="{$v['pic_url']}"   width="40" height="30"   id="img_{$v['id']}"  title="点击(显示/关闭)截图 " />
							</a>
						</span>
	                        <span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
								<a class="fb" href="javascript:void(0);"  onclick="hg_showAddTuJipics({$v['id']});"><em class="b2" ></em></a>
								<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
								<a class="fl"><em  class="overflow">{$v['tuji_title']}</em></a>
								<a class="zt"><em><span class="zt_a">{$v['status']}</span></em></a>
								<a class="tjr"><span>{$v['create_time']}</span></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;"><a id="tuji_pics_title_{$v['id']}">{$v['old_name']}</a></span>
                </li>