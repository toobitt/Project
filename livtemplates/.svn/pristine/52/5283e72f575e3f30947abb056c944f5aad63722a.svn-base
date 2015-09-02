{template:head}
{css:hg_sort_box}
{css:common/common_category}
{js:live/my-ohms}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{js:common/ajax_upload}
{css:common/common_form}
{css:column_node}
{js:column_node}
{js:carpark/carpark}
{css:ad_style}
{css:carpark}
{code}

	/*所有选择控件基础样式*/
	$all_select_style = array(
		'class' 	=> 'down_list',
		'state' 	=> 	0,
		'is_sub'	=>	1,
	);
{/code}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
	function hg_get_city_by_province(obj)
	{
		var url = "run.php?mid="+gMid+"&a=show_city&province_id="+$(obj).attr('attrid');
		hg_ajax_post(url);
	}

	function hg_show_citys(html)
	{
		$('#city_box').html(html);
	}

	function hg_get_area_by_city(obj)
	{
		var url = "run.php?mid="+gMid+"&a=show_area&city_id="+$(obj).attr('attrid');
		hg_ajax_post(url);
	}

	function hg_show_areas(html)
	{
		$('#area_box').html(html)
	}

	function hg_select_server_time_text(obj)
	{
		var state = $(obj).attr('checked');
		var name  = $(obj).attr('_name');
		var type = parseInt($(obj).val());
		var _id = 0;
		if($('.checkbox-server_time:last').length)
		{
			 _id =  parseInt($('.checkbox-server_time:last').attr('_id')) + 1;
		}
		if(state)
		{
			var url = "run.php?mid="+gMid+"&a=create_server_time_list&type="+type+"&key="+_id+"&name="+name;
			hg_ajax_post(url);
		}
	}

	function hg_over_create_server_time_list(html)
	{
		$('#server_time_top_box').append(html);
	}

	function hg_select_fees_text(obj)
	{
		var state = $(obj).attr('checked');
		var name  = $(obj).attr('_name');
		var type = parseInt($(obj).val());
		var _id = 0;
		if($('.checkbox-fees:last').length)
		{
			_id =  parseInt($('.checkbox-fees:last').attr('_id')) + 1;
		}
		if(state)
		{
			var url = "run.php?mid="+gMid+"&a=create_fees_list&type="+type+"&key="+_id+"&name="+name;
			hg_ajax_post(url);
		}
	}

	function hg_over_create_fees_list(html)
	{
		$('#fees_top_box').append(html);
	}
