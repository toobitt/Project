{code}
$arr['file_title'] = '图片批量上传';
$arr['upload_url'] = $_configs['tuji_api']['protocol'] . $_configs['tuji_api']['host'] . '/' . $_configs['tuji_api']['dir'] . 'tuji.php?a=add_new_tuji';
$arr['file_types'] = $_configs['flash_image_type'];
$arr['description'] = 'More Image Upload';
$arr['flagId'] = 'more_imageFlag';
$arr['button_left'] = '900px';
$arr['button_top'] = '125px';
$arr['padding_left'] = '5';
$arr['padding_top'] = '2';
$arr['admin_name'] = $_user['user_name'];
$arr['admin_id'] = $_user['id'];
$arr['token'] = $_user['token'];
$arr['mid'] = $_INPUT['mid'];
$arr['upload_type'] = 1;
$params = json_encode($arr);
{/code}
<script type="text/javascript">
    var params = '{$params}';
    $(function(){
    	top.livUpload.showTemplate(params);
    	top.livUpload.uploadMode = false;
    	top.livUpload.SWF.setButtonText("<span class='white'>选择图片创建</span>");
    	/*图片的排序*/
    	hg_tuji_pic_order('img_list');
    });

    function hg_startImageUpload()
 	{
 		top.livUpload.startUploadFile(true,true);
 	}

	function hg_fixStartImageUpload()
	{
		top.livUpload.startUploadFile(false,true);
	}
</script>

