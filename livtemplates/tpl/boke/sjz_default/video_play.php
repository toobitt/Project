<?php 
/* $Id: video_play.php 87 2011-06-21 07:10:24Z repheal $ */
?>
{template:head}
<div class="gareas">
  <div class="gtop" onmouseover="report_show({$single_video_info['id']},{$single_video_info['user']['id']});" onmouseout="report_hide({$single_video_info['id']},{$single_video_info['user']['id']});">
  			<div style="display:none;" id="cons_{$single_video_info['id']}_{$single_video_info['user']['id']}">上传的视频《{$single_video_info['title']}》</div>
			<div style="display:none;" id="ava_{$single_video_info['id']}_{$single_video_info['user']['id']}">{$single_video_info['user']['middle_avatar']}</div>
			<div style="display:none;" id="user_{$single_video_info['id']}_{$single_video_info['user']['id']}">{$single_video_info['user']['username']}</div>
			<div style="display:none;" id="url_{$single_video_info['id']}_{$single_video_info['user']['id']}"><?php echo SNS_VIDEO.'video_play.php?id='.$single_video_info['id'];?></div>
			<div style="display:none;" id="type_{$single_video_info['id']}_{$single_video_info['user']['id']}">2</div>	
  	<div class="lc">
  	<span class="subleft"><a href="{$_nav['main']['index']}">{$_nav['lang']['index']}</a></span><span class="subcolor">&gt;</span>
  	<span class="subleft"><a href="{$_nav['main']['channel']}">{$_nav['lang']['channel']}</a></span><span class="subcolor">&gt;</span>
	{if $vip_url}
		<span class="subleft"><a href="{$vip_url}">{$username}</a></span><span class="subcolor">&gt;</span>
	{else}
		<span class="subleft"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$user_id."#video"));?>">{$username}</a></span><span class="subcolor">&gt;</span>
	{/if}
  	<span class="subsub" id="vedio_name_container">{$single_video_info['title']}</span></div>
  	<input type="hidden" id="video_briefs" value="{$single_video_info['brief']}"/>
    <div class="rc">
  {if $_user['id']&&$_settings['report']}<span class="rsubcolor2" onclick="report_play({$single_video_info['id']},{$single_video_info['user']['id']});" style="display:none;cursor:pointer;" id="re_{$single_video_info['id']}_{$single_video_info['user']['id']}">{$_lang['report']}</span>{/if}  
    播放<span class="rsubcolor"><?php echo $single_video_info['play_count']?$single_video_info['play_count']:1;?></span>次<span class="subline">|</span>评论<span class="rsubcolor2"><?php echo $total_nums?$total_nums:0;?></span>次</div>
  </div>
  
  {if $single_video_info}
  <div class="gtv">
	<div class="gtg"><!--<iframe src="http://www.sjzntv.cn/ad/bokeadl/index.html" border="0" scrolling="no" width="205" height="355"></iframe>--></div>
    <div class="tv">
      <div class="tv_video"><div id="player"></div></div>
    </div>
    <div class="tvt"><!--<iframe src="http://www.sjzntv.cn/ad/bokeadl/index.html" border="0" scrolling="no" width="205" height="355"></iframe>--></div>
    <div class="tv_vidtt">
        <div class="tv1">              
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico2.gif" /></div>
                <div id="video_collect">
			        {if $single_video_info['relation']}
						<a href="javascript:void(0);">已收藏</a>
					{else}
						<a href="javascript:void(0);" onclick="add_collect({$single_video_info['id']},0,{$_user['id']});">收藏</a>
					{/if}
                </div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico5.gif"  /></div>
                <div>
				{if $_user['id']}
					<a href="javascript:void(0);" onclick="get_comment_plot(1)">评论</a>
				{else}
					<a href="javascript:void(0);" onclick="get_comment_plot(0)">评论</a>
				{/if}
				</div>
                
		<div id="share_container" class="panel panelShare" style="display: none;width:548px;"></div>
     	<div id="share_status" style="display: none;width:548px;"></div>
     	<div id="program_list" style="display: none;width:548px;"></div>            
      </div>
      </div>
  </div>
  {else}
	  {code}
		$null_text = "该视频不存在或已被删除";
		$null_url = $_SERVER['HTTP_REFERER'];
	  {/code}
	  {template:unit/null}
  {/if}
</div>

