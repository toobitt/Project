<?php 
/* $Id: face.php 387 2011-07-26 05:31:22Z lijiaying $ */
?>
        <ul class="face_menu">
        {code}
			$face_name = $_settings['smile_name'];
			$num = count($face_name);
			$j = 1;
		{/code}
        {foreach $face_name as $nk => $nv}
       
        <li onclick="face_tab({$j},{$num},'{$face_tab}_');">{$nv}</li>
		{code}	   
			$j++;
		{/code}
		{/foreach}
        </ul>
     
		{code}
			$face = $_settings['smile_face'];
			$i = 1;
		{/code}
        {foreach $face as $fk => $fv}
			{code}
				$facelist = hg_readdir($fv['dir']);
				$style = "";
			{/code}
        {if $i>1}
			{code}
        		$style = ' style="display:none"';
			{/code}
		{/if}
       	<ul id="{$face_tab}_{$i}" {$style}>
		
		{foreach $facelist as $lk => $lv}
			<li class="faces">
				<a onclick="insert_face('{$face_con}', ' :em{$fk}_{$lk}:','{$face_tab}');return false;" href="javascript:void(0);">
					<img alt="" smilietext=":em{$fk}_{$lk}:" src="{$fv['url']}{$lv}">
				</a>
			</li>
		{/foreach}
			</ul>
		{code}
			$i++;
		{/code}
		{/foreach}
