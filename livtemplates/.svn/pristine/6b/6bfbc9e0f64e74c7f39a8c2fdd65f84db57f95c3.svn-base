<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
	<span class="left">
		<a name="alist[]" class="lb">
			<input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]">
		</a>
	</span>
	<span style="width:495px;height:36px;" class="right">
		{if $v['pubinfo'][1]}
		<span class="left" style="width:27px;text-align: center;line-height: 36px;display:inline-block;margin-right: 12px;">
			<span onmouseover="" onmouseout="" style="cursor:pointer;width:16px;height:16px;display:inline-block;{if $v['state']}background:url('{$RESOURCE_URL}bg-all.png') -21px -187px no-repeat;{else}background:url('{$RESOURCE_URL}bg-all.png') -2px -187px no-repeat;{/if}"></span>
		</span>
		{else}
		<span class="left" style="width:27px;text-align: center;line-height: 36px;display:inline-block;margin-right: 12px;"></span>
		{/if}

		<a id="cz" style="width:25px;position:relative;" title="操作" class="cz"> <em class="b4"></em>
			<span style="display: none;width: 230px;" class="show_opration_button">
				<span onclick="hg_voteForm({$v['id']});" style="margin:4px 2px 0 0;" class="button_2">编辑</span>
				<span onclick="hg_delQuestionChecked({$v['id']},'{$v['title']}')" style="margin:4px 2px 0 0;" class="button_2">删除</span>
			<!--	<span onclick="hg_vote_remove(this,{$v['group_id']},{$v['id']});" style="margin:4px 2px 0 0;" class="button_2">移动</span>-->
				<span onclick="hg_statePublish({$v['id']});" id="statePublish_{$v['id']}" style="margin:4px 2px 0 0;" class="button_2">发布</span>
			</span>
		</a>
		<a class="zt" id="v_name_{$v['id']}" style="width: 60px;">{$v['n_name']}</a>
		<a class="zt" style="color:#7B7B7B;width:118px;text-align: center;margin-left: 30px;" title="{$v['end_time']}"><em>{if !$v['end_time_flag']}<font style="color:red;">已过期</font>{else}{$v['end_time']}{/if}</em></a>
		<a class="zt" style="padding-top: 10px;">
			<div class="need-switch" title="{if $v['state']}已审核{else}待审核{/if}" state="{if $v['state']}1{else}0{/if}" style="cursor:pointer;" vid="{$v['id']}"></div>
		</a>
		<a class="tjr">
			<em>{$v['admin_name']}</em>
			<span>{$v['create_time']}</span>
		</a>
	</span>
	<span style="cursor:pointer;" class="title overflow">
		<a href="###">
			<span>
				<img style="display:block;float:left;margin-right:5px;width:40px;height:30px;margin-top: 3px;" src="{$v['vote_img_small']}">
			</span>
			<span>{$v['title']}</span>
			<span class="vote_question_total" title="问题数">{$v['question_total']}</span>
		</a>
	</span>
</li>
