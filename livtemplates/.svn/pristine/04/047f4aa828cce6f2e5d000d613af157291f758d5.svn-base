<?php
/* $Id: top.php 896 2011-09-24 07:27:20Z repheal $ */
 ?> 
 <style>
.top_notice{display: none;left: 70%; position: fixed;width: 280px;z-index:999;bottom:0;}
.nav_notice{color:#fff;font-size:13px;}
.close_x{z-index: 999;float: right;left: 260px; top: 0;cursor:pointer; border-top: 1px solid #CCCCCC;}
#nav_002{width:280px;top: 0;background: none repeat scroll 0 0 #FDFFEA;border: 1px solid #CCCCCC;}
#nav_002 li {width:90%;padding-left:5px;line-height:20px;color:#666;}
#nav_002 li a{color:#333;} 
</style>
<div class="top_notice" id="notice_div" style="display:none;" >
{if $_user['id'] > 0}
<div class="nav_notice"> 
<a class="close_x" onclick="hide_notice()"><img src="{code} echo RESOURCE_DIR;{/code}img/close.jpg" title="关闭提示" onclick="hide_notice()"/></a>
<ul id="nav_002" > 
	{template:notice}
</ul> 
</div>
{/if}
</div>
 