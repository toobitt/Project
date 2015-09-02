<?php
/* $Id: k.tpl.php 1734 2011-01-13 05:58:57Z repheal $ */
 ?> 
 <style>
.top_notice{display: none; float: right;height:auto; left: 70%; position: fixed;width: 280px;z-index:999;}
.nav_notice{color:#fff;font-size:13px;margin-top:50px;text-align:left;}
.close_x{z-index: 999;float: right;left: 260px;position: absolute; top: 0;cursor:pointer; border-top: 1px solid #CCCCCC;}
#nav_002{width:280px;position: absolute;top: 0;background: none repeat scroll 0 0 #FDFFEA;border: 1px solid #CCCCCC;}
#nav_002 li {float:left;width:90%;padding-left:5px;line-height:20px;color:#666;}
#nav_002 li a{color:#333;} 
</style>
<?php if($this->input['t']){ ?>
<script type="text/javascript" src="<?php echo RESOURCE_DIR;?>scripts/jquery.min.js"></script> 
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_DIR . 'scripts/notify.js';?>"></script> 
<div class="top_notice" id="notice_div" style="display:none;" >
<?php if ($this->user['id'] > 0){ 	?>
<div class="nav_notice"> 
<a class="close_x" onclick="hide_notice()"><img src="<?php echo RESOURCE_DIR?>img/close.jpg" title="关闭提示" onclick="hide_notice()"/></a>
<ul id="nav_002" > 
    <?php  include_once (hg_load_template("notice"));?>
</ul> 
</div>
 <?php }?>
</div>
 