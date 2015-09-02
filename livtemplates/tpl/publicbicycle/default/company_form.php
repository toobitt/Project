{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
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
{css:station_style}
{js:public_bicycle/station}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 150px;top: 4px;}
.option_del {
display: none;
width: 16px;
height: 16px;
cursor: pointer;
float: right;
background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;
}
</style>
<script type="text/javascript">

	function hg_addMapVal()
	{
		var div = "<div class='form_ul_div clear'><span class='title title-name'>接口字段: </span><input type='text' name='map_val_key[]' size='30' class='title'>&nbsp;&nbsp;<span class='more-index'>映射为: </span>{if $_configs['bike_filed_dict']}<select name='map_val[]'>{foreach $_configs['bike_filed_dict'] as $key=>$val}<option value='{$key}'>{$val}</option>{/foreach}</select>{/if}&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#map_val').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if($(obj).data("save"))
		{
			if(confirm('确定删除该参数配置吗？'))
			{
				$(obj).closest(".form_ul_div").remove();
			}
		}
		else
		{
			$(obj).closest(".form_ul_div").remove();
		}
		hg_resize_nodeFrame();
	}

	function show_hide_add(id)
	{
		if(id == 4)
		{
			$("#add_long_lat").show();
		}
		else
		{
			$("#add_long_lat").hide();
		}
	}
</script>

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}运营单位</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">单位名称：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:257px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title" >LOGO：</span>
								<input type="file" name="logo"  value="submit"> 
							</div>
							{if $list['logo']}
							<div class="form_ul_div clear">
								<span style="float:right;margin-right:71%;border:0px solid #DADADA;">
									<img width="130" height="40" src="{$list['logo']}">
								</span>
							</div>
							{/if}
							<div class="form_ul_div clear">
								<span  class="title">备注描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">站点数：</span>
								<input type="text" value="{$list['station_count']}" name='station_count' style="width:257px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">API地址：</span>
								<input type="text" value="{$list['api_url']}" name='api_url' style="width:257px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">数据前缀：</span>
								<input type="text" value="{$list['data_pre']}" name='data_pre' style="width:257px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">数据节点：</span>
								<input type="text" value="{$list['data_node']}" name='data_node' style="width:257px;">
							</div>
						</li>
						<li class="i">
							<!-- 
							<div class='form_ul_div clear'>
								<span>站点id=stationid；站点名字=station；总数=totalnum；可借=currentnum；经度=stationx；纬度=stationy；地址=address</span>
							</div>
							 -->
							{if($list['map'])}
								{foreach $list['map'] as $k=>$v}
								<div class='form_ul_div clear'>
									<span class='title title-name'>接口字段: </span>
									<input type='text' name='map_val_key[]' value='{$k}' size='20' class='title'>&nbsp;
									 
									<span class="more-index">映射成: </span>
									
									<!--
									<input type='text' name='map_val[]' value='{$v}' size='20'/>&nbsp;
									 -->
									{if $_configs['bike_filed_dict']}
										<select name='map_val[]'>
										{foreach $_configs['bike_filed_dict'] as $key=>$val}
										<option value="{$key}" {if $v == $key}selected="selected"{/if}>{$val}</option>
										{/foreach}
										</select>
									{/if}
									<span class='option_del_box map'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
								</div>
								{/foreach}
							{/if}
							<div id="map_val"></div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addMapVal();">接口字段替换</span>
							</div>
						</li> 
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">坐标处理：</span>
								{code}
									$item_css = array(
										'class' => 'transcoding down_list',
										'show' => 'sort_item',
										'width' => 100,
										'state' => 0,
										'is_sub' => 1,
										'onclick'=>"show_hide_add(this.getAttribute('attrid'))",
										
									);
									$convert = $_configs['convert_set'];
									
									$convert_set = $list['convert_set'] ? $list['convert_set'] : 0;
								{/code}
								<div style="margin-left:-5px;float:left">{template:form/search_source,convert_set,$convert_set,$convert,$item_css}</div>
								<div id="add_long_lat" style="margin-left:20px;float:left;{if $list['convert_set'] != 4}display:none;{/if}">
									<span class="item-name">经度偏移量：</span>
									<input type="text" value="{$list['addlong']}" name="addlong" size="10"/>
									<span class="item-name">纬度偏移量：</span>
									<input type="text"  name="addlat" value="{$list['addlat']}" size="10"/>
									<span class="sel-con"></span>
								</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">可借地址：</span>
								<input type="text" value="{$list['park_num_api']}" name='park_num_api' style="width:257px;"><font class="important" style="color:red">可停可借接口地址</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title" >站点标识：</span>
								<input type="file" name="station_icon"  value="submit"> 
							</div>
							{if $list['station_icon']}
							<div class="form_ul_div clear">
								<span style="float:right;margin-right:81%;border:0px solid #DADADA;">
									<img width="60" height="60" src="{$list['station_icon']}">
								</span>
							</div>
							{/if}
						</li>
						<li class="i">
							<div class="form_ul_div clear more-z-index">
								<span  class="title">客服热线：</span>
								<input type="text" value="{$list['customer_hotline']}" name='customer_hotline' style="width:180px;">
								<span  class="more-index">办卡热线：</span>
								<input type="text" value="{$list['card_hotline']}" name='card_hotline' style="width:180px;">
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
								<input type="text" value="{$list['address']}" name='address' style="width:400px;" id="detailed_address"/>
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