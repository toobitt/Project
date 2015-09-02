{code}
	$__call_column_count  = $hg_attr['_callcounter'] ? $hg_attr['_callcounter'] : intval($__call_column_count);
	$__hg_Pre = 'hgCounter_'.$__call_column_count.'_';
	$hg_value = $hg_value ? (is_array($hg_value) ? implode(',', $hg_value) : $hg_value) : array();
	$hg_attr['slidedown'] = $hg_attr['slidedown'] ? 1 : 0;
	if (!$hg_attr['multiple'])
	{
		$inputtype = 'radio';
		$hg_multiple_suffix = '';
	}
	else
	{
		$inputtype = 'checkbox';
		$hg_multiple_suffix = '[]';
	}
	if(!$hg_attr['title'])
	{
		$hg_attr['title'] = '发布至';
	}
	if(!$hg_data)
	{
		if(!class_exists('column'))
		{
			include_once(ROOT_DIR . 'lib/class/column.class.php');
		}
		if(!$publish)
		{
			$publish = new column();
		}
		$hg_col_type = $publish->get_col_type($hg_attr['type']);
		$hg_attr['fid'] = $hg_attr['fid'] ? $hg_attr['fid'] : 0;
		$hg_attr['colid'] = $hg_attr['colid'] ? $hg_attr['colid'] : 0;
		$hg_attr['siteid'] = $hg_attr['siteid'] ? $hg_attr['siteid'] : 1;
		$hg_attr['exclude'] = $hg_attr['exclude'] ? $hg_attr['exclude'] : array();
		$_exclude = $hg_attr['exclude'] ? "_exclude=".$_INPUT['id'] : '';
		$hg_data = $publish->getdefaultcol($hg_attr['colid'], $hg_attr['fid'], 1,$hg_attr['siteid'], $hg_attr['exclude']);
		$hg_attr['request_url'] = 'fetch_column_node.php?'.$_exclude;
		/*开启多站点*/
		if($hg_attr['multiple_site'])
		{
			$hg_sites = $publish->getallsites();
			if(count($hg_sites) == 1)
			{
				$hg_attr['multiple_site'] = 0;
			}
		}
	}
	if($hg_value)
	{
		/*
			编辑时代表选中的栏目
			栏目ID=>栏目名称
		*/
		$hg_value = $publish->get_selected_col($hg_value);
	}
{/code}
<script type="text/javascript">
	if(typeof hg_itoggle != 'object')
	{
		var hg_itoggle = {};
	}
	hg_itoggle['{$__hg_Pre}'] = 0;
	if(typeof ghasChangedColor != 'object')
	{
		var ghasChangedColor = {};
	}
	ghasChangedColor['{$__hg_Pre}'] = [];
	if(typeof gCurrentlist != 'object')
	{
		var gCurrentlist = {};
	}
	gCurrentlist['{$__hg_Pre}'] = 1;
	if(typeof gColTempFid != 'object')
	{
		var gColTempFid = {};
	}
	gColTempFid['{$__hg_Pre}'] = 0;
</script>
{if $hg_attr['slidedown']}
<script type="text/javascript">
	hg_itoggle['{$__hg_Pre}'] = 1;
</script>
{/if}
<div class="info_all_node clear">
<!--多站点切换-->
{if $hg_attr['multiple_site']}
<div class="info_top clear">
{code}
			/*select样式*/
			$site_style = array(
			'class' => 'down_list i',
			'show' => $__hg_Pre.'site_ul',
			'width' => 95,
			'state' => 0,
			'is_sub'=>1,
			'onclick'=>"hg_change_site('$__hg_Pre')",
			);
			$_default_site =  $hg_attr['siteid'];
		{/code}
		{template:form/search_source,$__hg_Pre.siteid,$_default_site,$hg_sites,$site_style}
