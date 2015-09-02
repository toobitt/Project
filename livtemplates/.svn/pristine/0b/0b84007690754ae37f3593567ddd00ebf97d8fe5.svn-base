{template:head}
{css:ad_style}
{css:column_node}
{code}
	//hg_pre($formdata);
{/code}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del {
display: none;
width: 16px;
height: 16px;
cursor: pointer;
float: right;
background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;
}
</style>
<script type="text/javascript">
	function hg_addArgumentDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>开始时间: </span><input type='text' name='start_time[]' onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm'})\">&nbsp;结束时间: <input type='text' name='end_time[]' onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm'})\"><span>&nbsp;审核状态: </span><select name='type[]'><option value ='2'>已审核</option></select><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if($(obj).data("save"))
		{
			if(confirm('确定删除该参数配置吗？'))
			{
				$(obj).closest(".form_ul_div").remove();
			}
		}
		else
		{
			$(obj).closest(".form_ul_div").remove();
		}
		hg_resize_nodeFrame();
	}
	function change_type(val)
	{
		if(val)
		{
			$('#app_change').toggle();
			$('#default_change').toggle();
			if(val == 'default')
			{
				$('#bundle').attr('disabled',true);
			}
			else
			{
				$('#bundle').attr('disabled',false);
			}
		}
	}
	function hg_plan_repeat(e,type)
	{
		if(type == 2)
		{
			if($("div[id^=week_date] input[id^=week_day_]:checked").length < 7)
			{
				$("#every_day").removeAttr('checked');
			}
			else
			{
				$("#every_day").attr('checked','checked');
			}
		}
		else
		{
			if($(e).attr('checked'))
			{
				$("div[id^=week_date] input[id^=week_day_]").attr('checked','checked');
			}
			else
			{
				$("div[id^=week_date] input[id^=week_day_]").removeAttr('checked');
			}
		}
	}
	function hg_plan_check_day()
	{
		if(_dateToUnix($('#start_date').val() + ' 00:00:00') > _dateToUnix($('#end_date').val() + ' 00:00:00'))
		{
			$("#day_tips").html('选择日期有误！').fadeIn(2000).fadeOut(2000);
			$('#end_date').val($('#start_date').val());
		}
	}

	function _dateToUnix(str)
	{
		str = str.replace(/(^\s*)|(\s*$)/g, "");
		var new_str = str.replace(/:/g,'-');
		new_str = new_str.replace(/ /g,'-');
		var arr = new_str.split('-');

		var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
		return (datum.getTime()/1000);
	}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}设置</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标题&nbsp;&nbsp;</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:441px;height:26px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">描述：</span>
								<textarea name='brief'>{$formdata['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">URL路径：</span>
								<div id="app_change" {if !$formdata['bundle']} style="display:none"{/if}>
									{code}
											$attr_app = array(
												'class' => 'transcoding down_list',
												'show'  => 'select_ap',
												'width' => 180,/*列表宽度*/
												'state' => 0,/*0--正常数据选择列表，1--日期选择*/
												'onclick' => 'change_module();'
											);
											$apps = $apps[0];
											$apps['-1'] = "-请选择-";
											
											$bundle = $formdata['bundle'];
											$bundle = $bundle ? $bundle : -1;
									{/code}
										
									{template:form/search_source,bundle,$bundle,$apps,$attr_app}
									<font class="important">读取应用下文件</font>
								</div>
								<div id="default_change" {if $formdata['bundle']} style="display:none"{/if}>
									<div style="float: left">
										<input type="text" name="host" value="{$formdata['host']}" />/<input type="text" name="dir" value="{$formdata['dir']}" />
									</div>
									<font class="important">例如：localhost/public/api</font>
								</div>
								<div  style="float: left;margin-left: 10px;margin-top: 3px;">
									<select name='type' onchange="change_type(this.value);">
										<option {if !$formdata['bundle']}selected='selected'{/if} value='default'>默认</option>
										<option {if $formdata['bundle']}selected='selected'{/if} value='app'>应用</option>
									</select>	
								</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">请求文件名: </span>
								<input type="text" name="filename" size="20" value="{$formdata['filename']}" />
								方法名:
								<input type="text" name="funcname" size="20" value="{$formdata['funcname']}" />
								<font class="important" style="color:red">*方法名不填写默认为planAudit</font>
							</div>
						</li>
						<li class="i">
							<div class='form_ul_div clear'>
								<span class='title'>日期: </span>
								{code}
               						$type_source = array('other'=>' size="14" autocomplete="off" style="width:125px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'start_date','style'=>'width:140px;float: left;','type'=>'yyyy-MM-dd','focus' => "$('[lang=zh-cn]').hide();");
              						$dates = $formdata['start_time'] ? date('Y-m-d',$formdata['start_time']) : date('Y-m-d');
               					{/code}
                				{template:form/wdatePicker,start_date,$dates,'',$type_source}
                				<span style="margin:0 10px;float:left;">－</span>
                				{code}
                					$type_source = array('other'=>' size="14" autocomplete="off" style="width:125px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'end_date','style'=>'width:140px;float: left;','type'=>'yyyy-MM-dd','focus' => "$('[lang=zh-cn]').hide();");
              					  	$dates = $formdata['end_time'] ? date('Y-m-d', $formdata['end_time']) : '';
               					{/code}
                				{template:form/wdatePicker,end_date,$dates,'',$type_source}
                				<div style="color:red;display:inline" id="day_tips"></div>
                				<font class="important" style="color:red">*结束时间为空，计划无截止</font>
							</div>
							<div class='form_ul_div clear' id="week_date">
				                {code}
				                    $week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
				                {/code}
				                <label style="visibility:hidden;">重复：</label>
				                <label>
				                    <input class="n-h" type="checkbox" onclick="hg_plan_repeat(this,1);" id="every_day" name="every_day" {if count($formdata['week_day'])==7}checked{/if}/><span>每天</span>
				                </label>
				                {foreach $week_day_arr as $key => $value}
				                    <label>
				                        <input onclick="hg_plan_repeat(this,2);" class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $formdata['week_day'] as $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
				                    </label>
				                {/foreach}
							</div>
						</li>
						
						<li class="i">
						{if $formdata['infor']}
						{foreach $formdata['infor']['start_time'] as $key => $val}
						<div class='form_ul_div clear'>
							<span class='title'>开始时间: </span>
							<input type="text" name="start_time[]" value="{$formdata['infor']['start_time'][$key]}" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'HH:mm'})">
							结束时间: <input type="text" name="end_time[]" value="{$formdata['infor']['end_time'][$key]}" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'HH:mm'})">
							<span>审核状态: </span>
							<select name='type[]'>
								<option {if $formdata['infor']['type'][$key]==2}selected='selected'{/if} value ='2'>已审核</option>
							</select>&nbsp;
							<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
						</div>
						{/foreach}
						{/if}
						<div id="extend"></div>
						<div class="form_ul_div clear">
							<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom();">添加参数</span>
						</div>
						
					</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*为必填选项,审核状态以最后设置的时间段为准</font></span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br/>
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}