<?php 
/* $Id: program_template_set.php 5656 2013-10-18 09:30:02Z zhangzhen $ */
?>
{template:head}
{code}
$templateList = $formdata['templateList'];
$relation = $formdata['relation'];
{/code}

{js:2013/ajaxload_new}
{js:fullcalendar/fullcalendar}
{js:fullcalendar/hg_fullcalendar}
{js:program/set_template}
{css:fullcalendar/fullcalendar}
{css:2013/iframe}
{css:2013/m2o}
{css:program_set}

{code}
$channel_id = $_INPUT['channelId'];
$weeks = array('日','一','二','三','四','五','六');
{/code}
<div class="wrap">
	<div class="main-area m2o-flex" id="template-box">
		<div class="template-box-left  m2o-flex-one">
			<ul class="week-list">
				{foreach $weeks as $k =>$v}
				<li>{$v}</li>
				{/foreach}
			</ul>
			<div class="week-tip">拖动到周上可以为这一列设置节目模板</div>
			<div id="calendar" class="calendar-box" data-channel="{$channel_id}"></div>
			<div class="preview-box">
				<div class="preview-box-close">关闭</div>
				<div class="preview-box-title">节目模板预览</div>
				<div class="preview-box-content"></div>
			</div>
		</div>
		<div class="template-box-right">
			<div class="program-template">
			<div class="head">节目模板</div>
			<ul>
				{foreach $templateList as $k =>$v}
				<li data-id="{$v['id']}" data-title="{$v['title']}">
					<div class="title" data-id="{$v['id']}" data-title="{$v['title']}">{$v['title']}</div>
					<span class="preview" title="预览节目模板"></span>
				</li>
				{/foreach}
			</ul>
		</div>
		</div>
	</div>
</div>
<script>
	var data =  {code} echo $relation ? json_encode( $relation ) : '[]';{/code};
	if( data.length ){
		$.events = $.map( data, function( value ){
			var obj = {};
			obj.eid = value['template_id'];
			obj.title = value['template_title'];
			obj.start = value['date'];
			return obj;
		} );
	}else{
		$.events = data;
	}
	
</script>
<script type="text/x-jquery-tmpl" id="preview-tmpl">
<div class="item">
<div class="period">${title}</div>
<ul>
{{if data}}
{{each data}}
 <li>
  	<span class="time">{{= $value['start']}}</span>
  	<span class="name">{{= $value['theme']}}</span>
 </li>
{{/each}}
{{else}}
<li>无</li>
{{/if}}
</ul>
</div>
</script>
