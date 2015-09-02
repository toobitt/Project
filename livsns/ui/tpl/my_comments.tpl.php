<?php
/*$Id: my_comments.tpl.php 3767 2011-04-21 05:50:40Z repheal $*/
include hg_load_template("head");
?> 
<style>
#floatBoxBg{overflow: hidden; width: 100%; height: 100%; border: 0pt none; padding: 0pt; margin: 0pt; top: 0pt; left: 0pt; display: block; visibility: visible; background-color: rgb(0, 0, 0); opacity: 0.5; position: fixed; z-index: 100;
/*width:100%;height:100%;background:#fff;filter:alpha(opacity=50);opacity:0.5;position:absolute;top:0;left:0;*/}
.floatBox{display: block; visibility: visible; position: absolute; z-index: 1000;border:5px solid #ccc;}
.floatBox .title{height:23px;padding:7px 10px 0;background:none repeat scroll 0 0 #F4F4F4;color:#000;border-bottom:2px #ccc solid;}
.floatBox .title h4{float:left;padding:0;margin:0;font-size:14px;line-height:16px;}
.floatBox .title span{float:right;cursor:pointer;}
.floatBox .content{margin: 0;background:#fff repeat;width:200px;text-align:center;padding:0;}
</style> 
<div class="main clear">
<div class="ping">  
	<dl class="ping_title"><dd><a href="<?php echo hg_build_link('all_comment.php'); ?>" class="<?php if(!$tag){?>ping_current<?php }?>"><?php echo $this->lang['resived_comments']?></a> | <a href="<?php echo hg_build_link('all_comment.php' , array('t' => 1)); ?>" class="<?php if($tag){?>ping_current<?php }?>"><?php echo $this->lang['send_comments']?></a></dd></dl>
	<?php 
	if($tag == 1)
	{ 
		include hg_load_template("send");
	}
 	else
 	{ 
 		include hg_load_template("resived");
 	}
 ?>
	<?php echo $showpages;?>
</div> 

<div class="content-right">

	<div class="pad-all">
	<?php include hg_load_template("userImage");?>
	 <?php include hg_load_template("userInfo");?>
	 </div>

</div>
</div>
<?php include hg_load_template("foot");?>