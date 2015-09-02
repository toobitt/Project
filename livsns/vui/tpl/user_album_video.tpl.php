<?php
/* $Id: user_album_video.tpl.php 3565 2011-04-13 06:53:16Z repheal $ */
?>
<?php include hg_load_template('head');?>
<div class="vui">
	<div class="con-left">
		<div class="station_content" style="margin:0px 10px 10px 9px;">
		<div class="album">
			<div class="con_top">
				专辑名：<a title="<?php echo $album_video['name'];?>" class="get"><?php echo hg_cutchars($album_video['name'],6," ");?></a>
				视频数：<span class="get"><?php echo $album_video['video_count'];?></span>
			</div>
		<?php
		if($album_video['video'])
		{?>
			<ul class="album_ul con_middle">
			<?php 
			foreach ($album_video['video'] as $key=>$value)
			{
				?>
				<li class="album_li">
					<a target="_blank" title="<?php echo $value['title'];?>" href="<?php echo hg_build_link('video_play.php', array('id'=>$value['id']));?>"><img src="<?php echo $value['schematic'];?>"/></a>
					<div class="album_na">
						<a target="_blank" title="<?php echo $value['title'];?>" href="<?php echo hg_build_link('video_play.php', array('id'=>$value['id']));?>">
						<?php echo hg_cutchars($value['title'],7," ");?>(<?php echo $value['play_count'];?>)
						</a>
					</div>
				</li>
			<?php 	
			}
			?>
			<li><?php echo $showpages;?></li>
			</ul>
		
		<?php 
		} 
		else
		{ 		
			echo hg_show_null('提示', '该用户的专辑视频不存在!');
		}
		?>
		<div class="con_bottom clear"></div>
		</div>	
	</div>
	</div>
	<?php include hg_load_template('my_right_menu');?>
</div>
<?php include hg_load_template('foot');?>




