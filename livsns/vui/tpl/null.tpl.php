<div class="error">
	<h2><?php echo $title;?></h2>
	<p>
		<img align="absmiddle" src="<?php echo RESOURCE_DIR;?>img/error.gif" alt="" title=""><?php echo $text;?>
		<?php 
		if(!$type)
		{?>
			<a href="<?php echo $url;?>">返回上一页！</a>
		<?php 	
		}
		?>
	</p>
</div>