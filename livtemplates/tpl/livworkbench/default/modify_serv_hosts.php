<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<form name="dbform" id="dbform" action="?a=domodify_serv_hosts" method="post" class="form">
<div style="color:red;" id="dberrmsg" class="msg">{$message}</div>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">域名：</span>
			<input type="text" value="{$_INPUT['domain']}" name='domain' style="width:200px;">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;ip：</span>
			<input type="text" value="{$_INPUT['ip']}" name='ip' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
</ul>
<input type="submit" name="rsub" value=" 更改 " class="button_4" />
</form>
	
</body>
</html>