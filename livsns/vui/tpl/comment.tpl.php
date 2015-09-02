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
<?php include hg_load_template('report');?>
<?php  
if($this->user['id'])
{?>
<div id="comment_note" class="clear">
	<input type="hidden" id="com_cid" value="<?php echo $cid?$cid:0;?>"/>
	<input type="hidden" id="com_reply_id" value="<?php echo $reply_id?$reply_id:0;?>"/>
	<input type="hidden" id="com_reply_user_id" value="<?php echo $reply_user_id?$reply_user_id:0;?>"/>
	<input type="hidden" id="com_type" value="<?php echo $type?$type:0;?>"/>
	<ul class="comment_notes clear">
		<li>
			<span><?php echo $this->lang['commnet'];?></span>
			<span>
				<a id="choiceface" href="javascript:void(0);" class="choiceface">
					<img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif">
				</a>				
			</span>
			<div id="face" class="face_content" style="position: absolute; display: none; visibility: visible; margin-top: -210px;margin-left: 54px;*margin-top: -185px; *margin-left: -40px;"></div>
			<span class="count_pos">你可以输入<span id="counter">500</span>字</span>
		</li>
		<li><textarea onkeydown="countChar('counter','com_content',500);" onkeyup="countChar('counter','com_content',500);" rows="5" id="com_content" cols="50"></textarea></li>
		<li><input id="insert_comment" type="button" value="<?php echo $this->lang['refer'];?>" onclick="add_comment();"/><a id="comment_back"  href="javascript:void(0);" style="font-size:12px;display:none;" onclick="back_comment();"><?php echo $this->lang['commnet_topic'];?></a>
		<?php 
		$checked = $this->settings['default_sync']['comm']?' checked':'';
		?>
		<input type="checkbox" id="syn" <?php echo $checked;?>/>
		<label for="syn">同时发布到我的点滴</label></li>
	</ul>
<div class="clear"></div>
</div>
<?php 
}?>
<div id="comment" class="clear">
<?php
if(is_array($comment_list))
{
//	hg_pre($comment_list);
	?>
<ul class="comment_list" id="comment_list">
<?php 
foreach($comment_list as $key=>$value)
{
	if(!$value['reply_id'])
	{
	?>
	<li id="com_<?php echo $value['id'];?>" class="clear" onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">
		<a name="c<?php echo $value['id'];?>" id="c<?php echo $value['id'];?>"></a>
		<div class="comment-img"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$value['user']['id'],));?>"><img src="<?php echo $value['user']['middle_avatar'];?>"/></a></div>
		<div class="comment-bar">
			<a class="bar-left" target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$value['user']['id'],));?>"><?php echo $value['user']['username'];?></a>
			<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo hg_show_face($value['content']);?></div>
			<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['middle_avatar'];?></div>
			<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
			<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo hg_verify($value['content']);?></div>
<?php 
if($this->input['id'])
{
	$type = 5;//视频
	$url = SNS_VIDEO.'video_play.php?id='.$cid.'#c'.$value['id'];
}
if($this->input['sta_id'])
{
	$type = 11;//频道
	$url =  SNS_VIDEO.'station_play.php?sta_id='.$cid.'#c'.$value['id'];
}
?>
			<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $url;?></div>
			<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $type;?></div>
			
			<div class="bar-right">
				<span><?php echo hg_get_date($value['create_time']);?></span>
				<?php if($this->user['id']){?><a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $this->lang['report'];?></a><?php }?>
				<a href="javascript:void(0);" onclick="reply_comment(<?php echo $value['cid'];?>,<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">回复 </a>
				<?php if($value['relation'])
				{?>
					<a href="javascript:void(0);" onclick="del_comment(<?php echo $value['id'];?>,<?php echo $value['cid'];?>,<?php echo $type;?>);">删除</a>
				<?php 
				}?>
			</div>
		</div>
		<div class="comment-con"><?php echo hg_show_face($value['content']);?></div>
		<?php 
		if(is_array($value['reply']))
		{?>
		<ul class="reply_list" id="rep_<?php echo $value['id'];?>">
		<?php	
			foreach($value['reply'] as $k=>$v)
			{
		?>
			<li id="com_<?php echo $v['id'];?>" class="clear" onmouseover="report_show(<?php echo $v['id'];?>,<?php echo $v['user']['id'];?>);" onmouseout="report_hide(<?php echo $v['id'];?>,<?php echo $v['user']['id'];?>);">
				<a name="c<?php echo $v['id'];?>" id="c<?php echo $v['id'];?>"></a>
				<div class="comment-img"><a target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$v['user']['id'],));?>"><img src="<?php echo $v['user']['middle_avatar'];?>"/></a></div>
				<div class="comment-bar">
					<a class="bar-left" target="_blank" href="<?php echo hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$v['user']['id'],));?>"><?php echo $v['user']['username'];?></a>
					<div style="display:none;" id="cons_<?php echo $v['id'];?>_<?php echo $v['user']['id'];?>"><?php echo hg_show_face($v['content']);?></div>
					<div style="display:none;" id="ava_<?php echo $v['id'];?>_<?php echo $v['user']['id'];?>"><?php echo $v['user']['middle_avatar'];?></div>
					<div style="display:none;" id="user_<?php echo $v['id'];?>_<?php echo $v['user']['id'];?>"><?php echo $v['user']['username'];?></div>
<?php 
if($this->input['id'])
{
	$type = 5;//视频
	$url = SNS_VIDEO.'video_play.php?id='.$cid.'#c'.$value['id'];
}
if($this->input['sta_id'])
{
	$type = 11;//频道
	$url =  SNS_VIDEO.'station_play.php?sta_id='.$cid.'#c'.$value['id'];
}
?>
			<div style="display:none;" id="url_<?php echo $v['id'];?>_<?php echo $v['user']['id'];?>"><?php echo $url;?></div>
			<div style="display:none;" id="type_<?php echo $v['id'];?>_<?php echo $v['user']['id'];?>"><?php echo $type;?></div>					
					<div class="bar-right">
						<span><?php echo hg_get_date($v['create_time']);?></span>
						<?php if($this->user['id']){?><a onclick="report_play(<?php echo $v['id'];?>,<?php echo $v['user']['id'];?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $v['id'];?>_<?php echo $v['user']['id'];?>"><?php echo $this->lang['report'];?></a><?php }?>
						<a href="javascript:void(0);" onclick="reply_comment(<?php echo $value['cid'];?>,<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">回复</a>
						<?php if($v['relation'])
						{?>
							<a href="javascript:void(0);" onclick="del_comment(<?php echo $v['id'];?>,<?php echo $v['cid'];?>,<?php echo $type;?>);">删除</a>
						<?php 
						}?>
					</div>
				</div>
				<div class="comment-con"><?php echo hg_show_face($v['content']);?></div>
			</li>
		<?php 	
			}	?>
		</ul>
		<?php 	
		}?>
	</li>
<?php
	} 
}?>
</ul>
<?php 
}?>
<?php echo $showpages;?>
</div>