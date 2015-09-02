<div class='form_ul_div clear'>
	<span class='title'>参数名称: </span><input type='text' name='con_name[{$formdata['num']}][]' style='width:90px;' class='title'>&nbsp;&nbsp;
	<span>标识:</span> <input type='text' name='con_mark[{$formdata['num']}][]' style='width:90px;' class='title'>&nbsp;&nbsp;&nbsp;
	<span>字典:</span>
		{if $formdata['dict']}
		<select name='con_dict[{$formdata['num']}][]'>
		{foreach $formdata['dict'] as $key=>$val}
		<option value="{$key}">{$val}</option>
		{/foreach}
		</select>
		{/if}
	&nbsp;&nbsp;&nbsp;
	<span>值: </span><input type='text' name='con_value[{$formdata['num']}][]' size='40'/>&nbsp;&nbsp;
	<span>添加方式: </span>
		<select name='con_way[{$formdata['num']}][]'>
			<option value='1'>字典匹配</option>
			<option value ='2'>用户自定义</option>
		</select>
		<span class='option_del_box'>
			<span name='option_del[{$formdata['num']}]' class='option_del' title='删除' onclick='hg_optionDel(this);' style='display: inline; '></span>
		</span>
</div>