{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_columns first"><em></em><a>栏目</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}栏目</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	<li class="i">
	<div class="form_ul_div clear">
	<span class="title">站点：</span><span style="line-height:24px;">{$sitename}</span>
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">栏目名称：</span><input type="text" value="{$formdata['name']}" name="name">
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">栏目描述：</span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea>
	</div>
	</li>
	<!-- <li class="i">
		<div class="form_ul_div clear">
		<span class="title">父级栏目：</span>
		{code}
			/*select样式*/
			$col_style = array(
			'class' => 'down_list i',
			'show' => 'col_ul',
			'width' => 120,	
			'state' => 0, 
			'is_sub'=>1,
			'onclick'=>'',
			);
			$default = $formdata['fatherid'] ? $formdata['fatherid'] : 0;
			$default = $default ?  $default   :  intval($_INPUT['ffid']);
		{/code}
		{template:form/search_source,fatherid,$default,$columns,$col_style}
		</div>
	</li> -->
	<li class="i">
		{code}
		$hg_attr['multiple'] = 0;
		$hg_attr['_callcounter'] = 3;
		$hg_attr['title'] = '父栏目';
		$hg_attr['siteid'] = $_INPUT['siteid'] ? $_INPUT['siteid'] : $formdata['siteid'];
		$hg_attr['exclude'] = array($_INPUT['id']);
		$formdata['type'] = explode(',', $formdata['type']);
		{/code}
		<div>{template:unit/column_node,fatherid,$default}</div>
	</li>
	<li class="i">
	<div class="form_ul_div">
	<span class="title">栏目属性：</span>
	{foreach $column_types as $k=>$v}
	<input style="vertical-align:middle" type="checkbox" name="col_attr[{$k}]" value="{$k}" {if in_array($k, $formdata['type'])}checked="checked"{/if}><span style="margin:0px 10px;">{$v}</span>
	{/foreach}
	<font class="important">留空默认是网站</font>
	</div>
	</li>
	<li class="i">
	<div class="form_ul_div clear">
	<span class="title">专题栏目：</span><input {if $formdata['special']}checked="checked"{/if} type="checkbox" name="col_attr[-1]" value="-1">
	</div>
	</li>
	<!--
	 <li class="i">
	<div class="form_ul_div clear">
	<span class="title">栏目类型：</span>
	{code}
		/*select样式*/
		$type_style = array(
		'class' => 'down_list i',
		'show' => 'type_ul',
		'width' => 120,	
		'state' => 0, 
		'is_sub'=>1,
		);
		$default = $formdata['type'] ? $formdata['type'] : 0;
	{/code}
	{template:form/search_source, type, $default, $ctype,$type_style}
	</div>
	</li>
	-->
	<!--记录栏目的原父节点-->
	<li>
	<div class="form_ul_div">
	<input type="hidden" value="{$formdata['fatherid']}" name="fid" />
	</div>
	</li>
	<li>
	<div class="form_ul_div">
	<input type="hidden" value="{$siteid}" name="siteid" />
	</div>
	</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
{code}
$type = $formdata['type'] ? $formdata['type'] : 1;
{/code}
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br/>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}