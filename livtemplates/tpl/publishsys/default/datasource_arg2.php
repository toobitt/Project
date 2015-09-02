{code}
$argument = $formdata['input_param'];
{/code}
{foreach $argument as $k=>$v}
	{$v['name']}:<input type=text id='argument_{$v['sign']}' name='argument_{$v['sign']}' value='{$v['default_value']}' style="width:50px;height:15px;" >
{/foreach}