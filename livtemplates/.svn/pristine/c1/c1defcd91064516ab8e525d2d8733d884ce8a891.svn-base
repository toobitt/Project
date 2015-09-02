<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
{css:module_form_table}
{code}
	$arr = array(
	  0 => array('ziduan' => 'id','name' => 'id','desc' => '这是id'),
	  1 => array('ziduan' => 'create_time','name' => '创建时间','desc' => '这是创建的时间'),
	  2 => array('ziduan' => 'update_time','name' => '更新时间','desc' => '这是更新的时间'),
	  3 => array('ziduan' => 'ip','name' => 'ip地址','desc' => '这是ip地址'),
	);
	
	$type_length = array(
		'VARCHAR' => '文本',
		'TEXT' => '大文本',
		'INT' => '整型',
		'DATE' => '时间',
		'tinyint' => 'tinyint',
	);
	
	$data_source_arr = array(
	   1 => '来源1',
	   2 => '来源2',
	   3 => '来源3',
	   4 => '来源4',
	);
{/code}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
</style>
<script type="text/javascript">
	var g_num = 0;
	$(function(){
		$('tr[id^="tr_"]').each(function(){
			  if(parseInt($(this).attr('name')) > g_num)
			  {
				  g_num = parseInt($(this).attr('name'));
			  }
		});
		g_num++;
    });

	function hg_addOneList(num)
	{
		clearTimeout(g_TimeOut);
		var html  = '<tr id="tr_'+g_num+'" name="'+g_num+'">';
			html +=	'<td class="row"><div class="add_onelist" onclick="hg_addOneList('+num+');" onmouseover="hg_mouse_show_p('+g_num+');"  onmouseout="hg_clearTime();"><\/div><div class="c'+num+'"></div><\/td>';
			html +=	'<td class="row"><input type="text" name="bundle_id[]" \/><\/td>';
			html +=	'<td class="row"><input type="text" name="design_name[]" \/><\/td>';
			html +=	'<td class="row"><textarea style="width:150px;height:50px;" name="design_desc[]">描述</textarea><\/td>';
			html +=	'<td class="row"><select name="type_length[]"><option value="VARCHAR">文本<\/option><option value="TEXT">大文本<\/option><option value="INT">整型<\/option><option value="DATE">时间<\/option><option value="tinyint">tinyint<\/option><\/select><\/td>';
			html +=	'<td class="row"><select name="data_source[]"><option value="1">来源1<\/option><option value="2">来源2<\/option><option value="3">来源3<\/option><option value="4">来源4<\/option><\/select><\/td>';
			html +=	'<td class="row"><input type="radio" name="is_primary" value="'+g_num+'" \/><\/td>';
			html +=	'<td class="row"><input type="checkbox"  name="is_index[]" value="'+g_num+'" \/><\/td>';
			html +=	'<td class="row"><div class="remove_onelist" onclick="hg_removeOneList(this);"><\/div><\/td><input type="hidden" name="is_edit[]" value="0" \/><input type="hidden" name="ids_arr[]" value="'+g_num+'" \/><input type="hidden" name="data_type[]" value="'+num+'" \/><\/tr>';
		$('#mytable').append(html);
		g_num++;
	}

	function hg_removeOneList(obj)
	{
		$(obj).parent().parent().remove();
	}

	var g_TimeOut = 0;
	var g_AutoTimtOut = 0;/*打开窗口后，如果用户在3秒内没有任何操作就消失*/
	function hg_mouse_show_p(num)
	{
		clearTimeout(g_AutoTimtOut);
		clearTimeout(g_TimeOut);
		$('#stpl').hide();
		g_TimeOut = setTimeout("hg_show_stpl('"+num+"')",1000);
	}

	/*显示小框子*/
	function hg_show_stpl(num)
	{
		clearTimeout(g_AutoTimtOut);
		var top = parseInt($('#tr_'+num).position().top) + 25;
		$('#stpl').show().css({'top':top,'left':40});
		clearTimeout(g_TimeOut);
		g_AutoTimtOut = setTimeout(hg_hide_stpl,3000);
	}

	/*隐藏小框子*/
	function hg_hide_stpl()
	{
		$('#stpl').hide();
		clearTimeout(g_AutoTimtOut);
	}

	function hg_s_show_tp()
	{
		clearTimeout(g_AutoTimtOut);
		$('#stpl').show();
	}

	function hg_s_hide_tp()
	{
		g_AutoTimtOut = setTimeout(hg_hide_stpl,3000);
	}

	function hg_add_anew(num)
	{
		if(!num)
		{
			num = 1;
		}
		hg_hide_stpl();
		hg_addOneList(num);
	}

	function hg_clearTime()
	{
		clearTimeout(g_TimeOut);
	}
	
