<div class="error">
	<h2>{$null_title}</h2>
	<p>
		<img align="absmiddle" src="<?php echo RESOURCE_DIR;?>img/error.gif" alt="" title="">{$null_text}
		{if !$null_type}
			<a href="{$null_url}">{code} echo $null_tip ? $null_tip : '返回上一页！';{/code}</a>
		{/if}
	</p>
</div>