<?php 
/* $Id: redirect.php 88 2011-06-21 07:53:47Z repheal $ */
?>
{template:head}
    <div class="main_div">
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
{template:foot}