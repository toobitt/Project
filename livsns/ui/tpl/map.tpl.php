<?php 
/* $Id: map.tpl.php 1580 2011-01-06 09:59:49Z yuna $ */
?>
<?php include hg_load_template('head');?> 
 <div class="content clear" id="equalize">
 	 <div class="content-left">
 	 	<div class="rounded-top"></div>
 	 	<div style="width:100%;"> 
    		<div id="map_canvas" style="width:612px; height: 600px;"></div>
    	</div>
    </div>
    <div class="content-right">
		<div class="rounded-top-right"></div>
		<div class="pad-all"><?php $user_info = $this->user;?>
		<?php include hg_load_template("userImage");?>
		<?php include hg_load_template("userInfo");?>
		</div>
	</div>
    
</div>
   <?php include hg_load_template('foot'); ?>
  