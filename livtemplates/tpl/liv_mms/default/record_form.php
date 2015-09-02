<form class="iframe" action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="record_form" id="record_form" onsubmit="return hg_ajax_submit('record_form','','','');">
{if $id}
	{code}
	$optext = '更新';
	$action = 'record_update';
	{/code}
{else}
	{code}
	$optext = '创建';
	$action = 'record_create';
	{/code}
{/if}
<h2>{$optext}收录</h2>
		<ul class="form_ul">
			<li class="i"><span>频&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;道：</span>
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
			<li class="i"><span>开始时间：</span>{template:form/wdatePicker,start_time,$_INPUT['start_time']}</li>
			<li class="i"><span>结束时间：</span>{template:form/wdatePicker,end_time,$_INPUT['end_time']}</li>
			<li class="i"><span>栏&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目：</span>
				<select name="item" id="item">
					{foreach $program_item as $k => $v}
						<option{if $k == $item} selected{/if} value="{$k}">{$v}</option>	
					{/foreach}
				</select>
			</li>			
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_2" /><span id="errorTips" style="color:red;padding-left: 8px;"></span>


</form>