<?php 
/* $Id: redirect.php 390 2011-07-26 05:35:00Z lijiaying $ */
?>
{template:head}
    <div class="steering">
    	<div>
        	<span></span>
        	<strong><a href="{$url}">正在转向......{$message}</a></strong>
			{if $_mGuide}
				<ul> 
				{foreach $_mGuide AS $guide}
				
				<li><a href="{$guide['link']}">{$guide['name']}</a></li>
				{/foreach}
				</ul>
			{/if}
        </div>
    </div>
{template:foot}