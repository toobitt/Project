<ul>
<?php 
foreach ($vipUser AS $k => $v)
{		
	?>
	<li class="gz-ul">
		<a href="<?php echo SNS_UCENTER; ?>user.php?user_id=<?php echo $v['id']; ?>" ><img title="<?php echo $v['username']; ?>" style="border: 1px solid silver; height: 50px;padding: 2px;width: 50px;" src="<?php echo $v['middle_avatar']; ?>" /></a>
		<a title="<?php echo $v['username']; ?>" href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));?>"><?php echo hg_cutchars($v['username'] , 3 , '...' , 1); ?></a>
		
		<div class="close-concern">
		<?php
		if($this->user['id'] == $v['id']) 				//自己          
		{
			
		}
		else
		{
			if($v['is_friend'] == 1)    //已关注
			{
		?>
		<p><span class="been-concern">√已关注</span>	</p>			
		<?php	
			}
			else
			{
		?>
		<p id="<?php echo 'add_' . $v['id']; ?>"><a class="concern" href="javascript:void(0);" onclick="addFriends(<?php echo $v['id']; ?> , 4)">＋加关注</a></p>
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
