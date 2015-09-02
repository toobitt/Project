<div class="error">
	<h2>{$title}</h2>
	<p>
		<img align="absmiddle" src="<?php echo RESOURCE_DIR;?>img/error.gif" alt="" title="">{$text}
		{if !$type}
			<a href="{$url}">返回上一页！</a>
		{/if}
	</p>
</div>