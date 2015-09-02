<?php 
/* $Id: program_list_day.php 9559 2012-06-02 09:29:20Z lijiaying $ */
?>
{template:head}
{css:2013/iframe}
{css:2013/list}
{css:program_day}
{css:common/common_category}
<div id="hidden-nav-option">
    <a class="gray mr10" href="run.php?mid={$_INPUT['main_mid']}&a=frame" target="mainwin">
        <span class="left"></span>
        <span class="middle">返回节目单</span>
        <span class="right"></span>
    </a>
</div>
{code}
//$channel_info = $program_list['channel_info'][$_INPUT['channel_id']];
//is-set hg_pre($channel_info);
$program_am = $formdata['data']['am'];
$program_pm = $formdata['data']['pm'];
//print_r($program_am);
//print_r($program_pm);
//print_r($programLibraryList);
{/code}
<script>
$(window).on('beforeunload', function(){
	$('#program-bg').hide();
	return hg_window_destruct();
});
</script>
<div id="ohms-instance" style="position:absolute;display:none;"></div>
<div class="wrap common-list-content">
	<div class="common-top-content">
		<span class="model-group">
			<a class="model active">滑动模式</a>
			<a class="model">列表模式</a>
		</span>
	    <span class="common-button-group">
	    	<a class="print blue" id="print_temp" data-id="{$formdata['id']}">截屏</a>
	        <a class="save_temp blue" id="save_edit" data-id="{$formdata['id']}">保存</a>
	        <a class="resave blue" id="saveas_temp">另保存</a>
	    </span>
	</div>
	<div id="program_menu">
		<div class="program-am">
			<span class="time-area">上午</span>
			<div id="slider_am"></div>
		</div>
		<div class="program-pm">
			<span class="time-area">下午</span>
			<div id="slider_pm"></div>
		</div>
		<div class="clear"></div>
	</div>
	<div id="program_model">
		<div class="program-item">
			<div class="item-list item-amlist">
				<div class="m2o-title m2o-flex m2o-flex-center">
					<div class="item-interval">时段</div>
					<div class="item-time">时间</div>
					<div class="item-name m2o-flex-one">节目名称</div>
					<div class="item-image">索引图片</div>
					<div class="item-set"></div>
				</div>
				<div class="m2o-each-list m2o-am-list">
					
				</div>
				<div class="item-bottom m2o-flex-center add-am">
					增 加 节 目
				</div>
				<input type="file" name="img" accept="image/png,image/jpeg" class="image-file" style="display:none; ">
			 </div>
			 <div class="item-list item-pmlist">
				<div class="m2o-title m2o-flex m2o-flex-center">
					<div class="item-interval">时段</div>
					<div class="item-time">时间</div>
					<div class="item-name m2o-flex-one">节目名称</div>
					<div class="item-image">索引图片</div>
					<div class="item-set"></div>
				</div>
				<div class="m2o-each-list m2o-pm-list">
					
				</div>
				<div class="item-bottom m2o-flex-center add-pm">
					增 加 节 目
				</div>
			 </div>
		</div>
	</div>
	<div class="right-program">
		<div class="template-item sort-box-show">
			<label>节目库选择：</label>
			<ul id="program-choose">
				{foreach $programLibraryList[0] as $k => $temp}
				<li _noon="{$temp['noon']}"><img src="{$temp['indexpic']}"/><em>{$temp['start_time']}</em><span>{$temp['title']}</span>
				</li>
				{/foreach}
			</ul>
		</div>
		<div class="template-item template-name">
			<input type="text" name="template-name" placeholder="模板名称" value="{$formdata['title']}"/>
		</div>
	</div>
	<div class="clear"></div>
</div>
{js:common/ajax_upload}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload_new}
{js:2013/html2canvas}
{js:2013/printscreen}
{css:hg_date}
{js:live/my-ohms}
{js:program_day}
{js:program/program_template}
<script type="text/javascript">
$(function($){
	$.globalamProgram = {code} echo  $program_am ?  json_encode($program_am) : '{}'; {/code};
	$.globalpmProgram = {code} echo  $program_pm ?  json_encode($program_pm) : '{}'; {/code};
	var ohmsInstance = $('#ohms-instance').ohms();
	$(".wrap").program({
		ohms : ohmsInstance,
		'schedule-info' :  $.globalamProgram,
	});
});
</script>
<script>
	var program_am_slider = '{$program_am_slider}';
	var program_pm_slider = '{$program_pm_slider}';
</script>

<script type="text/x-jquery-tmpl" id="add-program-tpl">
<div class="program-li" _start="${start}" _id="${id}" _plan="${is_plan}" _slider="${slider}" _key="${key}" _noon="${noon}" style="top:${top}px;">
	<span class="program-start">${start}</span>
	<span class="program-con"><em></em></span>	
	<span class="theme-label{{if is_plan}} theme-plan{{/if}}" title="${user_name}">${theme}</span>
	<span class="theme-arrow"></span>
	<input class="theme" type="text" disabled="disabled" value="${theme}"/>
    <span class="program-delete">删除</span>
    <div class="slide-indeximage">
    	<img src='${index_pic}' />
	</div>
</div>
</script>

<script type="text/x-jquery-tmpl" id="add-item-tpl">
<div class="m2o-each m2o-flex m2o-flex-center" _start="${start}" _id="${id}" _plan="${is_plan}" _slider="${slider}" _key="${key}" _noon="${noon}" >
	<div class="item-interval">${interval}</div>
	<div class="item-time">
		<span class="item-t">${start}</span>
	</div>
	<div class="item-name m2o-flex-one">
		<span class="item-the">${theme}</span>
		<input class="item-theme" type="text" value="${theme}"/>
	</div>
	<div class="item-image">
		<div class="temp-indeximage">
			<img src='${index_pic}'/>
		</div>
	</div>
	<div class="item-set">
		<!--<a class="save-item">保存</a>-->
		<a class="del">删除</a>
	</div>
</div>
</script>
<script type="text/x-jquery-tmpl" id="add-nodata-tpl">
<div class="item-nodata">
	<div class="nodata" style="color:#da2d2d;text-align:center;font-size:13px; font-family:Microsoft YaHei;">暂未设置节目单</div>
</div>
</script>
<div id="program_bg" class="program-bg"></div>
{template:foot}

