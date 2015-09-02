<style type="text/css">
.comment_list{width:100%;}
.comment_list li{width:100%;margin:10px 0;}
.comment_list .comment-img{width:10%;float:left;}
.comment_list .comment-img img{padding:3px;border:1px solid #ccc;}
.comment_list .comment-bar{width:87%;float:right;background:#F7F7F7;height: 20px;line-height: 20px;display:inline-block;}
.comment_list .comment-bar .bar-left{margin-left:10px;float:left;}
.comment_list .comment-bar .bar-right{padding-right:10px;float:right;}
.comment_list .comment-con{width:86%;float:right;padding: 8px 0; margin-bottom: 10px;}
.reply_list{width:90%;margin-left:10%;}
.reply_list li{}
.reply_list .comment-img{width:10%;float:left;}
.reply_list .comment-img img{padding:3px;border:1px solid #ccc;}
.reply_list .comment-bar{width:86%;float:right;background:#F7F7F7;height: 20px;line-height: 20px;display:inline-block;}
.reply_list .comment-bar .bar-left{margin-left:10px;float:left;}
.reply_list .comment-bar .bar-right{padding-right:10px;float:right;}
.choiceface{cursor:pointer;background: url("<?php echo RESOURCE_DIR;?>img/mood_do.gif") no-repeat;display: inline-block;padding: 2px;width: 36px;}
.facelist{margin-left:40px;background:#FFFFFF;border: 1px solid #C3C3C3;height: 200px;opacity: 0.9;overflow-y: auto;position: relative;width: 260px;z-index: 999;}
.facelist ul li{border-bottom: 1px dotted #DDDDDD;float: left;height: 52px;overflow: hidden;padding: 4px;width: 52px;}
.count_pos{ margin-left: 220px;}
#comment_note{width:85%;}
#com_content{width:auto;}
.comment_notes{padding: 6px 0;}
.comment_notes li{padding: 3px 0;}
</style>
{template:unit/report}
{if $_user['id']}
<div id="comment_note" class="clear">
	<input type="hidden" id="com_cid" value="<?php echo $cid?$cid:0;?>"/>
	<input type="hidden" id="com_reply_id" value="<?php echo $reply_id?$reply_id:0;?>"/>
	<input type="hidden" id="com_reply_user_id" value="<?php echo $reply_user_id?$reply_user_id:0;?>"/>
	<input type="hidden" id="com_type" value="<?php echo $type?$type:0;?>"/>
	<ul class="comment_notes clear">
		<li>
			<span>{$_lang['commnet']}</span>
			<span>
				<a id="choiceface" href="javascript:void(0);" class="choiceface">
					<img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif">
				</a>				
			</span>
			<div id="face" class="face_content" style="position: absolute; display: none; visibility: visible; margin-top: -210px;margin-left: 54px;*margin-top: -185px; *margin-left: -40px;"></div>
			<span class="count_pos">你可以输入<span id="counter">500</span>字</span>
		</li>
		<li><textarea onkeydown="countChar('counter','com_content',500);" onkeyup="countChar('counter','com_content',500);" rows="5" id="com_content" cols="50"></textarea></li>
		<li><input id="insert_comment" type="button" value="{$_lang['refer']}" onclick="add_comment();"/><a id="comment_back"  href="javascript:void(0);" style="font-size:12px;display:none;" onclick="back_comment();">{$_lang['commnet_topic']}</a>
		
	</ul>
<div class="clear"></div>
</div>
{/if}
<div id="comment" class="clear">
{if is_array($comment_list)}
<ul class="comment_list" id="comment_list">
{foreach $comment_list as $key=>$value}
	{if !$value['reply_id']}
	<li id="com_{$value['id']}" class="clear" onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});">
		<a name="c{$value['id']}" id="c{$value['id']}"></a>
		<div class="comment-img"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$value['user']['id'],));?>"><img src="{$value['user']['middle_avatar']}"/></a></div>
		<div class="comment-bar">
			<a class="bar-left" target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$value['user']['id'],));?>">{$value['user']['username']}</a>
			<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}"><?php echo hg_show_face($value['content']);?></div>
			<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['middle_avatar']}</div>
			<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
			<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}"><?php echo hg_verify($value['content']);?></div>
{if $_INPUT['id']}
	{code}
		$type = 5;/*视频*/
		$url = SNS_VIDEO.'video_play.php?id='.$cid.'#c'.$value['id'];
	{/code}
{/if}
{if $_INPUT['sta_id']}
	{code}
		$type = 11;/*频道*/
		$url =  SNS_VIDEO.'station_play.php?sta_id='.$cid.'#c'.$value['id'];
	{/code}
{/if}
			<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}">{$url}</div>
			<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">{$type}</div>
			
			<div class="bar-right">
				<span><?php echo hg_get_date($value['create_time']);?></span>
				{if $_user['id']&&$_settings['report']}<a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">{$_lang['report']}</a>{/if}
				<a href="javascript:void(0);" onclick="reply_comment({$value['cid']},{$value['id']},{$value['user']['id']});">回复 </a>
				{if $value['relation']}
					<a href="javascript:void(0);" onclick="del_comment({$value['id']},{$value['cid']},{$type});">删除</a>
				{/if}
			</div>
		</div>
		<div class="comment-con"><?php echo hg_show_face($value['content']);?></div>
		{if is_array($value['reply'])}
		<ul class="reply_list" id="rep_{$value['id']}">
		{foreach $value['reply'] as $k=>$v}
			<li id="com_{$v['id']}" class="clear" onmouseover="report_show({$v['id']},{$v['user']['id']});" onmouseout="report_hide({$v['id']},{$v['user']['id']});">
				<a name="c{$v['id']}" id="c{$v['id']}"></a>
				<div class="comment-img"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$v['user']['id'],));?>"><img src="{$v['user']['middle_avatar']}"/></a></div>
				<div class="comment-bar">
					<a class="bar-left" target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$v['user']['id'],));?>">{$v['user']['username']}</a>
					<div style="display:none;" id="cons_{$v['id']}_{$v['user']['id']}"><?php echo hg_show_face($v['content']);?></div>
					<div style="display:none;" id="ava_{$v['id']}_{$v['user']['id']}">{$v['user']['middle_avatar']}</div>
					<div style="display:none;" id="user_{$v['id']}_{$v['user']['id']}">{$v['user']['username']}</div>
{if $_INPUT['id']}
	{code}
		$type = 5;/*视频*/
		$url = SNS_VIDEO.'video_play.php?id='.$cid.'#c'.$value['id'];
	{/code}
{/if}
{if $_INPUT['sta_id']}
	{code}
		$type = 11;/*频道*/
		$url =  SNS_VIDEO.'station_play.php?sta_id='.$cid.'#c'.$value['id'];
	{/code}
{/if}
			<div style="display:none;" id="url_{$v['id']}_{$v['user']['id']}">{$url}</div>
			<div style="display:none;" id="type_{$v['id']}_{$v['user']['id']}">{$type}</div>					
					<div class="bar-right">
						<span><?php echo hg_get_date($v['create_time']);?></span>
						{if $_user['id']&&$_settings['report']}<a onclick="report_play({$v['id']},{$v['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$v['id']}_{$v['user']['id']}">{$_lang['report']}</a>{/if}
						<a href="javascript:void(0);" onclick="reply_comment({$value['cid']},{$value['id']},{$value['user']['id']});">回复</a>
						{if $v['relation']}
							<a href="javascript:void(0);" onclick="del_comment({$v['id']},{$v['cid']},{$type});">删除</a>
						{/if}
					</div>
				</div>
				<div class="comment-con"><?php echo hg_show_face($v['content']);?></div>
			</li>
		{/foreach}
		</ul>
		{/if}
	</li>
{/if}
{/foreach}
</ul>
{/if}
{$showpages}
</div>