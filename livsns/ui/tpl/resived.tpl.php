<?php
/*$Id: resived.tpl.php 4238 2011-07-28 08:49:35Z lijiaying $*/  
?>
 
<style>
.show_comm{font-size: 12px; padding-top: 3px;}
.show_comm .input1{height: 0;width: 10px}
.show_comm label{font-size:12px;padding-left:2px;}
b.close_comm{ border: 1px solid #CCCCCC; color: #CCCCCC; cursor: pointer; font-size: 12px;padding: 1px 3px;}
</style>

 
<?php if($cnt != 0){?>		 
<p class="ping_quan"><span>共 <strong id="totalSend" ><?php echo intval($cnt);?></strong> 条</span> 
   <input type="checkbox" class="checks" name="sel_all" value=0 onClick="select_all(this,<?php echo $tag;?>);" id="_bot_"/><label for="_bot_"><?php echo $this->lang['selectAll']?>&nbsp; </label>|&nbsp;<a href="javascript:void(0);" onClick="deleteMore(<?php echo $this->user['id'];?>,<?php echo $tag;?>);" ><?php echo $this->lang['del_comment'];?></a></p>
<ul class="ping_list">
<?php
	foreach($sendCommArr as $key => $value){
		if($value['status']['member_id'] == $this->user['id'])
		{   
			$eend = '点滴';
			$flag = 1;
		}
		else
		{ 
			$eend='评论';
			$flag=0;
		}
	?>
	<li id="co_<?php echo $tag;?>_<?php echo $value['id'];?>" class="default" onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" >
		<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $text_show;?></div>
		<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
		<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
		<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['id'];?></div>
		<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">8</div>

	<?php if($flag){?><input type="checkbox" class="ping_checkbox  checks" name="resivedComments[]" value="<?php echo $value['id'];?>" onclick="addThis(this,<?php echo $tag;?>)"/><?php }?>
		<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['member_id'])); ?>" class="ping_img" title="<?php echo $value['user']['username']?>">
			<img class="pic" src="<?php echo $value['user']['middle_avatar'];?>">
		</a> 
		 
		<div class="ping_list_right">
			<p class="title_name"><a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['user']['id'])); ?>"><?php echo $value['user']['username'];?></a><span>：<?php echo hg_verify($value['text'])?> </span> <span class="ping_date">(<?php echo hg_get_date($value['create_at']);?>)</span></p>
			<p class="huifu"><span class="huifu_span" id="speak_<?php echo $value['id'];?>_<?php echo $tag;?>"><?php if($this->user['id']){?><a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>,<?php echo $value['status']['id']?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $this->lang['report'];?></a>&nbsp;<?php }?><a href="javascript:void(0);" onclick="replyComment(<?php echo $value['status']['id']?>,<?php echo $value['id']?>,<?php echo $tag;?>)"><?php echo $this->lang['reply']?></a>&nbsp;<?php if($flag){?><a href="javascript:void(0)" onclick="deleteComment(<?php echo $value['id']?>)"><?php echo $this->lang['del_comment'];?></a>&nbsp;<?php }?></span>
			<span class="ping_date">回复了我的<?php echo $eend;?>：<a href="<?php echo hg_build_link('show.php' , array('id' => $value['status']['id'])); ?>" ><?php if($flag){echo hg_show_face($value['status']['text']);}else{ echo hg_show_face($value['reply_comment_text']);} ?></a></span>
			<input type="hidden" name="commids" value="<?php echo $value['id'];?>" id="commids_<?php echo $value['id']?>_<?php echo $tag;?>" /> 
			<input type="hidden" id="rp_<?php echo $value['id'] . '_' . $tag;?>" name="replyUser" value="<?php echo $value['user']['id'] . '_' . $value['user']['username'];?>" />
			<input type="hidden" name="myself" value="<?php echo $this->user['id'] . '_' . $this->user['username'] . '_' . $value['status']['id'];?>" />
			</p>
			
<!--			<p><span class="ping_date">5分钟前 来自IPHONE</span></p>-->
		</div>
		<br class="clear">
	</li>
<?php }?>
</ul>
<p class="ping_quan pingbg"><input type="checkbox" class="checks" name="sel_all" value=0 onClick="select_all(this,<?php echo $tag;?>);" id="_bot_"/><label for="_bot_"><?php echo $this->lang['selectAll']?>&nbsp; </label>| &nbsp;<a href="javascript:void(0);" onClick="deleteMore(<?php echo $this->user['id'];?>,<?php echo $tag;?>);" ><?php echo $this->lang['del_comment'];?></a></p>
 <?php }else{ hg_show_null(' ','暂无回复');}?>
 <input type="hidden" value="" id="sendStr_<?php echo $tag;?>" name="count_comm" />

 
	









