</script>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first"><em></em><a>模块</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}模块</h2>
{if $message}
<div class="error">{$message}</div>
{/if}

<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">名称: </span><input type="text" name="name" value="{$formdata['name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">标识: </span><input type="text" name="mod_uniqueid" value="{$formdata['mod_uniqueid']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">icon: </span><input type="text" name="icon" value="{$formdata['icon']}" /><font class="important">无后缀表示class名</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">描述: </span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">所属系统: </span>{template:form/select,application_id,$formdata['application_id'],$applications}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">父级模块: </span>{template:form/select,fatherid,$formdata['fatherid'],$modules}
</div>
</li>
<li class="i">
<div class="form_ul_div clear l">
<span  class="title">关联模块: </span>{template:form/checkbox_t,relate_module,$formdata['relate_module'],$modules}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">主机: </span><input type="text" name="host" size="50" value="{$formdata['host']}" /><font class="important">不填继承系统设置</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">路径: </span><input type="text" name="dir" size="50" value="{$formdata['dir']}" /><font class="important">不填继承系统设置</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">文件: </span><input type="text" name="file_name" size="50" value="{$formdata['file_name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">方法名: </span><input type="text" name="func_name" size="50" value="{$formdata['func_name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">模板名: </span><input type="text" name="template" size="50" value="{$formdata['template']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">返回名: </span><input type="text" name="return_var" size="50" value="{$formdata['return_var']}" /><font class="important">不填默认为模板名</font>
</div>
</li>
<li class="i"  style="position:relative;">
	<table id="mytable" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series"> 
		<caption></caption> 
		<tr> 
			<th scope="col"></th>
			<th scope="col">标识</th>
			<th scope="col">名称</th> 
			<th scope="col">描述</th> 
			<th scope="col">类型长度</th> 
			<th scope="col">数据来源</th>
			<th scope="col">主键</th> 
			<th scope="col">索引</th>
			<th scope="col"></th>
		</tr>
		{if $formdata['app_design']}
			{foreach $formdata['app_design'] AS $kkk => $vvv}
				<tr id="tr_{$kkk}" name="{$kkk}">
					<td class="row">
						<div class="add_onelist" onclick="hg_addOneList({$vvv['data_type']});" onmouseover="hg_mouse_show_p({$kkk});" onmouseout="hg_clearTime();"></div>
						<div class="c{$vvv['data_type']}"></div>
					</td>
					<td class="row"><input type="text" value="{$vvv['bundle_id']}" {if $vvv['is_edit']}readonly="readonly"{/if}  name="bundle_id[]"  /></td>
					<td class="row"><input type="text" value="{$vvv['name']}"      {if $vvv['is_edit']}readonly="readonly"{/if}  name="design_name[]"  /></td>
					<td class="row"><textarea style="width:150px;height:50px;"     {if $vvv['is_edit']}readonly="readonly"{/if}  name="design_desc[]">{$vvv['desciption']}</textarea></td>
					<td class="row">
						<select name="type_length[]">
							{foreach $type_length AS $kk => $vv}
							   {if $vvv['type_length'] == $kk}
							   		<option value="{$kk}" selected>{$vv}</option>
							   {else}
							   		<option value="{$kk}">{$vv}</option>
							   {/if}
							{/foreach}
						</select>
					</td>
					<td class="row">
						<select name="data_source[]">
							{foreach $data_source_arr AS $nn => $mm}
							   {if $vvv['data_source'] == $nn}
							   		<option value="{$nn}" selected>{$mm}</option>
							   {else}
							   		<option value="{$nn}">{$mm}</option>
							   {/if}
							{/foreach}
						</select>
					</td>
					<td class="row"><input type="radio" {if $vvv['is_primary']}checked="checked"{/if}  name="is_primary"  value="{$kkk}" /></td>
					<td class="row"><input type="checkbox" name="is_index[]" {if $vvv['is_index']}checked="checked"{/if}  value="{$kkk}" /></td>
					<td class="row">
						{if $vvv['is_edit']}
						<div style="width:20px;height:20px;"></div>
						{else}
						<div class="remove_onelist" onclick="hg_removeOneList(this);"></div>
						{/if}
					</td>
					<input type="hidden" name="is_edit[]" value="{$vvv['is_edit']}" />
					<input type="hidden" name="ids_arr[]" value="{$kkk}" />
					<input type="hidden" name="data_type[]" value="{$vvv['data_type']}" />
				</tr>
			{/foreach}
		{else}
			{foreach $arr AS $k => $v}
			<tr id="tr_{$k}" name="{$k}">
				<td class="row">
					<div class="add_onelist" onclick="hg_addOneList(1);" onmouseover="hg_mouse_show_p({$k});" onmouseout="hg_clearTime();"></div>
					<div class="c1"></div>
				</td>
				<td class="row"><input type="text" value="{$v['ziduan']}" readonly="readonly"  name="bundle_id[]"  /></td>
				<td class="row"><input type="text" value="{$v['name']}"   readonly="readonly"  name="design_name[]"  /></td>
				<td class="row"><textarea style="width:150px;height:50px;" readonly="readonly" name="design_desc[]">{$v['desc']}</textarea></td>
				<td class="row">
					<select name="type_length[]">
						{foreach $type_length AS $kk => $vv}
						<option value="{$kk}">{$vv}</option>
						{/foreach}
					</select>
				</td>
				<td class="row">
					<select name="data_source[]">
						<option value="1">来源1</option>
						<option value="2">来源2</option>
						<option value="3">来源3</option>
						<option value="4">来源4</option>
					</select>
				</td>
				<td class="row"><input type="radio" {if !$k}checked="checked"{/if}  name="is_primary"    value="{$k}" /></td>
				<td class="row"><input type="checkbox" name="is_index[]" {if !$k}checked="checked"{/if}  value="{$k}"  /></td>
				<td class="row"><div style="width:20px;height:20px;"></div></td>
				<input type="hidden" name="is_edit[]" value="1" />
				<input type="hidden" name="data_type[]" value="1" />
			</tr>
			{/foreach}
		{/if}
</table>
<div id="stpl" class="float_tp" onmouseover="hg_s_show_tp();" onmouseout="hg_s_hide_tp();">
	<div onclick="hg_add_anew(1);" style="border-bottom:0px;background:#128d49;">字段</div>
	<div onclick="hg_add_anew(2);" style="border-bottom:0px;background:#ca0000;">扩展字段</div>
	<div onclick="hg_add_anew(3);" style="background:#0066ff;">附属信息</div>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">是否分页: </span>{template:form/radio,is_pages,$formdata['is_pages'],$option}
</div>
</li>
<li>
<div class="form_ul_div clear">
<span  class="title">每页显示数目: </span><input type="text" name="page_count" size="10" value="{$formdata['page_count']}" /><font class="important">不填自动设置</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">排序: </span><input type="text" name="order_id" size="4" value="{$formdata['order_id']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">位置: </span><input type="text" name="menu_pos" size="4" value="{$formdata['menu_pos']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">关联模块: </span><input type="text" name="relate_molude_id" size="4" value="{$formdata['relate_molude_id']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">关联发布: </span><input type="checkbox" name="is_pub" size="4" value="1" {if $formdata['is_pub']}checked="checked"{/if}/>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title overflow">发布模块ID: </span><input type="input" name="pub_module_id" size="4" value="{$formdata['pub_module_id']}"/><font class="important">自定义发布模块ID 发布优先级 模块ID<关联模块ID<自定义模块ID</font>
</div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span  class="title overflow">支持外链: </span><input type="checkbox" name=" accept_outerlink" size="4" value="1" {if $formdata['accept_outerlink']}checked="checked"{/if}/>&nbsp;<input type="text" name="create_update" value="{$formdata['create_update']}"/><font class="important">是否接受外链数据，后者输入框代表该模块的创建和更新方法</font>
    </div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">启用授权: </span><input  {if $formdata['need_auth']}checked="checked"{/if} type="checkbox" name="need_auth"   value="1" style="margin-top:4px;" />
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}