                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
							<a class="slt" onclick="hg_open_tuji({$v['id']});"><img src="{if $v['cover_url']}{$v['cover_url']}{else}{$image_resource}hill.png{/if}"   width="40" height="30"   id="img_{$v['id']}"  title="点击查看该图集下的图片" />
							</a>
						</span>
	                        <span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
								<a class="fb" style="display:none;"><em class="b2" onclick="hg_showAddTuJi({$v['id']});"></em></a>
								<a class="fb option-iframe" _tujiid="{$v['id']}"><em class="b2"></em></a>
								<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
								<a class="fl"><em  class="overflow" id="tuji_sort_{$v['id']}">{$v['sort_name']}</em></a>
								<a class="zt" > <em><span class="zt_a need-switch" id="tuji_status_{$v['id']}" data-id="{$v['id']}" data-status="{$v['status']}">{$v['status']}</span></em></a>
								<a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
						   </span>
						<span class="title overflow"  style="cursor:pointer;" onclick="hg_show_opration_info({$v['id']})">
						<span class="c_a">
								{if $v['pubinfo'][1]}
									<span class="lm"><em class="{if $v['status_display'] == 1}{else}b{/if}"  id="img_lm_{$v['id']}"    onmouseover="hg_fabu({$v[id]})"  onmouseout="hg_back_fabu({$v[id]})"></em></span>
								{/if}
								{if $v['pubinfo'][2]}
									<span class="sj"><em class="{if $v['status_display'] == 1}{else}b{/if}"  id="img_sj_{$v['id']}"    onmouseover="hg_fabu_phone({$v[id]})"  onmouseout="hg_back_fabu_phone({$v[id]})"></em></span>
								{/if}
						</span>
						<a  id="tuji_title_{$v['id']}">{$v['title']}</a>
						</span>
						<span class="fb_column"  style="display:none;"   id="fabu_{$v['id']}" >
							<span class="fb_column_l"></span>
							<span class="fb_column_r"></span>
							<span class="fb_column_m"><em></em><span class="fsz">发送至网站：</span>
							{if $v['pubinfo'][1]}
								{foreach $v['pubinfo'][1] as $c}
									<a class="overflow">{$c}</a>
								{/foreach}
							{/if}
							</span>
						</span>
						<span class="fb_column phone"  style="display:none;"   id="fabu_phone{$v['id']}" >
							<span class="fb_column_l"></span>
							<span class="fb_column_r"></span>
							<span class="fb_column_m"><em></em><span class="fsz" >发送至手机：</span>
							{if $v['pubinfo'][2]}
								{foreach $v['pubinfo'][2] as $c}
									<a class="overflow">{$c}</a>
								{/foreach}
							{/if}
							</span>
						</span>
						<div class="content_more clear" id="content_{$v['id']}"  style="display:none;height:auto;"></div>
                </li>   