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
<h2>{$optext}交易</h2>
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
	//$group_data = $group;
	foreach($group as $k =>$v)
	{
		$group_data[$v['id']] = $v['title'];	
	}
{/code}

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
		<span class="title">交易类型：</span>
		{code}
		if($is_sale==1)
		    echo "<input type=\"radio\" class=\"radio\" name=\"is_sale\" value=\"1\" checked=\"checked\" />我要卖 <input type=\"radio\" class=\"radio\" name=\"is_sale\" value=\"0\" />我要买";
		elseif($is_sale==0)
		    echo "<input type=\"radio\" class=\"radio\" name=\"is_sale\" value=\"1\" />我要卖 <input type=\"radio\" class=\"radio\" name=\"is_sale\" value=\"0\" checked=\"checked\" />我要买";
		else 
		    echo "<input type=\"radio\" class=\"radio\" name=\"is_sale\" value=\"1\" />我要卖 <input type=\"radio\" class=\"radio\" name=\"is_sale\" value=\"0\" />我要买";
		{/code}
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">选择区域：</span>{template:form/search_source,group_id,$default,$group_data,$item_source}
		<input type="text" id="roadname" value='{$roadname}' name='roadname' class="title" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">标题：</span><input type="text" id="address" value='{$address}' name='address' class="title" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">价格：</span><input type="text" id="price" value='{$price}' name='price' class="title" />元
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">联系人：</span><input type="text" id="real_name" value='{$real_name}' name='real_name' class="title" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">电话：</span><input type="text" id="tel" value='{$tel}' name='tel' class="title" />
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
				'areaname'  => '杭州',
				'is_drag'   => 1,
			);
		{/code}
		{template:form/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">图片1：</span>
		{code}
			$log_img = '';
			
			if($pic)
			{
				if(!$local_img)
				{
					$log_img = $pic[0]['host'] . $pic[0]['dir'] . $picsize[0]['thumbnail'] . $pic[0]['filepath'] . $pic[0]['filename'];
				}
				else
				{
					$log_img = $pic[0]['host'] . $pic[0]['dir'] .'100x75/'. $pic[0]['filepath'] . $pic[0]['filename'];
				}
			}
		{/code}
		{if $log_img}<img src="{$log_img}" alt="log" width="100" height="75"/>{/if}
		<input type="file" value='' name='Filedata[]'/>
		
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">图片2：</span>
		{code}
			$log_img_2 = '';
			if($pic[1])
			{
				if(!$$log_img_2)
				{
					$log_img_2 = $pic[1]['host'] . $pic[1]['dir'] . $picsize[1]['thumbnail'] . $pic[1]['filepath'] . $pic[1]['filename'];
				}
				else
				{
					$$log_img_2 = $pic[1]['host'] . $pic[1]['dir'] .'100x75/'. $pic[1]['filepath'] . $pic[1]['filename'];
				}
			}
		{/code}
		{if $log_img_2}<img src="{$log_img_2}" alt="log" width="100" height="75"/>{/if}
		<input type="file" value='' name='Filedata[]'/>
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">图片3：</span>
		{code}
			$log_img_3 = '';
			if($pic[2])
			{
				if(!$$log_img_3)
				{
					$log_img_3 = $pic[2]['host'] . $pic[2]['dir'] . $picsize[2]['thumbnail'] . $pic[2]['filepath'] . $pic[2]['filename'];
				}
				else
				{
					$$log_img_3 = $pic[2]['host'] . $pic[2]['dir'] .'100x75/'. $pic[2]['filepath'] . $pic[2]['filename'];
				}
			}
		{/code}
		{if $log_img_3}<img src="{$log_img_3}" alt="log" width="100" height="75"/>{/if}
		<input type="file" value='' name='Filedata[]'/>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">图片4：</span>
		{code}
			$log_img_4 = '';
			if($pic[3])
			{
				if(!$$log_img_4)
				{
					$log_img_4 = $pic[3]['host'] . $pic[3]['dir'] . $picsize[3]['thumbnail'] . $pic[3]['filepath'] . $pic[3]['filename'];
				}
				else
				{
					$$log_img_4 = $pic[3]['host'] . $pic[3]['dir'] .'100x75/'. $pic[3]['filepath'] . $pic[3]['filename'];
				}
			}
		{/code}
		{if $log_img_4}<img src="{$log_img_4}" alt="log" width="100" height="75"/>{/if}
		<input type="file" value='' name='Filedata[]'/>
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}交易" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}