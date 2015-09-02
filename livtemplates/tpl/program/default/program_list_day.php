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

$channel_info = $program_list['channel_info'][$_INPUT['channel_id']];
//is-set hg_pre($channel_info);
$dates = $program_list['date'];
$week = $program_list['week'];
$program_am = $program_list['program']['am'];
$program_pm = $program_list['program']['pm'];
//hg_pre($program);
$today = $dates;
$weekFlip = array_flip($week);
$index = $weekFlip[$today];
$months = array('一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二');
$currentMonth = $months[intval(date('m')) - 1] . '月';
$months = json_encode($months);
$this_week = date('W');
function getCurrentDay($date)
{
    $date = explode('-', $date);
    return $date[2];
}
//print_r($program_am);
//print_r($program_pm);

{/code}
<script>
$(window).on('beforeunload', function(){
$('#program-bg').hide();
	return hg_window_destruct();
});
var months = {$months};
var today = '{$today}';
var thisweek = '{$this_week}';
var currentIndex = '{$index}';
</script>
<div id="ohms-instance" style="position:absolute;display:none;"></div>
<div class="wrap">
<div class="common-top-content">
	<span class="top-title top-name">{$channel_info['name']}</span>
	<span class="top-title top-dates">{$dates}</span>
	<span class="model-group">
		<a class="model active">滑动模式</a>
		<a class="model">列表模式</a>
	</span>
    <span class="common-button-group">
    			<a class="mesave blue" id="resave" _channel_id="{$_INPUT['channel_id']}">另存为节目模板</a>
                 <a class="view blue" id="preview">预览</a>
                 <a class="save blue" _channel_id="{$_INPUT['channel_id']}" id="save_edit">保存</a>
                 <a class="copy blue" id="copy">复制</a>
                 <a class="reset blue" id="clear_all">清空</a>
                 <a class="uploading blue" onclick="showdiv();">上传节目单</a>
    </span>
    <div class="preview" style="display:none;">
	    <ul class="preview_ul am">
		    <li class="preview_title">上午</li>
	    </ul>
	    <ul class="preview_ul pm">
		    <li class="preview_title">下午</li>
	    </ul>
    </div>
    <div class="template-box" style="display:none;">
    	<label>模板名称:</label><input type="text" name="template-name" value="" placeholder="请输入模板名称"/>
    	<span class="sbutton">保存</span>
    </div>
    <div class="week-box" style="display:none;">
	    <span class="self-month">{$currentMonth}</span>
	    <span class="prev-week-btn" title="上一周">&lt;</span>
	    <span class="self-week" data-week="{$this_week}" data-channelid="{$channel_info['id']}">
	    {foreach $week as $kk => $vv}
	    <a class="{if $channel_info['is_schedule'][$kk]}is-set{/if}" href="javascript:void(0);" onclick="direct(this);" data-date="{$vv}" title="{$vv}">{code}echo getCurrentDay($vv);{/code}</a>
	    {/foreach}
	    </span>
	    <span class="next-week-btn" title="下一周">&gt;</span>
    </div>
    <div id="show_upload" class="show-upload" style="display:none;">
	  <span id="upload_tips" style="display:none;color: green;position: absolute;">上传成功！</span><span id="btnclose" class="btnclose"  onclick="hidediv();" style="float:right;margin-right:5px;cursor:pointer;">关闭</span>
		<form enctype="multipart/form-data" method="post" action="run.php?mid={$_INPUT['mid']}" id="upload_form" target="form_pos" name="upload_form" style="clear:both;">
			<span>上传节目单</span>
			<input type="file" name="program" id="upload_file"/>
			<input type="button" value="确定" name="sub" onclick="subform()" class="button_2"/>
			<input type="hidden" name="a" value="program_upload" />
			<input type="hidden" name="channel_id" value="{$_INPUT['channel_id']}" id="channel_id"/>
		</form>
		<div style="margin-top:10px;line-height:22px;padding-left:8px;">
			<span>节目单格式:(支持txt格式，编码为UTF-8)
				<a href="./download.php?a=example" style="text-decoration: underline;">下载txt模板</a>
			</span>
			<span style="display: inline-block;margin-left: 64px;">(支持xls格式)
				<a href="./download.php?a=download_xls" style="text-decoration: underline;">下载xls模板</a>
			</span>
			<ul style="margin-top:5px;">
				<li>{code} echo date("Y-m-d",TIMENOW);{/code}</li>
				<li>00:30:00,精彩节目</li>
				<li>00:33:00,精彩节目</li>
				<li>16:55:00,精彩节目,精彩节目的副标题</li>
			</ul>
			<ul>
				<li>{code} echo date("Y-m-d",TIMENOW+24*3600);{/code}</li>
				<li>00:30:00,精彩节目</li>
				<li>00:33:00,精彩节目</li>
				<li>06:25:00,精彩节目</li>
			</ul>
		</div>
	</div>
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
{js:hg_date}
{js:common/ajax_upload}
{js:jqueryfn/jquery.tmpl.min}
{css:hg_date}
{js:live/my-ohms}
{js:program_day}
<div id="right_date">
<div id="program_dates" class="program-date"></div>
<script type="text/javascript">
$('#right_date').hogeDate({
    showId:'program_dates',
		valueId:'dates',
		defaultValue:'{code} echo $_INPUT["dates"];{/code}',
		extra_click:function(event){
			location.href = location.href.replace(/dates=([0-9\-]*)/, 'dates=' + $(event.currentTarget).attr('_date'));
			return false;
		}
  });
</script>

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
	
</div>
	<div class="clear"></div>
</div>
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
		<div class="item-indeximage">
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
<input type="hidden" name="referto" id="referto" value="{$_INPUT['referto']}" />
<div id="program_bg" class="program-bg"></div>
{template:foot}

