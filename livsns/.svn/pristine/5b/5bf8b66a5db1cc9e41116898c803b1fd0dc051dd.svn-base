<?php 
/* $Id: my_left_menu.tpl.php 2224 2011-02-24 13:24:27Z repheal $ */
?>
<div class="con-right">
	<?php 
	if($this->user['id'])
	{?>
		<div class="pad-all">
			<div class="bk-top1">我的资料</div>
			<div class="wb-block1">
				<div class="users">
					<div class="user-set">
						<h5><a href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>"><?php echo $user_info['username']; ?></a></h5>
						
						
						<div class="user-name">

						<div style="font-size:12px;color:gray;">性别：<?php echo hg_show_sex($user_info['sex']);?></div>
						<div style="font-size:12px;color:gray;">所在地盘：<a style="color:#0164CC" href="<?php echo hg_build_link(SNS_UCENTER . 'geoinfo.php');?>"><?php echo $user_info['group_name'];?></a></div>
						<?php
							$relation = array('birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
							foreach($relation as $key =>$value)
							{
								$temp = $user_info[$key];
								if($temp)
								{
									if(strcmp($key,"birthday")==0 && is_numeric($temp))
									{
										echo '<div style="font-size:12px;color:gray;"><span>'.$value. ' : <span>' . $this->lang['xingzuo'][$temp] . '</div>';
									}
									else
									{
										echo '<div style="font-size:12px;color:gray;"><span style="font-size:12px;color:gray;">'.$value. ' : </span>' . $temp . '</div>';
									}
								}				
							}
						?>
					</div>
					</div> 
					<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php'); ?>"><img src="<?php echo $user_info['middle_avatar']; ?>" title="<?php echo $user_info['username']; ?>" /></a>
				</div>
				<div class="business">
				
				<span class="u-show1">
					<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php');?>"><?php echo $user_info['status_count']; ?></a>
	    		</span>
    			<span class="u-show2">
    				<a href="<?php echo hg_build_link('follow.php');?>"><?php echo $user_info['attention_count']; ?></a>
	    		</span>
    			<span class="u-show3">
	    			<a href="<?php echo hg_build_link('fans.php');?>"><?php echo $user_info['followers_count']; ?></a>
	    		</span>
    			<span class="u-show4">
	    			<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php#video');?>"><?php echo $user_info['video_count']; ?></a>
	    		</span>
				</div>
			</div>
    	</div>
    	<div class="pad-all">
			<div class="bk-top1">视频导航</div>
			<div class="wb-block1">
		    	<div class="menu">
						<?php 
						foreach($this->settings['nav'] as $k => $v)
						{
							if($gScriptName == "index")
		        			{
		        				$gScriptName = "my_station";
		        			}
							if($k == $gScriptName)
							{
						?>
							<a class="<?php echo $v['class'];?>_click" href="<?php echo hg_build_link($v['filename']);?>"></a>
						<?php 
							}
							else 
							{
						?>
							<a class="<?php echo $v['class'];?>" href="<?php echo hg_build_link($v['filename']);?>"></a>
						<?php 
							}
						}
						?>
				</div>  
			</div>
		</div>
    	
    	<?php 
    	if(is_array($album_info))
		{?>
    	<div class="pad-all">
			<div class="bk-top1">我的专辑</div>
			<div class="wb-block1">
				<ul class="hot-video">
				<?php 
					foreach($album_info as $key => $value)
					{
						?>
					<li>
						<ul class="hot-video-list">
							<li><a title="<?php echo  $value['name'];?>" href="<?php echo hg_build_link("user_album_video.php", array('id' => $value['id'],'user_id' => $value['user_id']));?>"><img src="<?php echo $value['cover'];?>"/></a></li>
							<li><a title="<?php echo  $value['name'];?>" href="<?php echo hg_build_link("user_album_video.php", array('id' => $value['id'],'user_id' => $value['user_id']));?>"><?php echo hg_cutchars($value['name'],8," ");?></a></li>
							<li><?php echo $this->lang['video_num'].$value['video_count'];?></li>
							<li><?php echo $this->lang['plays'].$value['play_count'];?></li>
						</ul>
					</li>
					<?php 
					}?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<?php	
		}
	}
	?>

	<?php 
	if(is_array($hot_video))
	{?>
	    	<div class="pad-all">
			<div class="bk-top1">热播视频</div>
			<div class="wb-block1">
				<ul class="hot-video">
					<?php
					unset($hot_video[count($hot_video)-1]);
					foreach($hot_video as $key => $value)
					{
						?>
					<li>
						<ul class="hot-video-list">
							<li><a title="<?php echo $value['title'];?>" href="<?php echo hg_build_link("video_play.php", array('id' => $value['id']));?>"><img src="<?php echo $value['schematic'];?>"/></a></li>
							<li><a title="<?php echo $value['title'];?>" href="<?php echo hg_build_link("video_play.php", array('id' => $value['id']));?>"><?php echo hg_cutchars($value['title'],7," ");?></a></li>
							<li><?php echo $this->lang['plays'].$value['play_count'];?></li>
							<li><?php echo $this->lang['comments'].$value['comment_count'];?></li>
						</ul>
					</li>
					<?php 
					}	
					?>
					</ul>
				<div class="clear"></div>
			</div>
		</div>
		<?php 
	}
	?>
				
	<?php 
		if(is_array($hot_station))
		{?>
    	<div class="pad-all">
			<div class="bk-top1">热播频道</div>
			<div class="wb-block1">
				<ul class="hot-station">
			<?php 
			foreach($hot_station as $key => $value)
			{?>
			<li>
				<a title="<?php echo $value['web_station_name'];?>" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$value['id']));?>"><img src="<?php echo $value['small'];?>"/></a><br/>
				<a title="<?php echo $value['web_station_name'];?>" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$value['id']));?>"><?php echo hg_cutchars($value['web_station_name'],5," ");?></a>
			</li>
			<?php 	
			}
			?>
				</ul>
				<div class="clear1"></div>
			</div>
		</div>
		</div>
	<?php
		}
	?>
