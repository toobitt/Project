{template:head}
{css:style}
{js:group}
{if is_array($apply_list)}
	{code}
		$apply_list = $apply_list[0];
	{/code}
{/if}
{if !empty($apply_list)}
<form name="group_grand_from" id="group_grand_form" onsubmit="return false">
<table class="granfs_table">
<tr class="grands_first"><td>讨论区名称</td><td>申请人</td><td>申请时间</td><td>是否同意</td></tr>
{foreach $apply_list as $gid => $info}
	{code}
		$first = 0;
		$count = count($info);
	{/code}
{foreach $info as $in}
<tr class="grands_tr" onmouseover="this.style.background='#f4faf7'" onmouseout="this.style.background='#F2F2F2'" > 
	{if !$first}
	<td rowspan="{$count}">{$in['group_name']}</td>
	{/if}
	<td>{$in['user_name']}</td>
	<td>{$in['apply_time']}</td>
	<td>
		<input type="radio" onclick="check_user_grandsnum({$in['user_id']},{$in['group_id']},1,{$module_id});" value="{$in['user_id']}" id="agree{$in['group_id']}_{$in['user_id']}" name="agree_{$in['group_id']}_{$in['user_id']}" {if $in['is_agree'] == 1} checked="checked" {/if}> 是
		&nbsp;&nbsp;
		<input type="radio" onclick="check_user_grandsnum({$in['user_id']},{$in['group_id']},0,{$module_id})" value="0" id="noagree{$in['group_id']}_{$in['user_id']}" name="agree_{$in['group_id']}_{$in['user_id']}" {if !$in['is_agree']} checked="checked" {/if}>否
	</td>
</tr>
	{code}
		$first = 1;
	{/code}
	{/foreach}
{/foreach}
 
</table> 
</form>
<div class="page_box gt" id="page_link">
{code}
echo $pagelink;
{/code}
</div>
{else}
<div>暂无地主申请...</div>
{/if}
{template:foot}