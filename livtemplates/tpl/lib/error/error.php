<?php 
/* $Id: error.tpl.php 1623 2011-01-08 06:44:58Z repheal $ */
?>
<style>
.error{width:200px;background:url("{$RESOURCE_URL}error.png") no-repeat;width:436px;height:205px;position:relative;margin:0px auto 0}
.error h5{font-family: Microsoft YaHei;font-weight:300;font-size:14px;text-align:center;line-height: 40px;}
.error p{position: absolute;bottom:99px;right:0;line-height:22px;color:#d36161;width:260px;text-indent:20pt;max-height: 70px;}
</style>

<div class="content clear" id="equalize">
		<div class="error">
				<h5>抱歉你访问的页面出错，</h5>
				<p>{$message}</p>
		</div>
		<div class="common">
		</div>
</div>