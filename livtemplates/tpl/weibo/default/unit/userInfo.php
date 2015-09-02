<script type="text/javascript">
var page = 1;

$(document).ready(function()
{	
	nextVip = function()
	{
		$.ajax({
		        url: 'index.php',
		        type: 'POST',
		        dataType: 'html',
					timeout: 5000,
					cache: false,
		        data: {
					a:'ajax_get_vip',
					page:page,
					total:$('#vip_nums').val()
		        	},
		        error: function() {
		        	tipsport('网络延迟！');
		        },
		        success: function(r) {

			        $('#vip').html(r);
					page++;

					if(page == $('#vip_nums').val())
					{
						page = 0;
					}
		        }
		});    
	};

	addFriends=function(id , relation){
		var target = '#add_' + id;
		$.ajax({
			url: "index.php",
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {a: "add_friend",
				  id: id
			},
			error: function(){
				tipsport('网络延迟！');
			},
			success: function(response){
				
				if(relation == 4)          /*未知这批人是否关注了我*/
				{
					$(target).html('<span class="been-concern">√已关注</span>');						
				}				
			}
		});			
	};	
	
});
	
</script>
{if $_user['id'] != $user_info['id']}
{code}
$extuserlink = '?user_id=' . $user_info['id'];
{/code}
{/if}
		<div class="business">
				<span class="u-show1">
					<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id'=>$user_info['id'])){/code}">{$user_info['status_count']}</a>
	    		</span>
    			<span class="u-show2">
    				<a href="{code} echo hg_build_link(SNS_UCENTER . 'follow.php' , array('user_id'=>$user_info['id']));{/code}">{$user_info['attention_count']}</a>
	    		</span>
    			<span class="u-show3">
	    			<a href="{code} echo hg_build_link(SNS_UCENTER . 'fans.php' , array('user_id'=>$user_info['id']));{/code}">{$user_info['followers_count']}</a>
	    		</span>
    			<span class="u-show4">
	    			<a href="{code} echo hg_build_link(SNS_VIDEO . 'user.php', array('user_id'=>$user_info['id']));{/code}">{$user_info['video_count']}</a>
	    		</span>
		</div>
		</div>
		{code} echo hg_advert('ui_index_rihgt');{/code}
		<div class="bk-top1">点滴导航</div>
		<div class="wb-block1">
			<div class="menu">
			 
			{foreach $_settings['nav'] as $k => $v}
				{if $k == $gScriptName}
		
				<a class="{$v['class']}_click" href="{code} echo hg_build_link($v['filename']);{/code}"></a>
				{else} 
				<a class="{$v['class']}" href="{code} echo hg_build_link($v['filename']);{/code}"></a>
				{/if}
			{/foreach}
			
			</div>
		</div>
		
		<!-- 会员信息   -->
		
		{if is_array($vipUser)}
		
			<input id="vip_nums" type="hidden" name="vip_nums" value="{code} echo $total;{/code}" />	
			<div id="vip_title" class="bk-top1">葫芦会员<a style="float:right;margin-right:10px;font-size:12px;" href="javascript:void(0);" onclick="nextVip();">换几个</a></div>
			
			<div id="vip" class="wb-block1" style="height:240px;text-align:center;">
				<ul>	
				{foreach $vipUser AS $k => $v}
	
					<li class="gz-ul">
						<a href="{code} echo SNS_UCENTER;{/code}user.php?user_id={$v['id']}" ><img style="width:50px;height:50px;" title="{$v['username']}" style="border:1px solid silver;padding:2px;" src="{$v['middle_avatar']}" /></a>
						<a title="{$v['username']}" href="{code} echo SNS_UCENTER;{/code}user.php?user_id={$v['id']}">{code} echo hg_cutchars($v['username'] , 3 , '...' , 1); {/code}</a>
						<div class="close-concern">
						
						{if $_user['id'] == $v['id']} 				<!-- //自己 --> 
						
						{else}
						
							{if $v['is_friend'] == 1}    <!-- //已关注 -->
							
						
						<p><span class="been-concern">√已关注</span>	</p>			
							
							
							{else}
							
						
						<p id="add_{$v['id']}"><a class="concern" href="javascript:void(0);" onclick="addFriends({$v['id']} , 4)">＋加关注</a></p>
								
							{/if} 
						{/if} 						
															
						</div>
					</li>
				{/foreach}
				</ul>	
			</div>				
		{/if}				
		
		{if is_array($topic)}
		<div class="bk-top1">热门话题</div>
		<div class="wb-block1">
		<ul class="topic clear">		
			{foreach $topic as $value}
			<li>
				<a title="{$value['title']}" href="{code} echo hg_build_link('k.php' , array('q' => $value['title'])); {/code}">
				{code} echo hg_cutchars($value['title'],10," ");{/code}</a><span>({$value['relate_count']})</span>
			</li>
			
			{/foreach}
		</ul>
		</div>
		{/if}
		 		
		{if $topic_follow}
		
		<!-- follow topic -->
		<div class="bk-top1">{$_lang['topic_follow']}<strong>(<span id="liv_topic_follow_num">{code} echo count($topic_follow);{/code}</span>)</strong></div>
		<div class="wb-block1">
		<ul class="topic clear">
		

		{foreach $topic_follow as $key=>$value}			
		<li class="topic_li" onmouseover="this.className='topic_li_hover'" onmouseout="this.className='topic_li'">
		{code}
		$title = '<a title="'.$value['title'].'" href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.hg_cutchars($value['title'],10," ") . '</a>';
		echo $title;
		{/code}
		<!-- <?php
		$title = '<a title="'.$value['title'].'" href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.hg_cutchars($value['title'],10," ") . '</a>';
		echo $title;
		?> -->
		<a class="close" href="javascript:void(0);" onclick="del_Topic_Follow('{$value['topic_id']}',this)"></a>
		<div class="hidden" id="topic_{$value['topic_id']}">{$value['title']}</div>
		</li>
		
		{/foreach}

		<!-- add follow topic -->
		<li id="addtopicfollow" class="topic-add"><a href="javascript:void(0);" onclick="add_Topic_Follow()">{$_lang['insert']}</a></li>
		<!-- end add follow topic -->
		</ul>
		</div>
		<dl id="topicbox" class="topicbox">
		<dt><a id="TopicBoxClose" href="javascript:void(0);" onclick="topicBoxClose()">x</a></dt>
		<dd class="topic_dd_title">
		<input type="text" name="topic" id="topic" style="font-size:12px;width:118px;height:20px;"/>
		<input type="button" style="font-size:12px;width:50px;height:25px;" value=" {$_lang['save']} " onclick="addTopic()"/>
		</dd>
		<dd class="topic_dd_about" id="topic_dd_about">{$_lang['topic_about']}</dd>
		</dl>
		{/if}