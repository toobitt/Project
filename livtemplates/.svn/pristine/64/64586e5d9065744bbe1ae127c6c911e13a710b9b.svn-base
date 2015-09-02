{foreach $statusline as $key => $value}
	{code}
		$type = 1;
		$text = $value['text'];
		$text_show = hg_verify($value['text']?$value['text']:'暂无');
		$create_time = date('Y-m-d H:i',$value['create_at']);
		$userinfo = $value['user'];
		$avatar = DEFAULT_AVATAR2;
		$user_link = hg_build_href_link(SNS_YOUTH . USER_URL . '?uid=' . $userinfo['id']);
		if($userinfo['host'])
		{
			$avatar = $userinfo['host'] . $userinfo['dir'] . '80x80/'. $userinfo['filepath'] . $userinfo['filename'];
		}
		if(!empty($praise_relation))
		{
			$praise = $praise_relation[$value['id']]['info'][$value['member_id']]['id'];
		}
		$medias = $value['medias'];
		$images = $video = array();
		if(!empty($medias))
		{
			foreach($medias as $km => $vm)
			{
				if($vm['type'])
				{
					
				}
				else
				{
					$images[] = $vm['host'] . $vm['dir'] . '200x/' . $vm['filepath'] . $vm['filename'];
				}
			}
		}
	{/code}
	{if $value['reply_status_id']}
		{code}/*属于专发微博*/
			$type = 2;
			$forward_show = '//@'.$value['user']['username'].' '.$text_show;
			$title = $value['retweeted_status']['text'];
			$status_id = $value['reply_user_id'];
			$transmit_info = $value['retweeted_status'];
		{/code}
	{else}
		{code}
			$forward_show = '';
			$title = $value['text'];
			$status_id = $value['member_id'];
			$transmit_info = array();
		{/code}
	{/if}
	{code}
		switch($type)
		{
			case 1:
	{/code}
	{template:unit/statusline_public_text}
	{code}
				break;
			case 2:
	{/code}
	{template:unit/statusline_transmit}
	{code}
				break;
			default:
				echo "暂无内容";
				break;
		}
	{/code}
{/foreach}

{template:unit/imgcw}