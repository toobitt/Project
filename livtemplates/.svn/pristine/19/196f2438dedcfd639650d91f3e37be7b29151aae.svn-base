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
			<li class="nav_columns first dq"><em></em><a>动态口令</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<a class="button_6" href="?a=form"><strong>新增口令</strong></a>
	</div>
</div>

<div class="wrap n">
<script type="text/javascript">
	function hg_stateAudit(id,salt)
	{
		gAuditId = id;
		var url = '?a=audit&id=' + id + '&salt=' + salt;
		hg_ajax_post(url,'','','stateAudit_back');

	}
	function stateAudit_back(id){
		$('#stateAudit_' + id).html('已审核');
	}
</script>


<div id="infotip" class="ordertip" ></div>
<form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list" id="columns_table">
	{if $list_fields}
	<tr>
		<th width="30" align="center">
			<img style="cursor: pointer;" id="is_order" title="开启排序模式/ALT+R" onclick="hg_switch_order('vodlist');" src="{code} echo RESOURCE_URL.'hg_logo.jpg';{/code}" />
		</th>
		
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
			<tr orderid="{$v['order_id']}"  salt="{$v['salt']}" id="r_{$v['user_id']}" name="{$v['user_id']}" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r{$v[$primary_key]}" valign="middle" align="left">
				
				<td valign="middle" align="center" id="primary_key_img_{$v['user_id']}"><a class="lb" name="alist[]" style="height: 11px;width: 14px;display: inline-block;background-position:0 0;margin-left:8px;"><input style="float: left;margin:0;" id="primary_key_{$v['user_id']}" class="n-h" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a></td>
				
				{foreach $list_fields AS $kk => $vv}
					{code}
						$exper = $vv['exper'];			
						eval("\$val = \"$exper\";");
					{/code}
				<td {if $kk == 'state'}id="stateAudit_{$v['user_id']}"{/if}>
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
							<a href="{$vv['link']}&amp;{$vv['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}&amp;salt={$v['salt']}" title="{$vv['brief']}"{$vv['attr']}>{$vv['name']}</a>
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
	<!--<div class="left" style="width:400px;clear:both;">
	   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
	   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'salt', '', 'ajax','');"    name="bataudit" >审核</a>
	   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
   </div>-->
	<div class="live_page">{$pagelink}</div>
</div>

</form>
</div>
</div>
{template:foot}