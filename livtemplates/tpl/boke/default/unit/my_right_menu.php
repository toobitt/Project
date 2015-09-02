<?php 
/* $Id: my_right_menu.php 10150 2012-07-25 02:25:39Z repheal $ */
?>
<div class="con-right">
	{if $_user['id']}
		<div class="pad-all">
			<div class="bk-top-liao"></div>
			<div class="wb-blocks">
				<div class="users">
					<a class="user-logo" href="<?php echo hg_build_link(SNS_UCENTER.'user.php'); ?>"><img src="{$user_info['larger_avatar']}" title="{$user_info['username']}" /></a>
					<div class="user-set">
						<a class="user-set-a" href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>">{$user_info['username']}</a>
						<div class="line-list"></div>
						<div class="user-name">
							<ul>
								<li><span class="info-pre">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</span><span class="info-fix"><?php echo hg_show_sex($user_info['sex']);?></span></li>
								<li><span class="info-pre">所在地盘：</span><a class="info-fix" href="<?php echo hg_build_link(SNS_UCENTER . 'geoinfo.php');?>">{$user_info['group_name']}</a></li>
								
							{code}
							/*,'email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机'*/
							$relation = array('birthday'=>'生&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日');
							{/code}
							{foreach $relation as $key =>$value}
								{code}
								$temp = $user_info[$key];
								{/code}
								{if $temp}
									{if strcmp($key,"birthday")==0 && is_numeric($temp)}
										<li><span class="info-pre">{$value}：</span><span class="info-fix">{$_lang['xingzuo'][$temp]}</span></li>
									{else}
										<li><span class="info-pre">{$value}：</span><span class="info-fix">{$temp}</span></li>
									{/if}
								{/if}
							{/foreach}	
							</ul>					
						</div>
					</div> 
				</div>
				<div class="business">
				
				<span class="u-show1">
					<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php');{/code}">{$user_info['status_count']}</a>
	    		</span>
    			<span class="u-show2">
    				<a href="{code} echo hg_build_link(SNS_UCENTER . 'follow.php');{/code}">{$user_info['attention_count']}</a>
	    		</span>
    			<span class="u-show3">
	    			<a href="{code} echo hg_build_link(SNS_UCENTER . 'fans.php');{/code}">{$user_info['followers_count']}</a>
	    		</span>
    			<span class="u-show4">
	    			<a href="{code} echo hg_build_link(SNS_VIDEO.'my_video.php');{/code}">{$user_info['video_count']}</a>
	    		</span>
				</div>
			</div>
    	</div>
		<div class="pad-all">
		{code} echo hg_advert('left_1');{/code}
		</div>
    	<div class="pad-all">
			<div class="bk-top1">视频导航</div>
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
				<ul class="hot-video">
				{foreach $album_info as $key => $value}
				<li>
					<ul class="hot-video-list">
						<li><a title="{$value['name']}" href="<?php echo hg_build_link("user_album_video.php", array('id' => $value['id'],'user_id' => $value['user_id']));?>"><img src="{$value['cover']}"/></a></li>
						<li><a title="{$value['name']}" href="<?php echo hg_build_link("user_album_video.php", array('id' => $value['id'],'user_id' => $value['user_id']));?>"><?php echo hg_cutchars($value['name'],8," ");?></a></li>
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
							<li><a title="{$value['title']}" href="<?php echo hg_build_link("video_play.php", array('id' => $value['id']));?>"><?php echo hg_cutchars($value['title'],7," ");?></a></li>
							<li>{$_lang['plays']}{$value['play_count']}</li>
							<li>{$_lang['comments']}{$value['comment_count']}</li>
						</ul>
					</li>
				{/foreach}
					</ul>
				<div class="clear"></div>
			</div>
		</div>
		{/if}
				
	{if is_array($hot_station)}
    	<div class="pad-all">
			<div class="bk-top1">视频达人</div>
			<div class="wb-block1">
				<ul class="hot-station clear">
			{foreach $hot_station as $key => $value}
			<li>
				<a class="images" title="{$value['web_station_name']}" href="<?php echo hg_build_link(SNS_VIDEO."user.php",array('user_id'=>$value['user_id']));?>"><img src="{$value['small']}"/></a>
				<a class="titles" title="{$value['web_station_name']}" href="<?php echo hg_build_link(SNS_VIDEO."user.php",array('user_id'=>$value['user_id']));?>"><?php echo hg_cutchars($value['web_station_name'],5," ");?></a>
			</li>
			{/foreach}
				</ul>
				<div class="clear1"></div>
			</div>
		</div>
		</div>
	{/if}