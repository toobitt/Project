<li class="common-list-data clear {$rowData['class']}" {$rowData['attr']}>
	{code} $tmp = array('left', 'right', 'biaoti'); {/code}
	{foreach $tmp as $kk => $vv}
	<div class="common-list-{$vv} {$rowData['innerHtml'][$vv]['class']}" {$rowData['innerHtml'][$vv]['attr']}>
		{foreach $rowData['innerHtml'][$vv]['innerHtml'] as $kkk => $vvv}
		<div class="common-list-item {$vvv['class']}" {$vvv['attr']}>
			<div class="common-list-cell">
			{$vvv['innerHtml']}	
			</div>
		</div>
		{/foreach}
	</div>
	{/foreach}
	<div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
	{code}
		if ($rowData['more']) {
			echo $rowData['more'];
		}
	{/code}
</li>
