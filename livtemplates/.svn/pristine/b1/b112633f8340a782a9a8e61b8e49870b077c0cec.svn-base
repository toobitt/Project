{template:head}
{code}
	
	foreach($formdata as $k=>$v)
	{
		$$k = $v;
	}
	if($id)
	{
		$optext="重新发送";
		$a="update";
	}
	else
	{
		$optext="发送";
		$a="create";
	}
	
{/code}
<script type="text/javascript">
	
</script>
{css:calendar}
{css:ad_style}
{css:admin_list}
{js:ad}
{js:jquery.multiselect.min}
{js:2013/ajaxload_new}
{js:ajax_upload}
{js:pop/base_pop}
{js:pop/pop_list}
{js:jqueryfn/jqueryfn_custom/hg_charcount}
{js:push_platform/notice_form}
{code}
{/code}
<script type="text/javascript">
function get_link_module(link_module){
	$('#link_module_2').val(link_module);
};
</script>
<style>
.red{color:red;}
.display{display:none;}
.platform-checkbox{float:left;display:none;}
.ad_form .form_ul li.i input[type="radio"]{margin-top: 5px;}
input[name="expire_time"]{margin-right:10px;}
.link-modules{display:-webkit-box;}
.item-name{display:block;margin: 5px 0px 5px 10px;}
.sel-con{width:30px;height:26px;background:url({$RESOURCE_URL}add_btn.png) no-repeat center;margin-left: 10px;cursor:pointer;display:block;}

.count-info{color:#808080;float: right;margin-right: 20px;}
.count-info .count{font-family: Constantia, Georgia;font-size: 22px;}

</style>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}通知</h2>

<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">选择应用：</span>
			{code}
				$app_source = array(
					'class' => 'down_list i select-app',
					'show' => 'app_shows_',
					'width' => 150,/*列表宽度*/		
					'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				);
				
				$default = $app_id ? $app_id : -1;
				$appendApp = $appendApp[0];
				$appendApp[-1] = '请选择';
			{/code}
			{template:form/search_source,app_push_id,$default,$appendApp,$app_source}
		</div>
	</li>
	
	<li class="i display platform">
		<div class="form_ul_div clear">
			<span class="title">推送对象：</span>
			<div class="platform-checkbox ios" style="{if $ios_dev}display:block;{/if}">
				<input type="radio" value='1' name='ios' {if $ios == 1}checked{/if}>
				<span class="platform">IOS开发环境</span>
			</div>
			<div class="platform-checkbox ios" style="{if $ios}display:block;{/if}">
				<input type="radio" value='2' name='ios'  class="platform_checkbox" {if $ios == 2}checked{/if}>
				<span class="platform">IOS生产环境</span>
			</div>
			<div class="platform-checkbox android" style="{if $android}display:block;{/if}">
				<input type="checkbox" value='1' name='android'  class="platform_checkbox" {if $android}checked{/if}>
				<span class="platform">Android</span>
			</div>
			<div class="platform-checkbox winphone" style="{if $winphone}display:block;{/if}">
				<input type="checkbox" value='1' name='winphone'  class="platform_checkbox" {if $winphone}checked{/if}>
				<span class="platform">WinPhone</span>
			</div>
		</div>
	</li>
	<li class="i display send_time">
		<div class="form_ul_div clear">
			<span class="title">发送时间：</span><input type="text" class="date-picker"  _time="true" name='send_time' value="{$send_time}" id="send_time" />
		</div>
	</li>
	<li class="i display expire_time"">
		<div class="form_ul_div clear">
			<span class="title">离线时间：</span><input type="text" name='expire_time' value="{$expire_time}"/>秒
		</div>
	</li>
	<li class="i display send_time">
		<div class="form_ul_div clear">
			<span class="title">注册id：</span><input type="text" value="{$install_id}" name='intall_id' class="title">
			<span class="tip">用于指定设备推送,群推不需要填</span>
		</div>
	</li>
	<li class="i display link-module">
		<div class="form_ul_div clear">
			<span class="title">链接模块：</span>
			<div class="link-modules">
				{code}
					$item_source = array(
						'class' => 'down_list i sel-link-module',
						'show' => 'item_shows_',
						'width' => 100,/*列表宽度*/		
						'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						'is_sub'=>1,
						'onclick'=>"get_link_module(this.getAttribute('attrid'))",
					);
					$group_id = $formdata['link_module'];
					$default = $group_id ? $group_id : -1;
					
					$appendModule = $appendModule[0];
					$appendModule[-1] = '选择模块';
				{/code}
				{template:form/search_source,link_module,$default,$appendModule,$item_source}
				<span class="item-name">模块标识：</span>
				<input type="text" id="link_module_2" value="{$formdata['link_module']}" name="link_module" size="10"/>
				<span class="item-name">内容id：</span>
				<input type="text"  name="content_id" value="{$formdata['content_id']}" size="10"/>
				<span class="sel-con"></span>
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">通知标题：</span><input type="text" value="{$title}" name='title' class="title">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">通知内容：</span>
			<textarea name="content" class="char-count">{$content}</textarea>
			<div class="count-info" style="">
				<span class="tip">还可以输入</span>
				<span class="count">50</span>个字
			</div>
		</div>
	</li>
</ul>

<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}通知" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2 style="display:none;"><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
	<div id="client-info-list"></div>
</div>
</div>
{template:foot}
