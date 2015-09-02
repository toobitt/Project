{template:head}
{css:style}
{css:vod_style}
{css:edit_video_list}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_columns first dq"><em></em><a>栏目</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<span class="button_6" onclick="getsiteid()"><strong>新增栏目</strong></span>
	</div>
</div>
<div class="wrap n">

<script type="text/javascript">
	function getsiteid()
	{
		
		if($('#siteid').length)
		{
			var text = $("#display_site_ul").text();
			var sid = $('#siteid').val();
		}
		else
		{
			var text = '{$sites[$currentsid]}';
			var sid = '{$currentsid}';
		}
		var fid = 0;
		var url = document.location.search;
		url = url.split('&');
		var url_para = {};
		for(var n in url)
		{
			var index, value, t;
			t = url[n].split('=');
			index = t[0];
			value = t[1];
			url_para[index] = value;
		}
		document.location.href= '?a=form&siteid='+sid+'&sitename='+text+'&ffid='+url_para.fatherid;
	}
	function filtersite(sid)
	{
		document.location.href= '?siteid='+sid;
	}

	$(function(){
		tablesort('vodlist','columns','order_id',true);
		$("#vodlist").sortable('disable');
	});
</script>
{if count($sites) > 1}
{code}
		/*select样式*/
		$site_style = array(
		'class' => 'down_list i',
		'show' => 'site_ul',
		'width' => '150',
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>"filtersite($('#siteid').val())",
		);
{/code}
<div class="clear" style="margin-top:10px;height:25px;line-height:25px;">
{template:form/search_source,siteid,$currentsid,$sites,$site_style}
<a href="?siteid={$currentsid}&fatherid" id="topcol" style="left:150px;margin-left:10px; position:relative;top:-23px;">全部</a>
{foreach $allpar as $fid=>$v}
<a href="?siteid={$v[siteid]}&fatherid={$v[id]}" style="left:150px;margin-left:10px;position:relative;top:-23px;">&gt;&gt;{$v['name']}</a>
{/foreach}
</div>
{else}
<div class="clear" style="margin-top:10px;height:25px;line-height:25px;">
<a href="?siteid={$currentsid}&fatherid" id="topcol">全部</a>
{foreach $allpar as $fid=>$v}
<a href="?siteid={$v[siteid]}&fatherid={$v[id]}">&gt;&gt;{$v['name']}</a>
{/foreach}
{/if}
<!--
<div class="search_a" id="info_list_search"></div>
-->
<div id="infotip" class="ordertip" ></div>
<form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list" id="columns_table">
	{if $list_fields}
	<tr>
		
		<th width="30" align="center"><img style="cursor: pointer;" id="is_order" title="开启排序模式/ALT+R" onclick="hg_switch_order('vodlist');" src="{code} echo RESOURCE_URL.'hg_logo.jpg';{/code}" /></th>
		
		{foreach $list_fields AS $k => $v}
		<th title="{$v['brief']}"{$v['width']}>{$v['title']}</th>
		{/foreach}
		{if $op}
		<th>管理</th>
		{/if}
	</tr>
	{/if}
	<tbody id="vodlist">
	{if $list}
		{foreach $list AS $k => $v}
			{if $list_fields}
			<tr orderid="{$v['order_id']}"  id="r_{$v['id']}" name="{$v['id']}" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r{$v[$primary_key]}" valign="middle" align="left">
				
				<td valign="middle" align="center" id="primary_key_img_{$v['id']}"><a class="lb" name="alist[]" style="height: 11px;width: 14px;display: inline-block;background-position:0 0;margin-left:8px;"><input style="float: left;margin:0;" id="primary_key_{$v['id']}" class="n-h" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a></td>
				
				{foreach $list_fields AS $kk => $vv}
					{code}
						$exper = $vv['exper'];			
						eval("\$val = \"$exper\";");
					{/code}
				<td>
				{if $kk == 'name' && $v['is_last']}
					<a href="?siteid={$v['siteid']}&fatherid={$v['id']}">{$val}</a>
					{else}
					{$val}
				{/if}
				</td>
				{/foreach}
				{if $op}
				<td align="center" class="right">
				{foreach $op AS $kk => $vv}
				{if !$vv['group_op']}
				<a href="{$vv['link']}&amp;{$vv['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}&amp;type={$v['type']}" title="{$vv['brief']}"{$vv['attr']}>{$vv['name']}</a>
				{else}
				{code}
				$group_op = $vv['group_op'];
				$name = $kk . '__' . $primary_key . '=' . $v[$primary_key];
				$attr['onchange'] = $vv['attr'];
				$value = $v[$kk];
				{/code}
				{template:form/select, $name, $value, $group_op, $attr}
				{/if}
				{/foreach}
				</td>
				{/if}
			</tr>
			{/if}
		{/foreach}
	{else}
	{code}
	$colspan = count($list_fields) + 2;
	{/code}
	<tr><td colspan="{$colspan}" style="text-align:center;" class="hg_error">暂无此类信息</td></tr>
	{/if}
	</tbody>
</table>
<div class="form_bottom clear">
	<div class="live_delete">
		<input type="hidden" name="a" id="a" value="delete" />
	</div>
	<div class="live_page">{$pagelink}</div>
</div>

</form>
</div>
{template:foot}