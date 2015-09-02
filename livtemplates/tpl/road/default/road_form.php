{template:head}
{code}
	if($id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="新增";
		$ac="create";
	}
	
{/code}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;	
		{/code}
	{/foreach}
	{code}//echo json_encode($road_area);{/code}
{/if}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}

<script>
$(function(){
	$.ajax({
		url:"./run.php?mid={$_INPUT['mid']}&a=show_sort&infrm=1",
		cache:false,
		type:'POST',
		success:function(datas)
		{
			$('#road_areas').html(datas);
			//alert(datas);
			var road_area = {code}echo json_encode($road_area);{/code};
			
			//alert(road_area.);
		}
	});
	
});
/*
$("#show_orgs").live("change", function() {
	var id = $('#show_orgs option:selected').val();
	$.ajax({
		url:'./run.php?mid=460&a=list_org_users&org_id='+id+'&infrm=1',
		cache:false,
		type:'POST',
		success:function(datas)
		{
			$('#get_org_users').html(datas);
		}
	});	
});
*/
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}路况</h2>
<ul class="form_ul">
{code}
	$item_source = array(
		'class' => 'down_list',
		'show' => 'item_show',
		'width' => 100,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	$default = $group_id ? $group_id : -1;
	$group_data[$default] = '选择分类';
	foreach($group as $k =>$v)
	{
		$group_data[$v['id']] = $v['title'];
	}
{/code}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">路况状态：</span>{template:form/search_source,group_id,$default,$group_data,$item_source}
	</div>
	
</li>
<li class="i">
	{if $ac=="update"}
	<div class="form_ul_div clear">
	 {template:unit/road_area}
	</div>
	{else}
	<div class="form_ul_div clear" id="road_areas">
	 {template:unit/road_area}
	</div>
	{/if}	
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">热门易堵：</span>
		{code}
		//var_dump($is_hot);
		if($is_hot==1)
			echo "<input type=\"checkbox\" class=\"checkbox\" name=\"is_hot\" value=\"1\" checked=\"checked\" />";
		else
			echo "<input type=\"checkbox\" class=\"checkbox\" name=\"is_hot\" value=\"1\"/>";
		{/code}
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">路况链接：</span><input type="text" value='{$live_traffic}' name='live_traffic' class="title" />
		<font class="important">实时路况链接</font>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">内容：</span><textarea name="content">{$content}</textarea>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">地图：</span>
		{code}
			$hg_bmap = array(
				'height' => 480,
				'width'  => 600,
				'longitude' => isset($formdata['baidu_longitude']) ? $formdata['baidu_longitude'] : '0', 
				'latitude'  => isset($formdata['baidu_latitude']) ? $formdata['baidu_latitude'] : '0',
				'zoomsize'  => 13,
				'areaname'  => $_configs['areaname'],
				'is_drag'   => 1,
			);
		{/code}
		{template:form/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">路况地点：</span><input type="text" id="address" value='{$address}' name='address' class="title" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">有效时间：</span>
		<input type="text" value="{$effect_time}" name="effect_time" class="title" />
		<font class="important">单位为分钟</font>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">图片：</span>
		{code}
			$log_img = '';
			if($pic)
			{
				if(!$local_img)
				{
					$log_img = $pic['host'] . $pic['dir'] . $picsize['thumbnail'] . $pic['filepath'] . $pic['filename'];
				}
				else
				{
					$log_img = $pic['host'] . $pic['dir'] .'100x75/'. $pic['filepath'] . $pic['filename'];
				}
			}
		{/code}
		{if $log_img}<img src="{$log_img}" alt="log" width="100" height="75"/>{/if}
		<input type="file" value='' name='Filedata'/>
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}路况" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}