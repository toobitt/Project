<?php
/* $Id: my_favorites.tpl.php 3377 2011-04-06 09:16:41Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<div class="main_div">
	<div class="right_window">
	<h3><a href="<?php echo hg_build_link("upload.php")?>">+上传视频</a>我的收藏</h3>
		<div class="show_info">
		<div class="clear">
			<?php 
				switch ($type)
				{
					case 0:
						if($stationInfo)
						{
							foreach($stationInfo as $k => $v)
							{?>
							<ul id="collect_<?php echo $v['collect_id'];?>" style="float:left;padding:10px 15px;line-height:23px;height:230px;">
								<li><span ><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?> "><img style="display:inline-block;border:1px solid silver; padding:2px;" src="<?php echo $v['schematic']; ?>" title="<?php echo $v['title']; ?>" /></a></span></li>
								<li><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?>" title="<?php echo $v['title']; ?>"><?php echo $v['title']; ?></a></li>
								<li><span>上传时间:</span><span><?php echo hg_get_date($v['create_time']); ?></span></li>
								<li><span>播放:</span><span><?php echo $v['play_count'] ?></span></li>
								<li><span>评论:</span><span><?php echo $v['comment_count'] ?></span></li>
								<li>
			                     <a href="javascript:void(0);" onclick="del_collect(<?php echo $v['collect_id'];?>,<?php echo $v['id'];?>,<?php echo $type;?>);"><img src="./res/img/sq_button.jpg" width="58" height="18" /></a>
			                   	</li>
							</ul>
							<?php 							
							}
						}
						else 
						{?>
						<ul style="float:left;padding:10px;">
								<li>暂无收藏</li>
						</ul>
					<?php 		
						} 
						break;
					case 1:
						if($stationInfo)
						{
							foreach($stationInfo as $k => $v)
							{?>
							<ul id="collect_<?php echo $v['collect_id'];?>" style="float:left;padding:10px;">
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><img src="<?php echo $v['small'];?>" width="138" height="103" /></a></li>
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><?php echo $v['web_station_name'];?></a></li>
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><?php echo $v['brief']?$v['brief']:'暂无介绍';?></a></li>
					        	<li>
			                     <a href="javascript:void(0);" onclick="del_collect(<?php echo $v['collect_id'];?>,<?php echo $v['id'];?>,<?php echo $type;?>);"><img src="./res/img/sq_button.jpg" width="58" height="18" /></a>
			                   	</li>
							</ul>
							<?php 							
							} 
						}
						else 
						{?>
						<ul style="float:left;padding:10px;">
								<li>暂无收藏</li>
						</ul>
					<?php 		
						}
						break;
					case 2:
						if($stationInfo)
						{
							foreach($stationInfo as $k => $v)
							{?>
							<ul id="collect_<?php echo $v['collect_id'];?>" style="float:left;padding:10px;">
								<li>
								    <a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['id']));?>">
				                		<img src="<?php echo $v['middle_avatar'];?>" width="53" height="56" class="header_photo"/>
				                	</a>
			                	</li>
			                    <li><a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['id']));?>"><?php echo $v['username'];?></a></li>
			                    <li>人气：<span id="c_<?php echo $v['id'];?>"><?php echo $v['collect_count'];?></span></li>
			                    <li>
			                     <a href="javascript:void(0);" onclick="del_collect(<?php echo $v['collect_id'];?>,<?php echo $v['id'];?>,<?php echo $type;?>);"><img src="./res/img/gq_button.jpg" width="58" height="18" /></a>
			                   	</li>
							</ul>
							<?php 							
							} 
						}
						else 
						{?>
						<ul style="float:left;padding:10px;">
								<li>暂无收藏</li>
						</ul>
					<?php 		
						} 
						break;
					default:
						break;
					
				}
			
			?>
		</div>
		<?php echo $showpages;?>
		</div>
		<img src="<?php echo RESOURCE_DIR?>img/right_window_bottom.gif" class="for_ie" />
	</div>
	<?php include hg_load_template('my_right_menu');?>
</div>

<?php include hg_load_template('foot');?>