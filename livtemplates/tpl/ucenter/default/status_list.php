<?php 
/* $Id: status_list.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
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