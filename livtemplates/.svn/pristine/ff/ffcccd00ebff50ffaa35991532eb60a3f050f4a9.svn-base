{code}
$argument = $formdata['argument'];
{/code}
{foreach $argument['argument_name'] as $k=>$v}
	{$v}:
	{if $argument['type'][$k]=='select'}
	{code}
	if(!empty($argument['other_value'][$k]))
	{
		$value_arr = explode(' ',$argument['other_value'][$k]);
	}
	{/code}
	<select name="argument_{$argument['ident'][$k]}">
	{foreach $value_arr as $kk=>$vv}
	{code}
	$option_value_arr = array();
	$option_value_arr = explode('=>',$vv);
	{/code}
	<option value="{$option_value_arr[0]}">
	{$option_value_arr[1]}
	</option>
	{/foreach}
	</select>
	{else}
	<input type=text id='argument_{$argument['ident'][$k]}' name='argument_{$argument['ident'][$k]}' style="width:50px;height:15px;" >
	{/if}
	
{/foreach}