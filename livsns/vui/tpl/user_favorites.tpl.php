<?php
/* $Id: user_favorites.tpl.php 2633 2011-03-10 06:17:11Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<div class="user">
	<div class="content clear">
		<ul class="collect-list">
		<?php 
		foreach($list as $key => $value)
		{
			if($key == $type)
			{
			?>
		<li><a href="<?php echo $value['url'];?>" class="blod"><?php echo $value['name'];?></a></li>
		<?php	
			}
			else 
			{?>
		<li><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
		<?php 	
			}
		}?>
		</ul>
		<div class="clear">
			<?php 
				switch ($type)
				{
					case 0:
						if(is_array($stationInfo))
						{
							foreach($stationInfo as $k => $v)
							{?>
							<ul style="float:left;padding:10px;">
								<li><span><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?> "><img style="display:inline-block;border:1px solid silver; padding:2px;" src="<?php echo $v['schematic']; ?>" title="<?php echo $v['title']; ?>" /></a></span></li>
								<li><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?>"><?php echo $v['title']; ?></a></li>								
								<li><span>上传时间:</span><span><?php echo hg_get_date($v['create_time']); ?></span></li>
								<li><span>时长:</span><span><?php echo hg_get_video_toff($v['toff']);?></span></li>
								<li><span>播放:</span><span><?php echo $v['play_count'] ?></span></li>
								<li><span>评论:</span><span><?php echo $v['comment_count'] ?></span></li>
								<?php if(!$relation)
								{?>
									<li id="collect_<?php echo $v['id'];?>">
									<?php 
									if($v['relation'])
									{?>
									<img src="./res/img/sy_button.jpg" width="58" height="18" />
									<?php 
									}
									else 
									{?>
									<a href="javascript:void(0);" onclick="add_collect(<?php echo $v['id'];?>,0,<?php echo $v['user_id'];?>);"><img src="./res/img/sc_button.jpg" width="58" height="18" /></a>
									<?php 		
									}
									?>
				                   	</li>
			                   	<?php 		
								}?>
							</ul>
							<?php 							
							} 
						}
						else 
						{
							echo hg_show_null(' ','暂无收藏');		
						}
						break;
					case 1:
						if(is_array($stationInfo))
						{
							foreach($stationInfo as $k => $v)
							{?>
							<ul style="float:left;padding:10px;">
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><img src="<?php echo $v['small'];?>" width="138" height="103" /></a></li>
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><?php echo $v['web_station_name'];?></a></li>
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><?php echo $v['brief']?$v['brief']:'暂无介绍';?></a></li>
								<li id="collect_<?php echo $v['id'];?>">	
								<?php if(!$relation)
								{?>
									<li id="collect_<?php echo $v['id'];?>">
									<?php 
									if($v['relation'])
									{?>
									<img src="./res/img/sy_button.jpg" width="58" height="18" />
									<?php 
									}
									else 
									{?>
									<a href="javascript:void(0);" onclick="add_collect(<?php echo $v['id'];?>,1,<?php echo $v['user_id'];?>);"><img src="./res/img/sc_button.jpg" width="58" height="18" /></a>
									<?php 		
									}
									?>
				                   	</li>
			                   	<?php 		
								}?>
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
							<ul style="float:left;padding:10px;">
								<li>
								    <a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['id']));?>">
				                		<img src="<?php echo $v['middle_avatar'];?>" width="53" height="56" class="header_photo"/>
				                	</a>
			                	</li>
			                    <li><a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['id']));?>"><?php echo $v['username'];?></a></li>
			                    <li>人气：<span id="c_<?php echo $v['id'];?>"><?php echo $v['collect_count'];?></span></li>
                   				<?php if(!$relation)
								{?>
									<li id="collect_<?php echo $v['id'];?>">
									<?php 
									if($v['relation'])
									{?>
									<img src="./res/img/sy_button.jpg" width="58" height="18" />
									<?php 
									}
									else 
									{?>
									<a href="javascript:void(0);" onclick="add_collect(<?php echo $v['id'];?>,2,<?php echo $v['user_id'];?>);"><img src="./res/img/sc_button.jpg" width="58" height="18" /></a>
									<?php 		
									}
									?>
				                   	</li>
			                   	<?php 		
								}?>
			                   	
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
	<div></div>
</div>

<?php include hg_load_template('foot');?>