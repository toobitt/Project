<?php 
/* $Id: space.php 8277 2012-03-12 05:44:13Z repheal $ */
?>
{template:head/head1}
<div class="b_space">
	<div class="space clear">
{if is_array($station)}
		<ul class="space_ul">
			<li class="space_li_title">
				<a class="s-logo" title="{$station['web_station_name']}" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php',array('sta_id'=>$station['id']));{/code}"><img src="{$station['small']}" /></a>
				<a class="s-name" title="{$station['web_station_name']}" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php',array('sta_id'=>$station['id']));{/code}">{code} echo hg_cutchars($station['web_station_name'],8," ");{/code}</a> 
			</li>
			<li class="space_li_con clear">
				<span title="{code} echo $station['brief'];{/code}">{code} echo hg_cutchars($station['brief']?$station['brief']:'暂无',72," ");{/code}</span>
			</li>
			<li class="space_li_btn">
			{if $is_my_page}
				<a class="gz_edit" href="{code} echo hg_build_link(SNS_VIDEO.'upload.php');{/code}"></a>
			{else}
				<a class="plays" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php',array('sta_id'=>$station['id']));{/code}"></a>
			{/if}
			</li>
			<li class="space_li_manage" id="collect_user">
	          {if !$is_my_page}
	          		<a class="gz_back1" href="{code} echo hg_build_link(SNS_UCENTER.'user.php',array('user_id'=>$user_info['id']));{/code}"></a>
					{code}
						switch($relation)
						{
							case 1:{/code}
								<a class="gz_del" href="javascript:void(0);" onclick="delFriend({$id});"></a>{code}
								break;
							case 2:{/code}
								<a class="gz_del" href="javascript:void(0);" onclick="delFriend({$id});"></a>{code}
								break;
							case 3:{/code}
								<a class="gz_get" href="javascript:void(0);" onclick="addFriends({$id} , {$relation});"></a>{code}
								break;
							case 4:{/code}
								<a class="gz_get" href="javascript:void(0);" onclick="addFriends({$id} , {$relation});"></a>{code}
								break;
							default :
								break;
						}
					{/code}	
			  {else}
					<a class="gz_back2" href="{code} echo hg_build_link(SNS_UCENTER.'user.php',array('user_id'=>$user_info['id']));{/code}"></a>
			  {/if}
			</li>
		</ul>
{/if}
	{if ALLOW_PROGRAME}
	  {if is_array($program)}
		<div class="pro_win"><a id="pic_href" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php', array('sta_id'=>$program[0]['sta_id'])).'#1';{/code}" target="_blank"><img id="pic_ct" src="{code} echo $program[0]['video']['images']?$program[0]['video']['larger']:$program[0]['video']['bschematic'];{/code}" width="493" height="260" /></a></div>
	    <div class="pro_list">
	      <div class="pro_sub"><span class="cs"></span><span class="title">节目单：</span>{if $is_my_page}<a style="float: right;color: #C61212;" href="{code} echo hg_build_link(SNS_VIDEO.'my_program.php');{/code}">编辑</a>{/if}</div>
	      <div class="pro_cot clear">
	          <ul>
			  {code}
	          	$i = 1;
			  {/code}
			  {foreach $program as $key=>$value}
					<li onmousemove="chang_img({$value['id']},{$value['sta_id']},{$value['user_id']})"><div class="list_left"><a id="bs_{$value['id']}" style="display:none;">{code} echo $value['video']['images']?$value['video']['larger']:$value['video']['bschematic'];{/code}</a><a title="{$value['programe_name']}" id="vs_{$value['id']}" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php', array('sta_id'=>$value['sta_id'])).'#'. $value['id'];{/code}">{code} echo hg_cutchars($value['programe_name'],10," ");{/code}</a></div><div class="list_right">{code} echo hg_toff_time($value['start_time'],$value['end_time'],0);{/code}</div></li>
				{code}
	          	$i++;
				{/code}
			  {/foreach}
	          </ul>
	      </div>
	   </div>
	  {/if}
	{/if}
	</div>
</div>

