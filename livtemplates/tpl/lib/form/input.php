<?php 
/* $Id: input.php 6434 2011-12-17 09:01:12Z repheal $ */
?>
<div class="input" style="{if $hg_attr['width']} width:{$hg_attr['width']}px;{/if}{$hg_attr['style']}">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle">
		<input type="text" name="{$hg_name}" id="{$hg_name}" value="{$hg_value}" style="{if $hg_attr['width']} width:{code}echo $hg_attr['width']-5;{/code}px;{/if}"/>
	</span>
</div>