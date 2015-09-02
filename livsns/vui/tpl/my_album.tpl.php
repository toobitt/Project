<?php
/* $Id: my_album.tpl.php 3495 2011-04-09 09:22:22Z repheal $ */
?>

<?php include hg_load_template('head');?>
<div class="main_div">
	<div class="right_window">
	<div id="album_info">
	<h3><a href="<?php echo hg_build_link("upload.php")?>">+上传视频</a><a href="javascript:void(0);"onclick="create_album(1);">+创建专辑</a>我的专辑</h3>
	<div class="show_info">
			<div class="album">
				<ul class="album_ul">
					<?php 
					if($album_info)
					{
						foreach($album_info as $key => $value)
						{
							?>
						<li class="album_li" onmousemove="album_mouse(<?php echo $value['id'];?>,0)" onmouseout="album_mouse(<?php echo $value['id'];?>,1)">
							<a href="javascript:void(0);" onclick="manage_album_video(<?php echo $value['id'];?>);"><img src="<?php echo $value['cover'];?>"/></a>
							<div id="album_na_<?php echo $value['id'];?>" class="album_na">
								<a href="javascript:void(0);" onclick="manage_album_video(<?php echo $value['id'];?>);"><?php echo hg_cutchars($value['name'],8," ");?>(<?php echo $value['video_count'];?>)</a>
							</div>
							<div id="album_ma_<?php echo $value['id'];?>" class="album_ma" style="display:none;">
<!--								<a target="_blank" href="<?php echo hg_build_link('user_album_video.php', array('id'=>$value['id'],'user_id'=>$value['user_id']));?>">预览</a>-->
								<a href="javascript:void(0);" onclick="del_album(<?php echo $value['id'];?>);">删除</a>
								<a href="javascript:void(0);" onclick="edit_album_info(<?php echo $value['id'];?>);">编辑</a>
							</div>
						</li>
					<?php 
						}
					}
					else 
					{?>
					<li>
						<?php echo hg_show_null(" ", "暂未创建专辑",1);?>
					</li>
					<?php 	
					}
					?>
				</ul>
				<div class="clear1"></div>
				<?php echo $showpages;?>
			</div>
	</div>
	<img src="<?php echo RESOURCE_DIR?>img/right_window_bottom.gif" class="for_ie" />
	</div>
	</div>
	<?php include hg_load_template('my_right_menu');?>
</div>

<?php include hg_load_template('tips');?>
<?php include hg_load_template('move_album_video');?>
<?php include hg_load_template('foot');?>