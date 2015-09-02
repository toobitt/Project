<?php 
/* $Id: program_list_day.php 9559 2012-06-02 09:29:20Z lijiaying $ */
?>
{template:head}
{css:2013/iframe}
{css:2013/list}
{css:program_day}
{css:common/common_category}
<style>
.movie-list{float:left;margin: 15px 0px 0px 30px;}
.movie-list .movie-cover{display: block;width: 200px;height: 35px;position: absolute;z-index: 9999999;opacity: 0;}
.input_left , .input_right{display:none;}
.input_middle{height:30px;border: 1px solid #cfcfcf;line-height: 30px;}
.down_list .input_middle em{margin: 13px 7px 0 0;}
.down_list ul{width: 198px;top: 31px!important;left: 1px!important;}
.down_list ul li{cursor: pointer;height: 27px!important;line-height: 27px!important;}
</style>
{code}
$channel_info = $program_list['channel_info'][$_INPUT['channel_id']];
//is-set hg_pre($channel_info);
$dates = $program_list['date'];
$week = $program_list['week'];
$program_am = $program_list['program']['am'];
$program_pm = $program_list['program']['pm'];
//hg_pre($program);
$dates = date('Y-m-d', time());
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
//print_r( $formdata );
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
	<span class="top-title top-name">当前影院：{$_INPUT['cinema_name']}</span>
	<div class="movie-list">
		{if $formdata['movie']}
		<span class="movie-cover"></span>
		{/if}
		{code}
	    	$movie_source = array(
	             'class' 	=> 'down_list i',
	             'show' 		=> 'movie_show',
	             'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
	             'is_sub'	=>	1,
	             'width'    => 200
	        );
	        if($formdata['movie']['movie_id']){
	         	$movie_default = $formdata['movie']['movie_id'];
	        }
	        else
	        {
	            $movie_default = 'other';
	        }
	        $movie_sort[other] = '-- 请先选择影片 --';
	        foreach($movie as $k =>$v)
	        {
	            $movie_sort[$v['id']] = $v['title'];
	        }
	    {/code}
	    {template:form/search_source,movie_id,$movie_default,$movie_sort,$movie_source}
	</div>
    <span class="common-button-group">
         <a class="save blue" _cinema_id="{$_INPUT['cinema_id']}" id="save_edit">保存</a>
         <input type="hidden" name="create_time" value="{$_INPUT['create_time']}" />
         <input type="hidden" name="type" value="{if $formdata['movie']}1{else}0{/if}" />
    </span>
    <div class="preview" style="display:none;">
	     <ul class="preview_ul am">
		    <li class="preview_title">上午</li>
	     </ul>
	     <ul class="preview_ul pm">
		    <li class="preview_title">下午</li>
	     </ul>
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
<div id="program_model" style="display: -webkit-box;">
	<div class="movie-box" title="{$formdata['movie']['movie_name']}">
		<img src="{if $formdata['movie']['movie_img']}{$formdata['movie']['movie_img']['host']}{$formdata['movie']['movie_img']['dir']}{$formdata['movie']['movie_img']['filepath']}{$formdata['movie']['movie_img']['filename']}
		{else}{$RESOURCE_URL}cinema/pic.png{/if}" />
		<span class="movie-name">{$formdata['movie']['movie_name']}</span>
	</div>
	<div class="program-item" style="margin: 0 20px 0px 0px;">
		<div class="item-list item-amlist">
			<div class="m2o-title m2o-flex m2o-flex-center">
				<div class="item-interval">时段</div>
				<div class="item-time">时间</div>
				<div class="item-position">厅号</div>
				<div class="item-price">票价</div>
				<div class="item-lan">语言</div>
				<div class="item-dim">维度</div>
				<div class="item-set"></div>
			</div>
			<div class="m2o-each-list m2o-am-list">
				
			</div>
			<div class="item-bottom m2o-flex-center add-am">
				增 加 场 次
			</div>
			<input type="file" name="img" accept="image/png,image/jpeg" class="image-file" style="display:none; ">
		 </div>
		 <div class="item-list item-pmlist">
			<div class="m2o-title m2o-flex m2o-flex-center">
				<div class="item-interval">时段</div>
				<div class="item-time">时间</div>
				<div class="item-position">厅号</div>
				<div class="item-price">票价</div>
				<div class="item-lan">语言</div>
				<div class="item-dim">维度</div>
				<div class="item-set"></div>
			</div>
			<div class="m2o-each-list m2o-pm-list">
			</div>
			<div class="item-bottom m2o-flex-center add-pm">
				增 加 场 次
			</div>
		 </div>
	</div>
</div>
{js:hg_date}
{js:common/ajax_upload}
{js:jqueryfn/jquery.tmpl.min}
{css:hg_date}
{js:live/my-ohms}
{js:cinema/project_form}
<div id="right_date">
{if $formdata['movie']}								<!-- 编辑时时间不可更改 -->
<div class="date-cover" style="width: 275px;height: 300px;position: absolute;"></div>
{/if}
<div id="program_dates" class="program-date"></div>
<script type="text/javascript">
$('#right_date').hogeDate({
    showId:'program_dates',
		valueId:'dates',
		defaultValue:'{if $_INPUT["create_time"]}{$_INPUT["create_time"]}{else}{$dates}{/if}',
		extra_click:function(event){
			var time = $(event.currentTarget).attr('_date');
			$('input[name="create_time"]').val( time );
			return false;
		}
  });
</script>

<script type="text/javascript">
$(function($){
	$.globalamProgram = {code} echo  $formdata['project_list']['am'] ?  json_encode($formdata['project_list']['am']) : '{}'; {/code};   /*时间选择*/
	$.globalpmProgram = {code} echo  $formdata['project_list']['pm'] ?  json_encode($formdata['project_list']['pm']) : '{}'; {/code};
	$.globalpmMovie = {code} echo  $movie ?  json_encode($movie) : '{}'; {/code};
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
<div class="m2o-each m2o-flex m2o-flex-center" _start="{{if project_time}}${project_time}{{else}}${start}{{/if}}" _id="${id}" _plan="${is_plan}" _slider="${slider}" _key="${key}" _noon="${noon}" >
	<div class="item-interval">${interval}</div>
	<div class="item-time">
		<span class="item-t">{{if project_time}}${project_time}{{else}}${start}{{/if}}</span>
	</div>
	<div class="item-position">
		<input class="position w50" type="text" value="${hall}"/>
	</div>
	<div class="item-price">
		<input class="price w50" type="text" value="${ticket_price}"/>
	</div>
	<div class="item-lan">
		<input class="lan w50" type="text" value="${language}"/>
	</div>
	<div class="item-dim">
		<input class="dim w50" type="text" value="${dimension}"/>
	</div>
	<div class="item-set">
		<!--<a class="save-item">保存</a>-->
		<a class="del">删除</a>
	</div>
</div>
</script>
<script type="text/x-jquery-tmpl" id="add-nodata-tpl">
<div class="item-nodata">
	<div class="nodata" style="color:#da2d2d;text-align:center;font-size:13px; font-family:Microsoft YaHei;">暂未设置电影场次！</div>
</div>
</script>
<div id="program_bg" class="program-bg"></div>
{template:foot}

