<?php 
/* $Id: move_album_video.tpl.php 2175 2011-02-23 08:19:07Z repheal $ */
?>

<style type="text/css">
	#AlbumClose{cursor:pointer;float:right;}
	.albumbox{width:392px;height:auto;display:none;margin:0 auto;width:550px;padding:10px}
	.albumbox h3{background:#E5E5E5;padding:0px 15px 5px 15px;height:23px;line-height:23px;font-size:12px;font-weight:bold;text-align: left;}
	.albumbox .text{padding:10px;margin:0;background:#fff;width:514px}
	.albumbox .text li{margin-bottom:7px;}
	.albumbox_top{background:url(./res/img/Rounded.png) 0 -319px no-repeat;height:16px;font-size:0}
	.albumbox_middle{padding:0 8px;background:url(./res/img/zp_bg.png) repeat-y;width:auto;text-align:center;}
	.albumbox_middle .album_list{text-align: left;}
	.albumbox_middle .album_list ul{ padding: 5px 0 5px 50px;}
	.albumbox_middle .album_list ul li{font-size: 14px;padding: 5px 0;}
	.albumbox_middle .album_list ul li span{float: right;margin-right: 100px;}
	
	.albumbox_bottom{background:url(./res/img/Rounded.png) 0 -336px no-repeat;height:16px;font-size:0}
	

</style>

<div id="albumVideo" class="albumbox" style="top:10%;left:5%;">
	<div class="albumbox_top"></div>
	<div class="albumbox_middle clear">
		<h3><span id="AlbumClose">X</span><?php echo $this->lang['albumList']?></h3>
		<div class="album_list">
		<?php 
		if($album_list)
		{?>
			<ul  id="album_list">
				<?php 
				foreach ($album_list as $key => $value)
				{?>
				<li><input name="alb" type="radio" onclick="move_album_video(this);" value="<?php echo $value['id'];?>"/><a href="javascript:void(0);" onclick="move_album_video(<?php echo $value['id'];?>);"><?php echo $value['name'];?></a><span><?php echo $value['create_time'];?></span></li>
				<?php 	
				}
				?>
				
			</ul>
		<?php 	
		echo $showpage;
		}
		else 
		{
			echo hg_show_null("提示","暂无专辑");
		}
		?>
		</div>
	</div>
	<div class="albumbox_bottom clear"></div>
</div>


