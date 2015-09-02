{if $formdata}
<div>
	<h3 class="opt_h3">{$formdata['title']}</h3>
</div>
<div style="max-height: 400px;overflow-y: auto;">
	<ul class="opt_ul">
	{foreach $formdata['option_title'] AS $kk => $vv}
		<li class="other_li">
			<span class="option_fir">{code} echo $kk+1; {/code}.</span>
			<span class="option_sec">{$vv['title']}</span>
			<span class="option_thi">
			{code}
				$width = intval(($vv['single_total']/$formdata['question_total'])*100);
			{/code}
				<span style="{if $width <1}width:1px;{else}width:{$width}px;{/if}height:2px;display:inline-block;background: #609CD2;"></span>
			</span>
			<span class="option_for f_r">{$vv['single_total']}&nbsp;票</span>
		</li>
	{/foreach}
	</ul>
{code}
	$other_option_title = array_slice($formdata['other_option_title'],0,5);
	$counts = count($other_option_title);
{/code}
	<h4 class="other_user">用户填写选项&nbsp;({$formdata['other_option_num']}&nbsp;条)</h4>
	<form name="otherForm" id="otherForm" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" onsubmit="return hg_updateOtherTitle();">
		<ul class="other_ul" id="other_append_box_{$formdata['id']}">
		{foreach $other_option_title AS $kk => $vv}
			<li class="other_li" id="other_option_id_{$vv['id']}">
				<span class="other_num" id="other_num_{$vv['id']}">{code} echo $kk+1;{/code}.</span>
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
		</ul>
		<input style="position: relative;left: 36px;top: 26px;" type="submit" name="sub" value="更新" />
		<input type="hidden" name="a" value="updateOtherTitle" />
		{if $counts == 5}
		<div style="margin:10px 0px 10px 444px;width:50px;text-align: center;cursor:pointer;"><span onclick="hg_getOtherMore(this,{$formdata['id']});">更多</span><span id="getOtherMore_img_{$formdata['id']}" style="display:none;position: relative;top: 4px;left: 10px;"><img width=16 height=16 src="{$RESOURCE_URL}loading6.gif" /></span></div>
		{/if}
	</form>
</div>
{/if}