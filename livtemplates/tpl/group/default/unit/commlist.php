			<li id="r_{$v['post_id']}" style="margin-top:0;padding:1px 0;" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" class="clear">
				<span class="left">
					<a name="alist[]" onclick="hg_row_interactive('#r_{$v['post_id']}', 'click', 'cur');" class="lb">
						<input type="checkbox" title="{$v['post_id']}" value="{$v['post_id']}" name="infolist[]">
					</a>
				</span>
	             <span class="right"  style="width:550px; height:36px;position:relative;" >
					<a target="_blank" href="{$v['pagelink']}" class="zt overflow" style="width:250px;margin-top:2px;"><span style="color:#8197BE;">@{$v['tname']} </span><span style="color:#7D7D7D;">: {$v['ttitle']}</span></a>
						<a class="cz"  title="操作"  style="width:25px;" id="cz" ><em class="b4"></em>
						</a>
						<span class="rr_1" id="rr_1_{$v['post_id']}">
							<a class="zt" style="width:65px;"><em><span id="text_{$v['post_id']}">{if $v['audit']}已审核{else}待审核{/if}</span></em></a>
							<a class="tjr zt" style="padding:0;width:140px;">
							<div>
							  {if $v['avatar']}
							  <img style="display:block;float:left;margin-right:5px;width:36px;height:36px;" width="36" height="36" src="{$v['avatar']}" alt="">
							  {/if}
							 <div>
							    <span style="display:block;color:#8199BD;font-size:12px;">{$v['user_name']}</span>
							    <span style="color:#7B7B7B;font-size:9px;">{$v['pub_time']}</span>
							  </div>				
						    </div>
					      </a>
				       </span>
					   <span class="rr_2" id="rr_2_{$v['post_id']}" style="display:none;z-index:1000px;position:absolute;top:0px;left:252px;padding-left:30px;">
					       <a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=audit&post_id={$v['post_id']}" onclick="return hg_ajax_post(this, '审核', 1);">审核</a>
							<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=back&post_id={$v['post_id']}" onclick="return hg_ajax_post(this, '打回', 1);">打回</a>
							<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&post_id={$v['post_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
					  </span>
				 </span>
				<span style="cursor:pointer;" class="title overflow">
					<a href="###">
						<span id="sort_name_86" style="color:#333;padding-right:10px;" title="{$v['pagetext']}" >{$v['pagetext']}</span>
					</a>
				</span>
			</li>