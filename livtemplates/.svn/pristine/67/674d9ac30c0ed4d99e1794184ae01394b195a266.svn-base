<!--文章-->
<div class="t_item clearfix" id="status_{$value['id']}">
	<a class="t_author_avator" href="{$user_link}"><img src="{$avatar}" width="80" height="80" /></a>
	<div class="t_main">
		<div class="t_cont"><a href="{$user_link}" class="t_author">{$value['user'][nick_name]}:</a>{$text_show}
			<br/>
			{if !empty($images)}
				{foreach $images as $kImg => $vImg}
				{code}
				$vBigImg = str_replace('/200x/', '/400x/', $vImg);
				{/code}
					<div class="vImageBox">
					    <span class="vImageOption"><span class="vImageLeft">向左</span><span class="vImageRight">向右</span></span>
					    <img class="vimage" src="{$vImg}" _now="{$vImg}" _big="{$vBigImg}"/>
					</div>
				{/foreach}
			{/if}</div>	
		<!--<div class="t_num"><span class="t_digg_num"><strong>3</strong>赞</span><span class="t_share_num"><strong>40</strong>分享</span><span class="t_reply_num"><strong>1</strong>回应</span></div>-->
				<input class="timestamp" type="hidden" value="{$value['create_at']}" />
		<div class="t_assist"><time class="publishtime">{code} echo hg_get_date($value['create_at']);{/code}</time> <span class="t_by_relay" style="cursor:pointer;" data-id="{$value['id']}" data-uid={$status_id} data-src="{$avatar}">转发<span style="display: none;">{$text_show}</span></span>{if $value['is_self']} <span class="t_by_delete" onclick="delete_weibo({$value['id']});">删除</span>{/if} <span class="t_by_device">来自：{$value['source']}</span><a href="###" class="{if $praise}btFeed_hover{else}btFeed{/if}" onclick="praise(this,{$value['id']});">赞</a><!--<a href="#" class="t_share">分享</a>--></div>
		<div class="t_reply">
			<form name="comment_form_{$value['id']}" id="comment_form_{$value['id']}" action="#" onSubmit="return add_comment({$value['id']});">
				<input type="text" class="t_reply_content" value="回应..." name="text"/>
				<input type="submit" value="回应" class="t_reply_btn"  style="display:none;"/>
				<input type="hidden" value="comment" name="a"/>
				<input type="hidden" value="{$value['id']}" name="status_id"/>
				<input type="hidden" value="0" name="reply_id"/>
			</form>			
		</div>
		<div class="t_comments clear" id="comment_list_{$value['id']}" style="display:none;"></div>
		
	</div>		
</div>