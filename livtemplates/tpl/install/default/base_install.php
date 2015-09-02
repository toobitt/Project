<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}

<div class="wrap n">
<form name="listform" action="" method="post">
<input type="hidden" name="a" value="{$a}" />
<div>请设置接口域名: 
<input type="text" name="host" value="" size="60" />
</div>
<div>请设置接口地址: 
<input type="text" name="dir" value="" size="60" />
</div>
<div>
<input type="submit" name="s" value="确定" />
</div>
</form>
</div>
{template:foot}