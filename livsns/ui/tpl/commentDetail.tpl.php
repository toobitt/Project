<?php
/*$Id: commentDetail.tpl.php 3629 2011-04-15 03:24:57Z repheal $*/
?>
<style>
.commbox{width:600px;background:#fff;padding-left:12px;text-align:center;padding-top:10px;}
.commbox .text{clear:both;height:50px;width:500px;line-height:18px;}
.cc{width:600px;background:#a2e0d9;text-align:left;padding-left:13px;fonct-size:13px;height:23px;padding-top:5px;}
.details dl dd{border-bottom: 1px solid #CCCCCC;padding-top:10px;}
.details{width:600px;background:#fff;padding-left:15px;}
</style>
<div class="commbox">
	<div >
		<textarea id="comm_text_<?php echo $status_id;?>" class="text" onkeyup="check_opt(<?php echo $status_id;?>)"></textarea> 
		<div style="font-size: 12px; padding-right: 60px; text-align: left;" onclick="changevalue(<?php echo $status_id;?>)" ><input type="checkbox" style="color:#444;height:auto;width:auto;font-size:12px;" onclick="changevalue(<?php echo $status_id;?>)" value="0" id="transmit_to_mt<?php echo $status_id;?>" name="transmit_to_mt">同时发布到我的点滴</div>
	</div>
	<div style="text-align:right;padding-right:45px;padding-top:8px;padding-bottom:8px;"> 
		<input type="button" id = "commBtn<?php echo $status_id;?>" name="comm_sub" value="<?php echo $this->lang['let_me_comm']?>" onClick="pushAction(<?php echo $status_id;?>)" />
	</div>
</div>

<input type="hidden" name="push_flag" value="0" id="push_flag" />
<input type="hidden" name="num" id="num_status_<?php echo $status_id;?>" value="<?php echo intval($cnum);?>" />
	<input type="hidden" name="blogger" value="<?php echo $this->user['id']?>" />

<div class="cc">评论<span style="padding-right:2px;">共<a id="comm_<?php echo $status_id;?>"><?php echo intval($cnum);?></a>条</span></div>	
<div class="details">
	<dl id="status_item_<?php echo $status_id;?>">  
		<dd style="border-bottom:0;" id="text_<?php echo $status_id;?>"></dd> 
		<?php 
		if(intval($comments_arr[0]) != 0)
		{ 
			$cnum = $comments_arr[0]; 
			unset($comments_arr[0]); 
			foreach($comments_arr  as $key => $value)
			{
			?>
			<dd id="co_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">
				<span style="float:right;">
				<a href="javascript:void(0);" onClick = "replyC(<?php echo $value['id'];?>,<?php echo $value['member_id'];?>,<?php echo $value['status']['id']?>)"><?php echo $this->lang['reply']?></a>&nbsp;&nbsp;<?php if(($userid == $value['member_id']) || ($userid == $value['status']['member_id'])){ ?><a href="javascript:void(0)" onClick="deleteC(<?php echo $value['id'];?>,<?php echo $value['status']['id']?>)" ><?php echo $this->lang['del_comment']?></a><?php }?>
				</span>
				<span id="tips_<?php echo $value['id']?>"><input type="hidden" name="hid" value="" /> </span>
				<a href="<?php echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); ?>"><img src="<?php echo $value['user']['small_avatar']?>" /></a>
				<a href="<?php echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); ?>">
					<?php echo $value['user']['username'];?>
				</a>：<?php echo hg_verify($value['content']);?>
				<span>
					<?php echo hg_get_date($value['create_at']);?>
					<input type="hidden" id="user_<?php echo $value['member_id']?>_<?php echo $value['id'];?>" name="user_<?php echo $value['member_id']?>" value="<?php echo $value['user']['username'];?>" />
				</span>
			</dd> 	
			<?php 
			} ?>
	</dl>
		<?php 
		}?>
</div>