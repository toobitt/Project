<?php 
/* $Id: redirect.tpl.php 1632 2011-01-08 07:30:59Z repheal $ */
?>
<?php include hg_load_template('head');?>
    <div class="steering">
    	<div>
        	<span></span>
        	<strong><a href="<?php echo $url;?>">正在转向......<?php echo $message;?></a></strong>
            <?php 
			if ($this->mGuide)
			{
				?>
				<ul>
				<?php 
				foreach ($this->mGuide AS $guide)
				{
				?>
				<li><a href="<?php echo $guide['link'];?>"><?php echo $guide['name'];?></a></li>
				<?php
				}
				?>
				</ul>
				<?php 
			}
			?>
        </div>
    </div>
<?php include hg_load_template('foot');?>