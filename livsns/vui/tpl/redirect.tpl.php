<?php 
/* $Id: redirect.tpl.php 2633 2011-03-10 06:17:11Z repheal $ */
?>
<?php include hg_load_template('head');?>
    <div class="main_div">
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
<?php include hg_load_template('foot');?>