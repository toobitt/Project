<?php

/* $Id: callback.php 387 2011-07-26 05:31:22Z lijiaying $ */
?>

{template:head}
<div class="content clear" id="equalize">
	<div class="content-left" style="background-color:white;margin:10px;padding:10px;">
		
		{if $is_bind}
		
		<p>授权完成,恭喜你已成功绑定新浪点滴!</p>
		<a href="index.php">返回你的点滴发布页面,即可同步发表点滴!</a>
	
		{else}
	
		<p>对不起,绑定失败!</p>
		{/if}
	</div>
</div>
	
