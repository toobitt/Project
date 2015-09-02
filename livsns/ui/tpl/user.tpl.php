<?php 
/* $Id: user.tpl.php 4084 2011-06-17 02:06:22Z repheal $ */
?>
<?php include hg_load_template('head');?>
<input type="hidden" value="点滴" name="source" id="source"/>

    <div class="content clear people" id="equalize">
    <div class="content-left">
		<div class="rounded-top"></div>
    	<div class="expression_user">
	    	<img src="<?php echo $user_info['larger_avatar'];?>" class="pic"/>
        	<div class="people_user">
                <h2 id="username"><?php echo $user_info['username'];?></h2>
                <p><?php echo $user_info['location']; ?></p>
				
					<?php
					if(!$is_my_page && $this->user['id'])
					{
						if($relation == 0)    //该用户已在黑名单中
						{
						?>
						<div class="blacklist">
						<span class="follw" id="<?php echo 'add_' . $id ?>">已加入黑名单</span>
						<span class="close-follw" id="deleteFriend"><a href="javascript:void(0);" onclick="deleteBlock(<?php echo $id; ?>);">解除</a></span>
						</div>
						<?php		
						}
						if($relation == 1)    //源用户和目标用户互相关注
						{
						?>
						<div class="follow-all">
						<span class="follw" id="<?php echo 'add_' . $id ?>"><a class="mul-concern"></a></span>
						<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend(<?php echo $id;?>);"></a></span>
						</div>
						<?php 		
						}	
						if($relation == 2)    //源用户关注了目标用户
						{
						?>
						<div class="follow-all">
						<span class="follw" id="<?php echo 'add_' . $id ?>"><a class="been-concern"></a></span>
						<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend(<?php echo $id;?>);"></a></span>
						</div>
						<?php		
						}	
						if($relation == 3 || $relation == 4)	  //目标用户关注了源用户或源用户和目标用户没有关系 
						{
						?>	
						<div class="follow-all">
						<span class="follw" id="<?php echo 'add_' . $id; ?>"><a class="concern" href="javascript:void(0);" onclick="addFriends(<?php echo $id;?> , <?php echo $relation;?>);"></a></span>
						<span class="close-follw" id="deleteFriend"></span>
						</div>
						<?php 		
						}						
					}	
					?>						
				
              
                <?php if($user_info['id'] == $this->user['id'])
				{?>
					<a class="set" href="javascript:void(0);" onclick="OpenReleased('')"><?php echo $this->lang['status_mine'];?></a>
				<?php 			
				}
				else 
				{?>
					<div style="position: relative;right: -390px;top: -40px;width: 25px;">
						<a class="set" href="javascript:void(0);" onclick="OpenReleased('<?php echo $user_info['username'];?>')"><?php echo $this->lang['chat'];?></a> 
						<a class="chat" title="和他聊天" style="position:absolute;right:30px;top:2px;" href="javascript:void(0);" onclick="showMsgBox('<?php echo  $user_info['username'];?>','<?php echo md5($user_info['id'] . $user_info['salt'] . $this->user['id'] . $this->user['salt']);?>')">&nbsp;&nbsp;</a>
					</div>
				<?php 	
				}
				?>
            </div>
        </div>
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
				<a href="<?php echo hg_build_link($v['filename'] , $user_param); ?>" class="<?php echo $v['class'];?>-b"><?php echo  $v['name'];?></a>
			<?php 
			} ?>
  <?php }?>
  
  		<!-- 自己的页面中添加黑名单  -->
  		<?php
		if($is_my_page)
		{	
  		?>	
  		<a class="text-hm-b" href="<?php echo hg_build_link('blacklist.php');?>" >黑名单</a>
  		<?php
		}  		
  		?>
        </div>
<?php

