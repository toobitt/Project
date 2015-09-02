<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="appauthform"  id="appauthform" onsubmit="return hg_ajax_submit('appauthform');">
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">小组名称：</label>
	<input type="text" name="name"   id="name"  style="width:450px;" />
</div>

<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">小组描述：</label>
	<textarea style="width:450px;height:100px;" name="intro" id="intro" ></textarea>
</div>

<div style="width:100%;margin-top:10px;">
	<label  style="float:left;margin-left:25px;">小组标识：</label>
	<div id="log_box" style="float:left;"></div>
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
				$cat[$k] = $v['c_name'];
			}
			$cat['0'] = '选择分类';
			$formdata['sort_id'] ? $formdata['sort_id'] : 0 ;
		{/code}
	<div style="float:left;margin-left:3px;">
		{template:form/search_source,category,$formdata['sort_id'],$cat,$attr_cat}
	</div>
</div>
<div class="clear"></div>

<div class="clear"></div>
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">小组标签：</label>
	<input type="text" name="tags"  id="team_mark"  style="width:450px;" />
</div>

{code}
//print_r($team_category);
{/code}
<div style="width:100%;margin-top:10px;">
	<input type="submit"  value="创建" class="button_6" style="margin-left:441px;" />
</div>
<input type="hidden" value="docreate" name="a" />
<input type="hidden" value="{$formdata['id']}"  name="apply_id" />
<input type="hidden" value="{$formdata['type']}"  name="type" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
