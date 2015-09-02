<?php 
/* $Id: my_right_menu.php 90 2011-06-21 08:26:06Z repheal $ */
?>
<div class="con-right">
	{if $_user['id']}
		<div class="pad-all">
			<div class="bk-top1">我的资料</div>
			<div class="wb-block1">
				<div class="users">
					<div class="user-set">
						<h5><span><a href="<?php echo hg_build_link(SNS_UCENTER."login.php", array("a"=>"logout"))?>">退出</a></span><a href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>">{$user_info['username']}</a></h5>	
						<div class="user-name">
							<div style="font-size:12px;color:gray;">创建专辑:<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php#album');?>">{$album_total}</a></div>
							<div style="font-size:12px;color:gray;">上传视频:<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php#video');?>">{$user_info['video_count']}</a></div>
						<div style="font-size:12px;color:gray;"></div>		
					</div>
					</div> 
					<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php'); ?>"><img src="{$user_info['middle_avatar']}" title="{$user_info['username']}" /></a>
				</div>				
			</div>
    	</div>
    	<div class="pad-all">
			<div class="bk-top1">我的导航</div>
			<div class="wb-block1">
		    	<div class="menu">
						{foreach $_settings['nav'] as $k => $v}
							{code}
							if($gScriptName == "my_station")
		        			{
		        				$gScriptName = "index";
		        			}
							{/code}
							{if $k == $gScriptName}
								<a class="{$v['class']}_click" href="<?php echo hg_build_link($v['filename']);?>"></a>
							{else}
								<a class="{$v['class']}" href="<?php echo hg_build_link($v['filename']);?>"></a>
							{/if}
						{/foreach}
				</div>  
			</div>
		</div>
    	
    	{if is_array($album_info)}
    	<div class="pad-all">
			<div class="bk-top1">我的专辑</div>
			<div class="wb-block1">
				<ul class="hot-video my_album_list">
				{foreach $album_info as $key => $value}
				<li>
					<ul class="my_album_list_info">
						<li><a title="{$value['name']}" href="<?php echo hg_build_link("user_album_video.php", array('id' => $value['id'],'user_id' => $value['user_id']));?>"><img width="72" height="54" src="{$value['cover']}"/></a></li>
						<li class="my_album_title"><a title="{$value['name']}" href="<?php echo hg_build_link("user_album_video.php", array('id' => $value['id'],'user_id' => $value['user_id']));?>"><?php echo hg_cutchars($value['name'],8," ");?></a></li>
						<li>{$_lang['video_num']}{$value['video_count']}</li>
						<li>{$_lang['plays']}{$value['play_count']}</li>
					</ul>
				</li>
				{/foreach}
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		{/if}
		{/if}

	{if is_array($hot_video)}
	    	<div class="pad-all">
			<div class="bk-top1">热播视频</div>
			<div class="wb-block1">
				<ul class="hot-video">
				{code}
					unset($hot_video[count($hot_video)-1]);
				{/code}
				{foreach $hot_video as $key => $value}
					<li>
						<ul class="hot-video-list">
							<li><a title="{$value['title']}" href="<?php echo hg_build_link("video_play.php", array('id' => $value['id']));?>"><img src="{$value['schematic']}"/></a></li>
							<li class="hot-video-title"><a title="{$value['title']}" href="<?php echo hg_build_link("video_play.php", array('id' => $value['id']));?>"><?php echo hg_cutchars($value['title'],7," ");?></a></li>
							<li>{$_lang['plays']}<strong>{$value['play_count']}</strong></li>
							<li>{$_lang['comments']}<strong>{$value['comment_count']}</strong></li>
						</ul>
					</li>
				{/foreach}
					</ul>
				<div class="clear"></div>
			</div>
		</div>
		{/if}

</div>
