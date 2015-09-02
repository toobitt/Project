<?php 
/* $Id: index.tpl.php 2113 2011-02-18 05:13:03Z yuna $ */
?>
<?php include hg_load_template('head');?>
<style type="text/css">
body{background:none}
clear{display: block}
.wb_list{width:100%;overflow:hidden;float:left;}
.wb_list li{width:44.5%;float:left;display:inline-block;margin-bottom:20px;}
.wb_list li img{margin-right:10px;}
.wb_list li a.title{font-family:Microsoft YaHei;font-size:20px;color:#0070bc;text-decoration:none;}
.wb_list li p{line-height:20px;font-size:12px;color:#626262}
</style>
	<ul class="wb_list">
	<?php
	foreach($app_modules AS $k => $v)
	{
	?>
		<li>
	   <a href="<?php echo $v['link']?>"><img src="<?php echo RESOURCE_DIR . $k?>.gif" align="left" /></a>
		<a href="<?php echo $v['link']?>" class="title"><?php echo $v['text']?></a>
		<p><?php echo $v['desc']?></p>
		</li>
	<?php
	}
	?>
	</ul>
<?php include hg_load_template('foot');?>
