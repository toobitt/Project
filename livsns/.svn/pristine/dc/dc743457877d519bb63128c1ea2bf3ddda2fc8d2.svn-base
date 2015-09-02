		
		<div class="business">
			<dl>
				<dt><a href="<?php echo hg_build_link('follow.php' , $user_param);?>" id="liv_info_attention_count"><?php echo $user_info['attention_count']; ?></a></dt>
				<dd><a href="<?php echo hg_build_link('follow.php' , $user_param);?>"><?php echo $this->lang['friends']; ?></a></dd>
			</dl>
			<dl class="border">
				<dt><a href="<?php echo hg_build_link('fans.php' , $user_param);?>" id="liv_info_followers_count"><?php echo $user_info['followers_count']; ?></a></dt>
				<dd><a href="<?php echo hg_build_link('fans.php' , $user_param);?>"><?php echo $this->lang['followers']; ?></a></dd>
			</dl>
			<dl>
				<dt><a href="<?php echo hg_build_link('user.php' , $user_param);?>"><?php echo $user_info['status_count']; ?></a></dt>
				<dd><a href="<?php echo hg_build_link('user.php' , $user_param);?>"><?php echo $this->lang['name']; ?></a></dd>
			</dl>
		</div>
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
		<?php if (is_array($topic))
		{?>
		<h3>热门话题</h3>
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
		<div class="clear"></div>
		<?php 		
		if($topic_follow)
		{?>
		<!-- follow topic -->
		<h3><?php echo $this->lang['topic_follow'];?><strong>(<span id="liv_topic_follow_num"><?php echo count($topic_follow);?></span>)</strong></h3>
		<ul class="topic clear">
		<?php

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
		
		?>
		<!-- add follow topic -->
		<li id="addtopicfollow" class="topic-add"><a href="javascript:void(0);" onclick="add_Topic_Follow()"><?php echo $this->lang['insert'];?></a></li>
		<!-- end add follow topic -->
		</ul>
		<dl id="topicbox" class="topicbox">
		<dt><a id="TopicBoxClose" href="javascript:void(0);" onclick="topicBoxClose()">x</a></dt>
		<dd class="topic_dd_title">
		<input type="text" name="topic" id="topic" style="font-size:12px;width:118px;height:20px;"/>
		<input type="button" style="font-size:12px;width:50px;height:25px;" value=" <?php echo $this->lang['save']?> " onclick="addTopic()"/>
		</dd>
		<dd class="topic_dd_about" id="topic_dd_about"><?php echo $this->lang['topic_about'];?></dd>
		</dl>
		<?php }?>