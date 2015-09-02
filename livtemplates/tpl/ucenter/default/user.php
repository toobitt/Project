<?php 
/* $Id: user.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}

<script type="text/javascript">
<!--

$(document).ready(function (){

	/*根据参数显示不同的页面*/
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

<span style="float:left;width:18px;text-align:left;margin-right:5px;padding-right: 5px;display:block;"></span>

{template:unit/user_info}
<div class="u-bg"></div>



<div class="garea">
	<div class="g_larea">
		{code} echo hg_advert('ucenter_top');{/code}
        <div class="g_are2">
        	<ul>
			<li id="t_2" onclick="changeContent(2 , {$id});" class="bt">点  滴</li>
        	<li id="t_3" onclick="changeContent(3 , {$id});" class="bt_d">相  册</li>
			<li id="t_4" onclick="changeContent(4 , {$id});" class="bt_d">贴  子</li> 
          </ul>
        </div>
        <div class="g_are4" id="content">
	        <div class="content-left" id="status_list">
				{if  !empty($statusline) && is_array($statusline)}	
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
							$title = $this->lang['forward_one'].$value['retweeted_status']['text'];
							$status_id = $value['reply_user_id'];
					    {/code}
						{else}
						{code}
							$forward_show = '';
							$title = $this->lang['forward_one'].$value['text'];
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
							<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}"><?php echo SNS_MBLOG; ?>show.php?id={$value['id']}</div>
							<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">3</div>
		
		
							<div class="blog-content">
								<p class="subject clear"><a href="<?php echo SNS_UCENTER; ?>{$user_url}">{$value['user']['username']}：</a>
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
										<a href="javascript:void(0);" onclick="OpenForward('{$value['id']}','{$status_id}')">{$_lang['forward']}({code} echo $value['transmit_count']+$value['reply_count'];{/code})</a>|
										<a  id="fal{$value['id']}" href="javascript:void(0);" onclick="favorites('{$value['id']}','{$_user['id']}')">{$_lang['collect']}</a>|
										<a href="javascript:void(0);" onclick="getCommentList({$value['id']},{$_user['id']})">{$_lang['comment']}(<span id="comm_{$value['id']}">{$value['comment_count']}</span>)</a>
									</span>
									<strong><?php echo hg_get_date($value['create_at']);?></strong>
									<strong>{$_lang['source']}{$value['source']}</strong>
										
					{if $_user['id']}
				        <a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">{$_lang['report']}</a>	
					{/if}
								</div> 
								<input type="hidden" name="count_comm" id="cnt_comm_{$value['id']}" value="{$value['comment_count']}"/>
								<div id="comment_list_{$value['id']}"></div>
							</div> 
							<a href="<?php echo SNS_UCENTER; ?>{$user_url}">
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
    </div>
     
    <div class="g_rarea">
		{code} echo hg_advert('ucenter_right');{/code}
       {if $_user['id'] > 0}
	
      <div class="g_bre1">
      <a style="font-size:12px;color:#00A0EA;float:right;margin-right:10px;font-weight: normal;" href="follow.php?user_id={$user_info['id']}">更多>></a>

      {if $_user['id'] > 0}
      		{if $is_my_page}
				我关注的人
		    {else}
				TA关注的人
			{/if} 
	   {else}
      		TA关注的人
       {/if} 

    	</div>
      <div class="g4_bre2">
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
					{code}
						$style = '';
						switch($i)
						{
							case 1:
								break;
							case 4:
								break;
							case 7:
								break;
							default:
								$style = ' class="cus_pad"';
								break;
						}
	        		{/code}
	        			<li{$style}><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><img title="{$value['username']}" src="{$value['middle_avatar']}" width="50" height="50" /></a><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a>
						</li>
	        		{code}$i++;{/code}	
	        	{/foreach}
        	 </ul>
	        {/if}
        	<div class="clear"></div>
      </div>
      <div class="g_bre3" ></div>
     {/if}
      
      
     
	  {if $_user['id'] > 0}

      <div class="g_bre1">
      <a style="font-size:12px;color:#00A0EA;float:right;margin-right:10px;font-weight: normal;" href="fans.php?user_id={$user_info['id']}">更多>></a>
    	
        {if $_user['id'] > 0}
           
           {if $is_my_page}
           		我的粉丝
           {else}
           		TA的粉丝
           {/if}
        
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
	        		{code}
						$style = '';
						switch($i)
						{
							case 1:
								break;
							case 4:
								break;
							case 7:
								break;
							default:
								$style = ' class="cus_pad"';
								break;
						}
	        		{/code}
	        			<li{$style}><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><img title="{$value['username']}" src="{$value['middle_avatar']}" width="50" height="50" /></a><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		{code}$i++;{/code}	
	        	{/foreach}
        	 </ul>
        	{/if}
        	<div class="clear"></div>
        </div>
        <div class="g_bre3"></div>
       {/if}
       <div style="text-align:center;">
    {code}
		echo hg_advert('google_2');
		echo hg_advert('baidu_1');
	{/code}
       </div>
	</div>
</div>

<div class="pop" id="pop">
	<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
	<div id="pop_s"></div>
</div>	

{template:unit/forward}
{template:unit/status_pub}
{template:foot}














