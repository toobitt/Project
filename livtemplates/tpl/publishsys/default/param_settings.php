<fieldset>
    <legend>数据源输入参数</legend>
    {if is_array($formdata['data_source_param']['input_param']) && count($formdata['data_source_param']['input_param'])>0}
    	{foreach $formdata['data_source_param']['input_param'] as $k => $v}
    		{if $v['type'] == 'text'}
    			{$v['name']}:<input type="text" name="input_param[{$v['sign']}]" value="{$v['default_value']}" /><br/>
    		{else}
    			{$v['name']}:
    			<select name="input_param[{$v['sign']}]">
    				{foreach $v['other_value'] as $kk => $vv}	
    					<option value="{$kk}">{$vv}</option><br/>
    				{/foreach}
    			</select><br/>
    		{/if}
    	{/foreach}
    {else}
    	暂无参数
    {/if}
</fieldset>  
<fieldset>
    <legend>样式参数</legend>
    {if is_array($formdata['cell_mode_param']['mode_param']) && count($formdata['cell_mode_param']['mode_param'])>0}
    	{foreach $formdata['cell_mode_param']['mode_param'] as $k => $v}
    		{if $v['type'] == 'text'}
    			{$v['name']}:<input type="text" name="mode_param[{$v['sign']}]" value="{$v['default_value']}" /><br/>
    		{else}
 				{$v['name']}:
    			<select name="mode_param[{$v['sign']}]">
    				{foreach $v['other_value'] as $kk => $vv}	
    					<option value="{$kk}">{$vv}</option>
    				{/foreach}
    			</select><br/>   		
    		{/if}
    	{/foreach}
    {else}
    	暂无参数
    {/if}
</fieldset>
<fieldset>
    <legend>参数关联</legend>
    {if is_array($formdata['cell_mode_param']['data_param']) && count($formdata['cell_mode_param']['data_param'])>0}
    	{foreach $formdata['cell_mode_param']['data_param'] as $k => $v}
    		{$v['name']}:
			<select name="assoc_param[{$v['name']}]">
			<option value="">
			请选择
			</option>
			{foreach $formdata['data_source_param']['out_param'] as $kk=>$vv}
			<option value="{$vv['name']}" {if $v['name'] ==$vv['name']}selected=selected{/if}>
				--{$vv['name']}--
			</option>
			{/foreach}
			</select>    		
    		<br/>
    	{/foreach}
    {else}
    	暂无参数
    {/if}
</fieldset>