<?php
/* $Id: my_comments.tpl.php 3553 2011-04-12 09:12:35Z repheal $ */
?>
<?php include hg_load_template('head');?>
<?php include hg_load_template('tips');?>
<div class="main_div">
	<div class="right_window">
	<h3>我的评论</h3>
	<div class="show_info">
		<div class="comment_menu">
			<ul class="comment_state">
			<?php foreach($list as $key => $value)
			{
				if($key == $state)
				{
				?>
			<li class="comment_state_now"><a href="<?php echo $value['url'];?>" class="blod"><?php echo $value['name'];?></a></li>
			<?php	
				}
				else 
				{?>
			<li><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
			<?php 	
				}
			}?>
			</ul>		

			<ul class="comment_type">
			<?php foreach($menu as $key => $value)
			{
				if($key == $type)
				{
				?>
			<li class="comment_type_now"><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
			<?php	
				}
				else 
				{?>
			<li><a href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a></li>
			<?php 	
				}
			}?>
			</ul>		
		</div>
		<div class="comment_manage clear">
			<?php if($stationInfo)
			{?>
			<ul class="comment_list">
				<?php 
				foreach($stationInfo as $key => $value)
				{?>
				<li id="com_<?php echo $value['id'];?>" class="clear">
					<div class="comment-img"><a href="<?php echo hg_build_link('user.php', array('user_id'=>$value['user']['id'],));?>"><img src="<?php echo $value['user']['middle_avatar'];?>"/></a></div>
					<div class="comment-bar">
						<a class="bar-left" href="<?php echo hg_build_link('user.php', array('user_id'=>$value['user']['id'],));?>"><?php echo $value['user']['username'];?></a>
						<div class="bar-right">
							<span><?php echo hg_get_date($v['create_time']);?></span>
							<?php if($state != 2)
							{?>
							<a href="javascript:void(0);" onclick="del_comment(<?php echo $value['id'];?>,<?php echo $value['cid'];?>,<?php echo $type;?>);">删除</a>
							<?php 
							}
							else 
							{?>
							<a href="javascript:void(0);" onclick="recover_comment(<?php echo $value['id'];?>,<?php echo $value['cid'];?>,<?php echo $type;?>);">恢复</a>
							<?php 
							}
							?>
						</div>
					</div>
					<div class="comment-con"><?php echo hg_show_face($value['content']);?></div>
				<?php 
				if(is_array($value['reply']))
				{?>
				<ul class="reply_list" id="rep_<?php echo $value['id'];?>">
				<?php	
					foreach($value['reply'] as $k=>$v)
					{
				?>
					<li id="com_<?php echo $v['id'];?>" class="clear">
						<div class="comment-img"><a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['user']['id'],));?>"><img src="<?php echo $v['user']['middle_avatar'];?>"/></a></div>
						<div class="comment-bar">
							<a class="bar-left" href="<?php echo hg_build_link('user.php', array('user_id'=>$v['user']['id'],));?>"><?php echo $v['user']['username'];?></a>
							<div class="bar-right">
								<span><?php echo hg_get_date($v['create_time']);?></span>
								<?php if($state != 2)
								{?>
								<a href="javascript:void(0);" onclick="del_comment(<?php echo $v['id'];?>,<?php echo $v['cid'];?>,<?php echo $type;?>);">删除</a>
								<?php 
								}
								else 
								{?>
								<a href="javascript:void(0);" onclick="recover_comment(<?php echo $v['id'];?>,<?php echo $v['cid'];?>,<?php echo $type;?>);">恢复</a>
								<?php 
								}
								?>
							</div>
						</div>
						<div class="comment-con"><?php echo hg_show_face($value['content']);?></div>
					</li>
				<?php 	
					}	?>
				</ul>
				<?php 
				}?>
					
				</li>
				<?php 		
				}?>
				
			</ul>
			<?php				
			}
			else 
			{
				hg_show_null('I’m Sorry', '暂无评论');				
			}?>
		</div>
		</div>
		<img src="<?php echo RESOURCE_DIR?>img/right_window_bottom.gif" class="for_ie" />
	</div>
	<?php include hg_load_template('my_right_menu');?>
</div>

<?php include hg_load_template('foot');?>