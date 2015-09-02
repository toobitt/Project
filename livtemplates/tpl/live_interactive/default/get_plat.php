{code}
	$plat = $formdata['plat'];
	$channel_info = $formdata['channel_info'];
{/code}
<div id="plat_box" class="plat_box">
	<ul class="ul">
		<li class="li b_0">
			<div class="t_p_name fl"><span>平台名称</span></div>
			<div class="t_t_name fl"><span>平台类型</span></div>
			<div class="t_auth_box fl"><span>添加授权</span></div>
			<!-- <div class="t_c_channel fl">选择频道</div> -->
			<div class="clear"></div>
		</li>
	{if $plat}
		{foreach $plat AS $k => $v}
			{code}
				$plat_id = $v['id'];
				$picurl = '';
				if ($v['picurl'])
				{
					$picurl = $v['picurl']['host'] . $v['picurl']['dir'] .'80x60/'. $v['picurl']['filepath'] . $v['picurl']['filename'];
				}
			{/code}
		<li class="li" id="li_{$plat_id}">
			<div class="p_img fl"><img src="{$picurl}" /></div>
			<div class="p_name fl"><span>{$v['name']}</span></div>
			<div class="t_name fl"><span>{$v['type_name']}</span></div>
			<div class="auth_box fl"><span onclick="hg_oauthlogin({$plat_id},{$v['type']});">点击添加授权</span></div>
			<!--
<div class="c_channel fr">
				{code}
				
					$attr_channel = array(
						'class' => 'down_list',
						'show' => 'channel_show_'.$plat_id,
						'width' => 100,/*列表宽度*/
						'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						'is_sub'=> 1,
					);
					
					$_INPUT['channel_id'] = $_INPUT['channel_id']?$_INPUT['channel_id']:-1;
					$_channel[-1] = '所有频道';
					if (!empty($channel_info))
					{
						foreach($channel_info AS $kk =>$vv)
						{
							$_channel[$vv['id']] = $vv['name'];
						}
					}
					
				{/code}
				{template:form/search_source,channel_id_$plat_id,$_INPUT['channel_id'],$_channel,$attr_channel}
			</div>
-->
			<div class="clear"></div>
		</li>
		{/foreach}
	{/if}
	</ul>
</div>