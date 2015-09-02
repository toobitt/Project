                 <li class="clear"  id="r_{$formdata['id']}"    name="{$formdata['id']}"   orderid="{$formdata['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$formdata[$primary_key]}" title="{$formdata[$primary_key]}"  /></a>
							<a class="fb overflow"  style="width:120px;"  id="tuji_sort_name_{$formdata['id']}">{$formdata['name']}</a>
						</span>
	                        <span class="right" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');"  style="width:239px;">
								<a class="fb" title="编辑" href="javascript:void(0)" onclick="return hg_showAddpicsort({$formdata['id']})"><em class="b2" ></em></a>
								<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}"><em class="b3" ></em></a>
								<a class="tjr"><em>{$formdata['user_name']}</em><span>{$formdata['create_time']}</span></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;"><a id="tuji_sort_desc_{$formdata['id']}">{$formdata['brief']}</a></span>
                </li>