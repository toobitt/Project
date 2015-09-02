<?php 
/* $Id: n.tpl.php 3676 2011-04-19 00:49:06Z develop_tong $ */
?>

<?php include hg_load_template('head');?>

<div class="content clear" id="equalize">
	<div class="content-left">
		<?php 
		if($have_result)
		{
		?>	
		
		<div class="my-business">
			
			<div class="search-box" style="float:right;">
				<form action="n.php" method="post">
				<input type="hidden" name="search" value="search">
				<input style="font-size:12px;color:gray;border:1px solid #CCCCCC;" class="search" id="search_content" onblur="showText(this);" onclick="clearText(this);" type="text" name="search_name" value="<?php echo $this->input['search_name'] ? $this->input['search_name'] : $this->lang['input_screen_name']; ?>" />
				<input type="submit" name="search_follow" value="<?php echo $this->lang['search']; ?>" style="padding:0px 10px;" />
				</form>
			</div>
			
			<span>共有<?php echo $total_nums; ?>位用户</span>
		</div>		
		
		<div class="followers_list">
			<ul class="status-item">
			<?php
			foreach($search_friend as $k => $v)
			{		
			?>
				<li>
					
					<div class="blog-content">
						<div class="attention clear">
							<p class="name"><a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id'] )); ?>" ><?php echo $v['username']; ?></a>：<span><a><?php echo $v['followers_count']; ?></a>粉丝&nbsp;&nbsp;<a><?php echo $v['attention_count']; ?></a>关注&nbsp;&nbsp;<a><?php echo $v['status_count']; ?></a>点滴&nbsp;&nbsp;<a><?php echo $v['video_count']; ?></a>视频</span></p>
						</div>
						
						<div class="close-concern">
						<?php
						if($this->user['id'] == $v['id']) //自己          
						{
							
						}
						else
						{
							if($v['is_friend'] == 1)    //已关注
							{
						?>
						<a class="been-concern"></a>					
						<?php	
							}
							else
							{
						?>
						<p id="<?php echo 'add_' . $v['id']; ?>"><a class="follow-gz" href="javascript:void(0);" onclick="addFriends(<?php echo $v['id']; ?> , 4)"></a></p>
						<?php		
							} 
						} 						
						?>									
						</div>
						
					
					</div>
					
					<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id'])); ?>"><img src="<?php echo $v['middle_avatar'] ?>" title="<?php echo $v['username']; ?>" /></a>
				    <div style="margin:5px;" >
						<?php echo hg_verify($v['text']);?>						
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
			echo hg_null_search($screen_name);					
		}
		?>	
	</div>
	
		<?php
		if($this->user['id'] > 0)
		{
			
			//print_r($this->user);
	?>
    <div class="content-right">
	<div class="pad-all">
	<!-- load userInfo -->
	<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
	<div class="user">
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
	<a href="<?php echo hg_build_link(SNS_UCENTER.'avatar.php'); ?>"><img src="<?php echo $user_info['middle_avatar']; ?>" title="<?php echo $user_info['username']; ?>" /></a>
	</div>
	
	<?php include hg_load_template('userInfo');?>
	
		</div>

	    </div>
	<?php 			
		}
		else 
		{
			?>
			<div class="content-right login">

		
		<form action="<?php echo SNS_UCENTER;?>login.php" method="POST">
			<div class="login-menu" id="login">
				<a class="register" href="<?php echo hg_build_link(SNS_UCENTER.'register.php'); ?>"></a>
				<div class="login-text">
					<input type="text" id="username" name="username" class="username_bg" onfocus="clearUser(this);" onblur="showUser(this);"/>
					<input type="password" id="password" name="password"/>
				</div>
				
				<div class="pwd-recovery" style=" visibility:hidden"><span>
				<input name="" type="checkbox" value="" checked />下次自动登录</span><a href="#">找回密码</a></div>
				<input id="login_bt" class="login-input" type="submit" value=" " name="submit"/>
			</div>
		<input type="hidden" value="dologin" name="a" />
		<input type="hidden" value="<?php echo $this->input['referto'];?>" name="referto" />
		</form>
		<div class="pad-all">
				
		<div class="bk-top1">热门话题</div>                                                                                                                                                                                              
		<div class="wb-block1">
		<?php if ($topic)
		{?>
		<ul class="topic clear">
			<?php
			foreach($topic as $value)
			{
			?>
			<li>
				<a href="<?php echo hg_build_link('k.php' , array('q' => $value['title'])); ?>">
				<?php echo $value['title'];?></a><span>(<?php echo $value['relate_count'];?>)</span>
			</li>
			<?php
			}
			?>
		</ul>
		
		<?php }?>
		</div>
		</div>

	</div>
	<?php 
		}
	?>
		</div>

<?php include hg_load_template('foot');?>