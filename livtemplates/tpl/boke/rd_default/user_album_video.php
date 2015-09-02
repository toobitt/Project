<?php
/* $Id: user_album_video.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
{template:head}
<div class="main_div">
	<div class="con-left">
		<div class="station_content" style="margin:0px 10px 10px 9px;">
		<div class="album">
			<div class="con_top">
				专辑名：<a title="{$album_video['name']}" class="get"><?php echo hg_cutchars($album_video['name'],6," ");?></a>
				视频数：<span class="get">{$album_video['video_count']}</span>
			</div>
			{if $album_video['video']}
				<ul class="album_ul con_middle">
				{foreach $album_video['video'] as $key=>$value}
					<li class="album_li">
						<a target="_blank" title="{$value['title']}" href="<?php echo hg_build_link('video_play.php', array('id'=>$value['id']));?>"><img src="{$value['schematic']}"/></a>
						<div class="album_na">
							<a target="_blank" title="{$value['title']}" href="<?php echo hg_build_link('video_play.php', array('id'=>$value['id']));?>">
							<?php echo hg_cutchars($value['title'],7," ");?>({$value['play_count']})
							</a>
						</div>
					</li>
				{/foreach}
				<li>{$showpages}</li>
				</ul>
			{else}
			<div class="album_ul con_middle">
			{code}
				$null_title = "";
				$null_text = "该用户的专辑视频不存在!";
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}
			</div>
			{/if}
		<div class="con_bottom clear"></div>
		</div>	
	</div>
	</div>
	{template:unit/my_right_menu}
</div>
{template:foot}




