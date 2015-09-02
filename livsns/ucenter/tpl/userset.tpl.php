<?php 
/* $Id: userset.tpl.php 1672 2011-01-10 07:04:37Z chengqing $ */
?>


<ul class="userset clear">
<?php
	foreach($this->settings['personset'] as $k => $v)
	{
                if($k == $gScriptName)
                {
	?>
		<li><strong><a href="<?php echo hg_build_link($v['filename']); ?>"><?php echo $v['name']?></a></strong></li>			
	<?php
                }
                else 
                {
                ?>
		<li><a href="<?php echo hg_build_link($v['filename']); ?>"><?php echo $v['name']?></a></li>			
			<?php
                }
	}
?>
</ul>
