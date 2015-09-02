		
		<div class="business">
			<dl>
				<dt><a href="<?php echo hg_build_link('follow.php' , $user_param);?>" id="liv_info_attention_count">{$user_info['attention_count']}</a></dt>
				<dd><a href="<?php echo hg_build_link('follow.php' , $user_param);?>">{$_lang['friends']}</a></dd>
			</dl>
			<dl class="border">
				<dt><a href="<?php echo hg_build_link('fans.php' , $user_param);?>" id="liv_info_followers_count">{$user_info['followers_count']}</a></dt>
				<dd><a href="<?php echo hg_build_link('fans.php' , $user_param);?>">{$_lang['followers']}</a></dd>
			</dl>
			<dl>
				<dt><a href="<?php echo hg_build_link('user.php' , $user_param);?>">{$user_info['status_count']}</a></dt>
				<dd><a href="<?php echo hg_build_link('user.php' , $user_param);?>">{$_lang['name']}</a></dd>
			</dl>
		</div>
		<div class="menu">
		{foreach $_settings['nav'] as $k => $v}
			{if $k == $gScriptName}
			<a class="{$v['class']}_click" href="<?php echo hg_build_link($v['filename']);?>"><span>{$v['name']}</span></a>
			{else}
			<a class="{$v['class']}" href="<?php echo hg_build_link($v['filename']);?>"><span>{$v['name']}</span></a>
			{/if}
		{/foreach}
		</div>  
		{if is_array($topic)}
		
		<h3>热门话题</h3>
		<ul class="topic clear">
			{foreach $topic as $value}
			<li>
				<a href="<?php echo hg_build_link('k.php' , array('q' => $value['title'])); ?>">
				{$value['title']}</a><span>({$value['relate_count']})</span>
			</li>
			{/foreach}
		</ul>
		{/if}
		<div class="clear"></div>
			
		{if $topic_follow}
		<h3>{$_lang['topic_follow']}<strong>(<span id="liv_topic_follow_num">{code} echo count($topic_follow);{/code}</span>)</strong></h3>
		<ul class="topic clear">

		{foreach $topic_follow as $key=>$value}
		
		<li class="topic_li" onmouseover="this.className='topic_li_hover'" onmouseout="this.className='topic_li'">
		{code}
			$title = '<a href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.$value['title'] . '</a>';
		{/code}
		{$title}
		
		<a class="close" href="javascript:void(0);" onclick="del_Topic_Follow('{$value['topic_id']}',this)"></a>
		<div class="hidden" id="topic_{$value['topic_id']}">{$value['title']}</div>
		</li>
		{/foreach}
		<!-- add follow topic -->
		<li id="addtopicfollow" class="topic-add"><a href="javascript:void(0);" onclick="add_Topic_Follow()"><?php echo $this->lang['insert'];?></a></li>
		<!-- end add follow topic -->
		</ul>
		<dl id="topicbox" class="topicbox">
		<dt><a id="TopicBoxClose" href="javascript:void(0);" onclick="topicBoxClose()">x</a></dt>
		<dd class="topic_dd_title">
		<input type="text" name="topic" id="topic" style="font-size:12px;width:118px;height:20px;"/>
		<input type="button" style="font-size:12px;width:50px;height:25px;" value=" {$_lang['save']} " onclick="addTopic()"/>
		</dd>
		<dd class="topic_dd_about" id="topic_dd_about">{$_lang['topic_about']}</dd>
		</dl>
		{/if}