{foreach $statusinfo as $value}
<li class="clearfix comment-item" id="s_{$value['id']}">
	<div class="user-pic"><a href="#"><img src="{code} echo hg_bulid_img($value['avatar'],50,50);{/code}" /></a></div>
	<div class="twitter-area">
	   <p class="twitter-title"><em class="user-name">{$value['user_name']}</em><span class="twitter-con">{if $value['reply_status_id'] && !$value['text']}转发微博{else}{$value['text']}{/if}</span></p>
	   {if $value['reply_status_id']}
	   <div class="retweeted">
	   		{template:unit/retweeted_status}
	   		<div class="replyArrow">
		   		<span class="h_arrow">◆</span>
		   		<span class="v_arrow">◆</span>
	   		</div>
	   </div>
	   {else}
		   {if $value['media']}
		   <p>
		   		{if $value['media']['img']}
		   		{foreach $value['media']['img'] as $img}
		   		<img src="{code} echo hg_bulid_img($img);{/code}" />
		   		{/foreach}
		   		{/if}
		   		{if $value['media']['video']}
		   		{foreach $value['media']['video'] as $video}
		   			{if $video['type'] == 1}
		   			{code}echo htmlspecialchars_decode($video['object']);{/code}
		   			{elseif $video['type'] == 2}
		   			  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="400" height="300">
						<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
						<param name="allowscriptaccess" value="always">
						<param name="allowFullScreen" value="true">
						<param name="wmode" value="transparent">
						<param name="flashvars" value="videoUrl={$video['url']}&autoPlay=false">
					  </object>
		   			{/if}
		   		{/foreach}
		   		{/if}
		   </p>
		   {/if}
	   {/if}
	   {code}
	   $transmit_count = $value['transmit_count'] ? intval($value['transmit_count']) : 0;
	   $comment_count = $value['comment_count'] ? intval($value['comment_count']) : 0;
	   {/code}
	   <p class="twitter-detail"><span class="date">{code}echo hg_get_date($value['create_at']);{/code}&nbsp;&nbsp;来自:{$value['app_name']}</span><span class="twitter-control">{if $value['member_id'] == $_user['id']}<a class="del_status" data-id="{$value['id']}">删除</a>|{/if}<a class="transmit-link">转发({$transmit_count})</a>|<a class="comment-link" data-id="{$value['id']}">评论({$comment_count})</a></span></p>
		<div class="twitter-comment">
			<em></em>
			<div class="twitter-comment-form">
				<input type="text" name="content" class="comment-txt"/>
				<input value="评论" name="sub" type="button" class="comment_btn" data-id="{$value['id']}" />
			</div>
			<ul>
			</ul>
			<span class="loading">正在加载,请稍候...</span>
		</div>
	</div>
</li>
{/foreach}