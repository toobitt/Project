<?php 
/* $Id: face.tpl.php 3633 2011-04-15 03:26:42Z repheal $ */
?>
        <ul class="face_menu">
        <?php 
        $face_name = $this->settings['smile_name'];
        $num = count($face_name);
        $j = 1;
        foreach($face_name as $nk => $nv)
        {?>
        <li onclick="face_tab(<?php echo $j;?>,<?php echo $num;?>,'<?php echo $face_tab;?>_');"><?php echo $nv;?></li>
		<?php 	   
		$j++;     		
        }
        ?>
        </ul>
        <?php 
        $face = $this->settings['smile_face'];
		$i = 1;
        foreach($face as $fk => $fv)
        {
        $facelist = hg_readdir($fv['dir']);
        $style = "";
        if($i>1)
        {
        	$style = ' style="display:none"';
        }
        ?>
       	<ul id="<?php echo $face_tab;?>_<?php echo $i?>" <?php echo $style;?>>
		<?php 
		foreach($facelist as $lk => $lv)
		{?>
			<li class="faces">
				<a onclick="insert_face('<?php echo $face_con;?>', ' :em<?php echo $fk;?>_<?php echo $lk;?>:','<?php echo $face_tab;?>');return false;" href="javascript:void(0);">
					<img alt="" smilietext=":em<?php echo $fk;?>_<?php echo $lk;?>:" src="<?php echo $fv['url'].$lv;?>">
				</a>
			</li>
		<?php 
		}
		?>
			</ul>
        <?php	
        $i++;
        }
        ?>