<?php 
/* $Id: favorites.tpl.php 3525 2011-04-11 05:06:19Z chengqing $ */
?>
{template:head}
<div class="content clear" id="equalize">
	<div class="content-left">
		<div class="news-latest">
		<div class="tp"></div>
		<div class="md"></div>
		</div>

{if !empty($statusline)&&is_array($statusline)}

<ul class="list clear">

{foreach $statusline as $key => $value}
	{code}
		$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
		$text = hg_verify($value['text']);
		$text_show = '：'.($value['text']?$value['text']:$_lang['forward_null']);
	{/code}
	{if $value['reply_status_id']}
		{code}
			$forward_show = '//@'.$value['user']['username'].' '.$text_show;
			$title = $_lang['forward_one'].$value['retweeted_status']['text'];
			$status_id = $value['reply_user_id'];
		{/code}
	{else}
		{code}
			$forward_show = '';
			$title = $_lang['forward_one'].$value['text'];
			$status_id = $value['member_id'];
		{/code}
	{/if}
	{code}
	$text_show = hg_verify($text_show);
	$transmit_info=$value['retweeted_status'];
	{/code}

	<li class="clear">
		<div class="blog-content">
			<p class="subject clear"><a href="{code} echo SNS_UCENTER.$user_url;{/code}">{$value['user']['username']}</a>
		{code}
			echo $text_show."<br/>";
		{/code}		
		</p>

{if $value['medias']}

	<input id="rot_{$value['id']}" type="hidden" value="0"/>
	<div id="prev_{$value['id']}" style="display:inline-block;">
	
	{foreach $value['medias'] as $mk => $mv}
		{code}
		$var = array(
			"url" => "",
			"imgname" => "",
			"ori" => "",
			"video_url" => "",
			"video_link" => "",
			"video_img" => "",
			"video_title" => ""
		);
		{/code}
		{if !$mv['type']}
		
			{code}
				$var['url'] = $mv['small'];
				$var['imgname'] = $mv['larger'];
				$var['ori'] = $mv['ori'];
			{/code}
			{if $var['url']}
			
			<div style="display:inline-block;*display:inline;">
			<a href="javascript:void(0);" onclick="scaleImg({$value['id']})">
				<img class="imgBig" src="{$var['url']}"/>
			</a>
			</div>
		 
			{/if}
		{else}
				{code}
					$var['video_url'] = $mv['url'];
					$var['video_link'] = $mv['link'];
					$var['video_img'] = $mv['img']?$mv['img']:"./res/img/videoplay.gif";
					$var['video_title'] = trim($mv['title'])?$mv['title']:$value['text'];
				{/code}
			{if $var['video_link']&&$var['video_img']&&$var['video_title']}
				<div class="hidden" id="vl_{code} echo $mv['id'] + $value['id'];{/code}">{$var['video_link']}</div>
				<div class="hidden" id="vt_{code} echo $mv['id'] + $value['id'];{/code}">{$var['video_title']}</div>
				<div class="hidden" id="vu_{code} echo $mv['id'] + $value['id'];{/code}">{$var['video_url']}</div>
				<div style="position:relative;display:inline-block;*display:inline;height:auto;">
				<img src="{$var['video_img']}"/>
				<a class="feedvideoplay" href="javascript:void(0);" onclick="scaleVideo({$value['id']},{$mv['id']},{$mv['self']})">
					<img class="pointer" src="./res/img/feedvideoplay.gif"/>
				</a>
				</div>
			{/if}
		{/if}	
	{/foreach}

	</div>
	<div id="disp_{$value['id']}" class="disp">
		<div class="pad_sp">
			<a href="javascript:void(0);" onclick="shlink({$value['id']})">收起</a>
			<a target="_blank" href="{$var['ori']}">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft({$value['id']});">左转</a>
			<a href="javascript:void(0);" onclick="runRight({$value['id']});">右转</a>
		</div>
		<canvas id="canvas_{$value['id']}" onclick="shlink({$value['id']})" class="imgSmall"></canvas>
		<img id="load_{$value['id']}"  onclick="shlink({$value['id']})" class="imgSmall" src="{$var['imgname']}"/>
	</div>	
	<div id="v_{$value['id']}" class="hidden" style="text-align:center">		
		
	</div>	
{/if}

