<!--
修改发布策略数据时 请求的广告位参数和效果参数 此时合并所有参数是合并在一起的
getadvpara方法
-->
<ul class="publish_para clear">
	{if !$formdata['__tpl__']}
	{foreach $formdata as $k=>$v}
		<li style="float:right"><a class="g" href="javascript:void(0)" onclick="hg_get_advsettings('run.php?mid={$_INPUT['mid']}&a=advanced_settings&pos_id={$k}&id={$id}&groupflag={$_INPUT['groupflag']}', '{$id}')">高级</a></li>
		{foreach $v[0] as $kk=>$vv}
			{code}
				$hg_attr['text'] = $vv;
			{/code}
			{template:unit/para_setting, $kk[$id], $hg_value, $v[1][$kk]}
		{/foreach}
		{if $id && $_INPUT['ani_id']}
			<li>
			<div class="form_ul_div">
				<input type="hidden" name="ani_id[{$id}]" class="pos_input" value="{$_INPUT['ani_id']}">
			</div>
			</li>
		{/if}
	{/foreach}
	{else}
		<li style="float:right"><a class="g" href="javascript:void(0)" onclick="hg_get_advsettings('run.php?mid={$_INPUT['mid']}&a=advanced_settings&pos_id={$k}&id={$id}&groupflag={$_INPUT['groupflag']}', '{$id}')">高级</a></li>
		{template:unit/select_animation}
	{/if}
</ul>