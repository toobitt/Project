                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                        <span class="right"  style="width:440px; position:relative;" >
								<a class="cz"  title="操作"  style="width:25px;" id="cz" ><em class="b4"></em>
								</a>
								<span class="rr_1" id="rr_1_{$v['id']}">
									<a class="zt" style="width:80px;"><em><span >{$v['name']}</span></em></a>
									<a class="zt"><em><span id="text_{$v['id']}">{if $v['state']}已审核{else}待审核{/if}</span></em></a>
									<a class="fl"><em><span>{$v['comm_num']}/{$v['click_num']}</span></em></a>
									<a class="tjr"><em>{$v['author']}</em><span>{$v['create_time_show']}</span></a>
								</span>
								<span class="rr_2" id="rr_2_{$v['id']}" style="display:none;z-index:1000px;position:absolute;top:0px;left:0px;padding-left:30px;">
									<a class="button_4 option-iframe" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&infrm=1" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$v['id']}" onclick="return hg_ajax_post(this, '审核', 1);">审核</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=back&id={$v['id']}" onclick="return hg_ajax_post(this, '打回', 1);">打回</a>
								</span>
						   </span>
						<span class="title overflow"  style="cursor:pointer;"><a>
						    {if $v['indexpic_url']}
							<div style="display:inline-block;height:100%;vertical-align:middle;margin-right:10px;width:40px;"><img  src="{$v['indexpic_url']}" style="vertical-align:middle;width:40px;height:30px;"  class="img_{$v['id']}" title="点击(显示/关闭)截图"  onclick="check_menu({$v['id']});" /></div>
							{/if}
							<span id="title_{$v['id']}" onclick="hg_show_opration_info({$v['id']},{if $_INPUT['_type']}{$_INPUT['_type']}{else}''{/if},{if $_INPUT['_id']}{$_INPUT['_id']}{else}''{/if});">{$v['title']}</span></a>
						</span>


						 <div class="content_more clear" id="content_{$v['id']}"  style="display:none;">
                             <div id="show_list_{$v['id']}" class="pic_list_r">
								  {foreach $v['material'] as $mk => $mv}
									<div class="material_list">
										<img src="{$mv['url']}" alt="$mv['filename']" onclick="hg_material_indexpic({$v['id']},{$mk});" />
						            </div>
								 {/foreach}
							</div>
                        </div>
                </li>