<div class="garea">
	<div class="gleft">
    <div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
    <div class="gsub2">相关视频</div>
    <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
    <div class="gsub4 mar_cus clear">
	{if !is_array($video)}
		{code}
			$null_text = "暂无相关视频！";
			$null_type = 1;
			$null_url = $_SERVER['HTTP_REFERER'];
		{/code}
		{template:unit/null}
	{else}
		{code}
		 $i = 1;
		{/code}
		{foreach $video as $key => $value}
			{if $i ==1}
				<div class="ottv"><a title="{$value['title']}" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><img src="{$value['schematic']}" width="122" height="91" /></a><a style="height: 14px; overflow: hidden; width: 120px;" title="{$value['title']}" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'],19," ");?></a>
				<p>播放:{$value['play_count']}</p>
				<p>评论:{$value['comment_count']}</p>
				</div>
			{else}
				<div class="ottv mars"><a title="{$value['title']}" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><img src="{$value['schematic']}" width="122" height="91" /></a><a style="height: 14px; overflow: hidden; width: 120px;" title="{$value['title']}" href="<?php echo hg_build_link("video_play.php", array('id'=>$value['id']));?>"><?php echo hg_cutchars($value['title'],19," ");?></a>
				<p>播放:{$value['play_count']}</p>
				<p>评论:{$value['comment_count']}</p>
				</div>
			{/if}
		{code}
		$i++;
		{/code}
		{/foreach}
	{/if}
    </div>
    	<div class="gsub1 clear"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
        <div class="gsub2">网友评论<span class="txt">(全部<span class="tcolor" id="count_num"><?php echo $total_nums?$total_nums:0;?></span>条)</span></div>
        <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
      <div class="gsub4">
       	<div class="g_tare">
       	<form action="<?php echo hg_build_link(SNS_UCENTER.'login.php');?>" method="post">
		{if !$_user['id']}
			<input type="hidden" value="dologin" name="a" />
			<input type="hidden" value="{$_INPUT['referto']}" name="referto" />
			<span class="g_tops">网友昵称:<input class="fm1" type="text" id="username" name="username"/>密码:<input class="fm1" type="password" id="password" name="password" /><input id="login_bt" class="fm_sub" type="submit" value="{$_lang['login']}" name="submit"/></span>
		{else}
			<span class="g_tops">网友昵称:<a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php');?>">{$_user['username']}</a></span>
		{/if}
          </form>
			{template:unit/comment}
          </div>
        </div>
    </div>
    <div class="gright">
	    <div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
		<div class="gsub2">视频信息</div>
		<div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
		<div class="gsub5">
			<div class="imgs"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$single_video_info['user']['id']));?>"><img src="{$single_video_info['user']['middle_avatar']}" width="50" height="50"/></a></div>
			<div class="img_txt">
				<div><span class="lc"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$single_video_info['user']['id']));?>">{$single_video_info['user']['username']}</a></span><span class="rc"><!--<a class="sline" href="#">发站内站</a>-->
		 		{if is_array($station)}
					{if !$station['myself']}
						{if $station['relation']}
							{code}
							echo "已关注";
							{/code}
						{else}
							<span id="collect_{$station['id']}"><a href="javascript:void(0);" onclick="add_collect({$station['id']},1,{$station['user']['id']});">关注TA</a></span>
						{/if}
					{/if}
				{else}
					{code}
					echo "已关注";
					{/code}
				{/if}
				</span></div>
				
				<ul class="img_t">
				<li>发布时间:<?php echo date("Y-m-d H:i:s",$single_video_info['create_time']);?></li>
				<li>标签：<?php echo $single_video_info['tags']?$single_video_info['tags']:'暂无';?></li>
				<li>视频简介：<?php echo $single_video_info['brief']?$single_video_info['brief']:'暂无';?></li>
				<li>视频已加入
				{if is_array($program)}
					{code}
						$j = 1; 
					{/code}
					{foreach $program as $key =>$value}
						{if $j<3}
							<span class="txt"><a href="<?php echo hg_build_link("station_play.php", array('sta_id'=>$value['id']));?>">{$value['web_station_name']}</a></span>
						{/if}
						{code}
			      		$j++;
						{/code}
					{/foreach}
					共<b>{$program_total}</b>个频道
				{/if}
				</li>
				</ul>     
			</div>
		</div>
		{if count($visit)>1}
			{code}
			$total = $visit_total;
			unset($visit['total']);
			{/code}
			<div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
	        <div class="gsub2">他们也看过<span class="txt">(全部<span class="tcolor">{$total}</span>人次)</span></div>
	        <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
	        <div class="gsub4">
	        	<ul>
				{code}
				$i = 1;
				{/code}
				{foreach $visit as $key => $value}
					{if $i==1||$i==6}
						<li><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="{$value['user']['middle_avatar']}" width="50" height="50" /></a><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>">{$value['user']['username']}</a></li>
					{else}
						<li class="mars"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="{$value['user']['middle_avatar']}" width="50" height="50" /></a><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>">{$value['user']['username']}</a></li>
					{/if}
					{code}
						$i++;
					{/code}
				{/foreach}
	            </ul>
	        </div>
		{/if}
    </div>
</div>
{template:unit/status_pub}
{code}
echo hg_advert('play_1');
{/code}
{template:foot}