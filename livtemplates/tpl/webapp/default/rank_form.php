{template:head}{code}	if($id)	{		$optext="更新";		$ac="update";	}	else	{		$optext="新增";		$ac="create";	}{/code}{if is_array($formdata)}	{foreach $formdata as $key => $value}		{code}			$$key = $value;			{/code}	{/foreach}{/if}{css:ad_style}{js:ad}{css:common/common_form}{js:common/auto_textarea}{js:common/common_form}{css:hg_sort_box}{js:hg_sort_box}<style>body{overflow:auto;height:auto;}</style><div id="channel_form" style="margin-left:40%;"></div><div class="wrap clear"><div class="ad_middle"><form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form"><h2>{$optext}排行类型</h2><ul class="form_ul"><li class="i">	<div class="form_ul_div clear">		<span class="title">名称：</span>			<div style="display:inline;float:left;margin-right:10px;">				<input type="text" value='{$title}' name='title' class="title" style="width:145px;">			</div>	</div></li><li class="i">	<div class="form_ul_div clear">		<span class="title">开始时间：</span>			<div style="display:inline;float:left;margin-right:10px;">				<input type="text" name="start_time" id="start_time" autocomplete="off" size="25" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{$start_time}"/>			</div>			<font class="important">默认不限制开始时间</font>	</div></li><li class="i">	<div class="form_ul_div clear">		<span class="title">时长：</span>			<div style="display:inline;float:left;margin-right:10px;">				<input type="text" value='{$duration}' name='duration' class="title" style="width:145px;" id="duration">			</div>			<font class="important" id="duration_important">单位为分钟,为0不限制时长</font>			<font class="important" id="duration_important_error" style="color:red;display:none;">必须是数字</font>	</div></li><li class="i">	<div class="form_ul_div clear">		<span class="title">排行条数：</span>			<div style="display:inline;float:left;margin-right:10px;">				<input type="text" value='{$limit_num}' name='limit_num' class="title" style="width:145px;" id="limit_num">			</div>			<font class="important" id="limit_num_important">默认前30条</font>			<font class="important" id="limit_num_important_error" style="color:red;display:none;">必须是数字</font>	</div></li><li class="i">	<div class="form_ul_div clear">		<span class="title">排序字段：</span>			<div style="display:inline;float:left;margin-right:10px;">				{if is_array($typecontent[0])}					{foreach $typecontent[0] as $k=>$v}						<input type="radio" value="{$v['pic_icon']}" name="ranktype" {if $v['pic_icon'] == $ranktype}checked=checked{/if}/> {$v['title']}					{/foreach}				{/if}			</div>			<font class="important" id="type_important">*</font>	</div></li><li class="i">	<div class="form_ul_div clear">		<span class="title">排序规则：</span>			<div style="display:inline;float:left;margin-right:10px;">				<input type="radio" value="desc" name="sort_type" {if $sort_type == 'desc'}checked=checked{/if}/> 降序				<input type="radio" value="asc" name="sort_type" {if $sort_type == 'asc'}checked=checked{/if}/> 升序				</div>			<font class="important" id="type_important">*</font>	</div></li><li class="i">	<div class="form_ul_div clear">		<span class="title">内容类型：</span>			<div style="display:inline;float:left;margin-right:10px;">				{code}					$contentType = $contentType[0]				{/code}				{if is_array($contentType)}					{foreach $contentType as $k=>$v}						<input type="checkbox" value="{$v['bundle_id']}" name="type[]" {if in_array($v['bundle_id'],$type)}checked=checked{/if}/>{$v['content_type']}					{/foreach}				{/if}			</div>			<font class="important" id="type_important">默认所有类型</font>	</div></li></ul><input type="hidden" name="a" value="{$ac}" /><input type="hidden" name="{$primary_key}" value="{$formdata['id']}" /><input type="hidden" name="referto" value="{$_INPUT['referto']}" /><input type="hidden" name="infrm" value="{$_INPUT['infrm']}" /><input type="hidden" name="mmid" value="{$_INPUT['mid']}" /><br /><input type="submit" id="submit_ok" name="sub" value="{$optext}排行类型" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/></form></div><div class="right_version">	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2></div></div><script>jQuery(function($){    var timeid = setInterval(function(){        if($.fn.commonPublish){            $('#publish-1').commonPublish({                column : 2,                maxcolumn : 2,                height : 224,                absolute : false            });            clearInterval(timeid);        }    }, 100);});$(document).ready(function(){	$("#submit_ok").click(function(){		var duration = $("#duration").val();		var limit_num = $("#limit_num").val();		var reg = new RegExp("^[0-9]*$");		if(!reg.test(duration))		{			$("#duration_important").hide();			$("#duration_important_error").show();					return false;		}		else		{			$("#duration_important").show();			$("#duration_important_error").hide();		}		if(!reg.test(limit_num))		{			$("#limit_num_important").hide();			$("#limit_num_important_error").show();					return false;		}		else		{			$("#limit_num_important").show();			$("#limit_num_important_error").hide();		}	});});</script>{template:foot}