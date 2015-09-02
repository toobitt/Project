{css:common/common}
{code}
$hg_value = $hg_value ? $hg_value : 0;
{/code}
<div class="common-list-item wd60 news-quanzhong {$hg_name} open-close">
	<div class="common-quanzhong-box">
		<div class="common-quanzhong-box{$hg_value}" _level="{$hg_value}">
			<div class="common-quanzhong" style="background:{code}echo create_rgb_color($hg_value);{/code}">
				<span class="common-quanzhong-label">{$hg_value}</span>
			</div>
			
		</div>
	</div>
</div>