<!-- 
<div class="space">
  <div class="pic_top1"></div>
  <div class="pic_cet1">
    <div class="pic_c">
{if !is_array($station)}
	<div class="no_station" id="collect_user">
	{if $is_my_page}
		<a href="http://v.hoolo.tv/my_station.php">创建频道</a>
	{else}
		{code}
			switch($relation)
			{
				case 1:{/code}
					<a class="gz_del" href="javascript:void(0);" onclick="delFriend({$id});"></a>{code}
					break;
				case 2:{/code}
					<a class="gz_del" href="javascript:void(0);" onclick="delFriend({$id});"></a>{code}
					break;
				case 3:{/code}
					<a class="gz_get" href="javascript:void(0);" onclick="addFriends({$id} , {$relation});"></a>{code}
					break;
				case 4:{/code}
					<a class="gz_get" href="javascript:void(0);" onclick="addFriends({$id} , {$relation});"></a>{code}
					break;
				default :
					break;
			}
		{/code}	
		<a class="gz_back" href="{code} echo hg_build_link(SNS_UCENTER.'user.php',array('user_id'=>$user_info['id']));{/code}"></a>
	{/if}
	</div>
{/if}
{if is_array($station)}
      <div class="pic_ct1">
        <ul>
          <li class="pic_logo"><a title="{$station['web_station_name']}" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php',array('sta_id'=>$station['id']));{/code}"><img src="{$station['small']}" /></a></li>
		  {if $is_my_page}<li class="lkts">{else}<li class="lktt">{/if}<a class="s-name" title="{$station['web_station_name']}" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php',array('sta_id'=>$station['id']));{/code}">{code} echo hg_cutchars($station['web_station_name'],8," ");{/code}</a> <a title="{code} echo $station['brief']?$station['brief']:'暂无';{/code}">{code} echo hg_cutchars($station['brief']?$station['brief']:'暂无',25," ");{/code}</a></li>
          <li class="lkbt" id="collect_{$station['id']}">
          	<a class="gz_back" href="{code} echo hg_build_link(SNS_UCENTER.'user.php',array('user_id'=>$user_info['id']));{/code}"></a>
		  {if ALLOW_PROGRAME}
			   {if !$is_my_page}
	          	<a class="plays" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php',array('sta_id'=>$station['id']));{/code}"></a>
	          {else}
	          	<a class="gz_edit" href="{code} echo hg_build_link(SNS_VIDEO.'my_program.php');{/code}"></a>
	          {/if}
		  {/if}
          {if !$is_my_page}
			{if !$station['relation']}
				<a class="gz_get" href="javascript:void(0);" onclick="add_concern({$station['id']},1,{$id});"></a>
			{else}
				<a class="gz_del" href="javascript:void(0);" onclick="del_concern({$station['concern_id']},{$station['id']},{$id},1);"></a>
			{/if}
		  {/if}
          </li>
        </ul>
        <div class="clear"></div>
      </div>
{/if}
{if ALLOW_PROGRAME}
  {if is_array($program)}
	<div class="pic_ct2"><a id="pic_href" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php', array('sta_id'=>$program[0]['sta_id'])).'#1';{/code}" target="_blank"><img id="pic_ct" src="{code} echo $program[0]['video']['images']?$program[0]['video']['larger']:$program[0]['video']['bschematic'];{/code}" width="680" height="274" /></a></div>
      <div class="pic_ct3">
	      <div class="pic_sub">节目单：</div>
	      <div class="pic_cot">
	          <ul>
			  {code}
	          	$i = 1;
			  {/code}
			  {foreach $program as $key=>$value}
					<li onmousemove="chang_img({$value['id']},{$value['sta_id']},{$value['user_id']})"><div class="list_left"><a id="bs_{$value['id']}" style="display:none;">{code} echo $value['video']['images']?$program[0]['video']['larger']:$value['video']['bschematic'];{/code}</a><a title="{$value['programe_name']}" id="vs_{$value['id']}" href="{code} echo hg_build_link(SNS_VIDEO.'station_play.php', array('sta_id'=>$value['sta_id'])).'#'. $value['id'];{/code}">{code} echo hg_cutchars($value['programe_name'],10," ");{/code}</a></div><div class="list_right">{code} echo hg_toff_time($value['start_time'],$value['end_time'],0);{/code}</div></li>
				{code}
	          	$i++;
				{/code}
			  {/foreach}
	          </ul>
	      </div>
      </div>
  {/if}
{/if}
    </div>
  </div>
  <div class="pic_bot1"></div>
</div>
 -->

<div class="space">
	<div class="g_larea" >
        <div class="g_are2">
        	<ul>
	        	<li class="bt">视频</li>
           </ul>
		   {if $is_my_page}<a style="float: right;color: #C61212;margin:10px;" href="{code} echo hg_build_link(SNS_VIDEO.'my_video.php');{/code}">编辑</a>{/if}
        </div>
        <div class="g_are4" id="content">
		{if !is_array($video)||!$video}
		<div class="no_video">
			<div class="noinfo">
				<div class="hl-link">
					<dl>
						<dt>
							暂未上传视频
						</dt>
						<dd class="return_page">
							<a class="f14bbule" href="{$_SERVER['HTTP_REFERER']}">返回上一页！</a>
						</dd>
						<dd class="pl150">
							<a class="f18bule" href="{code} echo SNS_VIDEO.'upload.php'; {/code}">上传视频</a>
						</dd>
					</dl>
				</div>
			</div>
		</div>
		{else}
			<ul class="video">
			{foreach $video as $key => $value}
				<li class="cus_pad"><a target="_blank" href="{code} echo hg_build_link(SNS_VIDEO.'video_play.php', array('id'=>$value['id']));{/code}"><img title="{$value['title']}" src="{$value['schematic']}" width="122" height="91" /></a><a title="{$value['title']}" target="_blank" href="{code} echo hg_build_link(SNS_VIDEO.'video_play.php', array('id'=>$value['id']));{/code}">{code} echo hg_cutchars($value['title'] , 10 , ' ');{/code}</a><span class="txt">播放：{$value['play_count']}</span><span class="txt">评论：{$value['comment_count']}</span><span class="txt">更新时间：{code} echo date("Y-m-d",$value['update_time']);{/code}</span></li>
			{/foreach}
			</ul>
			<div class="clear"></div>
		{/if}
        {$showpages}
        <div class="clear"></div>
        </div>
    </div>
    </div>
{template:foot}