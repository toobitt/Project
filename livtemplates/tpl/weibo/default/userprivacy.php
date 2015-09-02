<?php
/* $Id: userprivacy.php 390 2011-07-26 05:35:00Z lijiaying $ */

?>
{template:head/head_register_login}

<div class="registering">
	<div class="content clear">
	<div class="content_top"></div>	
		<div class="content_middle clear"> 
		<!-- 导航按钮  -->
		
		{template:unit/userset}
		
		<div class="content_ys">
		<h3>
			个人页面访问
		</h3>
		
		
		{foreach $_settings['authority']['visit_user_info'] as $k => $v}
			{if $authority[14] == $k}
		
		<p>
			<input type="radio" name="visit_user_info" checked="checked" value="{$k}"/>			
			<label>{$v}</label>
		</p>
			{else}
		<p>
			<input type="radio" name="visit_user_info"  value="{$k}"/>			
			<label>{$v}</label>
		</p>
			{/if}
		{/foreach}	
		
		</div>		
		<div class="content_ys">
		<h3>
			<span><strong>添加关注</strong></span>
		</h3>
		{foreach $_settings['authority']['follow'] as $k => $v}
			{if $authority[19] == $k}
		<p>
			<input type="radio" name="follow" checked="checked" value="{$k}"/>			
			<label>{$v}</label>
		</p>	
			{else}
		<p>
			<input type="radio" name="follow"  value="{$k}"/>			
			<label>{$v}</label>
		</p>
			{/if}
		{/foreach}
		</div>
		<div class="content_ys">	 		
		<h3>
			<span><strong>评论</strong></span>
		</h3>
		{foreach $_settings['authority']['comment'] as $k => $v}
	
			{if $authority[18] == $k}
		<p>
			<input type="radio" name="comment" checked="checked" value="{$k}"/>			
			<label>{$v}</label>
		</p>
			{else}
		<p>
			<input type="radio" name="comment"  value="{$k}"/>			
			<label>{$v}</label>
		</p>
			{/if}
		{/foreach}
		</div>	
		<div class="content_ys"> 		
		<h3>
			真实姓名
		</h3>
		{foreach $_settings['authority']['search_true_name'] as $k => $v}
			{if $authority[17] == $k}
		<p>
			<input type="radio" name="search_true_name" checked="checked" value="{$k}"/>			
			<label>{$v}</label>
		</p>	
			{else}
		<p>
			<input type="radio" name="search_true_name"  value="{$k}"/>			
			<label>{$v}</label>
		</p>		
			{/if}
		{/foreach}
			
		</div>
		
		<div class="content_ys">	
		<input type="button" name="sub" onclick="setPrivacy();" value=""  class="ok"/>
		<span id="show_notice" ></span>	
		</div>
	</div>
<div class="content_bottom"></div>
	</div>

</div>

{template:foot}