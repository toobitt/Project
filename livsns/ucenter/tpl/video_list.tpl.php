<?php 
/* $Id: video_list.tpl.php 3583 2011-04-13 10:09:01Z chengqing $ */
?>
	<?php 
	if(!is_array($video)||!$video)
	{
		echo hg_show_null(" ","暂未上传视频",1);
	}
	else 
	{?>
		<ul class="video"><?php 
		$i=0;
		foreach($video as $key => $value)
		{
			if($i%5)
			{?>
				<li class="cus_pad"><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><img title="<?php echo $value['title']; ?>" src="<?php echo $value['schematic'];?>" width="122" height="91" /></a><a title="<?php echo $value['title']; ?>" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'] , 10 , ' ');?></a><span class="txt">播放：<?php echo $value['play_count'];?></span><span class="txt">评论：<?php echo $value['comment_count'];?></span></li>
			<?php
			}
			else 
			{?>
				<li><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><img title="<?php echo $value['title']; ?>" src="<?php echo $value['schematic'];?>" width="122" height="91" /></a><a title="<?php echo $value['title'];?>" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'] , 10 , ' ');?></a><span class="txt">播放：<?php echo $value['play_count'];?></span><span class="txt">评论：<?php echo $value['comment_count'];?></span></li>
			<?php        			
			}
			$i++;
		}
		?>
		</ul>
		<div class="clear"></div>
	<?php 
	}
	echo $showpages;
	?>
	<div class="clear"></div>