{if $transmit_info['text']||!empty($transmit_info['medias'])}

	<div class="comment clear">
	<div class="top"></div>
	<div class="middle clear">
		<p class="subject">{code} echo hg_verify("@".$transmit_info['user']['username'].":".$transmit_info['text'])."<br/>";{/code}
		</p>
 
	{if is_array($transmit_info['medias'])}

		<input id="rot_{code} echo $transmit_info['id'] + $value['id'];{/code}" type="hidden" value="0"/>
		<div id="prev_{code} echo $transmit_info['id'] + $value['id'];{/code}" style="display:inline-block;">
		{foreach $transmit_info['medias'] as $mk => $mv}
		
			$var = array(
				"url" => "",
				"imgname" => "",
				"ori" => "",
				"video_url" => "",
				"video_link" => "",
				"video_img" => "",
				"video_title" => ""
			);
			{if !$mv['type']}
				{code}
					$var['url'] = $mv['small'];
					$var['imgname'] = $mv['larger'];
					$var['ori'] = $mv['ori'];
				{/code}
				{if $var['url']}
			
					<a href="javascript:void(0);" onclick="scaleImg({code} echo $transmit_info['id'] + $value['id'];{/code})">
						<img class="imgBig" src="{$var['url']}"/>
					</a>
			
				{/if}
			{else}
				{code}
					$var['video_url'] = $mv['url'];
					$var['video_link'] = $mv['link'];
					$var['video_img'] = $mv['img']?$mv['img']:"./res/img/videoplay.gif";
					$var['video_title'] = trim($mv['title'])?$mv['title']:$value['retweeted_status']['text']; 
				{/code}
				{if $var['video_link']&&$var['video_img']&&$var['video_title']}
					<div class="hidden" id="vl_{code} echo $mv['id'] + $transmit_info['id'] + $value['id'];{/code}">{$var['video_link']}</div>
					<div class="hidden" id="vt_{code} echo $mv['id'] + $transmit_info['id'] + $value['id'];{/code}">{$var['video_title']}</div>
					<div class="hidden" id="vu_{code} echo $mv['id'] + $transmit_info['id'] + $value['id'];{/code}">{$var['video_url']}</div>
					<div style="position:relative;display:inline-block;*display:inline;">
					<img src="{$var['video_img']}"/>
					<a class="feedvideoplay" href="javascript:void(0);" onclick="scaleVideo({code} echo $transmit_info['id'] + $value['id'];{/code},{$mv['id']},{$mv['self']})">
						<img class="pointer" src="./res/img/feedvideoplay.gif"/>
					</a>
					</div>
				{/if}
			{/if}				
		{/foreach}
	</div>
	<div id="disp_{code} echo $transmit_info['id'] + $value['id'];{/code}" class="disp">
		<div class="pad_sp">
			<a href="javascript:void(0);" onclick="shlink({code} echo $transmit_info['id'] + $value['id'];{/code})">收起</a>
			<a target="_blank" href="{$var['ori']}">查看原图</a>
			<a href="javascript:void(0);" onclick="runLeft({code} echo $transmit_info['id'] + $value['id'];{/code});">左转</a>
			<a href="javascript:void(0);" onclick="runRight({code} echo $transmit_info['id'] + $value['id'];{/code});">右转</a>
		</div>
		<canvas id="canvas_{code} echo $transmit_info['id'] + $value['id'];{/code}" onclick="shlink({code} echo $transmit_info['id'] + $value['id'];{/code})" class="imgSmall"></canvas>
		<img id="load_{code} echo $transmit_info['id'] + $value['id'];{/code}"  onclick="shlink({code} echo $transmit_info['id'] + $value['id'];{/code})" class="imgSmall" src="{$var['imgname']}"/>
	</div>	
	<div id="v_{code} echo $transmit_info['id'] + $value['id'];{/code}" class="hidden" style="text-align:center">
	</div>	
	{/if}
		<div class="speak">
			<span>
				<a href="{code} echo hg_build_link('show.php' , array('id' => $transmit_info['id']));{/code}">{$_lang['original_transmit']}({code} echo $transmit_info['transmit_count'] + $transmit_info['reply_count'];{/code})</a>|
				<a href="{code} echo hg_build_link('show.php' , array('id' => $transmit_info['id'])); {/code}">{$_lang['original_comment']}(<span>{$transmit_info['comment_count']}</span>)</a>
			</span>
		</div> 
		</div>
		<div class="bottom"></div>
	</div>
{/if}		
			<div class="speak clear">
				<div class="hidden" id="t_{$value['id']}">{$title}</div>
				<div class="hidden" id="f_{$value['id']}">{$forward_show}</div>
					<span id = "fa{$value['id']}" style="position:relative;">
				
					{if $value['user']['id'] == $user_info['id']}
					
					<a href="javascript:void(0);" onclick="unfshowd({$value['id']})">{$_lang['delete']}</a>|	
					{/if}
	<!--  -->		
				
					<a href="javascript:void(0);" onclick="OpenForward('{$value['id']}','{$status_id}')">{$_lang['forward']}({code} echo $value['transmit_count']+ $value['reply_count'];{/code})</a>|
					
					<a id="fal{$value['id']}" href="javascript:void(0);" onclick="unfshow('{$value['id']}')">{$_lang['uncollect']}</a>|
					<a href="javascript:void(0);" onclick="getCommentList({$value['id']},{$_user['id']})">{$_lang['comment']}(<span id="comm_{$value['id']}">{$value['comment_count']}</span>)</a>
	<!--  -->		
				</span>
				<strong>{code} echo hg_get_date($value['create_at']);{/code}</strong>
				<strong>{$_lang['source']}{$value['source']}</strong>
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_{$value['id']}" value="{$value['comment_count']}"/>
			<div id="comment_list_{$value['id']}"></div>
		</div> 
		<a href="{code} echo SNS_UCENTER.$user_url;{/code}">
		<img src="{$value['user']['middle_avatar']}"/>
		</a>

	</li>
{/foreach}
 <li class="more">{$showpages}</li>
