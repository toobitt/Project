<?php 
/* $Id: fans.tpl.php 3235 2011-03-31 05:23:38Z chengqing $ */
?>
<?php include hg_load_template('head');?>
 
<div class="content clear people" id="equalize">
	<div class="content-left">
		<!-- 个人信息   -->
		<div class="rounded-top"></div>
		<div class="expression">
	    	<img src="<?php echo $user_info['larger_avatar'];?>" class="pic"/>
        	<div class="people_user">

                <h2 id="username"><?php echo $user_info['username'];?></h2>
                <p><?php echo $user_info['location']; ?></p>

				<div class="follow-all">
					<?php
					if($is_my_page)
					{
						
					}
					else
					{
						if($relation == 0)    //该用户已在黑名单中
						{
					?>
					<span class="follw" id="<?php echo 'add_' . $id ?>">已加入黑名单</span>
					<span class="close-follw" id="deleteFriend"><a href="javascript:void(0);" onclick="deleteBlock(<?php echo $id; ?>);">解除</a></span>
					<?php		
						}
						if($relation == 1)    //源用户和目标用户互相关注
						{
					?>
					<span class="follw" id="<?php echo 'add_' . $id ?>">相互关注</span>
					<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend(<?php echo $id;?>);"></a></span>
					<?php 		
						}	
						if($relation == 2)    //源用户关注了目标用户
						{
					?>
					<span class="follw" id="<?php echo 'add_' . $id ?>"><a class="been-concern"></a></span>
					<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend(<?php echo $id;?>);"></a></span>
					<?php		
						}	
						if($relation == 3 || $relation == 4)	  //目标用户关注了源用户或源用户和目标用户没有关系 
						{
					?>		
					<span class="follw" id="<?php echo 'add_' . $id; ?>"><a class="concern" href="javascript:void(0);" onclick="addFriends(<?php echo $id;?> , <?php echo $relation;?>);"></a></span>
					<span class="close-follw" id="deleteFriend"></span>
					<?php 		
						}
					} 
					?>
				</div>
            </div>
        </div>
        
        
	
		<!-- 导航  -->
		<div class="menu">
        <?php foreach($this->settings['list'] as $k => $v)
		{
			if($k == $gScriptName)
			{?>
        		<a href="<?php echo hg_build_link($v['filename'] , $user_param);?>" class="<?php echo $v['class'];?>"><?php echo  $v['name'];?></a>
        	<?php 
			}
			else 
			{
				?>
				<a href="<?php echo hg_build_link($v['filename'] , $user_param);?>" class="<?php echo $v['class'];?>-b"><?php echo  $v['name'];?></a>
			<?php 
			} 
		}
		
		if($is_my_page)
		{
		?> 
		<a href="<?php echo hg_build_link('blacklist.php');?>" class="text-bl-b">黑名单</a>
		<?php	
		}		
		?>
			<?php
			if($is_my_page)
			{			
			?>
			<div style="float:right;margin-right:10px;">
				<form action="fans.php" method="post">
				<input type="hidden" name="search" value="search">
				<input style="font-size:12px;color:gray;border:1px solid #CCCCCC;" class="search" id="search_content" onblur="showText(this);" onclick="clearText(this);" type="text" name="screen_name" value="<?php echo $this->input['screen_name'] ? $this->input['screen_name'] : $this->lang['input_screen_name']; ?>" />
				<input type="submit" name="search_follow" value="搜 索" style="padding:0px 10px;" />
				</form>			
			</div>
			
			<?php 
			}
			?>			
        </div>
				
		<!-- 显示粉丝数和搜索  -->
		<?php  
		if($is_my_page)
		{		
		?>
		<div class="my-business">			
			<div class="left">我有<span id="liv_title_followers_count" ><?php echo $user_info['followers_count']; ?></span>个粉丝</div>			
		</div>		
		<?php
		}
		else
		{ 		
		?>
		<div class="my-business"><?php echo $user_info['username'];?>有<span><?php echo $user_info['followers_count']; ?></span>个粉丝</div>	
		<?php
		}
		?>		
					
		<div class="followers_list">
		
		<!-- 记录当前弹出框的ID -->
		<input id="showId" type="hidden" name="showId" value="0" />
		
		<?php
		if($have_followers == true)
		{
		?>
		<ul class="status-item">		
			<?php
			foreach($followers as $k => $v)
			{				
			?>	
				<li class="clear" id="<?php echo 'delete_' . $v['id']; ?>">
					<div class="blog-content">
						
						<div class="attention clear">
							<p class="name"><a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));?>" ><?php echo $v['username']; ?></a>：<span><a><?php echo $v['followers_count']; ?></a>粉丝</span></p>
							<span style="color:gray;font-size:11px;"><?php echo hg_get_date($v['follow_time']); ?></span>
						</div>
												
						<div class="close-concern">
							<span style="float:left;width:18px;text-align:left;margin-right:5px;padding-right: 5px;display:block;"><a class="chat" href="javascript:void(0);" onclick="showMsgBox('<?php echo  $v['username'];?>','<?php echo md5($v['id'] . $v['salt'] . $user_info['id'] . $user_info['salt']);?>')">&nbsp;&nbsp;</a></span>
						<?php						
						if($is_my_page)  	//查看自己粉丝
						{ 
							if($v['is_mutual'] == 0) 	//自己没有关注粉丝
							{
						?>
						<span id="<?php echo 'add_' . $v['id']; ?>"><a class="follow-gz" href="javascript:void(0);" onclick="addFriends(<?php echo $v['id']; ?> , 3)"></a></span>
						
						<?php 
							}
							else                     	//自己关注了粉丝
							{
						?>
						<a class="relation"></a>
						<?php 
							}						
						?>
						<a class="close-fs-a" href="javascript:void(0);" onclick="moveFans(<?php echo $v['id']; ?>)"></a>
												
						<?php
						}
						else                         	//查看别人粉丝
						{
							if($this->user['id'] == $v['id']) 	//粉丝中含有自己
							{
								//不显示任何操作
							}	
							else
							{
								if($v['is_mutual'] == 0)//自己没有关注粉丝
								{
						?>
						<p id="<?php echo 'add_' . $v['id']; ?>"><a class="follow-gz" href="javascript:void(0);" onclick="addFriends(<?php echo $v['id']; ?> , 4)"></a></p>
						<?php 			 	
							    }
							    else                    //自己关注了粉丝 
							    {  								
						?>
						<a class="been-concern"></a>
						<?php
							    }
							}
						}	
						?>
						
						
						</div>
						<div id="<?php echo 'deleteMove_' . $v['id']; ?>" class="followers-box4"></div>
					
					</div>
					
					<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));?>"><img src="<?php echo $v['middle_avatar'] ?>" title="<?php echo $v['username']; ?>" /></a>
										
				</li>		
			<?php 	
			} 		
			?>				
		</ul>
		
		<?php
		echo $showpages;
		}
		else if($no_result)
		{		
		?>
		<p class="no-result"><?php echo hg_show_null('真不给力，SORRY!','抱歉没有找到<span style="color:red;">'.$screen_name.'</span>相关的结果'); ?></p>
		<?php
		}
		else
		{
		?>
		<p class="no-result"><?php echo hg_show_null('真不给力，SORRY!','该用户还没有粉丝！'); ?></p>
		<?php	
		} 	
		?>
		</div>		
	</div>
	
		
	<div class="content-right">	

		<div class="pad-all">
		
		<div class="bk-top1">个人资料
				
		<?php 
		if($is_my_page)
		{?>
            <a class="link-right" href="<?php echo hg_build_link(SNS_UCENTER.'userprofile.php' , $user_param);?>">设置</a>
		<?php 
		}
		else
		{?>
            <a  class="link-right" href="<?php echo hg_build_link('info.php' , $user_param);?>">查看</a>
		<?php }?>
        </div>
       
       <div class="wb-block1">
       
	       <div class="business">
				<dl class="border">
					<dt><?php echo $user_info['attention_count']; ?></dt>
					<dd><a href="<?php echo hg_build_link('follow.php' , $user_param);?>"><?php echo $this->lang['friends']; ?></a></dd>
				</dl>
				<dl class="border">
					<dt><?php echo $user_info['followers_count']; ?></dt>
					<dd><a href="<?php echo hg_build_link('fans.php' , $user_param);?>"><?php echo $this->lang['followers']; ?></a></dd>
				</dl>
				<dl class="border">
					<dt><?php echo $user_info['status_count']; ?></dt>
					<dd><a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , $user_param);?>"><?php echo $this->lang['name']; ?></a></dd>
				</dl>
				<dl >
					<dt><?php echo $user_info['video_count']; ?></dt>
					<dd><a href="<?php echo hg_build_link(SNS_VIDEO.'my_video.php' , $user_param);?>"><?php echo $this->lang['videos']; ?></a></dd>
				</dl>
			</div>
            
        <ul class="information">

			<?php

				$relation = array('truename'=>'真实姓名','birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
				foreach($relation as $key =>$value)
				{
					$temp = $user_info[$key];
					if($temp)
					{
						if(strcmp($key,"birthday")==0 && is_numeric($temp))
						{
							echo "<li>".$value."： ".$this->lang['xingzuo'][$temp]."</li>";
						}
						else 
						{
							echo "<li>".$value."： ".$temp."</li>";
						}
					}
				}
			?>
         </ul>
         </div>
		
		
		
		
		<div class="bk-top1">点滴导航</div>
		<div class="wb-block1">
		
		<div class="menu">
		<?php 
		foreach($this->settings['nav'] as $k => $v)
		{
			if($k == $gScriptName)
			{
		?>
			<a class="<?php echo $v['class'];?>_click" href="<?php echo hg_build_link($v['filename']);?>"><span><?php echo $v['name'];?></span></a>
		<?php 
			}
			else 
			{
		?>
			<a class="<?php echo $v['class'];?>" href="<?php echo hg_build_link($v['filename']);?>"><span><?php echo $v['name'];?></span></a>
		<?php 
			}
		}
		?>
		</div>
		</div>
		
		
		
		
         
		<?php 	if ($topic)
				{
		?>
		<div class="bk-top1">热门话题</div>
		<div class="wb-block1">
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
		</div>
		<?php }?>
		
		<?php if($is_my_page)
		{
		?>	
            
   		<div class="clear"></div>
		<!-- follow topic -->
		<div class="bk-top1">
		<?php echo $this->lang['topic_follow'];?><strong>(<span id="liv_topic_follow_num"><?php echo count($topic_follow);?></span>)</strong>
		</div>
		<div class="wb-block1">
		
		<ul class="topic clear">
		<?php
		if($topic_follow)
		{
		foreach($topic_follow as $key=>$value)
		{
		?>
		<li class="topic_li" onmouseover="this.className='topic_li_hover'" onmouseout="this.className='topic_li'">
		<?php
		$title = '<a href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.$value['title'] . '</a>';
		echo $title;
		?>
		<a class="close" href="javascript:void(0);" onclick="del_Topic_Follow('<?php echo $value['topic_id'];?>',this)"></a>
		<div class="hidden" id="topic_<?php echo $value['topic_id']?>"><?php echo $value['title'];?></div>
		</li>
		<?php
		}
		}
		?>
		<!-- add follow topic -->
		<li id="addtopicfollow" class="topic-add"><a href="javascript:void(0);" onclick="add_Topic_Follow()"><?php echo $this->lang['insert'];?></a></li>
		<!-- end add follow topic -->
		</ul>
		</div>
		
		<dl id="topicbox" class="topicbox">
		<dt><a id="TopicBoxClose" href="javascript:void(0);" onclick="topicBoxClose()">x</a></dt>
		<dd class="topic_dd_title">
		<input type="text" name="topic" id="topic" style="font-size:12px;width:118px;height:20px;"/>
		<input type="button" style="font-size:12px;width:50px;height:25px;" value=" <?php echo $this->lang['save']?> " onclick="addTopic($('#topic').val())"/>
		</dd>
		<dd class="topic_dd_about" id="topic_dd_about"><?php echo $this->lang['topic_about'];?></dd>
		</dl>	
		<?php }?> 
		</div>

	</div>


</div>
<?php include hg_load_template('foot');?>