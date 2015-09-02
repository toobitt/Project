{js:jquery-ui-1.8.16.custom.min}
{css:jquery-ui-1.8.16.custom}
{css:jpicker-1.1.6.min}
{css:jpicker}
{js:jpicker-1.1.6.min}
{code}
   $water = $formdata['water'];
   unset($formdata['water']);
   $resource_url = RESOURCE_URL;
   $slider_percent = $water['opacity']*100;
   $wposition = $water['position'];
   $water_color = $water['water_color'];
   $water_type = $water['water_type'];
   
   $attr_font = array(
		'class' => 'transcoding down_list',
		'show' => 'water_font_show',
		'width' => 60,/*列表宽度*/
		'state' => 0,/*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	
	$attr_angle = array(
		'class' => 'transcoding down_list',
		'show' => 'water_angle_show',
		'width' => 40,/*列表宽度*/
		'state' => 0,/*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
{/code}
<script type="text/javascript">

    var slider_percent = '{$slider_percent}';
    var wposition = '{$wposition}';
    var wcolor = '{$water_color}';
    var wtype = '{$water_type}';
	function hg_change_slider()
	{
		   var slide_val = $("#slider").slider("value");
		   var percent = parseInt(slide_val)/100;
		   $('#opacity_num').text(percent);
		   $('#opacity').val(percent);
		   $('#img_preview').css('opacity',percent);
	}

	/*检测水印图片的宽与高*/
	function hg_check_val(obj,type)
	{
		var tip = '输入的数值必须在制定的范围内';
		var val = parseInt($(obj).val());
		if(type == 1)
		{
			if(val < 100 || val > 200)
			{
				alert(tip);
				$(obj).val(100);
			}
		}
		else if(type == 2)
		{
			if(val < 50 || val > 100)
			{
				alert(tip);
				$(obj).val(50);
			}
		}
		else
		{
			if(val < 0 || val > 30)
			{
				alert(tip);
				$(obj).val(0);
			}
		}
	}

	/*选择类型之间的切换*/
	
	function hg_select_type(obj,flag)
	{
		$('input[id^="select_type_"]').attr('checked',false);
		$(obj).attr('checked','checked');
		if(flag)
		{
			$('div[name="text_config"]').hide();
			$('div[name="pic_config"]').show();
		}
		else
		{
			$('div[name="text_config"]').show();
			$('div[name="pic_config"]').hide();
		}
	}
	

	$(function(){
		hg_waterUpload();
		$("#slider").slider({
			max:100,
		    min:0,
			slide: hg_change_slider,
			stop:  hg_change_slider
		});
		$("#slider").slider("value",parseInt(slider_percent));
		$('#img_preview').css('opacity',parseInt(slider_percent)/100);

		$('input[name="get_photo_waterpos"]').each(function(){
			if(parseInt($(this).val()) == wposition)
			{
				$(this).attr('checked','checked');
				$('#water_img_'+wposition).css('visibility','visible');
			}
		});

		/*一载入页面就根据水印类型进行显示*/
	
		if(parseInt(wtype))
		{
			hg_select_type('#select_type_1',1);
		}
		else
		{
			hg_select_type('#select_type_0',0);
		}

		/*颜色拾取器部分开始*/
		var jpiker_image = RESOURCE_URL + 'jpiker/';
        $.fn.jPicker.defaults.images.clientPath = jpiker_image;
        $('#water_color').jPicker({
            window:{title:'拖动鼠标拾取颜色',position:{x:'555px',y:'133px'}},
            color:{mode:'h',active:new $.jPicker.Color({hex:'e01aab'})},
            localization:{text:{newColor: '新颜色',currentColor: '当前颜色',ok:'确定',cancel:'取消'}}
        });

        $.jPicker.List[0].color.active.val('hex',wcolor,this);
		/*颜色拾取器部分结束*/

	});
</script>
{css:ad_style}
<style type="text/css">
	.water_type{width:100%;height:40px;border-bottom:1px dotted gray;}
	.left_title{width:56px;float:left;}
	.wleft_box{width:40%;height:100%;}
	.wright_box{width:39%;height:100%;margin-left:40px;}
	.wfl{float:left;}
	.marg{margin-top:2px;cursor:pointer;}
	.lab{margin-left:5px;margin-top:2px;}
	.inerbox{width:67px;height:32px;margin-left:16px;margin-top:12px;}
	.clor{background:#eeeeee;text-align:center;visibility:hidden;}
	.clor div{margin-top:8px;}
	.img_preview{width:270px;height:100%;float:left;margin-left:32%;}
</style>

<div class="ad_middle">
<form class="ad_form h_l" action="run.php?mid={$_INPUT['mid']}" method="post"   id="content_form" name="content_form" onsubmit="return hg_ajax_submit('content_form');">
<h2>配置</h2>
<ul class="form_ul">
{if  $formdata}
{foreach $formdata AS $k => $v}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['var_name']}：</span>
		<input type="hidden" value="{$v['var_name']}" name="st_key[]" />
		<input type="hidden" value="{$v['bundle_id']}" name="st_bundle_id[]" />
		<input type="hidden" value="{$v['module_id']}" name="st_module_id[]" />
		<input type="hidden" value="{$v['type']}" name="st_type[]" />
		<input type="text" value="{$v['value']}" name='st_val[]' class="title" />
	</div>
</li>
{/foreach}
{/if}
</ul>
<input type="hidden" name="a" value="update"  />
<br/>
<input type="submit" id="submit_ok" name="sub" value="更改" class="button_6_14" />
</form>

<form class="ad_form h_l" action="run.php?mid={$_INPUT['mid']}" method="post" id="water_form" name="water_form" onsubmit="return hg_ajax_submit('water_form');">
           <h2>水印配置</h2>
           <div class="water_type">
		   {code}
		   {/code}
		   		<input type="checkbox" value="1" {if $water['water_type']}checked="checked"{/if}  style="float:left;margin-top:8px;" id="select_type_1" name="image_type" onclick="hg_select_type(this,1);" />
		   		<div style="float:left;margin-top:10px;margin-left:10px;cursor:pointer" onclick="hg_select_type('#select_type_1',1)">图片</div>
		   		<input type="checkbox" value="1"  {if !$water['water_type']}checked="checked"{/if} style="float:left;margin-top:8px;margin-left:10px;"  id="select_type_0" name="text_type" onclick="hg_select_type(this,0);" />
		   		<div style="float:left;margin-top:10px;margin-left:10px;cursor:pointer;" onclick="hg_select_type('#select_type_0',0)">文字</div>
		   </div>
		   <div class="water_type"  name="pic_config">
		   		<div class="left_title" style="margin-top:14px;width:68px;">水印透明度:</div>
		   		<div style="width:200px;height:30%;float:left;margin-top:14px;background:green;" id="slider"></div>
		   		<div id="opacity_num" style="width:28px;height:30%;float:left;margin-top:14px;margin-left:10px;">{$water['opacity']}</div>
		   </div>
		   
		   <div class="water_type"  name="pic_config">
		   		<div class="left_title" style="margin-top:14px;">水印大小:</div>
		   		<div class="left_title" style="margin-top:11px;width:200px;">
		   		   <label class="wfl lab">宽:</label>
		   		   <input type="text" name="width" value="{$water['width']}" class="wfl" style="width:30px;margin-left:2px;height:14px;" onblur="hg_check_val(this,1);" />
		   		    <label class="wfl lab">px</label>
		   		    <div style="width:100px;float:left;margin-top:3px;margin-left:5px;">(100<=宽<=200)</div>
		   		</div>
		   		<div class="left_title" style="margin-top:11px;width:200px;">
		   		   <label class="wfl lab">高:</label>
		   		   <input type="text" name="height" value="{$water['height']}" class="wfl" style="width:30px;margin-left:2px;height:14px;" onblur="hg_check_val(this,2);"  />
		   		    <label class="wfl lab">px</label>
		   		    <div style="width:100px;float:left;margin-top:3px;margin-left:5px;">(50<=高<=100)</div>
		   		</div>
		   		<div class="left_title" style="width:200px;margin-top:14px;">
		   		  	(根据设定的大小按比例取图)
		   		</div>
		   </div>
		   
		   <div class="water_type"  name="pic_config">
		   		<div class="left_title" style="margin-top:14px;width:60px;">水印边距:</div>
		   		<input type="text" name="margin" value="{$water['margin']}" class="wfl" style="width:30px;margin-left:2px;height:14px;margin-top:13px;"  onblur="hg_check_val(this,3);"  />
		   		<label class="wfl lab" style="margin-top:13px;">px</label>
		   		<div class="left_title" style="width:200px;margin-top:14px;margin-left:10px;">
		   		  	(0<=边距<=30)
		   		</div>
		   </div>
		   
		   <div class="water_type"  name="text_config" style="display:none;">
		   		<div class="left_title" style="margin-top:14px;">水印文字:</div>
		   		<input type="text" name="water_text" value="{$water['water_text']}" class="wfl" style="width:180px;margin-left:2px;height:14px;margin-top:13px;"  />
		   		<div class="left_title" style="margin-top:14px;margin-left:10px;width:32px;">字体:</div>
		   		<div class="left_title" style="margin-top:11px;width:88px;">{template:form/search_source,water_font,$water['water_font'],$_configs['water_font'],$attr_font}</div>
		   		<div class="left_title" style="margin-top:14px;margin-left:10px;width:32px;">方向:</div>
		   		<div class="left_title" style="margin-top:11px;width:40px;">{template:form/search_source,water_angle,$water['water_angle'],$_configs['water_angle'],$attr_angle}</div>
		   		<div class="left_title" style="margin-top:14px;margin-left:54px;width:32px;">颜色:</div>
		   		<input type="text" id="water_color" name="water_color" value="{$water['water_color']}" class="wfl" style="width:100px;margin-left:2px;height:14px;margin-top:13px;"  />
		   </div>
		   
		   <div class="water_type">
		   		<div class="left_title" style="margin-top:14px;">水印位置:</div>
		   </div>
		   
		   <div class="water_type" style="height:142px;">
		   		<div class="wleft_box  wfl">
		   			<table width="100%"  cellspacing="1" cellpadding="0" style="margin-top:36px;">
				      <tr>
				        <td width="33%" onclick="hg_show_water_pos(this);"><input type="radio" name="get_photo_waterpos"  value="1" class="wfl"><div class="marg">顶部居左</div></td>
				        <td width="33%" onclick="hg_show_water_pos(this);"><input type="radio" name="get_photo_waterpos"  value="2" class="wfl"><div class="marg">顶部居中</div></td>
				        <td onclick="hg_show_water_pos(this);"><input  type="radio" name="get_photo_waterpos"  value="3" class="wfl"><div class="marg">顶部居右</div></td>
				      </tr>
				      <tr>
				        <td onclick="hg_show_water_pos(this);"><input  type="radio" name="get_photo_waterpos"  value="4" class="wfl" ><div class="marg">左边居中</div></td>
				        <td onclick="hg_show_water_pos(this);"><input  type="radio" name="get_photo_waterpos"  value="5" class="wfl" ><div class="marg">图片中心</div></td>
				        <td onclick="hg_show_water_pos(this);"><input  type="radio" name="get_photo_waterpos"  value="6" class="wfl" ><div class="marg">右边居中</div></td>
				      </tr>
				      <tr>
				        <td onclick="hg_show_water_pos(this);"><input  type="radio" name="get_photo_waterpos"  value="7" class="wfl"><div class="marg">底部居左</div></td>
				        <td onclick="hg_show_water_pos(this);"><input  type="radio" name="get_photo_waterpos"  value="8" class="wfl"><div class="marg">底部居中</div></td>
				        <td onclick="hg_show_water_pos(this);"><input  type="radio" name="get_photo_waterpos"  value="9" class="wfl"><div class="marg">底部居右</div></td>
				      </tr>
    				</table>
		   		</div>
		   		<div class="wright_box wfl" style="background:#71AADF;">
		   			<div id="water_img_1"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_2"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_3"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_4"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_5"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_6"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_7"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_8"  class="inerbox wfl clor" ><div>水印</div></div>
		   			<div id="water_img_9"  class="inerbox wfl clor" ><div>水印</div></div>
		   		</div>
		   </div>
		   
		   <div class="water_type"  style="height:206px;margin-top:10px;" name="pic_config">
		   		<div style="float:left;"><span id="waterplace"></span></div>
		   		<div id="water_preview" class="img_preview">
		   			<img src="{if $water['filename']}{$water['filename']}{else}{$resource_url}hill.png{/if}"  id="img_preview" />
					<input type="hidden" name="water_filename" value="{$water['filename']}" />
		   		</div>
		   </div>
		   
		   <div class="water_type"  style="height:30px;margin-top:10px;border:0px;">
				<input type="hidden" name="module_id" value="{$water['mid']}" />
				<input type="hidden" name="opacity" id="opacity" value="{$water['opacity']}" />
				<input type="hidden" name="a" value="water_settings_update" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		   		<input type="submit" class="button_6" value="确 &nbsp;&nbsp;定" />
		   </div>
       </div>
</form>
</div>


