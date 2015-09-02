<?php
/* $Id: blacklist.tpl.php 3069 2011-03-28 02:35:44Z chengqing $ */

?>
<?php include hg_load_template('head');?>

<div class="content clear people" id="equalize">
	<div class="content-left">
		
		<div class="news-latest">
			<div class="tp"></div>
			<div class="md"><h3 style="padding-left:20px;font-size:15px;"><?php echo $this->lang['block'];?></h3></div>
		</div>
				
		<div class="black_list">
		
		<!-- 记录当前弹出框的ID -->
		<input id="showId" type="hidden" name="showId" value="0" />
		
		<?php  
		if($hava_blocks)
		{
			//print_r($black_list);
			
		?>
			<ul class="black_ul">
			
			<?php 
			foreach($black_list as $k => $v)
			{
			?>
				<li class="clear" id="<?php echo 'deleteBlock_' . $v['id']; ?>" style="border-left:1px solid #CCCCCC;border-right:1px solid #CCCCCC;">
										
					<div class="attention clear">					
						<a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id']));?>"><img style="border:1px solid silver; padding:2px;" src="<?php echo $v['middle_avatar'] ?>" title="<?php echo $v['screen_name']; ?>" /></a>&nbsp;&nbsp;
						<a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id'])); ?>"><?php echo $v['screen_name']; ?></a>
						<span class="black-cr" style="margin-left:20px;font-size:12px;color:gray">
					<?php  echo date("m月  d日 H:i:s" , $v['join_time']); ?>	
					</span>
					
					</div>
					
					
					
					<span class="close-concern">
						<a href="javascript:void(0);"  onclick="moveBlocks(<?php echo $v['id']; ?>)"><?php echo $this->lang['destroy_block']; ?></a>
					</span>
										
					<span id="<?php echo 'showMove_' . $v['id']; ?>" class="black-show">
					</span>
				</li>
				
			<?php 
			}		
			?>			
			</ul>
		<?php
		}
		else
		{ 	
		?>

		<p class="no-result"><?php echo $this->lang['no_blocks'];?></p>
		<p style="line-height:20px;font-size:15px;padding-left:20px;padding-bottom:10px;border-bottom:1px solid #CCC;"><?php echo $this->lang['black_explain'];?></p>	
		<?php
		}
		?>		
		</div>
		
	</div>
	
	
	<div class="content-right">	

		<div class="pad-all">
		
		<div class="bk-top1">个人资料
				
		<?php 
		if($this->user['id'] > 0)
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
					<dt><a href="<?php echo hg_build_link('follow.php' , $user_param);?>" id="liv_info_attention_count"><?php echo $user_info['attention_count']; ?></a></dt>
					<dd><a href="<?php echo hg_build_link('follow.php' , $user_param);?>"><?php echo $this->lang['friends']; ?></a></dd>
				</dl>
				<dl class="border">
					<dt><a href="<?php echo hg_build_link('fans.php' , $user_param);?>" id="liv_info_followers_count"><?php echo $user_info['followers_count']; ?></a></dt>
					<dd><a href="<?php echo hg_build_link('fans.php' , $user_param);?>"><?php echo $this->lang['followers']; ?></a></dd>
				</dl>
				<dl class="border">
					<dt><a href="<?php echo hg_build_link('user.php' , $user_param);?>"><?php echo $user_info['status_count']; ?></a></dt>
					<dd><a href="<?php echo hg_build_link('user.php' , $user_param);?>"><?php echo $this->lang['name']; ?></a></dd>
				</dl>
				<dl >
					<dt><a href="<?php echo hg_build_link(SNS_VIDEO.'my_video.php' , $user_param);?>" id="liv_info_attention_count"><?php echo $user_info['video_count']; ?></a></dt>
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
		
		<?php if($this->user['id'] > 0)
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