</script>
<style>
.date-time{margin-top: -3px;}
.date-time li{width:100%;margin: 5px 0 0 5px;display: -webkit-box;display:-moz-box}
.date-time{width: 610px;float:left}
.date-time .time{width: 60px;}
.date-time .b_time{display: -webkit-box;display:-moz-box}
.date-time input[type="checkbox"]{vertical-align: sub;}
.date-time .date{margin: 2px 0 0 5px;display: block;}
.date-time  .time-info{margin-left: 10px;}
.date-time .time-copy{width: 20px;height: 20px;background: #6ea5e8;margin: 3px 0 0 10px;display:none;cursor:pointer;border-radius: 10px;font-size: 14px;color: #fff;line-height: 20px;text-align: center;}
.date-time li:hover .time-copy{display:block;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}站点</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">类型：</span>
								{code}
									$type_item_source = $all_select_style;
									$type_item_source['show'] = 'carpark_item_show';
									$carpark_type[0] = '站点类型';
									foreach($carpark_types[0] as $k =>$v)
									{
										$carpark_type[$v['id']] = $v['name'];
									}
									if($type_id)
									{
										$carpark_type_default = $type_id;
									}
									else
									{
										$carpark_type_default = 0;
									}
								{/code}
								<div>{template:form/search_source,type_id,$carpark_type_default,$carpark_type,$type_item_source}</div>
								<span  class="title">站点名称：</span>
								<input type="text" value="{$name}" name='name' required="true" style="width:257px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">物业单位：</span>
								{code}
									$unit_item_source = $all_select_style;
									$unit_item_source['show'] = 'carpark_unit_item_show';
									$unit_default = 0;
									$manage_unit[0] = '物业单位';
									foreach($manage_units[0] as $k =>$v)
									{
										$manage_unit[$v['id']] = $v['name'];
									}
									if($unit_id)
									{
										$unit_default = $unit_id;
									}
									else
									{
										$unit_default = 0;
									}
								{/code}
								<div>{template:form/search_source,unit_id,$unit_default,$manage_unit,$unit_item_source}</div>
								<span  class="title">备案号：</span>
								<input type="text" value="{$icp_number}" name='icp_number' style="width:257px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">编号：</span>
								<input type="text" value="{$parking_num}" name='parking_num' style="width:257px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">联系电话：</span>
								<input type="text" value="{$tel}" name='tel' style="width:173px;" />
							</div>
							<div class="form_ul_div">
								<span  class="title">泊位数：</span>
								<input type="text" value="{$parking_space}" name='parking_space' style="width:173px;" />
							</div>
							<div class="form_ul_div">
								<span  class="title">空车位：</span>
								<input type="text" value="{$empty_space}" name='empty_space' style="width:173px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title" style="display:block;float:left;">服务时间：</span>
								{code}
									if($server_time)
									{
										$server_time_arr = $server_time;
									}
									else
									{
										$server_time_arr = array();
									}
									$config_server_time_arr = array(
											array('name' => '平时',	'server_type' => '1'),
											array('name' => '工作日','server_type' => '2'),
											array('name' => '周末',	'server_type' => '3'),
											array('name' => '特定',	'server_type' => '4'),
										);
								{/code}
									<div id="server_time_top_box">
									{foreach $server_time_arr AS $key => $val}
										<div class="service-time" style="width:{if $val['server_type'] == 4}650px;{else}350px;{/if}">
											<input class="checkbox-server_time" _id="{$key}" type="checkbox" name="server_time[{$key}]" value="{$val['server_type']}" {if $val['id']}checked="checked"{/if}/>
											<span>{$val['name']}</span>
											{if $val['server_type'] == 4}
											<input type="text" name="start_date_{$key}" value="{$val['start_date']}"  style="margin-left:12px;"/> - <input type="text" name="end_date_{$key}" value="{$val['end_date']}" />
											{/if}
											<input type="text" name="start_time_{$key}" value="{$val['start_time']}"  {if $val['server_type'] != 2} style="margin-left:12px;"{/if} /><span>-</span><input type="text" name="end_time_{$key}" value="{$val['end_time']}" />
										</div>
									{/foreach}
									</div>
									<div class="add-area">
									    <div class="checkbox-list">
										     {foreach $config_server_time_arr AS $key => $val}
										       <div class="checkbox-item">
										         <input type="checkbox" value="{$val['server_type']}"   onclick="hg_select_server_time_text(this);" _name="{$val['name']}" />
											     <span class="name">{$val['name']}</span>
											  </div>
										     {/foreach}
										 </div>
									     <div class="add-button">+</div>
									</div>
							</div>
						</li>
						<li class="i business-hours">
							<div id="ohms-instance" style="position:absolute;display:none;z-index:999999999"></div>
							<div class="form_ul_div" style="height:210px;padding-bottom:10px;">
								<span  class="title">营业时间：</span>
								{code}
									$config_b_time_arr = $arr = array(
												array(
													'date' => 0,
													'date_text' => '周日',
													'stime' => '',
													'etime' => '',
												),
												array(
													'date' => 1,
													'date_text' => '周一',
													'stime' => '',
													'etime' => '',
												),
												array(
													'date' => 2,
													'date_text' => '周二',
													'stime' => '',
													'etime' => '',
												),
												array(
													'date' => 3,
													'date_text' => '周三',
													'stime' => '',
													'etime' => '',
												),
												array(
													'date' => 4,
													'date_text' => '周四',
													'stime' => '',
													'etime' => '',
												),
												array(
													'date' => 5,
													'date_text' => '周五',
													'stime' => '',
													'etime' => '',
												),
												array(
													'date' => 6,
													'date_text' => '周六',
													'stime' => '',
													'etime' => '',
												),
											);
								{/code}
								<div class="date-time">
									<ul>
									{foreach $config_b_time_arr AS $_kk => $_vv}
										{code}
											$flag = 0;
											if($business_time_arr[$_vv['date']])
											{
												$flag = 1;
											}
										{/code}
										<li>
											<div class="b_time">
												<input type="checkbox" name="date[{$_kk}]" value="{$_kk}" {if $flag}checked{/if}/><span class="date">{$_vv['date_text']}</span>
												<div class="time-info">
													<input type="text" class="time item-t" name="b_stime[]" value="{if $flag}{$business_time_arr[$_vv['date']]['stime']}{/if}"/>
													<span>-</span>
													<input type="text" class="time item-t" name="b_etime[]" value="{if $flag}{$business_time_arr[$_vv['date']]['etime']}{/if}"/>
												</div>
											</div>
											{if !$_kk==0}
											<div class="time-copy" title="点击复制上一条时间">c</div>
											{/if}
										</li>
									{/foreach}	
									</ul>
								
								</div>
							</div>
						</li>
						
						
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title" style="display:block;float:left;">收费标准：</span>
									{code}
										if($collect_fees)
										{
											$collect_fees_type = $collect_fees;
										}
										else
										{
											$collect_fees_type = array();
											$car_type_default = -1;/*车型的默认值*/
											$charge_unit_default = -1;/*计费单位默认值*/
										}
										
										$collect_fees_type_arr = array(
												array('name' => '人工收费','fees_type' => '1'),
												array('name' => '咪表收费','fees_type' => '2'),
												array('name' => '包月收费','fees_type' => '3'),
												array('name' => '免费'	,'fees_type' => '4'),
										);
										
									{/code}
									<div id="fees_top_box">
										{foreach $collect_fees_type AS $kk => $vv}
										<div class="collect-fee">
											<input type="checkbox" name="fees[{$kk}]" value="{$vv['fees_type']}"  style="float:left; margin-top:5px;" {if $vv['id']}checked="checked"{/if}  class="checkbox-fees" _id="{$kk}"/>
											<span>{$vv['name']}</span>
											{if $vv['fees_type'] != 3}
											<input type="text" name="s_time[{$kk}]" value="{$vv['start_time']}"  style="margin-left:{if $vv['fees_type'] == 4}34px;{else}12px;{/if}" />
											<span> - </span>
											<input type="text" name="e_time[{$kk}]" value="{$vv['end_time']}"  	style="margin-left:5px;" />
											{/if}
											{code}
												${'car_type_style_' . $kk} = $all_select_style;
												${'car_type_style_' . $kk}['show'] = 'car_type_item_show_' . $kk;
												$hidden_name = 'car_type_' . $kk;
												$style_name = ${'car_type_style_' . $kk};
												if($vv['car_type'])
												{
													$car_type_default = $vv['car_type'];
												}
											{/code}
											<div style="float:left;{if $vv['fees_type'] == 3}margin-left:11px;{else}margin-left:2px;{/if}">{template:form/search_source,$hidden_name,$car_type_default,$_configs['car_type'],$style_name}</div>
											<input type="text" name="instruction[{$kk}]" value="{$vv['instructions']}" style="margin-left:2px;{if $vv['fees_type'] == 3}width:200px;{elseif $vv['fees_type'] == 4}width:242px;{else}width:85px;{/if}" />
											{if $vv['fees_type'] != 4}
											<input type="text" name="price[{$kk}]" value="{$vv['price']}" style="margin-left:2px;width:18px;" />
											<span>元/</span>
											{code}
												${'charge_unit_style_' . $kk} = $all_select_style;
												${'charge_unit_style_' . $kk}['show'] = 'charge_unit_item_show_' . $kk;
												$c_hidden_name = 'charge_unit_' . $kk;
												$c_style_name = ${'charge_unit_style_' . $kk};
												if($vv['charge_unit'])
												{
													$charge_unit_default = $vv['charge_unit'];
												}
											{/code}
											<div style="float:left;margin-left:3px;">{template:form/search_source,$c_hidden_name,$charge_unit_default,$_configs['charge_unit'],$c_style_name}</div>
											{/if}
										</div>
										{/foreach}
									</div>
									<div class="add-area">
									    <div class="checkbox-list">
										     {foreach $collect_fees_type_arr AS $key => $val}
										       <div class="checkbox-item">
										         <input type="checkbox" value="{$val['fees_type']}"   onclick="hg_select_fees_text(this);" _name="{$val['name']}" />
											     <span class="name">{$val['name']}</span>
											  </div>
										     {/foreach}
										 </div>
									     <div class="add-button">+</div>
									</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">最低收费：</span>
								<input type="text" value="{$price_text}" name='price_text' style="width:440px;" placeholder="如: x元/单位"/>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">价格描述：</span>
								<textarea name='price_brief'>{$price_brief}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div" style="height:130px;">
								<span class="title" style="display:block;float:left;">结构形式：</span>
							    <div class="struct-style" style="float:left;width:610px;">
									<div class="struct-list">
											<input type="checkbox" name="struct_type[]" value="1" {if in_array(1,$struct_type_arr)}checked="checked"{/if} />
											<span>露天</span>
									</div>
									<div class="struct-list">
											<input type="checkbox" name="struct_type[]" value="2" {if in_array(2,$struct_type_arr)}checked="checked"{/if} />
											<span>室内</span>
									</div>
									<div class="struct-list" style="clear:both;">
											<input type="checkbox" name="struct_type[]" value="3" {if in_array(3,$struct_type_arr)}checked="checked"{/if} />
											<span>地下</span>
									</div>
									<div class="struct-list">
											<input type="checkbox" name="struct_type[]" value="4" {if in_array(4,$struct_type_arr)}checked="checked"{/if} />
											<span>地上</span>
									</div>
									<div class="struct-list" style="width:130px;">
											<input type="checkbox" name="struct_type[]" value="9" {if in_array(9,$struct_type_arr)}checked="checked"{/if} />
											<span>多层</span>
											<input type="text" name="building_storey" style="width:50px;" value="{$building_storey}" />
											<span>层</span>
									</div>
									<div class="struct-list" style="clear:both;">
											<input type="checkbox" name="struct_type[]" value="5" {if in_array(5,$struct_type_arr)}checked="checked"{/if} />
											<span>机械式</span>
									</div>
									<div class="struct-list">
											<input type="checkbox" name="struct_type[]" value="6" {if in_array(6,$struct_type_arr)}checked="checked"{/if} />
											<span>坡道式</span>
									</div>
									<div class="struct-list">
											<input type="checkbox" name="struct_type[]" value="7" {if in_array(7,$struct_type_arr)}checked="checked"{/if} />
											<span>混凝土结构</span>
									</div>
									<div class="struct-list">
											<input type="checkbox" name="struct_type[]" value="8" {if in_array(8,$struct_type_arr)}checked="checked"{/if} />
											<span>钢结构</span>
									</div>
									<div class="struct-list" style="width:130px;">
											<input type="checkbox" name="struct_type[]" value="10" {if in_array(10,$struct_type_arr)}checked="checked"{/if} />
											<span>其他</span>
											<input type="text" name="other_struct_type" style="width:50px;"  value="{$other_struct_type}" />
									</div>
									<div class="struct-list" style="width:130px;clear:both;">
											<span>入口数</span>
											<input type="text" name="entrance_num" style="width:50px;" value="{$entrance_num}" />
									</div>
									<div class="struct-list" style="width:130px;">
											<span>出口数</span>
											<input type="text"  name="exitus_num" style="width:50px;"  value="{$exitus_num}" />
									</div>
									<div class="struct-list" style="width:130px;">
											<input type="checkbox" name="is_inout_same" {if $is_inout_same}checked="checked"{/if} />
											<span>出入口为同一个</span>
									</div>
									<div class="struct-list" style="width:160px;">
											<span>出入口限高</span>
											<input type="text" name="limited_height" style="width:50px;" value="{$limited_height}" />
											<span>米</span>
									</div>
								</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">主要设施：</span>
								<input type="text" value="{$main_device}" name='main_device' style="width:440px;" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">备注描述：</span>
								<textarea name='description'>{$description}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">实景照片：</span>
								<div class="photo-area">
									<div class="add-photo">添加图片</div>
									<div class="photo-list">
										{foreach $photo AS $k_k => $pic}
										<div class="photo-item" _id="{$k_k}">
											<img src="{$pic['img']}">
	         								<span class="delete">x</span>
	         								<input type="hidden" name="photo[]" value="{$pic['id']}" />
         								</div>
         								{/foreach}
									</div>
									<input type="file" name='photo' style="display:none;" class="photo-file"/>
								</div>
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
									if($province_id)
									{
										$province_default = $province_id;
									}
									foreach($province[0] AS $k => $v)
									{
										$province_data[$v['id']] = $v['name'];
									}
									
									$city_style = $all_select_style;
									$city_style['show'] = 'city_item_show';
									$city_default = -1;
									$city_data[$city_default] = '所有市';
									if($city_id)
									{
										$city_default = $city_id;
									}
									if($now_city_data)
									{
										foreach($now_city_data AS $k => $v)
										{
											$city_data[$v['id']] = $v['city'];
										}
									}

									$area_style = $all_select_style;
									$area_style['show'] = 'area_item_show';
									$area_default = -1;
									$area_data[$area_default] = '所有区';
									if($area_id)
									{
										$area_default = $area_id;
									}
									if($now_area_data)
									{
										foreach($now_area_data AS $k => $v)
										{
											$area_data[$v['id']] = $v['area'];
										}
									}
								{/code}
								<div>{template:form/search_source,province_id,$province_default,$province_data,$province_style}</div>
								<div style="margin-left:10px;float:left;" id="city_box">{template:form/search_source,city_id,$city_default,$city_data,$city_style}</div>
								<div style="margin-left:10px;float:left;" id="area_box">{template:form/search_source,area_id,$area_default,$area_data,$area_style}</div>
							</div>
							<div class="form_ul_div clear">
								<span class="title"></span>
								<input type="text" value="{$address}" name='address' style="width:440px;" id="detailed_address"/>
							</div>
							<div class="form_ul_div clear">
								<span class="title"></span>
								{code}
									$hg_bmap = array(
										'height' => 480,
										'width'  => 600,
										'longitude' => $baidu_longitude? $baidu_longitude : '0', 
										'latitude'  => $baidu_latitude? $baidu_latitude : '0',
										'zoomsize'  => 13,
										'areaname'  => $city_name?$city_name:'',
										'is_drag'   => 1,
									);
								{/code}
								{template:map/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">区域划分：</span>
								{code}
									$hg_attr['exclude'] = 1;
									$hg_attr['node_en'] = 'carpark_district';
								{/code}
								{template:unit/class,district_id,$district_id,$node_data}
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
<script type="text/x-jquery-tmpl" id="photo-tpl">
   <div class="photo-item">
         <img />
         <span class="delete">x</span>
   </div>
</script>