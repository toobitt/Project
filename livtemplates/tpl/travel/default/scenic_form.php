{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{code}
$list = $formdata[0];
//$sorts = $sorts[0];
$css_attr['style'] = 'style="width:100px"';
$re = $list['sort_id']?$list['sort_name']:'请选择分类';
{/code}
{code}
//echo hg_editor('introduce',$list['introduce']);id form-edit-box
{/code}
{css:ad_style}
{css:column_node}
{js:column_node}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del{display:none;width:16px;height:16px;cursor:pointer;float:right;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 140px;top: 4px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
</style>
<script type="text/javascript">
function change_province()
{
	var url= './run.php?mid='+gMid+'&a=get_city&province='+$('#province').val();
	hg_ajax_post(url);
}

function city_back(json)
{	
	var data = $.parseJSON(json);
	$('#city').html(get_city_html('city',data));
}

function get_city_html(name,data)
{
	var html = '<select name='+name+'><option  value="0">-请选择-</option>';
	for (var i in data)
	{
		
		html = html + '<option onclick= "change_city('+i+')"    value="'+i+'">' +data[i] + '</option>';
	}
	html = html + '</select>';
	return html;
}

function change_city(i)
{
	var url= './run.php?mid='+gMid+'&a=get_area&city='+i;
	hg_ajax_post(url);
}

function area_back(json)
{	
	var data = $.parseJSON(json);
	$('#area').html(get_area_html('area',data));
}

function get_area_html(name,data)
{
	var html = '<select name='+name+'><option  value="0">-请选择-</option>';
	for (var i in data)
	{
		
		html = html + '<option value="'+i+'">' +data[i] + '</option>';
	}
	html = html + '</select>';
	return html;
}

function hg_addArgumentDom()
{
	var div = "<div class='form_ul_div clear'><span class='title'>标题: </span><input type='text' name='argument_name[]' style='width:100px;' class='title'>&nbsp;&nbsp;概要: <input type='text' name='value[]' size='55'/>&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
	$('#extend').append(div);
	hg_resize_nodeFrame();
}
function hg_optionTitleDel(obj)
{
	if(confirm('确定删除该概要吗？'))
	{
		$(obj).parent().parent().remove();
	}
	hg_resize_nodeFrame();
}
$(document).ready(function(){
	var t1 = $("form select[name=sort_id]").find('option:selected').val();
	var c1 = $("input[name=referto]").val() + '&sortid=' + t1;
	$("input[name=referto]").val(c1);

	$("form select[name=sort_id]").change(function(){
		var t2 = $("form select[name=sort_id]").find('option:selected').val();
		var c2 = $("input[name=referto]").val() + '&sortid=' + t2;
		$("input[name=referto]").val(c2);
	});	
});

function show_map()
{
	var url= './run.php?mid='+gMid+'&a=get_map&address='+$('#address').val();
	hg_ajax_post(url);
}
function map_back(json)
{	
	alert(json);
	$('#map').html(json);
	hg_resize_nodeFrame();
}

</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
			{if $_INPUT['fid']}
			{if $_INPUT['id']}
				<h2>编辑景点信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景点名：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
								<font class="important">景点名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景点描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区地址：</span>
								<input type="text" value="{$list['address']}" id ='address' name='address' style="width:440px;">
							</div>
						</li>
						<li class="i" id="map">
							{code}
							$hg_map = array(
									'height'=>180,
									'width'=>600,							
									'longitude'=>$list['longitude'],         	//经度
									'latitude'=>$list['latitude'], 			    //纬度
									'zoomsize'=>13,          					//缩放级别，1－21的整数
									'areaname'=>$list['address'],          		//显示地区名称，纬度,经度与地区名称二选1
									'is_drag'=>1,            					//是否可拖动 1－是，0－否
									'objid'=>'address', 
								);
							{/code}
							{template:form/google_map,longitude,latitude,$hg_map}
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景点等级：</span>
								<input type="text" value="{$list['grade']}" name='grade' style="width:30px;">&nbspA
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">关键字：</span>
								<input type="text" value="{$list['keywords']}" name='keywords' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">景点示意图：</span>
								<input type="file" name="Filedata" id="Filedata"  value="submit">        
							</div>
						</li>
					</ul>
					{else}
					<h2>新增景点信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景点名：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
								<font class="important">景点名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景点描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景点地址：</span>
								<input type="text" value="{$list['address']}" id ='address' name='address' style="width:440px;">
							</div>
						</li>
						<li class="i" id="map">
							{code}
							$hg_map = array(
									'height'=>180,
									'width'=>600,							
									'longitude'=>$list['longitude'],         	//经度
									'latitude'=>$list['latitude'], 			    //纬度
									'zoomsize'=>13,          					//缩放级别，1－21的整数
									'areaname'=>$city_name[0],          		//显示地区名称，纬度,经度与地区名称二选1
									'is_drag'=>1,            					//是否可拖动 1－是，0－否
									'objid'=>'address', 
								);
							{/code}
							{template:form/google_map,longitude,latitude,$hg_map}
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景点等级：</span>
								<input type="text" value="{$list['grade']}" name='grade' style="width:30px;">&nbspA
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">关键字：</span>
								<input type="text" value="{$list['keywords']}" name='keywords' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title" >景点示意图：</span>
								<input type="file" name="Filedata" id="Filedata"  value="submit">        
							</div>
						</li>
					</ul>
				{/if}
			{else}
			{if $_INPUT['id']}
				<h2>编辑景区信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区名：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
								<font class="important">景区名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<input type="hidden" name="sort_id" id="sort_id" value="{$list['sort_id']}" />
								<span class="title">景区分类：</span>
								{code}
									$hg_attr['node_en'] = 'scenic_sort';
								{/code}
								
								{template:unit/class,sort_id,$list['sort_id'],$node_data}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
			            <li class="i  clear" id="select">
							<div class="form_ul_div">
								<div style="float:left;width:60px;">国家:中国</div>
								<div style="float:left;width:30px;">省份</div>
								{code}
									$attr_pro = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_pro',
										'width' => 180,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
										'onclick' => 'change_province();'
									);
									$provinces[0]['-1'] = "-请选择-";
									$list['province'] = $list['province'] ? $list['province'] : -1;
								{/code}
								
								{template:form/search_source,province,$list['province'],$provinces[0],$attr_pro}
								<div style="float:left;width:30px;">市</div>
								<div id='city' style="float:left;width:100px;">
									{if $list['city']}
									<select name='city' ><option>{$list['city']}</option></select>
									{else}
									<select name='city' ><option>-请选择-</option></select>
									{/if}
								</div>
								<div style="float:left;width:30px;">区域</div>
								<div id='area' style="float:left;width:100px;">
									{if $list['area']}
									<select name='area'><option>{$list['area']}</option></select>
									{else}
									<select name='area' ><option>-请选择-</option></select>
									{/if}
								</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区地址：</span>
								<input type="text" value="{$list['address']}" id ='address' name='address' style="width:440px;">
							</div>
						</li>
						<li class="i" id="map">
							{code}
							$hg_map = array(
									'height'=>180,
									'width'=>600,							
									'longitude'=>$list['longitude'],         	//经度
									'latitude'=>$list['latitude'], 			    //纬度
									'zoomsize'=>13,          					//缩放级别，1－21的整数
									'areaname'=>$list['address'],          			//显示地区名称，纬度,经度与地区名称二选1
									'is_drag'=>1,            					//是否可拖动 1－是，0－否
									'objid'=>'address', 
								);
							{/code}
							{template:form/google_map,longitude,latitude,$hg_map}
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区等级：</span>
								<input type="text" value="{$list['grade']}" name='grade' style="width:30px;">&nbspA
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">关键字：</span>
								<input type="text" value="{$list['keywords']}" name='keywords' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">示意图：</span>
								<input type="file" name="Filedata" id="Filedata"  value="submit">        
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">导览图：</span>
								<input type="file" name="Filedaolan" id="Filedaolan"  value="submit">        
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								{code}
		       					$picinfo = unserialize($list['guidepic']);
		       	 				$url = $picinfo['host'].$picinfo['dir'].'400x300/'.$picinfo['filepath'].$picinfo['filename'];
		       	 				{/code}
		       	 				<img src="{$url}" id="img_{$v['id']}"  />	      
							</div>
						</li>
					</ul>
					{else}
					<h2>新增景区信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区名：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:440px;">
								<font class="important">景区名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<input type="hidden" name="sort_id" id="sort_id" value="{$list['sort_id']}" />
								<span class="title">景区分类：</span>
								{code}
									$hg_attr['node_en'] = 'scenic_sort';
								{/code}
								
								{template:unit/class,sort_id,$list['sort_id'],$node_data}
							</div>
						</li>	
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
			            <li class="i clear" id="select">
							<div class="form_ul_div">
								<div style="float:left;width:60px;">国家:中国</div>
								<div style="float:left;width:30px;">省份</div>
								{code}
									$attr_pro = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_pro',
										'width' => 180,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
										'onclick' => 'change_province();'
									);
									$provinces[0]['-1'] = "-请选择-";
									$list['province'] = $list['province'] ? $list['province'] : -1;
								{/code}
								
								{template:form/search_source,province,$list['province'],$provinces[0],$attr_pro}
								<div style="float:left;width:30px;">市</div>
								<div id='city' style="float:left;width:100px;">
									{if $list['city']}
									<select name='city' ><option>{$list['city']}</option></select>
									{else}
									<select name='city' ><option>-请选择-</option></select>
									{/if}
								</div>
								<div style="float:left;width:30px;">区域</div>
								<div id='area' style="float:left;width:100px;">
									{if $list['area']}
									<select name='area'><option>{$list['area']}</option></select>
									{else}
									<select name='area' ><option>-请选择-</option></select>
									{/if}
								</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区地址：</span>
								<input type="text" value="{$list['address']}" id ='address' name='address' style="width:440px;">
							</div>
						</li>
						<li class="i" id="map">
							{code}
							$hg_map = array(
									'height'=>180,
									'width'=>600,							
									'longitude'=>$list['longitude'],         	//经度
									'latitude'=>$list['latitude'], 			    //纬度
									'zoomsize'=>13,          					//缩放级别，1－21的整数
									'areaname'=>$city_name[0],          		//显示地区名称，纬度,经度与地区名称二选1
									'is_drag'=>1,            					//是否可拖动 1－是，0－否
									'objid'=>'address', 
								);
							{/code}
							{template:form/google_map,longitude,latitude,$hg_map}
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区等级：</span>
								<input type="text" value="{$list['grade']}" name='grade' style="width:30px;">&nbspA
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">关键字：</span>
								<input type="text" value="{$list['keywords']}" name='keywords' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title" >示意图：</span>
								<input type="file" name="Filedata" id="Filedata"  value="submit">        
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">导览图：</span>
								<input type="file" name="Filedaolan" id="Filedaolan"  value="submit">        
							</div>
						</li>
					</ul>
				{/if}
				{/if}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="fid" value="{$_INPUT['fid']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
<script type="text/javascript">
$(function () {
	var old_fn = hg_selected_col;
	
	hg_selected_col = function (name, id) {
		old_fn.apply(this, arguments);
		$('#sort_id').val( id );
	}
});
</script>
{template:foot}