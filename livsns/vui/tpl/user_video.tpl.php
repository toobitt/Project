<?php
/* $Id: user_video.tpl.php 2633 2011-03-10 06:17:11Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<div class="user">
	<div class="content clear">
	    <div class="user_video_title"><h3><?php echo $user_info['username'] . "的视频";?></h3></div>
		<?php
		if(count($video_info)>1)
		{
			?>
			<ul class="show_video_nav clear">
			<li>浏览:</li>
			<?php
			$tmp_count = 0;
			$counts = count($this->settings['user_video_nav']);
			
			foreach($this->settings['user_video_nav'] as $k => $v)
			{
				if($v == $show_type)
				{
		?>
				<li><strong><?php echo $k; ?></strong></li> 
		<?php 			
				}
				else
				{
		?>
				<li><a href="<?php echo hg_build_link('user_video.php', $this->input['user_id']?array('user_id'=>$this->input['user_id'],'show_type'=>$v):array('show_type'=>$v)); ?>"><?php echo $k; ?></a></li>
		<?php 			
				} 
				
				$tmp_count++;
				
				if($tmp_count != $counts)
				{
				?>
				<li> | </li>
				<?php
				}
			}
		?>
				<li >共有 <?php echo $total_nums;?> 部视频</li>
			</ul>
			
		<?php	
			
			foreach($video_info as $k => $v)
			{				
		?> 
			<ul style="float:left;padding:10px;">
				<li><span ><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?> "><img style="display:inline-block;border:1px solid silver; padding:2px;" src="<?php echo $v['schematic']; ?>" title="<?php echo $v['title']; ?>" /></a></span></li>
				<li><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?>" title="<?php echo $v['title']; ?>"><?php echo $v['title']; ?></a></li>
				<li><span>上传时间:</span><span><?php echo hg_get_date($v['create_time']); ?></span></li>
				<li><span>时长:</span><span><?php echo hg_get_video_toff($v['toff']);?></span></li>
				<li><span>播放:</span><span><?php echo $v['play_count'] ?></span></li>
				<li><span>评论:</span><span><?php echo $v['comment_count'] ?></span></li>
				<?php if(!$relation)
				{?>
					<li id="collect_<?php echo $v['id'];?>"><?php 
					if($v['relation'])
						{?>
						<img src="./res/img/sy_button.jpg" width="58" height="18" />
					<?php 
						}
						else 
						{?>
						<a href="javascript:void(0);" onclick="add_collect(<?php echo $v['id'];?>,0,<?php echo $v['user_id'];?>);"><img src="./res/img/sc_button.jpg" width="58" height="18" /></a>
					<?php 		
						}?>
					</li>
				<?php 
				}?>
			</ul>
		<?php
			}
		?>
			<div class="page clear"><?php echo $showpages; ?></div>
		</div>
		
		<?php			
		}
		else
		{
		?>
		<p>该用户还没有发布的视频!</p>
		<?php
		}
		?>	
</div>

<?php include hg_load_template('foot');?>