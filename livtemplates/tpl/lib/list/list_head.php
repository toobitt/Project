<ul class="common-list {$headData['class']}">
	{code} $tmp = array('left', 'right', 'biaoti'); {/code}
	<li class="common-list-head clear" {$headData['attr']}>
		{foreach $tmp as $kk => $vv}
		<div class="common-list-{$vv} {$headData['innerHtml'][$vv]['class']}" {$headData['innerHtml'][$vv]['attr']}>
			{foreach $headData['innerHtml'][$vv]['innerHtml'] as $kkk => $vvv}
			<div class="common-list-item open-close {$vvv['class']}" {$vvv['attr']}>
				{$vvv['innerHtml']}					
			</div>
			{/foreach}
		</div>
		{/foreach}
		{code}
			if ($headData['more']) {
				echo $headData['more'];
			}
		{/code}
	</li>
</ul>