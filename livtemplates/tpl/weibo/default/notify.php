<?php 
/* $Id: notify.php 390 2011-07-26 05:35:00Z lijiaying $ */
?>
{template:head}
<div class="content">
<div class="notify border">
<div class="head">
<div style="width:130px;float:left">{$_lang['notify']}</div><div>{$_lang['unreadNum']}:<span id="unreadNum">{$unreadNum[0][count]}</span></div>
</div>
<div class="notify-info" id="notifyInfo">
{foreach $notifyInfo as $key => $value}
	{code}
		$paixu[$key] = $value['is_read'];
	{/code}
{/foreach}
{code}
	array_multisort($paixu, SORT_ASC,$notifyInfo);
{/code}
{foreach $notifyInfo as $key => $value}
<ul><li style="width:160px;">{$value['content']}</li><li style="width:30px;">{$value['is_read']}</li></ul>
{/foreach}
</div>
</div>
</div>
{template:foot}