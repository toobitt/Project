{code}
//hg_pre($formdata);
{/code}
<style>
 .edit_show .info .record-list li{width:100%;}
</style>
<div class="info clear vider_s" id="vodplayer_{$formdata['id']}" style="height:20px;">
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
			 <div id="video_opration" class="clear common-list" style="border:0;height:auto">
		<div class="common-opration-list">
		    <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
			{if $formdata['program_id'] || $formdata['plan_id']}
				<a class="button_4" href="javascript:void(0);" onclick="hg_disable_action('无法删除来源于节目单或节目计划的录制，请到源地址处进行删除操作！');">删除</a>
			{else}
				<a class="button_4" href="javascript:void(0);" onclick="hg_plan_del({$formdata['id']});">删除</a>
				{/if}
				{foreach $_relate_module AS $kkk => $vvv}
				<a class="button_4" href="./run.php?mid={$kkk}&record_id={$formdata['id']}&infrm=1">{$vvv}</a>
				{/foreach}
			</div>
		 </div>
</div>
<div class="channel_info info clear vo">
		 <h4 onclick="hg_slide_up(this,'record_info')"><span title="展开\收缩"></span>计划收录</h4>
     <div id="record_info" class="record_info_box">
		 <ul class="clear record-list">
		     <li><span>收录频道：</span>{$formdata[channel_name]}</li>
		     <li><span>节目名称：</span>{$formdata[title]}</li>
		     <!--<li><span>起止时间：</span><span>-</span><span class="text_b live-duration"></span></li>-->
			<li><span>发布至：</span>{$formdata[column_name]}</li>
					 <!--<li><span>归档分类：</span>{$formdata[sort_name]}</li>-->
					 <!--<li><span>来源：</span>{$formdata[source]}</li>－－>
					 <!--  <li><span>状态：</span>{$formdata[action]}</li>-->
					 <li><span>创建时间：</span>{$formdata[create_time]}</li>
					 <li><span>结束时间：</span>{$formdata[end_time]}</li>
				 </ul>
      </div>
</div>