<?php
/*$Id: commentDetail.tpl.php 3009 2011-03-23 00:59:35Z repheal $*/
?>
<style>
.commbox{width:565px;text-align:center;padding-top:10px;}
.commbox .text{clear:both;
border:1px solid #AAADB2;
height:25px;
margin-top:10px;
width:548px;}
.cc{width:548px;background:#BDDAFA;text-align:left;padding-left:13px;fonct-size:13px;height:23px;padding-top:5px;}
.details dl dd{border-bottom: 1px solid #CCCCCC;padding-top:10px;padding-bottom:10px;}
.details{width:560px;background:#fff;}
.details a img{padding:2px;border:1px solid #ccc;}
</style>
<?php 
if($this->user['id'])
{
?>
<div class="commbox">
	<div >
		<input id="comm_text_<?php echo $status_id;?>" class="text" onkeyup="check_opt(<?php echo $status_id;?>)" />
		<div style="font-size:12px;text-align:right;line-height: 23px;margin-top:10px;margin-bottom:10px;position: relative;">
		<a  onclick="global_face('comm_text_<?php echo $status_id;?>','com_face_<?php echo $status_id;?>');" href="javascript:void(0);" class="choiceface">
			<img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif">
		</a>
		<span onclick="changevalue(<?php echo $status_id;?>)" style="padding:7px 0px;font-size:12px;cursor:pointer;">
		<?php 
		$checked = $this->settings['default_sync']['comm_main']?' checked':'';
		?>
		<input onclick="changevalue(<?php echo $status_id;?>)" type="checkbox" style="color:#444;height:auto;width:auto;font-size:12px;margin-top:10px;vertical-align: top;" value="0" id="transmit_to_mt<?php echo $status_id;?>" name="transmit_to_mt" <?php echo $checked;?>>&nbsp;同时发布到我的点滴&nbsp;</span>
		<div id="com_face_<?php echo $status_id;?>" class="face_content" style="position: absolute; visibility: visible; top: 25px; left: 300px;display:none;text-align: left;"></div>
		<input type="button" style="height:28px;" id = "commBtn<?php echo $status_id;?>" name="comm_sub" value="<?php echo $this->lang['let_me_comm']?>" onClick="pushAction(<?php echo $status_id;?>)" /></div>		
	</div>
</div>
<?php 
}?>

<input type="hidden" name="push_flag" value="0" id="push_flag" />
<input type="hidden" name="num" id="num_status_<?php echo $status_id;?>" value="<?php echo intval($cnum);?>" />
	<input type="hidden" name="blogger" value="<?php echo $this->user['id']?>" />

<div class="cc">评论<span style="padding-right:2px;">共<span id="comm_<?php echo $status_id;?>"><?php echo intval($comments_arr[0]);?></span>条</span></div>	
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
			<dd id="co_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>" onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">
				<a name="<?php echo $value['id'];?>" id="<?php echo $value['id'];?>"></a>
				<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo hg_verify($value['content']);?></div>
				<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
				<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
				<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['status']['id'] .'#'.$value['id'];?></div>
				<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">8</div>
				
				<span style="float:right;">
				<?php if($this->user['id']){?><a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $this->lang['report'];?></a>&nbsp;<a href="javascript:void(0);" onClick = "replyC(<?php echo $value['id'];?>,<?php echo $value['member_id'];?>,<?php echo $value['status']['id']?>)"><?php echo $this->lang['reply']?></a>&nbsp;<?php }?><?php if(($userid == $value['member_id']) || ($userid == $value['status']['member_id'])){ ?><a href="javascript:void(0)" onClick="deleteC(<?php echo $value['id'];?>,<?php echo $value['status']['id']?>)" ><?php echo $this->lang['del_comment']?></a>&nbsp;<?php }?></span>
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