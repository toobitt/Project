<?php 
/* $Id: video_list.php 8320 2012-03-16 08:08:52Z repheal $ */
?>


	{if !is_array($video)||!$video}
	{code}
		$null_title = "";
		$null_text = "暂未上传视频";
		$null_type = 1;
		$null_url = $_SERVER['HTTP_REFERER'];
	{/code}
	{template:unit/null}
	{else}
		<ul class="video">
		
	    {code} 
			$i=0;
		{/code}
		{foreach $video as $key => $value}
		
			{if $i%5}
			
				<li class="cus_pad"><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><img title="{$value['title']}" src="{$value['schematic']}" width="122" height="91" /></a><a title="{$value['title']}" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'] , 10 , ' ');?></a><span class="txt">播放：{$value['play_count']}</span><span class="txt">评论：{$value['comment_count']}</span></li>
			{else}
				<li><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><img title="{$value['title']}" src="{$value['schematic']}" width="122" height="91" /></a><a title="{$value['title']}" target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'] , 10 , ' ');?></a><span class="txt">播放：{$value['play_count']}</span><span class="txt">评论：{$value['comment_count']}</span></li>
			{/if}
			{code}
				$i++;
			{/code}
		
		{/foreach}
		</ul>
		<div class="clear"></div>
	{/if}
	{$showpages}
	
	<div class="clear"></div>
