<style type="text/css">.part .css_column_id li{margin:0px 5px 2px 0px;}
</style>
{code}
	$__call_column_count  = $hg_attr['_callcounter'] ? $hg_attr['_callcounter'] : intval($__call_column_count);
	$__hg_Pre = 'hgCounter_'.$__call_column_count.'_';
	$hg_value = $hg_value ? (is_array($hg_value) ? implode(',', $hg_value) : $hg_value) : array();
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
	$_exclude = !$hg_attr['exclude'] ? "_exclude=".$_INPUT['id']: '';
	if(!$hg_data)
	{
		if(!is_array($hg_data) && $hg_attr['node_en'])
		{
			if(!class_exists('hg_get_node'))
			{
				include_once(ROOT_DIR . 'get_node.php');
			}
			/** $hg_attr['expand']是扩展数据，一维数组格式，如：$hg_attr['expand']=array('a'=>1,'b'=>2)，如根据条件选择对应节点，传递数据到节点方法，节点直接$this->input['a'] */
			$node_object = new hg_get_node($hg_attr['node_en'],$hg_attr['expand']);
			if($_exclude)
			{
			$node_info = $node_object->get_level1_node($_INPUT['id']);
			}
			else
			{
			$node_info = $node_object->get_level1_node();
			}

			$node_data = $node_info['data'];
			if($node_info['curl_info'])
			{
				foreach($node_info['curl_info']as $k=>$v)
				{
					$$k = $v;
				}
			}
			if($hg_value)
			{
				$ret = $node_object->getNodeInfoByIds($hg_value);
				foreach($ret as $k=>$v)
				{
					$info[$v['id']][] = $v;
				}
				$hg_value = $info[$hg_value][0];
			}
		}
	}
	$hg_attr['fid'] = $hg_attr['fid'] ? $hg_attr['fid'] : 0;
	$hg_attr['request_url'] = '_fetch_node.php?';
	if($_exclude)
	{
		$hg_attr['request_url'] .= $_exclude;
	}
	if($hg_attr['multiple_node'])
	{
		$hg_sites = $hg_attr['multiple_node'];
			if(count($hg_sites) == 1)
			{
				$hg_attr['multiple_site'] = 0;
			}
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

	$(document).ready(function(){

		$("form input[name^='_node_id']").change(function(event){

			var nid = $(this).val();
			var state = $(this).attr("checked");
			$("#node"+nid).show();
			$("#node"+nid).find('input').attr('disabled',false);
			var vv = $("#node"+nid).attr('id');
			if(state == 'checked')
			{
				if(!vv)
				{
					var node_name = $(this).next().text() + '的设置';

					var str = $("#node_one .node_moban").html();
					re=new RegExp("nodeid","g");
					var newstr=str.replace(re,nid);
					$(newstr).prependTo("#clone_node");

					$("#clone_node h2").first().text(node_name);
					$("#clone_node").find('ul').first().attr("id",'node'+nid);
					$("#clone_node").find('div').first().show();
				}

			}
			else
			{
				$("#node"+nid).find('input').attr('disabled','disabled');
				$("#node"+nid).hide();
			}
		});
	});
	function hg_change_multinode(counter, formname,formtype,url, siteid)
	{
		$('#'+counter+'column_id').html('');
		$('#'+counter+'hg_selected_hidden').html('');
		siteid = $('#'+counter+'siteid').val() ? $('#'+counter+'siteid').val() : siteid;
		var url = url+'&counter='+counter+'&formtype='+formtype+'&formname='+formname+'&formurl='+url+'&multi='+siteid;
		hg_request_to(url,{}, '','hg_show_coltype');
		if(hg_itoggle[counter] == 0)
		{
			hg_openall(counter);
			hg_itoggle[counter] = 1;
		}
	}
</script>
{/if}
<div class="info_all_node clear" style="float: left; width: 525px;">
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
			'onclick'=>"hg_change_multinode('$__hg_Pre','{$hg_name}$hg_multiple_suffix','$inputtype','$hg_attr[request_url]','$hg_attr[siteid]')",
			);
			$_default_site =  $hg_attr['siteid'];
		{/code}
		{template:form/search_source,$__hg_Pre.siteid,$_default_site,$hg_sites,$site_style}
</div>
{else}
<input type="hidden" value="{$hg_attr['node_en']}" id="{$__hg_Pre.siteid}">
{/if}
<div class="info_show" style="padding: 0px;">
	<ul class="part clear">
		<li id="{$__hg_Pre}show">
<!--		   <span class={if $hg_attr['multiple_site']}"col_sort l"{else}"col_sort"{/if} ></span>-->
		   <ul id="{$__hg_Pre}column_id" class="clear css_column_id">
			{if $hg_value}
				{foreach $hg_value as $k=>$v}
					{if $k == 'name'}
					<li id="{$__hg_Pre}li_{$k}">
						<span class="a"></span>
						<span class="b"></span>
						<span class="c overflow">{$v}</span>
						<span class="close" onclick="hg_cancell_selected('{$k}', '{$__hg_Pre}')" ></span>
					</li>
					{/if}
				{/foreach}
			{/if}
		   </ul>
		</li>
		<li id="{$__hg_Pre}column" class="clear shows" style="border:0px;padding: 0px;">
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
							<a class="overflow" href="javascript:void(0)" {if !$value['is_last']}onclick="hg_getcol_childs(event,'{$__hg_Pre}','{$hg_name}{$hg_multiple_suffix}','{$inputtype}','{$hg_attr[request_url]}',{$value['id']},1)"{else}onclick="hg_coldbclick('{$value['name']}',{$value['id']},event,'{$__hg_Pre}','{$hg_name}{$hg_multiple_suffix}','{$inputtype}')"{/if} id="{$__hg_Pre}hg_colid_{$value['id']}"    ondblclick="hg_coldbclick('{$value['name']}',{$value['id']},event,'{$__hg_Pre}','{$hg_name}{$hg_multiple_suffix}','{$inputtype}')">{$value['name']}{if !$value['is_last']}<strong>»</strong>{/if}</a>
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