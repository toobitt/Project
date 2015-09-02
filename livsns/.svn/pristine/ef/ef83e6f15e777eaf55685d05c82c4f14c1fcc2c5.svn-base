<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
<div class="news-latest">
<?php echo hg_advert('index_1');?>
	<div class="tp"></div>
	<div class="md"><img style="display:none;" id="new_img" src="./res/img/loading.gif"/><a style="display:none;" id="new_st" href="javascript:void(0);" onclick="new_status(<?php echo hg_get_cookie('since_id');?>);">有新点滴，点击查看</a></div>
</div>
<ul id="list" class="list clear">
<?php
foreach($statusline as $key => $value)
{
	$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
	$text = hg_verify($value['text']);
	$text_show = '：'.($value['text']?$value['text']:$this->lang['forward_null']);
	if($value['reply_status_id'])
	{
		$forward_show = '//@'.$value['user']['username'].' '.$text_show;
		$title = $this->lang['forward_one'].$value['retweeted_status']['text'];
		$status_id = $value['reply_user_id'];
	}
	else
	{
		$forward_show = '';
		$title = $this->lang['forward_one'].$value['text'];
		$status_id = $value['member_id'];
	}
	$text_show = hg_verify($text_show);
	$transmit_info=$value['retweeted_status'];
?>
	<li class="clear" id="mid_<?php echo $value['id'];?>"  onmouseover="report_show(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" onmouseout="report_hide(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);">
		<div style="display:none;" id="cons_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $text_show;?></div>
		<div style="display:none;" id="ava_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['small_avatar'];?></div>
		<div style="display:none;" id="user_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo $value['user']['username'];?></div>
		<div style="display:none;" id="url_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>"><?php echo SNS_MBLOG.'show.php?id='.$value['id'];?></div>
		<div style="display:none;" id="type_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">3</div>
		
		<div class="blog-content">
			<p class="subject clear"><a href="<?php echo SNS_UCENTER.$user_url;?>"><?php echo $value['user']['username'];?></a>
		<?php
		echo $text_show."<br/>";
				?>		
		</p>
<?php include hg_load_template('statusline_content');?>			
			<div class="speak clear">
				<div class="hidden" id="t_<?php echo $value['id'];?>"><?php echo hg_verify($title);?></div>
				<div class="hidden" id="f_<?php echo $value['id'];?>"><?php echo $forward_show;?></div>
					<span id = "<?php echo "fa".$value['id']?>" style="position:relative;">
					<?php
					if($value['user']['id'] == $user_info['id'])
					{
					?>
					<a href="javascript:void(0);" onclick="unfshowd(<?php echo $value['id']?>)"><?php echo $this->lang['delete'];?></a>|	
					<?php
					}
					?>	
					<a href="javascript:void(0);" onclick="OpenForward('<?php echo $value['id']?>','<?php echo $status_id;?>')"><?php echo $this->lang['forward'].'('.($value['transmit_count']+ $value['reply_count']).')'?></a>|
					<a  id="<?php echo "fal".$value['id']?>" href="javascript:void(0);" onclick="favorites('<?php echo $value['id']?>','<?php echo $this->user['id'];?>')"><?php echo $this->lang['collect'];?></a>|
					<a href="javascript:void(0);" onclick="getCommentList(<?php echo $value['id']?>,<?php echo $this->user['id']?>)"><?php echo $this->lang['comment'];?>(<span id="comm_<?php echo $value['id']?>"><?php echo $value['comment_count'];?></span>)</a>
				</span>
				<input class="timestamp" type="hidden" value="<?php echo $value['create_at']; ?>" />
				<strong class="publishtime"><?php echo hg_get_date($value['create_at']);?></strong>
				<strong class="overflow" style="max-width:230px"><?php echo $this->lang['source'].$value['source'];?></strong>
				<a onclick="report_play(<?php echo $value['id'];?>,<?php echo $value['user']['id'];?>);" href="javascript:void(0);" style="display:none;" id="re_<?php echo $value['id'];?>_<?php echo $value['user']['id'];?>">
				<?php echo $this->lang['report'];?></a>				
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_<?php echo $value['id']?>" value="<?php echo $value['comment_count']?>"/>
			<div id="comment_list_<?php echo $value['id'];?>"></div>
		</div> 
		<a href="<?php echo SNS_UCENTER.$user_url;?>">
		<img src="<?php echo $value['user']['middle_avatar'];?>"/>
		</a>
	</li>
<?php
}
?>
 <li class="more"><?php echo $showpages;?></li>
 </ul>