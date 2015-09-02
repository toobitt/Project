<?php
/*$Id: send.tpl.php 4238 2011-07-28 08:49:35Z lijiaying $*/
?> 
<?php 	if($cnt != 0){?>
<p class="ping_quan"><span>共<strong id="totalSend" ><?php echo intval($cnt);?></strong>条</span>
	  <input type="checkbox" name="sel_all" value=0 class="checks" onClick="select_all(this,<?php echo $tag;?>);" id="_top_" />&nbsp;<label for="_top_" style="cursor:pointer;"><?php echo $this->lang['selectAll']?></label>&nbsp; | &nbsp;<a href="javascript:void(0);" onClick="deleteMore(<?php echo $this->user['id'];?>,<?php echo $tag;?>);" ><?php echo $this->lang['del_comment'];?></a></p>
<ul class="ping_list">		
<?php
		foreach($sendCommArr as $key => $value){
			if($value['reply_comment_id'] != 0)
			{ 
				$begin = '回复了';
				$eend = '评论';
				$flag = 0;
			}
			else
			{
				$begin = '评论了';
				$eend='点滴';
				$flag=1;
			} 
	?>
			<li class="default" id="co_<?php echo $tag;?>_<?php echo $value['id'];?>" onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);"> 
				<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $text_show;?></div>
				<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
				<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
				<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['id'];?></div>
				<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">8</div>

				<input type="checkbox" class="ping_checkbox  checks" name="sendComments[]" value="<?php echo $value['id']?>" onclick="addThis(this,<?php echo $tag;?>);"/> 
				<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['status']['user']['id'])); ?>"  class="ping_img" title="<?php echo $value['status']['user']['username'];?>">
					<img src="<?php echo $value['status']['user']['middle_avatar'];?>">
				</a>
				<div class="ping_list_right">
					<p class="title_name">
						<span><?php echo hg_verify($value['text'])?></span> <span class="ping_date">(<?php echo hg_get_date($value['create_at']);?>)</span>
						<input type="hidden" name="commids" value="<?php echo $value['id'];?>" id="commids_<?php echo $value['id']?>_<?php echo $tag;?>" />
					</p>
					<p class="huifu" id="confirm_<?php echo $value['id'];?>"><span class="huifu_span"><?php if($this->user['id']){?><a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>,<?php echo $value['status']['id']?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $this->lang['report'];?></a>&nbsp;<?php }?><a href="javascript:void(0);" onClick="deleteComment(<?php echo $value['id'];?>,<?php echo $tag;?>);" ><?php echo $this->lang['del_comment']?></a></span>
						<span class="ping_date"><?php echo $begin ?><a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['status']['user']['id'])); ?>"><?php echo  $value['status']['user']['username'] ;?></a><?php echo $eend;?>:<a href="<?php echo hg_build_link('show.php' , array('id' => $value['status']['id'])); ?>" >
						<?php echo $text = ($flag == 1) ? hg_show_face($value['status']['text']) : hg_show_face($value['reply_comment_text']);?>
						</a></span>
					</p>  
				</div>
				<br class="clear" />
			</li>
		 <?php }?>
		</ul>
		<p class="ping_quan pingbg">
		 <input type="checkbox" name="sel_all" value=0 class="checks" onClick="select_all(this,<?php echo $tag;?>);" id="_bot_" />&nbsp;<label for="_bot_" style="cursor:pointer;"><?php echo $this->lang['selectAll']?></label>&nbsp; | &nbsp;<a href="javascript:void(0);" onClick="deleteMore(<?php echo $this->user['id'];?>,<?php echo $tag;?>);" ><?php echo $this->lang['del_comment'];?></a></p>
  		<?php }else{ hg_show_null(' ','暂无评论');}?>
 	<input type="hidden" value="" id="sendStr_<?php echo $this->input['t'];?>" name="count_comm" />
