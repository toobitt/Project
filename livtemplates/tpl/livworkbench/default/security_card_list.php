<?php 
/* $Id$ */
?>
{template:head}
{css:ad_style}
<div class="wrap">
<div class="ad_middle">
<h2>绑定成功</h2>
<img src="{$secret_img}"  />
<a href="infocenter.php?a=download_card&img={$secret_img}" class="button_6">下载密保卡</a>
<a href="infocenter.php?a=bind_card&id={$id}" class="button_6">重新绑定</a>
</div>
<div class="right_version"><h2><a href="infocenter.php">返回前一页</a></h2></div>
</div>
{template:foot}