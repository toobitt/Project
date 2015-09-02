<?php 
/* $Id: program_list_day.php 9559 2012-06-02 09:29:20Z lijiaying $ */
?>
{template:head}
{css:2013/iframe}
{css:jsonview/jquery.jsonview}
{js:jsonview/jquery.jsonview}
<style>
input[type="text"]{width:440px;height:30px!important;border-radius:3px;padding:0 10px;}
.json-collapsed{width:440px;min-height:250px;border: 1px solid #ddd;padding: 10px;margin-bottom: 10px;border-radius:3px;overflow-x: auto;}
.wrap{padding:20px;}
.wrap .nav-title{display: block;width: 110px;height: 35px;background: #5394e4;color:#fff;font-size: 14px;line-height: 35px;text-align: center;margin-bottom: 15px;border-radius: 2px;}
.form_ul .i:not(:last-child){min-height:50px;min-height: 45px;border-bottom: 1px dotted #ddd;margin-bottom: 15px;}
.form_ul_div{float:left;margin-right: 20px;}
.form_ul_div .title{float:left;width: 60px;font-size: 14px;padding-top: 5px;text-align: right;}
</style>
	<div class="wrap clear">
		<div class="">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<span class="nav-title">日志操作信息</span>
				<div class="controll-area fr mt5" id="hg_parent_page_menu"><a class="back" onclick="javascript:history.go(-1);">返回</a></div>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标题：</span>
								<input type="text" value="{$formdata['title']}" name='title' disabled="disabled">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">操作：</span>
								<input type="text" value="{$formdata['operation']}" name='operation' disabled="disabled">
							</div>
						</li>
						<li class="i" style="overflow:hidden;">
							<div class="form_ul_div">
								<span  class="title">操作前：</span>
								<!-- <textarea rows="3" cols="80" name='pre_data'>{$formdata['pre_data']}</textarea>-->
								 <div id="json-collapsed-start" class="json-collapsed"></div>
							</div>
							<div class="form_ul_div">
								<span  class="title">操作后：</span>
								<!--<textarea rows="3" cols="80" name='up_data'>{$formdata['up_data']}</textarea>-->
								<div id="json-collapsed-end" class="json-collapsed"></div>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</form>
		</div>
	</div>
<script type="text/javascript">
$(function(){
	var pre = {code}echo $formdata['pre_data'] ? json_encode($formdata['pre_data']) : '{}';{/code};
	var end = {code}echo $formdata['up_data'] ? json_encode($formdata['up_data']) : '{}';{/code};
	$("#json-collapsed-start").JSONView( pre , {collapsed: true, nl2br: true});
	$("#json-collapsed-end").JSONView( end , {collapsed: true, nl2br: true});
});
</script>
{template:foot}