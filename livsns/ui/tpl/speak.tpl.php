<?php
foreach($statusline as $key => $value)
{	
	$user_url = hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $value['member_id']));
	$len = strlen('#' . $keywords . '#');
	$value['text'] = substr(trim($value['text']), $len);
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
	$text_show =hg_match_red(hg_verify($text_show),$keywords);
	$transmit_info=$value['retweeted_status'];
?>
		
		<li>
			<span class="zhibo_huifu"><a href="javascript:void(0);" onclick="disreplyStatus(<?php echo $value['id'];?>, '<?php echo $value['user']['username'];?>');return false;">回复</a></span>
			<a href="<?php echo $user_url;?>" class="zhibo_name" target="_blank"><?php echo $value['user']['username'];?></a>：
			
			<?php 
			if($this->input['is_comment'])
			{
			?> 
			<span class="zhibo_detail" style="color:black;"><?php echo $text;?></span>
			<?php  	
			}
			else 
			{
			?>
			<span class="zhibo_detail" ><?php echo $text;?></span>
			<?php
			}
			?>		
			<span class="zhibo_time"><?php echo hg_get_date($value['create_at']);?></span>
		</li>
<?php 
}
?>
