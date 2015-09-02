{template:head}
{js:common/auto_textarea}
{css:common/common_form}
{code}
$list = $formdata;
if($id)
{
	$optext="更新";
	$a="update";
}
else
{
	$optext="添加";
	$a="create";
}
/*所有选择控件基础样式*/
$all_select_style = array(
	'class' 	=> 'down_list',
	'state' 	=> 	0,
	'is_sub'	=>	1,
);
{/code}
{css:ad_style}
{css:column_node}
{js:column_node}
{css:station_style}
{js:common/ajax_upload}
{js:public_bicycle/station}
{js:public_bicycle/station_picUpload}
{js:common/common_form}	

<div id="channel_form" style="margin-left:40%;" {if $list['id']}data-id="{$list['id']}"{/if}></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}站点</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">站点编号：</span>
								<input type="text" value="{$list['station_id']}" name='station_id' style="width:200px;">
								<font class="important">必填</font>
								<font class="important" style="color:red">*</font>
							</div>
							<div class="form_ul_div">
								<span  class="title">站点名称：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:200px;">
								<font class="important">必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">运营单位：</span>
								{code}
									$item_css = array(
										'class' => 'transcoding down_list',
										'show' => 'sort_item',
										'width' => 150,
										'state' => 0,
										'is_sub' => 1
									);
									$company = $company[0];
									$default_contri_sort = 0;
									$company[$default_contri_sort] = '未选择';
									
									$formdata['company_id'] = $formdata['company_id'] ? $formdata['company_id'] : 0;
								{/code}
								<div style="margin-left:-5px;float:left">{template:form/search_source,company_id,$formdata['company_id'],$company,$item_css}</div>
								<font class="important">必选</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear more-z-index">
								<span  class="title">车辆总数：</span>
								<input type="text" value="{$list['totalnum']}" name='totalnum' style="width:100px;">
								<span  class="more-index">可借数量：</span>
								<input type="text" value="{$list['currentnum']}" name='currentnum' style="width:100px;">
								<span  class="more-index">可停数量：</span>
								<input type="text" value="{$list['park_num']}" name='park_num' disabled="disabled" style="width:100px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">站点描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						<li class="i clear">
							<!--  <div class="form_ul_div">
								<span class="title" >上传照片：</span>
								<input type="file" name="Filedata[]"  value="submit"> 
								<input type="file" name="Filedata[]"  value="submit">         
							</div>-->
							
							<div class="form_ul_div photo-box-area">
						 			<span  class="title">实景照片：</span>
						 			<div style="overflow:hidden;">
							 			<div class="photo-item-list">
							 			  <input type="file" name="Filedata[]" style="display:none;" class="photo-file">
							 			  {if !$list['pic']}
							 			  <div class="weather-img photo-item default">
							 			       <span class="set-index">索引图</span>
							 			  </div>
							 			  {/if}         
							 			  {if $list['pic']}
								 			{foreach $list['pic'] as $k=>$v}
								 			{code}
								 				$url1 = $v['host'].$v['dir'].$v['filepath'].$v['filename'];
								 			{/code}
								 			<div class="weather-img photo-item">
								 			    <img alt="" src="{$url1}" id="img_{$v['id']}">
								 			    <span class="set-index {if $v['id'] == $list['material_id']}current{/if}">索引图</span>
								 				<span class="delete">x</span>
								 				<input name="img_id[]" type="hidden" value="{$v['id']}">
								 			</div>
							 				{/foreach}
							 			  {/if}
						 				</div>
						 				<input name="indexpic_id" id="indexpic_id" type="hidden" value="{$list['material_id']}">
						 				<span class="photo-add">+</span>
						 		   </div>
					 		</div>
					 		
						</li>
						
						<li class="i clear">
							<div class="form_ul_div clear">
								<input type="hidden" name="region_id" value="{$list['region_id']}" />
								<span class="title">区域划分：</span>
								{code}
									$hg_attr['exclude'] = 1;
									$hg_attr['node_en'] = 'station_node';
								{/code}
								
								{template:unit/class,region_id,$list['region_id'],$node_data}
								<font class="important">必选</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						
			           
						<li class="i" id='map'>
							<div class="form_ul_div clear">
								<span class="title">坐标地址：</span>
								{code}
									$province_style = $all_select_style;
									$province_style['onclick'] = 'hg_get_city_by_province(this);';
									$province_style['show'] = 'province_item_show';
									$province_default = -1;
									$province_data[$province_default] = '所有省';
									if($list['province'])
									{
										$province_default = $list['province'];
									}
									
									foreach($provinces[0] AS $k => $v)
									{
										$province_data[$v['id']] = $v['name'];
									}
									
									$city_style = $all_select_style;
									$city_style['show'] = 'city_item_show';
									$city_default = -1;
									$city_data[$city_default] = '所有市';
									if($list['city'])
									{
										$city_default = $list['city'];
									}
									if($list['now_city_data'])
									{
										foreach($list['now_city_data'] AS $k => $v)
										{
											$city_data[$v['id']] = $v['city'];
										}
									}

									$area_style = $all_select_style;
									$area_style['show'] = 'area_item_show';
									$area_default = -1;
									$area_data[$area_default] = '所有区';
									if($list['area'])
									{
										$area_default = $list['area'];
									}
									if($list['now_area_data'])
									{
										foreach($list['now_area_data'] AS $k => $v)
										{
											$area_data[$v['id']] = $v['area'];
										}
									}
								{/code}
								<div>{template:form/search_source,province,$province_default,$province_data,$province_style}</div>
								<div style="margin-left:10px;float:left;" id="city_box">{template:form/search_source,city,$city_default,$city_data,$city_style}</div>
								<div style="margin-left:10px;float:left;" id="area_box">{template:form/search_source,area,$area_default,$area_data,$area_style}</div>
							</div>
							<div class="form_ul_div clear">
								<span class="title"></span>
								<input type="text" value="{$list['address']}" name='address' style="width:400px;" {if !$list['address']}id="detailed_address"{/if}/>
							</div>
							<div class="form_ul_div clear">
								<span class="title"></span>
								{code}
									$hg_bmap = array(
										'height' => 480,
										'width'  => 600,
										'longitude' => isset($list['baidu_longitude']) ? $list['baidu_longitude'] : '0', 
										'latitude'  => isset($list['baidu_latitude']) ? $list['baidu_latitude'] : '0',
										'zoomsize'  => 13,
										'areaname'  => $city_name[0],
										'is_drag'   => 1,
									);
								{/code}
								{template:map/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
							</div>
						</li>
				</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="photo-list-tpl">
   <div class="weather-img photo-item default">
        <span class="set-index">索引图</span>
   </div>
</script>