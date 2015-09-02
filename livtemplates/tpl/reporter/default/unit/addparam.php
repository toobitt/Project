<div class='form_ul_div clear'>
	<span class='title'>参数名称: </span><input type='text' name='con_name[{$ckey}][]' style='width:90px;' class='title' value="{$param}">&nbsp;&nbsp;
	<span>标识:</span> <input type='text' name='con_mark[{$ckey}][]' style='width:90px;' class='title' value="{$conf['match_rule']['mark'][$pkey]}">&nbsp;&nbsp;&nbsp;
	<span>字典:</span>
		{if $_configs['con_dictionary']}
		<select name='con_dict[{$ckey}][]'>
		{foreach $_configs['con_dictionary'] as $kkkk=>$vvvv}
		<option value="{$kkkk}" {if $kkkk==$conf['match_rule']['dict'][$pkey] } selected="selected" {/if}>{$vvvv}</option>
		{/foreach}
		</select>
		{/if}
	&nbsp;&nbsp;&nbsp;
	<span>值: </span><input type='text' name='con_value[{$ckey}][]' size='40' value="{$conf['match_rule']['value'][$pkey]}"/>&nbsp;&nbsp;
	<span>添加方式: </span>
		<select name='con_way[{$ckey}][]'>
			<option value ='1' {if $conf['match_rule']['way'][$pkey]==1} selected="selected"  {/if}>字典匹配</option>
			<option value ='2' {if $conf['match_rule']['way'][$pkey]==2} selected="selected"  {/if}>用户自定义</option>
		</select>
		<span class='option_del_box'>
			<span name='option_del[{$ckey}]' class='option_del' title='删除' onclick='hg_optionDel(this);' style='display: inline; '></span>
		</span>
</div>