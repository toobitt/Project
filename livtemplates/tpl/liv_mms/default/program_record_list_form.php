<?php 
/* $Id: program_record_list_form.php 5170 2011-12-02 08:05:20Z gengll $ */
?>
{template:head}
{css:ad_style}
{js:mms_default}
<script type="text/javascript">
	
</script>
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l"">
		<h2>{$optext}收录</h2>
		<ul class="form_ul">
			<li class="i"><span>频道：</span>
				<select name="channel_id">
				{foreach $channel_info as $key => $value}
					{if $value['id'] == $channel_id}
						<option selected value="{$value['id']}">{$value['name']}</option>
					{else}
						<option value="{$value['id']}">{$value['name']}</option>
					{/if}
				{/foreach}
				</select>
			</li>
			<li class="i"><span>开始时间：</span><input id="start_time" type="text" name="start_time" value="{$start_time}" /></li>
			<li class="i"><span>结束时间：</span><input id="end_time" type="text" name="end_time" value="{$end_time}" /></li>
			<li class="i"><span>栏目：</span>
				<select name="item" id="item">
					{foreach $program_item as $k => $v}
						<option{if $k == $item} selected{/if} value="{$k}">{$v}</option>	
					{/foreach}
				</select>
			</li>			
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	{if $action == 'update'}
	<input type="hidden" name="channel_id" value="{$channel_id}" />
	{/if}
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_2" />
	</form>
	</div>
	<div class="right_version">
		<h2>返回前一页</h2>
	</div>
{template:foot}