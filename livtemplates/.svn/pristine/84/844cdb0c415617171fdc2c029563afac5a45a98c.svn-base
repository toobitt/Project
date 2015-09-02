{js:vod_video_edit}
<form class="iframe" action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="theme_form" id="theme_form">
<div class="bg_middle" >
            <div class="info clear" style="padding-bottom:0;">
				<div class="col_choose clear">
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'item_show',
							'width' => 135,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = -1;
						$program[$default] = '选择分类';
						foreach($program_item[0] as $k =>$v)
						{
							$program[$v['id']] = $v['name'];
						}
					{/code}
					{template:form/search_source,item,$default,$program,$item_source}
					<span class="show_span" id="show_span" onclick="hg_channel_show(0,19);">选择频道</span><span id="default_value" class="default_value" style="display:none;">当前选取：<a id="channel_name" onclick="hg_show_record_list();"></a></span><input id="channel_id" name="channel_id" value="" type="hidden"/>
				</div>
				<div class="channel_list clear" id="channel_list" style="display:none;">
				</div>
				<div class="info_live clear">
					<div class="tv" id="info_live" style="display:none;min-height:100px;"></div>
				</div>
            </div>       
			
			<div class="info clear">
				<div class="info-left-top" style="width:100%">节目起止时间：</div>
				{code}
				$type_source = array('other'=>' size="14" autocomplete="off" style="width:180px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;"','name'=>'start_time');
				{/code}{template:form/wdatePicker,start_times,$_INPUT['start_time'],'',$type_source} <span class="time-h-k">-</span> 
				{code}
				$type_source = array('other'=>' size="14" autocomplete="off" style="width:180px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;"','name'=>'end_time','style'=>'margin-left:5px;');
				{/code}{template:form/wdatePicker,end_times,$_INPUT['end_time'],'',$type_source}
				<!--<span id="select_repeat" class="select_repeat" onclick="hg_show_record_list();">重新选择</span>-->
			</div>
			{code}
				$hg_attr['multiple'] = 1;
				$hg_attr['_callcounter'] = 1;
			{/code}
              {template:unit/vod_form,record}
			  <div class="submit clear">
              	<input type="button" class="fix" value="确定并继续添加" onclick="hg_check_record(1);" />
                <input type="button" class="fix" value="确定" onclick="hg_check_record(0);"/><span id="error_tips_1" style="margin-right: 100px;"></span>
              </div>
            </div>
			  <input type="hidden" value="columnidrecord" name="colname" />
			  <input type="hidden" value="create" name="a" />
			  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
			  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			  <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			  <input type="hidden" name="goon" id="goon" disabled value="1" />
</form>

<script type="text/javascript">
function hg_select_channel_error(e,id,save_time)
{
	$("#channel_list").slideUp();
	$("#info_live").slideDown();
	$("#default_value").slideDown();
	var str = '<span style="color:red;text-align:center;display:block;margin-top:30px;font-size:14px;">该频道未启动手机流，无法获取时移数据！</span>';
	$("#info_live").html(str);
	$("#channel_name").html($(e).html()+"&nbsp;&nbsp;<span class='col_c'>("+ save_time + "小时时移)</span>");
	$("#show_span").html("重新选择频道");	
}
</script>



