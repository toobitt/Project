{template:head}
{code}
//print_r($_INPUT['referto']);
//print_r(REFERRER);
//$referto = REFERRER;
{/code}
{css:ad_style}
{css:column_node}
{js:common/common_form}
{js:common/auto_textarea}
{js:hg_news}
{js:hg_water}
{js:hg_sort_box}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l" id="webform">
			{if $_INPUT['id']}
			{template:unit/publish_for_form, 1, $formdata['column_id']}
				<h2>编辑信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标题名：</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:440px;">
								<font class="important">标题名必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$formdata['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">关键字：</span>
								<input type="text" value="{$formdata['keywords']}" name='keywords' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div" style="margin-left: 73px;">
								<a class="common-publish-button overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
							</div>
							
						</li>
					</ul>
					{else}
				{/if}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$referto}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="program_id" value="{$formdata['program_id']}" />
				<input type="hidden" name="id" value="{$formdata['program_id']}" />
				<input type="hidden" name="submit_type" id="submit_type"/>
				<br/>
	
				     <input type="submit" name="sub" value="更新并发布" class="button_6_14"   />
				     <input type="button" value="取消"  class="button_6_14 option-iframe-back" style="margin-left:28px;" onclick="" />

			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
<script>
$(function () {
	hg_resize_nodeFrame(1);
	$([parent, parent.document, parent.document.documentElement]).scrollTop(0);
	var timeid = setInterval(function(){
        if($.fn.commonPublish){
            $('#publish-1').commonPublish({
                column : 2,
                maxcolumn : 2,
                height : 224,
                absolute : false
            });
            clearInterval(timeid);
        }
    }, 100);
    var isHas=window.parent.$('#formwin').length;
    if(!isHas){
          $('.option-iframe-back').attr('onclick','javascript:history.go(-1)');
     }
});
$(window).on("unload", function ()  {
	window.parent.$('#mainwin').trigger('iclose');
});

</script>
{template:foot}