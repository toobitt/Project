                 <li class="clear"  id="r_{$v['group_id']}"    name="{$v['group_id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" 
				 onclick="hg_row_interactive(this,'click','cur');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left" style="margin-top:10px;">
							<a class="lb" onclick="hg_row_interactive('#r_{$v['group_id']}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                        <span class="right"  style="width:600px; position:relative;height:36px;margin-top:7px;" >
								<a class="cz"  title="操作"  style="width:30px;" id="cz" ><em class="b4"></em>
								</a>
								<span class="rr_1" id="rr_1_{$v['group_id']}">
									<a class="zt" style="width:80px;"><em><span>{$v['post_count']}</span></em></a>
									<a class="zt" style="width:70px;"><em><span >{$v['thread_count']}</span></em></a>
									<a class="zt" style="width:65px;"><em><span >{$v['group_unconfirmed_member_count']}/{$v['group_member_count']}</span></em></a>
									<a class="zt" style="width:70px;"><em><span>{$v['type_name']}</span></em></a>
									<a class="zt" style="width:65px;"><em><span id="text_{$v['group_id']}">{if $v['audit']}已审核{else}待审核{/if}</span></em></a>
									<a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
								</span>
								<span class="rr_2" id="rr_2_{$v['group_id']}" style="display:none;z-index:1000px;position:absolute;top:0px;left:0px;padding-left:30px;">
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=form&group_id={$v['group_id']}&infrm=1">编辑</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&group_id={$v['group_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=audit&group_id={$v['group_id']}" onclick="return hg_ajax_post(this, '审核', 1);">审核</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=back&group_id={$v['group_id']}" onclick="return hg_ajax_post(this, '打回', 1);">打回</a>
									<a class="button_4" style="margin-top:4px;" href="javascript:void(0);" onclick="hg_statePublish({$v['group_id']});" id="statePublish_{$v['group_id']}" >发布</a>
								</span>
						   </span>
						<span class="title overflow"  style="cursor:pointer;">
							<a href="###">
								<span id="title_{$v['group_id']}" onclick="hg_show_opration_group({$v['group_id']},{if $_INPUT['_type']}{$_INPUT['_type']}{else}''{/if},{if $_INPUT['_id']}{$_INPUT['_id']}{else}''{/if});">
									{if $v['logo']}
										<img style="margin-right:5px;vertical-align:middle;" width="50" height="50" src="{$v['logo']}" alt="">
									{/if}
									{$v['name']}
								</span>
							</a>
					  </span>
                </li>