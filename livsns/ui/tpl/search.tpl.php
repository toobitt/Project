<?php 
/* $Id: search.tpl.php 1564 2011-01-05 09:20:12Z repheal $ */
?>

<?php include hg_load_template('head');?>

<div class="content">
	<div class="con-left">
		<?php 
		if($have_result)
		{
		?>	
		<div class="left_list_first_line">
			<div class="search-name">
				<span>共有<?php echo $total_nums; ?>位用户</span>
			</div>		
		</div>
		<div class="search_list">
			<ul class="status-item">
			<?php
			foreach($search_friend as $k => $v)
			{		
			?>
				<li>
					<div class="conBox_l">
						<a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id'])); ?>"><img src="<?php echo $v['middle_avatar'] ?>" title="<?php echo $v['username']; ?>" /></a>
					</div>
					<div class="conBox_c">
						<span><a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id'])); ?>" ><?php echo $v['username']; ?></a></span>
						<p style="margin-top:10px;">
						   <?php echo $v['location']; ?>
						   <span><?php echo $this->lang['followers']; ?><strong><?php echo $v['followers_count']; ?></strong><?php echo $this->lang['people']; ?></span>
						</p>
					</div>
					<div class="conBox_r">
					<?php
					if($this->user['id'] == $v['id']) 				//自己          
					{
						
					}
					else
					{
						if($v['is_friend'] == 1)    //已关注
						{
					?>
					<p>已关注</p>					
					<?php	
						}
						else
						{
					?>
					<p id="<?php echo 'add_' . $v['id']; ?>"><a href="javascript:void(0);" onclick="addFriends(<?php echo $v['id']; ?> , 4)"><?php echo $this->lang['add_friends']; ?></a></p>
					<?php		
						} 
					} 						
					?>					
					</div>
				</li>
			<?php
			}			
			?>		
			</ul>
			<div style="clear:both;"></div>
			<?php echo $showpages; ?>
		</div>		
		<?php 
		}
		else
		{ 		
			echo hg_show_null('真不给力，SORRY!','抱歉没有找到<span style="color:red;">'.$screen_name.'</span>相关的结果');
		}
		?>	
	</div>
</div>

<?php include hg_load_template('foot');?>