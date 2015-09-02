<?php 
/* $Id: my_left_menu.tpl.php 2224 2011-02-24 13:24:27Z repheal $ */
?>
<div class="left_menu">
		<ul>
			<?php	
     		$num = count($this->settings['my_menu']);	
        	$i = 1;	
			foreach($this->settings['my_menu'] as $k => $v)
			{
                if($k == $gScriptName)
                {
			?>
			<li class="<?php echo $v['classname']."_now";?>"><a href="<?php echo hg_build_link($v['filename']); ?>"><?php echo $v['name']?></a></li>			
			<?php
                }
                else 
                {
                	?>
				<li class="<?php echo $v['classname'];?>"><a href="<?php echo hg_build_link($v['filename']); ?>"><?php echo $v['name']?></a></li>			
					<?php
                }
				if($num != $i)
				{
				?>
				<?php		
				}
				$i++;
			}
            ?>
        </ul>
	</div>