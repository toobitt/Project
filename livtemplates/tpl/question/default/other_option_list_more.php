{if $formdata}
	{foreach $formdata AS $kk => $vv}
		<li class="other_li" id="other_option_id_{$vv['id']}">
			<span class="other_num" id="other_num_{$vv['id']}"></span>
			<input type="text" value="{$vv['title']}" style="width:440px;{if $vv['flag']}border:1px solid #83ABCF;{/if}" name="other_title[{$vv['id']}]" onchange="hg_changeOtherTitle(this,{$vv['id']});" />
			<span class="other_operate" onclick="hg_otherOperate(this,{$vv['id']});"></span>
			<span class="other_state" id="other_state_{$vv['id']}">
				{if !$vv['state']}待审核{else}已审核{/if}
			</span>
			
			<span class="other_operate_box" id="other_operate_box_{$vv['id']}">
				<a href="javascript:void(0);" onclick="hg_optionOtherDel(this,{$vv['id']});">删除</a>
				<a href="javascript:void(0);" onclick="hg_optionOtherState(this,{$vv['id']});">{if !$vv['state']}审核{else}打回{/if}</a>
			</span>
			{if $vv['state']}
			<span class="other_single_total f_r">{if $vv['single_total']}<font style="color:#CE2427;">{$vv['single_total']}&nbsp;票</font>{/if}</span>
			{/if}
			<input type="hidden" name="hiddenFlag[{$vv['id']}]" id="hiddenFlag_{$vv['id']}" value="" />
			<input type="hidden" id="Flag_{$vv['id']}" value="{$vv['flag']}" />
			<input type="hidden" name="hiddenTitle[{$vv['id']}]" id="hiddenTitle_{$vv['id']}" value="{$vv['title']}" />
		</li>
	{/foreach}
{/if}