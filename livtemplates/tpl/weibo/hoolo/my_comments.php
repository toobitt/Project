<?php
/*$Id: my_comments.php 390 2011-07-26 05:35:00Z lijiaying $*/
?>
{template:head}
<style>
#floatBoxBg{overflow: hidden; width: 100%; height: 100%; border: 0pt none; padding: 0pt; margin: 0pt; top: 0pt; left: 0pt; display: block; visibility: visible; background-color: rgb(0, 0, 0); opacity: 0.5; position: fixed; z-index: 100;
/*width:100%;height:100%;background:#fff;filter:alpha(opacity=50);opacity:0.5;position:absolute;top:0;left:0;*/}
.floatBox{display: block; visibility: visible; position: absolute; z-index: 1000;border:5px solid #ccc;}
.floatBox .title{height:23px;padding:7px 10px 0;background:none repeat scroll 0 0 #F4F4F4;color:#000;border-bottom:2px #ccc solid;}
.floatBox .title h4{float:left;padding:0;margin:0;font-size:14px;line-height:16px;}
.floatBox .title span{float:right;cursor:pointer;}
.floatBox .content{margin: 0;background:#fff repeat;width:200px;text-align:center;padding:0;}
</style> 
<div class="main clear">
<div class="ping">  
	<dl class="ping_title"><dd><a href="{code} echo hg_build_link('all_comment.php');{/code}" class="{if !$tag}ping_current{/if}">{$_lang['resived_comments']}</a> | <a href="{code} echo hg_build_link('all_comment.php' , array('t' => 1)); {/code}" class="{if $tag}ping_current{/if}">{$_lang['send_comments']}</a></dd></dl>
	
	{if $tag == 1}
		{template:unit/send}
 	{else}
		{template:unit/resived}
 	{/if}

	{$showpages}
</div> 

<div class="content-right">

	<div class="pad-all">
	 {template:unit/userImage}
	 {template:unit/userInfo}
	 </div>

</div>
</div>
{template:foot}