<?php 
/* $Id: station_play.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
{template:head}
<div class="station">
  <div class="gtop">
	<div class="lc" onmouseover="report_show({$station['id']},{$station['user']['id']});" onmouseout="report_hide({$station['id']},{$station['user']['id']});">
  		<div style="display:none;" id="cons_{$station['id']}_{$station['user']['id']}">创建的频道《{$station['web_station_name']}》</div>
		<div style="display:none;" id="ava_{$station['id']}_{$station['user']['id']}">{$station['user']['middle_avatar']}</div>
		<div style="display:none;" id="user_{$station['id']}_{$station['user']['id']}">{$station['user']['username']}</div>
		<div style="display:none;" id="url_{$station['id']}_{$station['user']['id']}"><?php echo SNS_VIDEO.'station_play.php?sta_id='.$station['id'];?></div>
		<div style="display:none;" id="type_{$station['id']}_{$station['user']['id']}">9</div>	
			
  	<span class="subleft"><a href="{$_nav['main']['index']}">{$_nav['lang']['index']}</a></span><span class="subcolor">&gt;</span>
  	<span class="subleft"><a href="{$_nav['main']['channel']}">{$_nav['lang']['channel']}</a></span><span class="subcolor">&gt;</span>
	{if $vip_url}
		<span class="subleft"><a href="{$vip_url}">{$username}</a></span><span class="subcolor">&gt;</span>
	{else}
		<span class="subleft"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php", array('user_id'=>$user_id."#video"));?>">{$username}</a></span><span class="subcolor">&gt;</span>
	{/if} 	
  	<span class="subsub">{$station['web_station_name']}</span>
  	<span style="display:none;" id="vedio_name_container">{$video_name}</span>
  	{if is_array($station)}
	{if $_user['id']}
	<span onclick="report_play({$station['id']},{$station['user']['id']});" style="color: #0163C9; cursor: pointer; display: none; float: right; font-size: 12px; font-weight: normal;" id="re_{$station['id']}_{$station['user']['id']}">{$_lang['report']}</span>{/if}{/if}
  	</div>
  	</div><input type="hidden" id="video_briefs" value="{$video_brief}"/>
  <div class="gtv" id="gtv">
   <div class="gtg">
    {if !is_array($station)}
		{code}
			$null_title = "sorry!!!";
			$null_text = "暂未创建频道";
			$null_type = 1;
			$null_url = $_SERVER['HTTP_REFERER'];
		{/code}
		{template:unit/null}
   {else}
   	<div class="gt_logo"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$station['user_id']));?>" class="acor"><img src="{$station['small']}"/></a><div><a id="station_name" href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$station['user_id']));?>" class="acor"><?php echo hg_cutchars($station['web_station_name'],8," ");?></a></div></div>
    <div class="gt_cont">
    	<ul>
        	<li>创建人：<a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array("user_id"=>$station['user_id']));?>" class="acor">{$station['user']['username']}</a></li>
            <li>创建时间：<?php echo date("Y-m-d",$station['create_time']);?></li>
            <li>标签：<a title="<?php echo $station['tags']?$station['tags']:'暂无';?>"><?php echo hg_cutchars($station['tags']?$station['tags']:'暂无',24," ");?></a></li>
            <li>频道简介：<a title="<?php echo $station['brief']?$station['brief']:'暂无';?>"><?php echo hg_cutchars($station['brief']?$station['brief']:'暂无',20," ");?></a></li>
            <li>关注次数：<span id="co_{$station['id']}">{$station['collect_count']}</span></li>
        </ul>
    </div>
		{if !$is_my_page}
		<div class="gt_boot" id="con_{$station['id']}">
			{if !$station['relation']}
				<a class="gz_get" href="javascript:void(0);" onclick="add_collect({$station['id']},1,{$user_id});">关注该频道</a>
			{else}
				<a class="gz_get" href="javascript:void(0);" onclick="del_concern({$station['concern_id']},{$station['id']},{$user_id},1);">取消关注</a>
			{/if}
    	</div>
		{/if}
      {/if}
   </div>
    <div class="tv">
      <div class="tv_video" id="play_next">
		<div id="tvie_flash_players"></div>
		</div>
    </div>
	<div style="float:right">
    <div class="top_bt" onclick="change_list(1);" ></div>
    <div class="tvt" id="tvt">
    	<ul class="cent_bt" id="cent_bt">
			{if !is_array($program)}
				<li>暂未创建节目单<span class="stxt"></span></li>
			{else}
				{foreach $program as $key=>$value}
					{code}
					$start_time = $value['end_time'];
					{/code}
					{if $value['video']}
						<li title="{$value['programe_name']}" id="line_{$value['id']}"{$play} onclick="play_new({$value['id']});"><?php echo hg_cutchars($value['programe_name'],10," ");?><span class="stxt"><?php echo hg_toff_time($value['start_time'], $value['end_time'],0);?></span></li>
					{/if}
				{/foreach}
			{/if}
        </ul>
    </div>
    <div class="bot_bt" onclick="change_list(2);"></div>
	</div>
     <div class="tv_vidtt">
        <div id="tv1" class="tv1">
            	<div><img src="<?php echo RESOURCE_DIR;?>img/adico1.gif"  /></div>
                <div id="status_share"><a href="javascript:void(0);" onclick="show_status_share({$video_id});">分享到点滴</a></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico2.gif" /></div>
                <div id="video_collect">
					{if $relation}
						<a href="javascript:void(0);">已收藏</a>
					{else}
						<a href="javascript:void(0);" onclick="add_collect({$video_id},0,{$_user['id']});">收藏</a>
					{/if}
                </div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico3.gif"  /></div>
                <div id="program_bt"><a href="javascript:void(0);" onclick="add_program_out({$video_id},{$n_sta_id},{$toff})">选入编单</a></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico4.gif"  /></div>
                <div id="la_2"><a href="javascript:void(0);" onclick="lamps(2,0);">关灯</a></div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico5.gif"  /></div>
                <div>
					{if $_user['id']}
						<a href="javascript:void(0);" onclick="get_comment_plot(1)">评论</a>
					{else}
						<a href="javascript:void(0);" onclick="get_comment_plot(0)">评论</a>
					{/if}
				</div>
                <div><img src="<?php echo RESOURCE_DIR;?>img/adico6.gif"  /></div>
                <div><a href="javascript:void(0);" onclick="show_share(1,'{$url}')">分享</a></div>
      </div>
     </div>
  </div>
</div>
	<div id="share_container" class="panel panelShare" style="display: none;width:548px;"></div>
	<div id="share_status" style="display: none;width:548px;"></div>
	<div id="program_list" style="display: none;width:548px;"></div>
<div class="station">
	<div class="gleft">
    	<div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
        <div class="gsub2">网友评论<span class="txt">(全部<span id="count_num" class="tcolor"><?php echo $total_nums?$total_nums:0;?></span>条)</span></div>
        <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
      <div class="gsub4">
       	<div class="g_tare">
			<form action="<?php echo hg_build_link(SNS_UCENTER.'login.php');?>" method="post">
				{if !$_user['id']}
					<input type="hidden" value="dologin" name="a" />
					<input type="hidden" value="{$_INPUT['referto']}" name="referto" />
					<span class="g_tops">网友昵称:<input class="fm1" type="text" id="username" name="username"/>密码:<input class="fm1" type="password" id="password" name="password" /><input id="login_bt" class="fm_sub" type="submit" value="{$_lang['login']}" name="submit"/></span>
				{else}
					<span class="g_tops">网友昵称:<a href="<?php echo hg_build_link(SNS_UCENTER.'user.php');?>">{$_user['username']}</a></span>
				{/if}
          </form>
			{template:unit/comment}
          </div>
        </div>
    </div>
    <div class="gright">
	{if count($visit)>1}
	{code}
		$total = $station['click_count'];
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
					<li><a title="{$value['user']['username']}" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="{$value['user']['middle_avatar']}" width="50" height="50" /></a><a title="{$value['user']['username']}" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><?php echo hg_cutchars($value['user']['username'],4," ");?></a></li>
				{else}
					<li class="mars"><a title="{$value['user']['username']}" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><img src="{$value['user']['middle_avatar']}" width="50" height="50" /></a><a title="{$value['user']['username']}" href="<?php echo hg_build_link(SNS_UCENTER."user.php", array("user_id"=>$value['user']['id']));?>"><?php echo hg_cutchars($value['user']['username'],4," ");?></a></li>
				{/if}
	        	{code}
					$i++;
				{/code}
				{/foreach}
	            </ul>
	        </div>
		{/if}
        <div class="gsub1"><img src="<?php echo RESOURCE_DIR;?>img/hcar_1.gif" width="19" height="28" /></div>
        <div class="gsub2">热门标签</div>
        <div class="gsub3"><img src="<?php echo RESOURCE_DIR;?>img/hcar_4.gif" width="7" height="28" /></div>
        <div class="gsub4_2">
        	{if !HOT_TOPIC_URL}
				{code}
					$null_title = "sorry!!!";
					$null_text = "暂未标注地点！";
					$null_type = 1;
					$null_url = $_SERVER['HTTP_REFERER'];
				{/code}
				{template:unit/null}
			{else}
				<script src="<?php echo HOT_TOPIC_URL;?>" type="text/javascript"></script> 
			{/if}
        </div>
    </div>
</div>
{template:unit/status_pub}
{template:foot}