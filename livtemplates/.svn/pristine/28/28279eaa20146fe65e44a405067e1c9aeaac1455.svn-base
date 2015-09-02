			<li id="r_{$v['id']}" style="margin-top:0;padding:1px 0;" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" class="clear">
				<span class="left">
					<a name="alist[]" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');" class="lb">
						<input type="checkbox" title="{$v['id']}" value="{$v['id']}" name="infolist[]">
					</a>
				</span>
	             <span class="right"  style="width:650px;position:relative;" >
				 <!--
				 	   {if is_array($v['reported_user'])}
							{foreach $v['reported_user'] as $kk=> $vv}
								<a class="zt overflow" style="width:200px;">
									<img style="vertical-align:middle;margin-right:5px;" width="36" height="36" src="{$vv['small_avatar']}" alt="">
									<span style="color:#8197BE;">{$vv['username']}</span>
									<span style="color:#7D7D7D;">:ccc</span>
								</a>
							{/foreach}
						{/if}
						-->
						<a class="zt overflow" style="width:200px;">
							<img style="vertical-align:middle;margin-right:5px;" width="36" height="36" src="{$v['user']['small_avatar']}" alt="">
						    <span style="color:#8197BE;">{$v['user']['username']}</span>
							<span style="color:#7D7D7D;">:ccc</span>
						</a>
					     <a class="zt" style="width:80px;">{$v['type']}</a>
						 <a class="zt" style="width:80px;">{$v['cid']}</a>
						<a class="cz"  title="操作"  style="width:25px;" id="cz" ><em class="b4"></em>
						</a>
						<span class="rr_1" id="rr_1_{$v['id']}">
							<a class="zt" style="width:60px;"><em><span id="text_{$v['id']}">{$v['state_tags']}</span></em></a>
							<a class="tjr zt" style="padding:0;width:140px;">
							<div>
							 <div>
							   <!--
							    {if is_array($v['user'])}
								{foreach $v['user'] as $key => $value}
									<span style="display:block;color:#8199BD;font-size:12px;">{$value['username']}</span>
								{/foreach}
								{/if}
								-->
								<span style="display:block;color:#8199BD;font-size:12px;">{$v['user']['username']}</span>
							    <span style="color:#7B7B7B;font-size:9px;">{$v['create_time']}</span>
							  </div>				
						    </div>
					      </a>
				       </span>
					   <span class="rr_2" id="rr_2_{$v['id']}" style="display:none;z-index:1000px;position:absolute;top:0px;left:385px;padding-left:30px;">
					       <a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$v['id']}" onclick="return hg_ajax_post(this, '审核', 1);">审核</a>
							<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=back&id={$v['id']}" onclick="return hg_ajax_post(this, '打回', 1);">打回</a>
							<a class="button_4" style="margin-top:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
					  </span>
				 </span>
				<span style="cursor:pointer;" class="title overflow">
					<a href="###">
						<span id="sort_name_{$v[id]}" class="m2o-common-title" style="color:#333;padding-right:10px;" title="{$v['content']}" >{$v['content']}</span>
					</a>
				</span>
			</li>