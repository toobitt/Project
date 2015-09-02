                 <li class="clear"  id="r_{$v['thread_id']}"    name="{$v['thread_id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v['thread_id']}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                        <span class="right"  style="width:540px; position:relative;" >
								<a class="cz"  title="操作"  style="width:40px;" id="cz" ><em class="b4"></em>
								</a>
								<span class="rr_1" id="rr_1_{$v['thread_id']}">
									<a class="zt" style="width:65px;"><em><span>{if $v['type_name']}{$v['type_name']}{else}未分类{/if}</span></em></a>
									<a class="zt overflow" style="width:120px;"><em><span >{$v['group_name']}</span></em></a>
									<a class="fl" style="width:65px;"><em><span >{$v['post_count']}/{$v['click_count']}</span></em></a>
									<a class="zt" style="width:65px;"><em><span id="text_{$v['thread_id']}">{if $v['audit']}已审核{else}待审核{/if}</span></em></a>
									<a class="tjr"><em>{$v['user_name']}</em><span>{$v['pub_time']}</span></a>
								</span>
								<span class="rr_2" id="rr_2_{$v['group_id']}" style="display:none;z-index:1000px;position:absolute;top:0px;left:0px;padding-left:30px;">
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=audit&thread_id={$v['thread_id']}" onclick="return hg_ajax_post(this, '审核', 1);">审核</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=back&thread_id={$v['thread_id']}" onclick="return hg_ajax_post(this, '打回', 1);">打回</a>
									<a class="button_4" style="margin-top:4px;" href="javascript:void(0);" onclick="hg_statePublish({$v['thread_id']});">发布</a>
									<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&thread_id={$v['thread_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
								</span>
						   </span>
						<span class="title overflow"  style="cursor:pointer;">
							<a href="javascript:void(0);">
							<!--onclick="hg_show_opration_info({$v['group_id']},{if $_INPUT['_type']}{$_INPUT['_type']}{else}''{/if},{if $_INPUT['_id']}{$_INPUT['_id']}{else}''{/if});"-->
								<span >
									{if $v['arrow_img']}
										{$v['arrow_img']}
									{/if}
									{if $v['logo']}
									<img style="margin-right:5px;vertical-align:middle;" width="40" height="30" src="{$v['logo']}" alt="缩略图" />
									{/if}
									{$v['title']}
									<a style="font-size:12px;color:blue;" href="{$v['link']}" target="_blank">原文</a>
								</span>

							</a>
					 </span>
                </li>