<div style="width:585px;float:left;">
	<form action="run.php?mid={$_INPUT['mid']}"   method="post" enctype="multipart/form-data" name="tuji_form" id="tuji_form"  onsubmit="return hg_ajax_submit('tuji_form')" >	
		<div id="tuji_base_info" style="border-bottom:1px dotted gray;">
			{code}
				$attr_tuji_sortname = array(
					'class' => 'down_list',
					'show' => 'tuji_sort_show',
					'width' => 100,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'is_sub' => 1
				);
				
				$tuji_sort_default = -1;
				$tuji_sortarray[$tuji_sort_default] = '图集类别';
				if(!$formdata['tuji_sort_id'])
				{
					$formdata['tuji_sort_id'] = $tuji_sort_default;
				}
				
				foreach($tuji_sortname[0] as $k => $v)
				{
					$tuji_sortarray[$v['id']] = $v['sort_name'];
				}
			{/code}
			<!--
			<div id="tuji_sort" style="float:left;">
				{template:form/search_source,tuji_sort_id,$formdata['tuji_sort_id'],$tuji_sortarray,$attr_tuji_sortname}
			</div>
			-->
			<input  type="text" name="title"  id="title"  class="info-title info-input-left t_c_b"  autocomplete="off"  style="width:578px;height:18px;" value="{if $formdata['title']}{$formdata['title']}{else}在这里添加标题{/if}" onfocus="text_value_onfocus(this,'在这里添加标题');" onblur="text_value_onblur(this,'在这里添加标题');hg_check_ischange();" />
			<div id="big_content">
				<textarea rows="2"  name="comment" id="comment"  class="info-description info-input-left t_c_b"  style="height:96px;width:578px;margin-top:10px;"  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');"  onkeyup="hg_onkeyup_copy(this);">{if $formdata['comment']}{$formdata['comment']}{else}这里输入描述{/if}</textarea>
				<div style="width:100%;height:28px;">
					<input type="checkbox" name="likeup" id="likeup" style="margin-top:5px;float:left;" onclick="hg_copy_comment();" />
					<a style="float:left;margin-top:7px;margin-left:10px;cursor:pointer;" href="javascript:void(0);"  onclick="hg_checkbox();">所包含图片默认描述</a>
				</div>
				<textarea rows="2"  name="default_comment" id="default_comment"  class="info-description info-input-left t_c_b"  style="height:96px;width:578px;"  onfocus="text_value_onfocus(this,'这里输入默认描述');" onblur="text_value_onblur(this,'这里输入默认描述');">{if $formdata['default_comment']}{$formdata['default_comment']}{else}这里输入默认描述{/if}</textarea>
				<div class="info-left-top" style="margin-top:10px;">关键字</div>
				<div class="info-left-bottom"  style="margin-top:10px;">
					<input type="text"  name="keywords" id="keywords"  class="info-title info-input-left t_c_b"  value="{if $formdata['keywords']}{$formdata['keywords']}{else}在这里输入关键字{/if}" onfocus="text_value_onfocus(this,'在这里输入关键字');" onblur="text_value_onblur(this,'在这里输入关键字');"  style="width:99%;"/>
				</div>
				
				<div style="height:250px;width:550px;margin-top:20px;" id="tuji_sort">
					<div class="info-left-top" style="margin-top:10px;">分类</div>
					{code}
						$hg_attr['node_en'] = 'tuji_node';
						$hg_attr['_callcounter'] = 1;
					{/code}
					{template:unit/class,tuji_sort_id,$formdata['tuji_sort_id'],$node_data}
				</div>
			</div>

			<div style="width:100%;height:28px;margin-top:50px;">
				<!--
				<input type="checkbox" name="auto_cover" id="auto_cover" style="float:left;" {if $formdata['auto_cover']}checked="checked"{/if} />
				<a style="float:left;margin-top:3px;margin-left:10px;cursor:pointer;" onclick="hg_checkboxOn('auto_cover');">自动设置封面</a>
				-->
				<input type="checkbox" name="is_namecomment" id="is_namecomment"   style="float:left;margin-left:10px;" {if $formdata['is_namecomment']}checked="checked"{/if} />
				<a style="float:left;margin-top:3px;margin-left:10px;cursor:pointer;" onclick="hg_checkboxOn('is_namecomment');">图片名作为描述</a>
				<input type="checkbox" name="is_orderby_name" id="is_orderby_name" style="float:left;margin-left:10px;" {if $formdata['is_orderby_name']}checked="checked"{/if} />
				<a style="float:left;margin-top:3px;margin-left:10px;cursor:pointer;" onclick="hg_checkboxOn('is_orderby_name');">以图片名排序</a>
				<!--
				<input type="checkbox" name="is_add_water" id="is_add_water" style="float:left;margin-left:10px;" {if $formdata['is_add_water']}checked="checked"{/if} />
				<a style="float:left;margin-top:3px;margin-left:10px;cursor:pointer;" onclick="hg_checkboxOn('is_add_water');">加水印</a>
				-->
				<input type="submit" value="保存基本信息" class="button_6" style="float:right;display:none;" id="save_base_info" />
			</div>
		</div>
		<input type="hidden" id="stitle" value="{$formdata['title']}" />
		<input type="hidden" name="a" value="{$a}" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>

	<div style="width:100%;height:600px;overflow:auto;">
		<form action="run.php?mid={$_INPUT['mid']}"   method="post" enctype="multipart/form-data" name="tuji_picform" id="tuji_picform"  onsubmit="return hg_ajax_submit('tuji_picform')" >
			<div style="width:100%;height:30px;margin-top:10px;">
				<input type="button" id="base_info"  value="基本信息" class="button_4"  style="display:none;float:left;" onclick="hg_showAllImage();" />
				<input type="button" id="show_all"   value="列出所有" class="button_4"  onclick="hg_showAllImage();" style="float:left;" />
				<div id="more_imageFlag"  class="localurl"  style="float:left;"></div>
				<input type="button" value="直接创建" style="float:right;display:none;"  class="button_6" id="direct_create" onclick="hg_direct_create_tuji('tuji_form');" />
				<input type="submit" id="save_tuji" value="保存图片信息" class="button_6" style="float:right;display:none;" /> 
			</div>
			<!-- 显示图片列表的容器 -->
			<div style="width:99%;overflow:auto;margin-top:10px;height:490px;" id="img_list"></div>
			<input type="hidden" name="pic_cover_id" id="pic_cover_id" value="0" />
			<!-- 显示按钮的容器 -->
			<div id="button_content" style="display:none;">
				<span id="uploadStatus"></span>
				<input class="button_6_14" style="margin-left:211px;"  type="button" onclick="hg_fixStartImageUpload();" value="确定并继续添加" />
				<input class="button_6_14"  type="button" onclick="hg_startImageUpload();" value="确定" />
			</div>
			<input type="hidden" name="a"		value="save_image_info" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="infrm"	value="{$_INPUT['infrm']}" />
			<input type="hidden" name="tuji_id" value="{$$primary_key}" />
		</form>
	</div>
</div>
