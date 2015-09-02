<div style="position: relative;">
	<div id="open-close-box">
		<span></span>
		<div class="open-close-title">显示/关闭</div>
		<ul>
		{foreach $columnData as $kk => $vv}
			<li which="{$vv['class']}"><label class="overflow"><input type="checkbox" checked />{$vv['innerHtml']}</label></li>
		{/foreach}
		</ul>
	</div>
</div>
