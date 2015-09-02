{template:head}
{code}
$list = $vod_config_list;

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

{/code}
<!-- 选择控件的数据设置 -->
{code}

	$item_sort_leixing = array(
		'class' => 'transcoding down_list',
		'show' => 'sort_leixing_show',
		'width' => 100,	
		'state' => 0, 
		'is_sub'=>1,
	);

	$item_sort_addleixing = array(
		'class' => 'transcoding down_list',
		'show' => 'addleixing_show',
		'width' => 100,	
		'state' => 0, 
		'is_sub'=>1,
	);
	
	$leixing_default = -1;
	$vod_leixing = $_configs['video_upload_type'];
	$vod_leixing[$leixing_default] = '选择类型';

	$attr_date = array(
		'class' => 'colonm down_list data_time',
		'show' => 'colonm_show',
		'width' => 104,
		'state' => 1,
	);
	
{/code}

{css:vod_style}
{css:mark_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:water_upload_handler}
{js:vod_config}

<style>
.right .list .right .fl, .right .list_first .right .fl {width:50px}
.right .list .right .fl em{width:50px}
#water-position-area{position:relative;margin:50px;background:#ccc;}
#water-org{position:absolute;background:red;cursor:move;}
.v_list_show{margin-top:15px;}
</style>
<script type="text/javascript">
	function hg_del_keywords()
	{
		var value = $('#search_list').val();
		if(value == '关键字')
		{
			$('#search_list').val('');
		}

		return true;
	}

   $(document).ready(function(){
	   upload_water_image();
		/*拖动排序部分开始*/
		tablesort('vod_config_form_list','vod_config','config_order_id');
		$("#vod_config_form_list").sortable('disable');
   });   
   
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6"  onclick="hg_showAddConfig();" ><strong>新增配置</strong></span>
</div>
<div class="content clear">
 <div class="f">
		<!-- 新增配置面板 开始-->
 		 <div id="add_config"  class="single_upload" style="height:auto;">
 		 	<h2><span class="b" onclick="hg_closeConfigTpl();"></span><span id="config_title">新增配置</span></h2>
 		 	<div id="add_config_tpl" class="add_collect_form">
 		 	<form action="./run.php?mid={$_INPUT['mid']}"  name="config_form"  id="config_form"   method="post"  enctype="multipart/form-data"  onsubmit=" return hg_ajax_submit('config_form')" >
 		 	    <div class="collect_form_top info  clear">
						<div style="width:100%;margin-top:10px;">
							<label>配置名称：</label>
							<input type="text" name="config_name"   id="config_name"  value="超清转码配置" />
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>输出格式：</label>
							<input type="text" name="output_format" id="output_format"  value="mp4" />
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>编码格式：</label>
							<input type="text" name="codec_format" id="codec_format"  value="H264" />
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>编码质量：</label>
							<input type="text" name="codec_profile"  id="codec_profile"  value="H264_MAIN" />
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>宽&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;度：</label>
							<input type="text" name="width"  id="width"  value="720" />
							<label>像素</label>
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>高&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;度：</label>
							<input type="text" name="height"  id="height" value="480" />
							<label>像素</label>
						</div>
												
						<div style="width:100%;margin-top:10px;">
							<label>视频码率：</label>
							<input type="text" name="video_bitrate"  id="video_bitrate"  value="900" />
							<label>kbps</label>
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>音频码率：</label>
							<input type="text" name="audio_bitrate"  id="audio_bitrate"  value="48" />
							<label>kbps</label>
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>帧&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;频：</label>
							<input type="text" name="frame_rate" id="frame_rate"  value="24" />
							<label>帧/秒</label>
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>距关键帧：</label>
							<input type="text" name="gop"  id="gop"  value="50" />
							<label>秒</label>
						</div>	
						
						<div style="width:100%;margin-top:10px;">
							<label>转码质量：</label>
							<input type="text" name="vpre" id="vpre"  value="slow" />
						</div>	
						
						<div style="width:100%;margin-top:10px;">
							<label>是否使用：</label>
							是：<input type="radio" name="is_use" id="is_use_1"  value=1  />
							否：<input type="radio" name="is_use" id="is_use_0"  value=0  />
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>水&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;印：</label>
							<div id="water_mark"></div>
							<div style="width:160px;height:160px;float:right;margin-right:224px;overflow:auto;">
							<img src='' id='pic_face' /></div>
							<input type="hidden" name="water_mark"   id="water_mark" value="" />
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>开启水印：</label>
							是：<input type="radio" name="is_open_water" id="is_open_water_1"  value=1  />
							否：<input type="radio" name="is_open_water" id="is_open_water_0"  value=0  />
						</div>
						
						<div style="width:100%;margin-top:10px;">
							<label>水印位置：</label>
							<div style="width:169px;height:119px;margin-left:53px;" id="_water_position">
								<input type="button" value="左上" _val="0,0" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);"/>
								<input type="button" value="中上" _val="1,0" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);" />
								<input type="button" value="右上" _val="2,0" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);" />
								<input type="button" value="左中" _val="0,1" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);" />
								<input type="button" value="中中" _val="1,1" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);" />
								<input type="button" value="右中" _val="2,1" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);"/>
								<input type="button" value="左下" _val="0,2" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);"/>
								<input type="button" value="中下" _val="1,2" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);"/>
								<input type="button" value="右下" _val="2,2" style="float:left;margin-left:8px;margin-top:8px;background:green;" class="button_2"  onclick="select_water_position(this);"/>
							</div>
							
							<input type="hidden" name="water_pic_position" id="water_pic_position" value="" />
						</div>
						<div style="display:none;">
						<div id="water_text"></div>
						<input type="hidden" id="water_offset" />
						<div id="water-position-area" style="margin:10px auto;">
							<div id="water-org"></div>
						</div>
						</div>
			 	 </div>
			 	 <input type="hidden"   name="a" value=""  id="action"   />
			 	 <input type="hidden"   name="id" value=""  id="config_id"   />
			 	 <input id="add_config_button" class="button_6_14" type="submit"  value="添加" style="cursor:pointer;font-weight:bold;display:none;">
			 	 <input id="edit_config_button" class="button_6_14" type="submit"  value="编辑" style="cursor:pointer;font-weight:bold;display:none;">
			  </form>
 		 	</div>
		 </div>
 		 <!-- 新增配置面板结束-->
 		 
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
							{template:form/search_source,vod_leixing_id,$leixing_default,$vod_leixing,$item_sort_leixing}
							{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
	                    </div>
	                    <div class="right_2">
	                    	<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
	                        </div>
							{template:form/search_input,k,$_INPUT['k']}                        
	                    </div>
                   </form>
                </div>

                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb" style="cursor:pointer;"  onclick="hg_switch_order('vod_config_form_list');"  title="排序模式切换/ALT+R"><em></em></a></span>
                        <span class="right" style="width:860px"><a class="fb">编辑</a><a class="fb">删除</a><a class="fl">输出格式</a><a class="fl">编码格式</a><a class="fl" style="width:60px;">编码质量</a><a class="fl">宽度</a><a class="fl">高度</a><a class="fl">视频码率</a><a class="fl">音频码率</a><a class="fl">帧频</a><a class="fl">距关键帧</a><a class="fl">转码质量</a><a class="fl">水印</a><a class="fl">是否启用</a><a class="fl">开启水印</a></span>
						<span class="title overflow">配置名称</span>
                </div>
                <form method="post" action="" name="vod_config_listform">
	                <ul class="list" id="vod_config_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v} 
		                      {template:unit/vod_configlist}
		                    {/foreach}
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
	                </ul>
		            <div class="bottom clear">
		               <div class="left">
		                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
					       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"   name="batdelete">删除</a>
					   </div>
		               {$pagelink}
		            </div>	
    			</form>
            </div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
</body>
{template:foot}