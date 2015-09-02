{template:head}
{css:2013/iframe}
{css:2013/list}
{css:program_day}
{css:common/common_category}
{js:common/ajax_upload}
{js:live/my-ohms}
{js:2013/ajaxload_new}
{js:program/program_library}
{code}
//print_r($list);
{/code}
<div id="ohms-instance" style="position:absolute;display:none;"></div>
<div style="display:none">
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<!-- <a type="button" class="button_6" href="run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=form" target="mainwin">新增节目库</a> -->
		<!-- <a class="gray mr10" href="run.php?mid={$_INPUT['main_mid']}&a=frame" target="mainwin">返回节目单</a> -->
	</div>
</div>
<div class="wrap common-list-content">
	 <ul class="library-list clear">
	 	<li class="library-each library-add">
	 		<div class="m2o-flex">
		 		<div class="library-img"><img src=""/>节目图片</div>
		 		<div class="library-info m2o-flex-one">
		 			<div class="library-name"><input type="text" name="title" placeholder="节目名称" value=""/>
		 				<div class="set"><em class="li_save" data-type="add" title="新增节目">新增</em></div>
		 			</div>
		 			<div class="library-item"><label>时间：</label><input type="text" class="start_time ohms" placeholder="节目开始时间" readonly="readonly" value="" /></div>
		 			<div class="library-item"><label>描述：</label><input type="text" class="brief" name="brief" value=""/></div>
		 			<div class="library-item"><label>时期：</label>
	 					<ul class="period">
	 						<li><input type="checkbox" name="event_day" class="event_day" value="1" id="event_day"/><label for="event_day">每天</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="1" id="week_day_1"/><label for="week_day_1">星期一</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="2" id="week_day_2"/><label for="week_day_2">星期二</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="3" id="week_day_3"/><label for="week_day_3">星期三</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="4" id="week_day_4"/><label for="week_day_4">星期四</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="5" id="week_day_5"/><label for="week_day_5">星期五</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="6" id="week_day_6"/><label for="week_day_6">星期六</label></li>
	 						<li><input type="checkbox" name="week_day[]" value="7" id="week_day_7"/><label for="week_day_7">星期日</label></li>
	 					</ul>
	 				</div>
		 		</div>
	 		</div>
	 	</li>
	 	{foreach $list as $k=> $base}
	 	<li class="library-each" data-id="{$base['id']}" channel_id="{$base['channel_id']}">
	 		<div class="m2o-flex">
		 		<div class="library-img">
		 			{if $base['indexpic']}<img src="{$base['indexpic']}"/>{/if}节目图片
		 		</div>
		 		<div class="library-info m2o-flex-one">
		 			<div class="library-name"><input type="text" name="title" disabled="disabled" placeholder="节目名称" value="{$base['title']}"/>
		 				<div class="set"><em class="li_edit" data-type="edit" title="编辑节目">编辑</em><em class="li_del" data-type="del" title="删除节目">删除</em></div>
		 			</div>
		 			<div class="library-item"><label>时间：</label><input type="text" class="start_time" placeholder="节目开始时间" disabled="disabled" value="{$base['start_time']}" />
	 				</div>
		 			<div class="library-item"><label>描述：</label><input type="text" class="brief" disabled="disabled" name="brief" value="{$base['brief']}"/>
	 				</div>
	 				<div class="library-item"><label>时期：</label>
	 					<ul class="period">
	 						{code}
								$week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
							{/code}
	 						<li><input type="checkbox" name="event_day" value="1" class="event_day" id="event_day_{$base['id']}" {if count($base['week_day'])==7}checked{/if} disabled="disabled"/><label for="event_day_{$base['id']}">每天</label></li>
	 						{foreach $week_day_arr as $key => $value}
								<li><input type="checkbox" name="week_day[]" value="{$key}" id="week_day_{$base['id']}{$key}" {foreach $base['week_day'] as $k => $v}{if $v == $key}checked{/if}{/foreach} disabled="disabled"/><label for="week_day_{$base['id']}{$key}">{$value}</label></li>
							{/foreach}
	 					</ul>
	 				</div>
		 		</div>
	 		</div>
	 	</li>
	 	{/foreach}
	 	<input type="file" name="index_pic" accept="image/png,image/jpeg" class="image-file" style="display: none;">
	 </ul>
	  <div class="record-bottom m2o-flex m2o-flex-center">
	  	 <div class="record-operate">
	  	 	<input type="checkbox" name="checkall" class="checkAll" title="全选" />
	  	    <a name="delete" data-method="delete" class="batch-delete">删除</a>
	  	 </div>
	  	 <div class="m2o-flex-one">
	  	 {$pagelink}
	  	 </div>
	 </div>
</div>
<script type="text/x-jquery-tmpl" id="list-each-tpl">
<li class="library-each" data-id="${id}">
	<div class="m2o-flex">
 		<div class="library-img">
 			{{if index_img}}<img src="${index_img}"/>{{/if}}节目图片
 		</div>
 		<div class="library-info m2o-flex-one">
 			<div class="library-name"><input type="text" name="title" disabled="disabled" placeholder="节目名称" value="${title}"/>
 				<div class="set"><em class="li_edit" data-type="edit">编辑</em><em class="li_del" data-type="del">删除</em></div>
 			</div>
 			<div class="library-item"><label>时间：</label><input type="text" class="start_time" placeholder="节目开始时间" disabled="disabled" value="${start_time}" />
			</div>
 			<div class="library-item"><label>描述：</label><input type="text" disabled="disabled" name="brief" value="${brief}"/>
			</div>
			<div class="library-item"><label>时期：</label>
				<ul class="period">
					<li><input type="checkbox" name="event_day" value="1" class="event_day" id="event_day_${id}" disabled="disabled"/><label for="event_day_${id}">每天</label></li>
					<li><input type="checkbox" name="week_day[]" value="1" id="week_day_${id}1" disabled="disabled"/><label for="week_day_${id}1">星期一</label></li>
					<li><input type="checkbox" name="week_day[]" value="2" id="week_day_${id}2" disabled="disabled"/><label for="week_day_${id}2">星期二</label></li>
					<li><input type="checkbox" name="week_day[]" value="3" id="week_day_${id}3" disabled="disabled"/><label for="week_day_${id}3">星期三</label></li>
					<li><input type="checkbox" name="week_day[]" value="4" id="week_day_${id}4" disabled="disabled"/><label for="week_day_${id}4">星期四</label></li>
					<li><input type="checkbox" name="week_day[]" value="5" id="week_day_${id}5" disabled="disabled"/><label for="week_day_${id}5">星期五</label></li>
					<li><input type="checkbox" name="week_day[]" value="6" id="week_day_${id}6" disabled="disabled"/><label for="week_day_${id}6">星期六</label></li>
					<li><input type="checkbox" name="week_day[]" value="7" id="week_day_${id}7" disabled="disabled"/><label for="week_day_${id}7">星期日</label></li>
				</ul>
			</div>
 		</div>
	</div>
</li>
</script>