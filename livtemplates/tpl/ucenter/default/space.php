<?php 
/* $Id: space.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
<script type="text/javascript">
<!--
$(document).ready(function(){	
	chang_img = function(id,sta_id,user_id){
		$("#pic_href").attr("href",$("#vs_"+id).attr("href"));
		$("#pic_ct").attr('src',$("#bs_"+id).html());
	}
});
//-->
</script>
<script type="text/javascript">
<!--

$(document).ready(function (){

	//根据参数显示不同的页面
	show_content = function(type)
	{
		switch (type)
		{
			case 'status' : changeContent(2 , {$id});break;
			case 'video'  : changeContent(1 , {$id});break;
			case 'albums' : changeContent(3 , {$id});break;
			case 'thread' : changeContent(4 , {$id});break; 
			default: onchange(2 , {$id});
		}
	};

	var type = document.location.hash.substring(1);
	
	if(type)
	{
		show_content(type);
	}	
});
//-->
</script>

{if is_array($station)}
<div class="space">
  <div class="pic_top1"></div>
  <div class="pic_cet1">
    <div class="pic_l"></div>
    <div class="pic_c">
      <div class="pic_ct1">
    
        <ul>
          <li class="pic_logo"><a title="{$station['web_station_name']}" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$station['id']));?>"><img src="{$station['small']}" /></a>
          <div><a title="{$station['web_station_name']}" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$station['id']));?>"><?php echo hg_cutchars($station['web_station_name'],8," ");?></a></div></li>
          <li class="lktt clear">简介：<a title="{code} echo $station['brief']?$station['brief']:'暂无';{/code}">{code} echo hg_cutchars($station['brief']?$station['brief']:'暂无',25," ");{/code}</a></li>
          <li class="lkbt" id="collect_{$station['id']}">
          {if is_array($program)}
          <a class="plays" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array('sta_id'=>$station['id']));?>"></a>
          {/if}
       
          {if !$is_my_page}
          
          	  {if !$station['relation']}
	          
	          	<a class="gz_get" href="javascript:void(0);" onclick="add_concern({$station['id']},1,{$id});">关注该频道</a>
	          {else}
				<a class="gz_get" href="javascript:void(0);" onclick="del_concern({$station['concern_id']},{$station['id']},{$id},1);">取消关注</a>
	          {/if}
	      {/if}
          </li>
        </ul>
        <div class="clear"></div>
      </div>
      {if is_array($program)}
      	
      	<div class="pic_ct2"><a id="pic_href" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php", array('sta_id'=>$program[0]['sta_id']))."#1";?>" target="_blank"><img id="pic_ct" src="{$program[0]['video']['bschematic']}" width="464" height="238" /></a></div>
	      <div class="pic_ct3">
	        <div class="pic_sub">节目单：</div>
	        <div class="pic_cot">
	          <ul>
	            {code}
	          		$i = 1;
	          	{/code}
	          	{foreach $program as $key=>$value}
	          	
	          	<li onmousemove="chang_img({$value['id']},{$value['sta_id']},{$value['user_id']})"><div class="list_left"><a id="bs_{$value['id']}" style="display:none;">{$value['video']['bschematic']}</a><a title="{$value['programe_name']}" id="vs_{$value['id']}" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php", array('sta_id'=>$value['sta_id']))."#". $value['id'];?>"><?php echo hg_cutchars($value['programe_name'],10," ");?></a></div><div class="list_right"><?php echo hg_toff_time($value['start_time'],$value['end_time'],0);?></div></li>
	            {code}
	          		$i++;	
	          	{/code}
	          	{/foreach}
	          
	          </ul>
	        </div>
	      </div>
      {else}
		{code}
			$null_title = "";
			$null_text = "暂未增加节目单";
			$null_type = 1;
			$null_url = $_SERVER['HTTP_REFERER'];
		{/code}
		{template:unit/null}
      {/if}
    </div>
    <div class="pic_r"></div>
  </div>
  <div class="pic_bot1"></div>
</div>
{/if}
<div class="space">
	<div class="g_larea" >
    	<div class="g_are1"></div>
        <div class="g_are2">
        	<ul>
				<li id="t_2" class="bt" onclick="changeContent(2 , {$id});" style="cursor: pointer;">点滴</li>
	        	<li id="t_1" onclick="changeContent(1 , {$id});" style="cursor: pointer;">视频</li>
               	<li id="t_3" onclick="changeContent(3 , {$id});" style="cursor: pointer;">相册</li>
                <li id="t_4" onclick="changeContent(4 , {$id});" style="cursor: pointer;">贴子</li>
           </ul>
        </div>
        <div class="g_are3"></div>
        
        
        <div class="g_are4" id="content">
       <div class="content-left" id="status_list">

		{if  !empty($statusline)&&is_array($statusline)}

		<ul class="mblog">
		{foreach $statusline as $key => $value}
		    {code}
				$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
				$text = hg_verify($value['text']);
				$text_show = $value['text']?$value['text']:$_lang['forward_null'];
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
		
			<li class="my-blog" id="mid_{$value['id']}"  onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});">
				<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}">{$text_show}</div>
				<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
				<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
				<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}"><?php echo SNS_MBLOG;?>show.php?id={$value['id']}</div>
				<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">3</div>
		
				<div class="blog-content">
					<p class="subject clear"><a href="<?php echo SNS_UCENTER.$user_url;?>">{$value['user']['username']}：</a>
					{$text_show}<br/>		
					</p>
					{template:unit/statusline_content}	
					<div class="speak">
						<div class="hidden" id="t_{$value['id']}"><?php echo hg_verify($title);?></div>
						<div class="hidden" id="f_{$value['id']}">{$forward_show}</div>
						<span id = "fa{$value['id']}" style="position:relative;">
							{if $is_my_page && $_user['id'] > 0}
							
							<a href="javascript:void(0);" onclick="unfshowd({$value['id']})">{$_lang['delete']}</a>	
							{/if}
							<a href="javascript:void(0);" onclick="OpenForward('{$value['id']}','{$status_id}')">{code} echo $_lang['forward'].'('.($value['transmit_count']+$value['reply_count']).')'{/code}</a>|
							<a  id="fal{$value['id']}" href="javascript:void(0);" onclick="favorites('{$value['id']}','{$_user['id']}')">{$_lang['collect']}</a>|
							<a href="javascript:void(0);" onclick="getCommentList({$value['id']},{$_user['id']})">{$_lang['comment']}(<span id="comm_{$value['id']}">{$value['comment_count']}</span>)</a>
						</span>
						<strong><?php echo hg_get_date($value['create_at']);?></strong>
						<strong>{$_lang['source']}{$value['source']}</strong>
						
					{if $_user['id']}
					
				<a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">
					{$_lang['report']}</a>	
					{/if}
					</div> 
					<input type="hidden" name="count_comm" id="cnt_comm_{$value['id']}" value="{$value['comment_count']}"/>
					<div id="comment_list_{$value['id']}"></div>
				</div> <a href="<?php echo SNS_UCENTER.$user_url;?>">
		<img style="border:1px solid #ccc;padding:1px;" src="{$value['user']['middle_avatar']}"/>
		</a>
			</li>
		{/foreach}
	 </ul>
	
	 {$showpages}
	 {else}
		{code}
			$null_title = "";
			$null_text = "暂未发布任何点滴！";
			$null_type = 1;
			$null_url = $_SERVER['HTTP_REFERER'];
		{/code}
		{template:unit/null}
	 {/if}
	</div>
	<div class="clear"></div>
        </div>
        <div style="font-size:0px;line-height:0px;"><img src="./res/img/hc_pic_btns.gif"/></div>
    </div>
    <div class="g_rarea">
    
    	<div class="g_bre1">台 长</div>
        <div class="g_bre2">
	        	<div class="pic_img">
	        		<a href="<?php echo hg_build_link("user.php", array('user_id'=>$user_info['id']));?>">
	        			<img src="{$user_info['larger_avatar']}" width="127" height="128"/>
	        		</a>
	        		<span class="txt">
	        			<a href="<?php echo hg_build_link("user.php", array('user_id'=>$user_info['id']));?>">
						 {$user_info['username']}
	        			</a>
	        		</span>
	        	</div>
	                <div class="pic_iis">
	                	<ul>
	                		
	                    	<li><span class="tcolor">性别：</span><?php echo hg_show_sex($user_info['sex']);?></li>
							<li><span class="tcolor">所在地盘：</span>
								{if $is_my_page}
								<a href="<?php echo hg_build_link('geoinfo.php');?>">
								{if $user_info['group_name']}
								  {$user_info['group_name']}
								{else}
								  暂无
								{/if}
								</a>
								{else}
									{if $user_info['group_name']}
									 <a href="<?php echo hg_build_link(SNS_TOPIC, array('d' => 'group' , 'm' => 'thread' , 'group_id' => $user_info['group_id']))?>">{$user_info['group_name']}</a>
									{else}
									{/if}
								{/if}
								
							</li>
							
							 {code}
								$relation1 = array('email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
								$relation_count =0;
							{/code}
							
							{foreach $relation1 as $key =>$value}
								{code}$temp = $user_info[$key];{/code}
								{if $temp}
									{if strcmp($key,"birthday")==0 && is_numeric($temp)}
										<li>{$value}： <span class='txt'>{$_lang['xingzuo'][$temp]}</span></li>
									{else}
										<li>{$value}： <span class='txt'>{$temp}</span></li>
									{/if}
									{code}
									  $relation_count ++;
									{/code}
								{/if}
							{/if}
							<li class="urls"><span class="tcolor">个人主页：</span><?php echo SNS_UCENTER;?>user.php?user_id={$user_info['id']}</li>
	                    </ul>
	                </div>
	                <div class="pic_coss">
	                	<ul>
	                    	<li class="line"><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;">{$user_info['attention_count']}</div><div><a style="color:black;" href="<?php echo hg_build_link(SNS_UCENTER."follow.php", array('user_id'=>$user_info['id']));?>">关注</a></div></li>
	                        <li class="line"><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;">{$user_info['followers_count']}</div><div><a style="color:black;" href="<?php echo hg_build_link(SNS_UCENTER."fans.php", array('user_id'=>$user_info['id']));?>">粉丝</a></div></li>
	                        <li class="line"><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;" id="status_count">{$user_info['status_count']}</div><div><a style="color:black;cursor:pointer;" onclick="changeContent(2 , {$user_info['id']});">点滴</a></div></li>
	                        <li><div class="p1" style="color: #0164CA;font-size: 14px;font-weight: bold;">{$user_info['video_count']}</div><div><a style="color:black;cursor:pointer;" onclick="changeContent(1 , {$user_info['id']});">视频</a></div></li>
	                    </ul>
	                </div>
	          <div class="pic_bottn clear"> <!--<a href="#"><img src="./res/img/hc_send1.gif"  /></a>-->&nbsp;
	          {if !$is_my_page}
                  
	                	{if $relation == 0}    
							<div class="blacklist">
							<span class="follw" id="add_{$id}">已加入黑名单</span>
							<span class="close-follw" id="deleteFriend"><a href="javascript:void(0);" onclick="deleteBlock({$id});">解除</a></span>
							</div>
						{/if}
						
						{if $relation == 1}   
							<div class="follow-all">
							<span class="follw" id="add_{$id}"><a class="mul-concern"></a></span>
							<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
							</div>
						{/if}
						
						{if $relation == 2}   
							<div class="follow-all">
							<span class="follw" id="add_{$id}"><a class="been-concern"></a></span>
							<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
							</div>
						{/if}
						
						{if $relation == 3 || $relation == 4}	  
							<div class="follow-all">
							<span class="follw" id="add_{$id}"><a class="concern" href="javascript:void(0);" onclick="addFriends({$id} , {$relation});"></a></span>
							<span class="close-follw" id="deleteFriend"></span>
							</div>
						{/if}
				 {/if}
	          </div>
        </div>
        
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
        
        <div class="g_bre1">
		<a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="station.php?user_id={$user_info['id']}">更多>></a>
        {if $_user['id'] > 0}
           
           {if $is_my_page}
           		我关注的频道
           {else}
           		TA关注的频道
           {/if}
        
        {else}
            TA关注的频道
        {/if}	
    	</div>
      <div class="g2_bre2">
       {if !is_array($concern)||!$concern}
        	{code}
				$null_title = "";
				$null_text = "暂未关注任何频道";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}
            {else}
        	<ul>
        		{code}
	        	  unset($concern['total']);
	        	{/code}
	        	{code}
	        	  $i = 1;
	        	{/code}
	        	{foreach $concern as $key=>$value}
	        	
	        		{if $i<7}
	        		
	        			{if $i%2}
		        		
		        		<li>
		            	  <div class="img_left"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><img src="{$value['small']}" width="46px" height="46px"/></a></div>
		                  <div class="txt_right"><a title="{$value['web_station_name']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><?php echo hg_cutchars($value['web_station_name'],4," ");?></a>
		                  <span id="gz_{$value['id']}" class="gztxt">
		                  
		      
		        		  {if !$value['relation']}
				          
				        	<a href="javascript:void(0);" onclick="add_concern({$value['id']},1,{$value['user_id']});">+关注</a>
				          {else}
				          		已关注
				          {/if}
				          </span></div>
	              		</li>
		        		{else}
		        		<li class="cus_pad">
		            	  <div class="img_left"><a href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><img src="{$value['small']}" width="46px" height="46px"/></a></div>
		                  <div class="txt_right"><a title="{$value['web_station_name']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['user_id']));?>"><?php echo hg_cutchars($value['web_station_name'],4," ");?></a>
						<span id="gz_{$value['id']}" class="gztxt">
						  {if !$value['relation']}
				          
				        	<a href="javascript:void(0);" onclick="add_concern({$value['id']},1,{$value['user_id']});">+关注</a>
				          {else}
				          		已关注
				          {/if}
				          </span></div>
	             		</li>
		        	  {/if}
	        		{/if}
	        		{code}
	        		  $i++;	
	        		{/code}
	        	{/foreach}
        	 </ul>
	       {/if}
        	<div class="clear"></div>
      </div>
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
         
        {if $_user['id'] > 0}
        <div class="g_bre1">
      <a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="follow.php?user_id=<?php echo $user_info['id'];  ?>">更多>></a>
        {if $is_my_page}
			我关注的人
		{else}
			TA关注的人
		{/if}
    	</div>
      <div class="g2_bre2">
            {if !is_array($friends)||!$friends}  
			{code}
				$null_title = "";
				$null_text = "暂未关注任何人";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}  		        		
            {else}
        	<ul>
	        	{code}$i = 1;{/code}
	        	{foreach $friends as $key=>$value}
	        		{if $i%2}
	        		<li>
	            	  <div class="img_left"><a title="{$value['username']}" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><img title="{$value['username']}" src="{$value['middle_avatar']}" /></a></div>
	                  <div class="txt_right"><a title="{$value['username']}" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],3," ");?></a><span class="gztxt"></span></div>
             		</li>
	        		{else}
	        		<li class="cus_pad">
	            	  <div class="img_left"><a title="{$value['username']}" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><img title="{$value['username']}" src="{$value['middle_avatar']}" /></a></div>
	                  <div class="txt_right"><a title="{$value['username']}" href="<?php echo hg_build_link("user.php",array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],3," ");?></a><span class="gztxt"></span></div>
             		</li>
	        		{/if}
	        		{code}$i++;{/code}	
	        	{/foreach}
        	 </ul>
	        {/if}
        	<div class="clear"></div>
      </div>
      <div class="g_bre3" ><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
      
     {/if}
        
        
        {if $_user['id'] > 0}
		
         <div class="g_bre1">
         <a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="fans.php?user_id={$user_info['id']}">更多>></a>
      
		
        {if $is_my_page}
			我的粉丝
		{else}
			TA的粉丝
		{/if}
 
    	</div>
        <div class="g4_bre2">
		{if !is_array($fans)||!$fans}
			{code}
				$null_title = "";
				$null_text = "暂无粉丝";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}
        	{else} 
        	<ul>
	        	{code}$i = 1;{/code}
	        	{foreach $fans as $key=>$value}
	        		{if $i ==1}
	        		    <li><img title="{$value['username']}" src="{$value['middle_avatar']}" width="50" height="50" /><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		{else if $i ==4}
	        		    <li><img title="{$value['username']}" src="{$value['middle_avatar']}" width="50" height="50" /><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		{else} 
	        			<li class="cus_pad"><img title="{$value['username']}" src="{$value['middle_avatar']}" width="50" height="50" /><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		{/if}
	        		{code}$i++;{/code}	
	        	{/foreach}
        	 </ul>
        	{/if}
        	<div class="clear"></div>
        </div>
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
        
       {/if}
        
        <div class="g_bre1">
        <a style="font-size:12px;color:gray;float:right;margin-right:10px;font-weight: normal;" href="group.php?user_id={$user_info['id']}">更多>></a>
       
       
         {if $_user['id'] > 0}
           
           {if $is_my_page}
           		我加入的地盘
           {else}
           		TA加入的地盘
           {/if}
        
        {else}
           TA加入的地盘
        {/if}	
       
    	</div>
  <div class="g3_bre2">
                  		 
                  		{if !is_array($group)||!$group}
                		{code}
							$null_title = "";
							$null_text = "暂未关注地盘";
							$null_type = 1;
							$null_url = $_SERVER['HTTP_REFERER'];
						{/code}
						{template:unit/null}
                		{else}
                		{code}
                			$i = 1;
                		{/code}	
                			{foreach $group as $key=>$value}
                			
                				{if $i<9}
                				
								<li> <a title="{$value['name']}" href="{$value['href']}" ><?php echo hg_cutchars($value['name'],6," ");?></a>   </li>
                				{/if}
                				$i++;
                			{/foreach}
                			</ul>
						{/if}
                		<div class="clear"></div>
        </div>
        <div class="g_bre3"><img src="./res/img/hc_area_bottom.gif" width="246" height="7" /></div>
    </div>
    </div>
{template:unit/forward}
{template:unit/status_pub}
{template:unit/tips}
{template:foot}
