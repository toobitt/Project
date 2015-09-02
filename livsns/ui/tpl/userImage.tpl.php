<?php
/*$Id:$*/
?>
<div class="bk-top1">
	<?php if($this->user['id'] == $user_info['id']){?>我<?php }else{ ?>TA<?php } ?>的资料</div>
<div class="wb-block1">
	<div class="user">
	<div class="user-set">
		<h5><a href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>">
				<?php echo $user_info['username']?>
			</a> </h5>
		<div class="user-name" style="margin-right:90px;_margin-right:45px;">
			<?php if($user_info['group_name']){?><div style="font-size:12px;color:gray;">所在地盘：<a style="color:#0164CC" href="<?php echo hg_build_link(SNS_TOPIC . '?m=thread&group_id=' . $user_info['group_id']);?>"><?php echo $user_info['group_name'];?></a></div><?php }?>
		</div>
		</div> 
		
		<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>"><img title="<?php echo $user_info['username'];?>" src="<?php echo $user_info['middle_avatar'];?>"></a>
	</div>