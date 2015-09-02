<?php
/* $Id: my_favorites.php 452 2011-08-05 05:59:26Z repheal $ */
?>
{template:head}
<div class="main_div">
	<div class="right_tips right_window">
	<h3><a href="<?php echo hg_build_link("upload.php")?>">+上传视频</a>我的收藏</h3>
		<div class="show_info">
		<div class="clear">
		{code}
		switch ($type)
				{
					case 0:
		{/code}
			{if $stationInfo}
							{foreach $stationInfo as $k => $v}
							<ul id="collect_{$v['collect_id']}" style="float:left;padding:10px 15px;line-height:23px;height:230px;">
								<li><span ><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?> "><img style="display:inline-block;border:1px solid silver; padding:2px;" src="{$v['schematic']}" title="{$v['title']}" /></a></span></li>
								<li><a href="<?php echo hg_build_link('video_play.php' , array('id' => $v['id'])) ; ?>" title="{$v['title']}">{$v['title']}</a></li>
								<li><span>上传时间:</span><span><?php echo hg_get_date($v['create_time']); ?></span></li>
								<li><span>播放:</span><span>{$v['play_count']}</span></li>
								<li><span>评论:</span><span>{$v['comment_count']}</span></li>
								<li>
			                     <a href="javascript:void(0);" onclick="del_collect({$v['collect_id']},{$v['id']},{$type});"><img src="./res/img/sq_button.jpg" width="58" height="18" /></a>
			                   	</li>
							</ul>
							{/foreach}
						{else}
						<ul style="float:left;padding:10px;">
								<li>暂无收藏</li>
						</ul>
						{/if}
						{code}
						break;
					case 1:
						{/code}
					{if $stationInfo}
							{foreach $stationInfo as $k => $v}
							<ul id="collect_{$v['collect_id']}" style="float:left;padding:10px;">
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><img src="{$v['small']}" width="138" height="103" /></a></li>
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>">{$v['web_station_name']}</a></li>
					        	<li><a href="<?php echo hg_build_link('user_station.php', array('user_id'=>$v['id']));?>"><?php echo $v['brief']?$v['brief']:'暂无介绍';?></a></li>
					        	<li>
			                     <a href="javascript:void(0);" onclick="del_collect({$v['collect_id']},{$v['id']},{$type});"><img src="./res/img/sq_button.jpg" width="58" height="18" /></a>
			                   	</li>
							</ul>
							{/foreach}
						{else}
						<ul style="float:left;padding:10px;">
								<li>暂无收藏</li>
						</ul>
					{/if}
						{code}
						break;
					case 2:
						{/code}
						{if $stationInfo}
							{foreach $stationInfo as $k => $v}
							<ul id="collect_{$v['collect_id']}" style="float:left;padding:10px;">
								<li>
								    <a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['id']));?>">
				                		<img src="{$v['middle_avatar']}" width="53" height="56" class="header_photo"/>
				                	</a>
			                	</li>
			                    <li><a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['id']));?>">{$v['username']}</a></li>
			                    <li>人气：<span id="c_{$v['id']}">{$v['collect_count']}</span></li>
			                    <li>
			                     <a href="javascript:void(0);" onclick="del_collect({$v['collect_id']},{$v['id']},{$type});"><img src="./res/img/gq_button.jpg" width="58" height="18" /></a>
			                   	</li>
							</ul>
							{/foreach}
						{else}
						<ul style="float:left;padding:10px;">
								<li>暂无收藏</li>
						</ul>
					{/if}
						{code}
						break;
					default:
						break;
					}
						{/code}
		</div>
		{$showpages}
		</div>
	</div>
	{template:unit/my_right_menu}
</div>

{template:foot}