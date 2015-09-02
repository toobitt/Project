<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="appauthform"  id="appauthform" onsubmit="return hg_ajax_submit('appauthform');">
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">小组名称：</label>
	<input type="text" name="name"   id="name"  style="width:450px;"  value="{$formdata['team_name']}"/>
</div>

<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">小组描述：</label>
	<textarea style="width:450px;height:100px;" name="intro" id="intro" >{$formdata['introduction']}</textarea>
</div>

<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">小组公告：</label>
	<textarea style="width:450px;height:100px;" name="notice" id="notice" >{$formdata['notice']}</textarea>
</div>

<div style="width:100%;margin-top:10px;">
	<label  style="float:left;margin-left:25px;">小组标识：</label>
	<div id="log_box" style="float:left;">
	{code}
		$log = '';
		$serialize_logo = '';
		$log = $formdata['team_logo']['host'] . $formdata['team_logo']['dir'] .'100x75/'. $formdata['team_logo']['filepath'] . $formdata['team_logo']['filename'];
		$serialize_logo = serialize($formdata['team_logo']); 
	{/code}
	{if $formdata['team_logo']}
		<img style="float:left;" src="{$log}"  width="100" height="75" />
		<input type='hidden' name='team_logo' value = "{$serialize_logo}" />
	{/if}
	</div>
	<div id="circle_upload" style="float: left;"></div>
</div>

<div style="width:100%;margin-top:10px;">
	<label style="float:left;margin-left:25px;">小组分类：</label>
		{code}
			$attr_cat = array(
				'class' => 'down_list data_time',
				'show' => 'app_show',
				'width' => 104,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				'is_sub' => 1,
			);
			$cat = array();
			foreach($team_category as $k => $v)
			{
				$cat[$v['c_id']] = $v['c_name'];
			}
			$cat[-1] = '选择分类';
			$formdata['team_category'] ? $formdata['team_category'] : 0 ;
		{/code}
	<div style="float:left;margin-left:3px;">
		{template:form/search_source,category,$formdata['team_category'],$cat,$attr_cat}
	</div>
</div>
<div class="clear"></div>

<div class="clear"></div>
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">小组标签：</label>
	<input type="text" name="tags"  id="team_mark"  style="width:450px;" value="{$formdata['tags']}"/>
</div>

<div style="width:100%;margin-top:10px;">
	<input type="submit"  value="更新" class="button_6" style="margin-left:441px;" />
</div>
<input type="hidden" value="update" name="a" />
<input type="hidden" value="{$formdata['team_id']}"  name="team_id" />
<!--<input type="hidden" value="{$formdata['type']}"  name="type" /> -->
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
