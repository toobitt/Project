<?php 
/* $Id: redirect.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
{template:head}
    <div class="redirect vui">
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