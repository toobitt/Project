<?php 
/* $Id: redirect.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>
{template:head}
{$extra_header}
    <div class="steering">
    	<div>
        	<span></span>
        	<strong><a href="{$url}">正在转向......{$message}</a></strong>
            {if $mGuide}
				<ul>
				{foreach $mGuide AS $guide}
				<li><a href="{$guide['link']}">{$guide['name']}</a></li>
				{/foreach}
				</ul>
			{/if}
        </div>
    </div>
{template:foot}