</ul>
<div style="clear:both;"></div>
{$showpages}

{else}
	{code}
		$null_title = "真不给力，SORRY!";
		$null_text = '我暂时还没有任何收藏！';
	{/code}
	{template:unit/null}
{/if}
</div>






<div class="content-right">

<div class="pad-all">
	<!-- load userInfo -->
	<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
	<div class="user">
	<div class="user-set">
		<h5><a href="{code} echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); {/code}">{$user_info['username']}</a><span><a class="bind" href="{code} echo hg_build_link(SNS_UCENTER.'bind.php'); {/code}">绑定</a></span></h5>
		<a href="{code} echo hg_build_link(SNS_UCENTER.'userprofile.php'); {/code}">个人设置</a>
		<a href="{code} echo hg_build_link(SNS_UCENTER.'login.php' , array('a' => 'logout')); {/code}">{$_lang['logout']}</a>
		<div class="user-name">
			<div style="font-size:12px;color:gray;">性别：{code} echo $user_info['sex']?'男':'女';{/code}</div>
			<div style="font-size:12px;color:gray;">所在地盘：<a style="color:#0164CC" href="{code} echo hg_build_link(SNS_UCENTER . 'geoinfo.php');{/code}">{$user_info['group_name']}</a></div>
			{code}
				$relation = array('birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
			{/code}
				{foreach $relation as $key =>$value}
					{code}
						$temp = $user_info[$key];
					{/code}
					{if $temp}
					
						{if strcmp($key,"birthday")==0 && is_numeric($temp)}
							{code}
								echo '<div style="font-size:12px;color:gray;"><span>'.$value. ' : <span>' . $_lang['xingzuo'][$temp] . '</div>';
							{/code}
						{else}
							{code}
								echo '<div style="font-size:12px;color:gray;"><span style="font-size:12px;color:gray;">'.$value. ' : </span>' . $temp . '</div>';
							{/code}
						{/if}
					{/if}				
				{/foreach}
			
		</div>
	</div> 

	<a href="{code} echo hg_build_link(SNS_UCENTER.'avatar.php'); {/code}"><img src="{$user_info['middle_avatar']}" title="{$user_info['username']}" /></a>
	</div>
	{template:unit/userInfo}
		</div>
	    </div>
</div>

<input type="hidden" value="update" name="a" id="a"/>
<input type="hidden" value="点滴" name="source" id="source"/>

{template:unit/forward}
{template:foot}