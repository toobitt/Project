<li class="list clear"  id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" style="margin-top:0;padding:1px 0;">
	<span class="left">		
		<a class="lb" name="alist[]">
			<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" />
		</a>
	</span>
	<span class="right" style="width: 280px">
		<a class="fb" onclick="hg_edit_records({$v['id']},{$v['interview_id']})" href="javascript:void(0)"><em class="b2"></em></a>									
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>	
		<a class="fl" style="text-align: center" id="pub_{$v['id']}">
			<em>
				{if $v['is_pub']}
				<span style="color:green" onclick="hg_con_pub({$v['id']},{$v['is_pub']})">已发布</span>
				{else}
				<span style="color:red" onclick="hg_con_pub({$v['id']},{$v['is_pub']})">未发布</span>
				{/if}
			</em>
		</a>
		<a class="tjr">
			<em>{$v['user_name']}</em>
			<span>{$v['time']}</span>
		</a>
	</span>
	<span class="title overflow" id="td_{$v['id']}">
		<a href="javascript:void(0);" style="display:block;"><span class="m2o-common-title">{$v['question']}</span></a>
	</span>
</li>