{template:head}
<div style="width:96%;height:400px;margin:20px;border:1px solid black;position:relative;">
	<div style="padding-top:20px;padding-left:20px;">
	<h3>填写数据库信息</h3>
	<form action="run.php?a=step4&mid={$_INPUT['mid']}&type={$_INPUT['type']}" method="post">
	<div>
		{if $formdata}
		<select name="sdbtype">
			{foreach $formdata as $key=>$val}
				<option value="{$val}">{$val}</option>
			{/foreach}
		</select>
		{/if}
		<label>来源数据库地址：<input type="text" name="shost" /></label>
		<label>用  户 名：<input type="text" name="susername" /></label>
		<label>密     码：<input type="text" name="spassword" /></label>
		<label>数  据 库：<input type="text" name="sdbname" /></label>
		
	</div>
	<div>
		{if $formdata}
		<select name="ddbtype">
			{foreach $formdata as $key=>$val}
				<option value="{$val}">{$val}</option>
			{/foreach}
		</select>
		{/if}
		<label>目标数据库地址：<input type="text" name="dhost" /></label>
		<label>用  户 名：<input type="text" name="dusername" /></label>
		<label>密     码：<input type="text" name="dpassword" /></label>
		<label>数  据 库：<input type="text" name="ddbname" /></label>
		
	</div>
	<input type="submit" value="下一步" style="position:absolute;right:20px;bottom:20px"/>
	</form>
	</div>
</div>
{template:foot}