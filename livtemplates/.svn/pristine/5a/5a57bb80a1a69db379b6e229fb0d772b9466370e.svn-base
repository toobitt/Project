<li class="list clear"  id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" style="margin-top:0;padding:1px 0;">
	<span class="left">		
		<a class="lb" name="alist[]">
			<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" />
		</a>
		{code}
			$avatar = '';
			if ($v['avatar'])
			{
				$avatar = $v['avatar']['host'].$v['avatar']['dir'].'40x30/'.$v['avatar']['filepath'].$v['avatar']['filename'];
			}
		{/code}
		<a class="fl" style="width:40px">
			<em><img alt="" src="{$avatar}"></em>
		</a>
	</span>
	<span class="right" style="width: 520px">
		<a class="fb" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>									
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>	
		{code}
			$sex = '';
			if ($_configs['staff_sex'])
			{
				$sex = $_configs['staff_sex'][$v['sex']];
			} 
		{/code}
		<a class="fl" ><em>{$sex}</em></a>
		<a class="fl"><em>{$v['department']}</em></a>
		<a class="fl"><em>{$v['position']}</em></a>	
		{code}
			$status = '';
			if ($_configs['staff_status'])
			{
				$status = $_configs['staff_status'][$v['status']];
			} 
		{/code}
		<a class="fl"><em id="status_{$v['id']}">{$status}</em></a>
		<a class="tjr">
			<em>{$v['user_name']}</em>
			<span>{$v['update_time']}</span>
		</a>
	</span>
	<span class="title overflow" >
		<a href="javascript:void(0);" style="display:block;"><span onclick="hg_show_opration_info({$v['id']})" class="m2o-common-title">{$v['name']}</span></a>
	</span>
</li>