</div>
{/if}
<div class="info_show">
	<ul class="part clear">
		<li id="{$__hg_Pre}show" class="show{if $hg_attr['slidedown']}_move{/if}" onmousemove="hg_show_mousemove('{$__hg_Pre}');" onmouseout="hg_show_mouseout('{$__hg_Pre}');">
		   <span class={if $hg_attr['multiple_site']}"col_sort l"{else}"col_sort"{/if} onmouseover=hg_get_coltype('{$__hg_Pre}') onmouseout="$('#{$__hg_Pre}coltype').hide();"><a id="{$__hg_Pre}column_a"  onclick="hg_show_column('{$__hg_Pre}');" >{$hg_attr['title']}<span id="{$__hg_Pre}type">{$hg_col_type[1]}</span></a><span id="{$__hg_Pre}coltype" class="coltype" style="display:none">&nbsp;&nbsp;
		   <!--栏目类型占位符-->
		   {foreach $hg_col_type as $typeid=>$typename}
				<span onclick="hg_change_coltype('{$__hg_Pre}',{$typeid},'{$hg_name}{$hg_multiple_suffix}','{$inputtype}','{$hg_attr[request_url]}',this, '{$hg_attr[siteid]}')">{$typename}</span>&nbsp;
		   {/foreach}
		   </span></span>
		   <ul id="{$__hg_Pre}column_id" class="clear css_column_id">
			{if $hg_value}
				{foreach $hg_value as $k=>$v}
					<li id="{$__hg_Pre}li_{$k}">
						<span class="a"></span>
						<span class="b"></span>
						<span class="c overflow">{$v}</span>
						<span class="close" onclick="hg_cancell_selected('{$k}', '{$__hg_Pre}')" ></span>
					</li>
				{/foreach}
			{/if}
		   </ul>
		</li>
		<li id="{$__hg_Pre}column" class="clear shows" {if !$hg_attr['slidedown']}style="display:none"{/if}>
			<div id="{$__hg_Pre}all" class='css_all'>
				<div class="pub_div_bg clear" id="{$__hg_Pre}allcol">
					 <div class="pub_div clear" id="{$__hg_Pre}level_1">
						<ul id="{$__hg_Pre}level1col">
							<li class="first"><span class="checkbox"></span><a href="##">最近使用<strong>»</strong></a></li>
							{foreach $hg_data as $index=>$value}
							{code}
								$checked = '';
								if(in_array($value['name'], $hg_value))
								{
									$checked = 'checked = "checked"';
								}
							{/code}
							<li>
							<input name="_{$hg_name}{$hg_multiple_suffix}" type="{$inputtype}" {$checked} value="{$value['id']}" class="checkbox" onclick="hg_selected_col('{$value['name']}',this.value,event,'{$__hg_Pre}','{$hg_name}{$hg_multiple_suffix}','{$inputtype}')" id="{$__hg_Pre}checkbox_{$value['id']}"/>
							<a class="overflow" href="javascript:void(0)" {if $value['is_last']}onclick="hg_getcol_childs(event,'{$__hg_Pre}','{$hg_name}{$hg_multiple_suffix}','{$inputtype}','{$hg_attr[request_url]}',{$value['id']},1)"{else}onclick="hg_coldbclick('{$value['name']}',{$value['id']},event,'{$__hg_Pre}','{$hg_name}{$hg_multiple_suffix}','{$inputtype}')"{/if} id="{$__hg_Pre}hg_colid_{$value['id']}"    ondblclick="hg_coldbclick('{$value['name']}',{$value['id']},event,'{$__hg_Pre}','{$hg_name}{$hg_multiple_suffix}','{$inputtype}')">{$value['name']}{if $value['is_last']}<strong>»</strong>{/if}</a>
							</li>
							{/foreach}
						</ul>
					 </div>
					 <div class="pub_div" id="{$__hg_Pre}level_2" onclick="hg_roll_col(this.id,0,'{$__hg_Pre}')" showit="yes">
						 <ul id="{$__hg_Pre}level2col">
						 </ul>
					 </div>
					 <div class="pub_div" id="{$__hg_Pre}level_3" onclick="hg_roll_col(this.id,0,'{$__hg_Pre}')" showit="yes">
						 <ul id="{$__hg_Pre}level3col">
						 </ul>
					 </div>
				</div>
			</div>
			<div id="{$__hg_Pre}hg_selected_hidden">
				{if $hg_value}
				{foreach $hg_value as $k=>$v}
					<input type="hidden" name="{$hg_name}{$hg_multiple_suffix}" value="{$k}" id="{$__hg_Pre}hg_hidden_{$k}">
				{/foreach}
				{/if}
			</div>
			{if $hg_attr['rule']}
			{code}
				$checked2 = $checked1 = '';
				if($hg_attr['rule_selected'] == 1)
				{
					$checked1 = 'checked="checked"';
				}
				else if ($hg_attr['rule_selected'] == 2)
				{
					$checked2 = 'checked="checked"';
				}
				else if($hg_attr['rule_selected'] == 3)
				{
					$checked1 = 'checked="checked"';
					$checked2 = 'checked="checked"';
				}
				else
				{
					$checked1 = 'checked="checked"';
				}
			{/code}
			<div class="clear" style="margin-top:8px;">
			<input type="checkbox" name="{$hg_name}_attr[]" style="vertical-align:middle;margin-right:7px;" value="1" {$checked1}/><span>栏目本身</span>
			<input type="checkbox" name="{$hg_name}_attr[]" style="vertical-align:middle;margin-right:7px;" value="2" {$checked2}/><span>子栏目</span>
			</div>
			{/if}
		</li>
	</ul>
	<input type="hidden" name="_type_" value="1" id="{$__hg_Pre}changecoltype"/>
</div>
</div>
{code}
$__call_column_count++;
{/code}