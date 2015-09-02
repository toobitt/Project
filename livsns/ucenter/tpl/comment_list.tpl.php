<input type="hidden" name="status_id" value="<?php echo $status_id;?>" id="mainStatus" />
<input type="hidden" name="status_user_id" value="<?php echo $status_id;?>" id="mainUser_<?php echo $status_id;?>_<?php echo $user_info['id'];?>" />

<div class="comment-content clear" >
<span class="triangle">&nbsp;</span>
<div class="top"></div>
<div class="middle clear">
<dl id="status_item_<?php echo $status_id;?>">
<dt><a><span onClick="closeComm(<?php echo $status_id;?>);"></span></a></dt>
<dd class="text" id="text_<?php echo $status_id;?>">
	<input type="hidden" name="num" id="num_status_<?php echo $status_id;?>" value="<?php echo intval($comments_arr[0]);?>" />
	<a  onclick="global_face('comm_text_<?php echo $status_id;?>','com_face_<?php echo $status_id;?>');" href="javascript:void(0);" class="choiceface">
		<img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif">
	</a>
	<div id="com_face_<?php echo $status_id;?>" class="face_content" style="position: absolute; visibility: visible; top: 55px; left: 11px;display:none;"></div>
	<input class="txt" name="comm_text_<?php echo $status_id;?>" type="text" id="comm_text_<?php echo $status_id;?>" onkeyup="check_opt(<?php echo $status_id;?>);" />
	<input type="hidden" name="push_flag" value="0" id="push_flag" />
	<input type="button" id = "commBtn<?php echo $status_id;?>" name="comm_sub" value="<?php echo $this->lang['let_me_comm']?>" onClick="pushAction(<?php echo $status_id;?>)"/>
	<div style="font-size: 12px; padding-right: 60px; text-align: left;" onclick="changevalue(<?php echo $status_id;?>)" >
	<?php 
		$checked = $this->settings['default_sync']['comm_list']?' checked':'';
	?>
	<input type="checkbox" style="color:#444;height:auto;width:auto;font-size:12px;" onclick="changevalue(<?php echo $status_id;?>)" value="0" id="transmit_to_mt<?php echo $status_id;?>" name="transmit_to_mt" <?php echo $checked;?>>同时转发到我的点滴</div>
</dd>
<?php if(intval($comments_arr[0]) != 0){ $itemnum = 1;$num = intval($comments_arr[0]); unset($comments_arr[0]);?>
<?php foreach($comments_arr  as $key => $value){?>
<dd id="co_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>" onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">
	<a name="<?php echo $value['id'];?>" id="<?php echo $value['id'];?>"></a>
	<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo hg_verify($value['content']);?></div>
	<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
	<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
	<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['status']['id'] .'#'.$value['id'];?></div>
	<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">8</div>
	
	<span style="float:right;">
		<?php if($this->user['id']){?><a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $this->lang['report'];?></a>&nbsp;<?php }?><a href="javascript:void(0);" onClick = "replyC(<?php echo $value['id'];?>,<?php echo $value['member_id'];?>,<?php echo $value['status']['id']?>)"><?php echo $this->lang['reply']?></a>&nbsp;<?php if(($userid == $value['member_id']) || ($userid == $value['status']['member_id'])){ ?><a href="javascript:void(0)" onClick="deleteC(<?php echo $value['id'];?>,<?php echo $value['status']['id']?>)" ><?php echo $this->lang['del_comment']?></a>&nbsp;<?php }?>
	</span>
	<div id="tips_<?php echo $value['id']?>"><input type="hidden" name="hhid" value="" /></div>
		
	<a href="<?php echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); ?>"><img src="<?php echo $value['user']['small_avatar'];?>" align="middle"/></a>

	<a href="<?php echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); ?>">
		<?php echo $value['user']['username'];?>
	</a>：<?php echo hg_verify($value['content']);?>
	<span>
		<?php echo hg_get_date($value['create_at']);?>
		<input type="hidden" id="user_<?php echo $value['member_id']?>_<?php echo $value['id'];?>" name="user_<?php echo $value['member_id']?>" value="<?php echo $value['user']['username'];?>" />
	</span> 
</dd>
<?php
		if($this->input['ajax'])
		{
			$itemnum++;
			if($itemnum>10)
			{
				break;
			}
		}
	}
}?> 
<?php if($num > 10){?>
<dd class="all" id="tips_<?php echo $status_id;?>"><a href="<?php echo hg_build_link('show.php' , array('id' => $status_id)); ?>">查看全部<strong id="numTips_<?php echo $status_id;?>"><?php echo (!$num) ? 0 : $num;?></strong>次评论</a>
<?php }?>
<input type="hidden" name="blogger" value="<?php echo $this->user['id'];?>" />
</dd>

</dl>
</div>
<div class="bottom"></div> 
</div>  
