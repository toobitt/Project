<?php 
/* $Id: space.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
{template:head}
<div class="space vui">
	<div class="right_window con-left" >
		<div class="station_content">
		<h3 class="con_top">视频</h3>
        <div class="g_are4" id="content">
		{if !is_array($video)||!$video}			
			{code}
				$null_title = "sorry!!!";
				$null_text = "暂未上传视频";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}
		{else}
			<ul class="video">
			{foreach $video as $key => $value}
				<li class="cus_pad"><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><img title="{$value['title']}" src="{$value['schematic']}" width="144" height="108" /></a><a title="{$value['title']}" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'] , 10 , ' ');?></a><span class="txt">播放：<strong>{$value['play_count']}</strong></span><span class="txt">评论：<strong>{$value['comment_count']}</strong></span></li>
			{/foreach}
			</ul>
			<div class="clear"></div>
		{/if}
        {$showpages}
        <div class="clear"></div>
        </div>
		<div class="con_bottom clear"></div>
		</div>
    </div>
	{template:unit/my_right_menu}
</div>
{template:foot}