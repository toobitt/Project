<?php
/* $Id: user_video.tpl.php 1898 2011-01-27 03:17:41Z chengqing $ */
?>

<?php include hg_load_template('head');?>

<div class="user">
	<div class="content">
	<div class="album_list" style="width:800px;height:600px;">
		<?php
		if($album_info)
		{?>
		<ul class="album-info">
		<?php 
			foreach ($album_info as $key=>$value)
			{?>
				<li>
					<a target="_blank" href="<?php echo hg_build_link('user_album_video.php', array('id'=>$value['id'],'user_id'=>$value['user_id']));?>"><img src="<?php echo $value['cover'];?>"/></a>
					<ul>
						<li>专辑名：<a target="_blank" href="<?php echo hg_build_link('user_album_video.php', array('id'=>$value['id'],'user_id'=>$value['user_id']));?>"><?php echo $value['name'];?></a></li>
						<li>视频数：<?php echo $value['video_count'];?></li>
						<li>播放次数：<?php echo $value['play_count'];?></li>
					</ul>
				</li>
			<?php 	
			}
		?>
		</ul>
		<?php
		echo $showpages; 
		} 
		else
		{ 		
			echo hg_show_null('提示', '该用户还没有编辑专辑!');
		}
		?>
	</div>
</div>
</div>

<?php include hg_load_template('foot');?>


