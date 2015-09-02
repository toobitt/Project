{template:head}
<div style="width:96%;height:400px;margin:20px;border:1px solid black;position:relative;">
	<div style="padding-top:20px;padding-left:20px;">
	<h3>选择要导入的表</h3>
	<form action="run.php?a=step5&mid={$_INPUT['mid']}&type={$_INPUT['type']}" method="post">
	<div>
		{code}
			$source = $formdata['source'];
			$destin = $formdata['destin'];
		{/code}
		{if $destin}
		<select name="destin_table">
		{foreach $destin as $k=>$v}
		<option value="{$v}">{$v}</option>
		{/foreach}
		</select>
		{/if}
		{if $source}
			<select name="source_table">
				{foreach $source as $key=>$val}
					<option value="{$val}">{$val}</option>
				{/foreach}
			</select>
		{/if}
	</div>
	<input type="submit" value="下一步" style="position:absolute;right:20px;bottom:20px"/>
	</form>
	</div>
</div>
{template:foot}