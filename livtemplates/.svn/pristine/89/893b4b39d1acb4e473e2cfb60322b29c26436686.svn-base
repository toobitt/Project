{template:head}
<div style="width:96%;height:400px;margin:20px;border:1px solid black;position:relative;">
	<div style="padding-top:20px;padding-left:20px;">
	<h3>请选择您想要的操作？</h3>
	<form action="run.php?a=step2&mid={$_INPUT['mid']}" method="post">
	{foreach $_configs['opration'] as $type=>$val}
	<label><input type="radio" value={$type} name="type"/>{$val}</label>
	<br/>
	{/foreach}
	<input type="submit" value="下一步" style="position:absolute;right:20px;bottom:20px"/>
	</form>
	</div>
</div>
{template:foot}