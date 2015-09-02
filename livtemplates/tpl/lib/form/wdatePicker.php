<?php 
/* $Id: wdatePicker.php 16135 2013-01-10 06:44:53Z repheal $ */
?>
<div class="input {$hg_attr['div_class']}" style="{$hg_attr['style']}">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle">
		<input type="text" name="{code} echo $hg_attr['name']?$hg_attr['name']:$hg_name;{/code}" id="{$hg_name}" onfocus="if({code} echo $hg_attr['other_focus']?$hg_attr['other_focus']:true;{/code}){WdatePicker({skin:'whyGreen',dateFmt:'{code} echo ($hg_attr['type']?$hg_attr['type']:'yyyy-MM-dd HH:mm:ss');{/code}'});}{$hg_attr['focus']}" {code} echo $hg_attr['other']?$hg_attr['other']:'class="Wdate"';{/code} value="{$hg_value}"/>
	</span>
</div>