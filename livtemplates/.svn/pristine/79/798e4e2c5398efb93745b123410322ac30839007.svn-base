<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if $_nav}
<div class="heard_menu"{if $_INPUT['infrm']} style="display:none"{/if}>
<!--<div class="clear top_omenu" id="_nav_menu">
			<ul class="menu_part" id="menu-part">
				{code}
					$len = count($_nav) - 1;
					$zindex = 10000;
				{/code}
				{foreach $_nav AS $k => $nav}
				{code}
					$class = '';
					$zindex--;
					if($k == 0)
					{
						$class .= $nav['class'] . ' first';
					}
					if ($len == $k)
					{
						$class .= ' last';
						$liid = ' id="hg_cur_nav_last"';
					}
				{/code}
				<li class="{$class}"{$liid} style="z-index:{$zindex};">
				{if $k == 0}
					<span class="first-part"></span>
				{/if}
				{if $k == $len}
					<span class="last-part"></span>
				{/if}
				{if $nav['class']}
				<em></em>
				{/if}
				{if $nav['link'] && $nav['link'] != '#'}
				<a href="{$nav['link']}{$_ext_link}" target="mainwin">{$nav['name']}</a>
				{if $nav['has_setting']}
				<a class="menu_set" href="run.php?a=configuare&amp;mid={$nav['mid']}" target="mainwin">设置</a>
				{/if}
				{else}
				<a class="mao">{$nav['name']}</a>
				{if $nav['has_setting']}
				<a class="menu_set" href="run.php?a=configuare&amp;mid={$nav['mid']}" target="mainwin">设置</a>
				{/if}
				{/if}
				</li>
				{/foreach}
			</ul>   	
</div>-->
<div class="twitter-head-r"><img src="{$RESOURCE_URL}login/login-logo2.png" class="need-ratio" _src2x="{$RESOURCE_URL}login/login-logo2-2x.png" /></div>
<div id="hg_parent_page_menu" style="float:right;margin-right:10px;margin-top:8px;"></div>
</div>
{/if}