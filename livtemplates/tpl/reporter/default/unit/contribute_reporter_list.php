<li class="list clear"  id="r_{$v['id']}" name="{$v['id']}"  _id="{$v['id']}" order_id="{$v['order_id']}" style="margin-top:0;padding:1px 0;">
	<span class="left">		
		<a class="lb" name="alist[]">
			<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" />
		</a>
		{code}
			$avatar = '';
			$ori_avatar = '';
			if ($v['avatar'])
			{
				$ori_avatar = $v['avatar']['host'].$v['avatar']['dir'].$v['avatar']['filepath'].$v['avatar']['filename'];
				$avatar = $v['avatar']['host'].$v['avatar']['dir'].'40x30/'.$v['avatar']['filepath'].$v['avatar']['filename'];
			}
		{/code}
		<a class="fl"  style="width:40px">
			<em><img alt="" src="{$avatar}"></em>
		</a>
	</span>
	<span class="right" style="width: 700px">
		<a class="fb" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>									
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>	
		<a class="fl" style="width: 100px"><em>{$v['name']}</em></a>
		{code}
			$sex = '';
			if ($_configs['reporter_sex'])
			{
				$sex = $_configs['reporter_sex'][$v['sex']];
			} 
		{/code}
		<a class="fl" ><em>{$sex}</em></a>
		<a class="fl" style="width: 100px"><em>{$v['tel']}</em></a>
		<a class="fl" style="width: 100px"><em>{$v['email']}</em></a>	
		{code}
			$status = '';
			if ($_configs['reporter_status'])
			{
				$status = $_configs['reporter_status'][$v['status']];
			} 
		{/code}
		<a class="fl"><em id="status_{$v['id']}">{$status}</em></a>
		<a class="tjr">
			<em>{$v['user_name']}</em>
			<span>{$v['update_time']}</span>
		</a>
	</span>
	<span class="title overflow" >
		<a href="javascript:void(0);" style="display:block;"><span onclick="hg_show_opration_info({$v['id']})">{$v['account']}</span></a>
	</span>
</li>