if (!empty($statusline)&&is_array($statusline))
{
?>
<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
<ul>
<?php
foreach($statusline as $key => $value)
{
	$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
	$text = hg_verify($value['text']);
	$text_show = $value['text']?$value['text']:$this->lang['forward_null'];
	if($value['reply_status_id'])
	{
		$forward_show = '//@'.$value['user']['username'].' '.$text_show;
		$title = $this->lang['forward_one'].$value['retweeted_status']['text'];
		$status_id = $value['reply_user_id'];
	}
	else
	{
		$forward_show = '';
		$title = $this->lang['forward_one'].$value['text'];
		$status_id = $value['member_id'];
	}
	$text_show = hg_verify($text_show);
	$transmit_info=$value['retweeted_status'];
?>
	<li class="clear" id="mid_<?php echo $value['id'];?>"  onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">
		<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $text_show;?></div>
		<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
		<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
		<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['id'];?></div>
		<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">3</div>
		
		<div class="blog-content">
			<p class="subject">
			<?php echo $text_show."<br/>";?>		
			</p>
<?php include hg_load_template('statusline_content');?>		
			<div class="speak">
				<div class="hidden" id="t_<?php echo $value['id'];?>"><?php echo hg_verify($title);?></div>
				<div class="hidden" id="f_<?php echo $value['id'];?>"><?php echo $forward_show;?></div>
				<span id = "<?php echo "fa".$value['id']?>" style="position:relative;">
					<?php if($is_my_page)
					{?>
					<a href="javascript:void(0);" onclick="unfshowd(<?php echo $value['id']?>)"><?php echo $this->lang['delete'];?></a>	
					<?php }?>
					<a href="javascript:void(0);" onclick="OpenForward('<?php echo $value['id']?>','<?php echo $status_id;?>')"><?php echo $this->lang['forward'].'('.($value['transmit_count']+$value['reply_count']).')'?></a>|
					<a  id="<?php echo "fal".$value['id']?>" href="javascript:void(0);" onclick="favorites('<?php echo $value['id']?>','<?php echo $this->user['id'];?>')"><?php echo $this->lang['collect'];?></a>|
					<a href="javascript:void(0);" onclick="getCommentList(<?php echo $value['id']?>,<?php echo $this->user['id']?>)"><?php echo $this->lang['comment'];?>(<span id="comm_<?php echo $value['id'];?>"><?php echo $value['comment_count'];?></span>)</a>
				</span>
				<strong><?php echo hg_get_date($value['create_at']);?></strong>
				<strong class="overflow" style="max-width:230px"><?php echo $this->lang['source'].$value['source']?></strong>
				<?php 
					if($this->user['id'])
					{?>
				<a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">
			<?php echo $this->lang['report'];?></a>	
					<?php 	
					}
				?>
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_<?php echo $value['id']?>" value="<?php echo $value['comment_count']?>"/>
			<div id="comment_list_<?php echo $value['id'];?>"></div>
		</div> 
	</li>
<?php 
}
?>
 <li class="more"><?php echo $showpages;?></li>
 </ul>
<?php
}
else
{
	echo hg_show_null('真不给力，SORRY!',"暂无没发表点滴或您的权限不够！");
}
?>
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
		       	<span class="u-show1">
					<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , $user_param);?>"><?php echo $user_info['status_count']; ?></a>
	    		</span>
    			<span class="u-show2">
    				<a href="<?php echo hg_build_link('follow.php' , $user_param);?>"><?php echo $user_info['attention_count']; ?></a>
	    		</span>
    			<span class="u-show3">
	    			<a href="<?php echo hg_build_link('fans.php' , $user_param);?>"><?php echo $user_info['followers_count']; ?></a>
	    		</span>
    			<span class="u-show4">
	    			<a href="<?php echo hg_build_link(SNS_VIDEO.'my_video.php' , $user_param);?>"><?php echo $user_info['video_count']; ?></a>
	    		</span>
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
				<a title="<?php echo $value['title'];?>" href="<?php echo hg_build_link('k.php' , array('q' => $value['title'])); ?>">
				<?php echo hg_cutchars($value['title'],10," ");?></a><span>(<?php echo $value['relate_count'];?>)</span>
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
            
   		<div class="clear2"></div>
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
		
		$title = '<a title="'.$value['title'].'" href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.hg_cutchars($value['title'],10," ") . '</a>';
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
<?php include hg_load_template('forward');?>
<?php include hg_load_template('status_pub');?>
<?php include hg_load_template('foot');?>