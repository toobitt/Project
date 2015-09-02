<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{js:area}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{js:jquery.upload}
{js:team_apply}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:tree/animate}
{js:action_ts}
<script type="text/javascript">
jQuery(function($){
	{if $province}
	new PCAS("c_province", "c_city", "c_area", "{$province}", "{$city}", "{$area}");
	{else}
	new PCAS("c_province", "c_city", "c_area");
	{/if}
});

$(function() {
	$('#upload_img').click(function() {
		if ($('#logo').val()) return;
		var _self = $(this);
		// 上传方法
		$.upload({
			// 上传地址
			url: "?mid={$_INPUT['mid']}&a=upload{$_ext_link}", 
			// 文件域名字
			fileName: 'Filedata', 
			// 其他表单数据
			params: {},
			// 上传完成后, 返回json, text
			dataType: 'json',
			// 上传之前回调,return true表示可继续上传
			onSend: function() {
				return true;
			},
			// 上传之后回调
			onComplate: function(data) {
				if (data && data[0])
				{
					var obj = data[0];
					var url = obj.host + obj.dir + '100x100/' + obj.filepath + obj.filename;
					var con = '<img src="'+url+'" width="100" height="100" />';
					_self.html(con);
					$('#logo').val(obj.id);
					var btn = '<span id="delete_upload" class="com_btn">删 除</span>';
					_self.parent().append(btn);
				}
			}
		});
	});

	$('#selectMaterial').click(function() {
		$('#auth_title').html('未使用的素材');
		if ($('#add_auth').css('display')=='none')
		{
			if ($('#showPicMaterial'))
			{
				var con = $('#showPicMaterial').html();
				$('#auth_form').html(con);
				$('#showPicMaterial').remove();
			}
			$('#add_auth').css({'display':'block'});
			$('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
				hg_resize_nodeFrame();
			});
		}
		else
		{
			hg_closeAuth();
		}
	});

	$('#delete_upload').live('click', function() {
		var id = $('#logo').val();
		var ids = [];
		$('#auth_form span').each(function() {
			ids.push($(this).attr('data-id'));
		});
		if ($.inArray(id, ids) == -1)
		{
			var url = $('#upload_img img').attr('src');
			var con = '<li id="pic_'+id+'">\
			<img id="img_'+id+'" src="'+url+'" />\
			<span data-id="'+id+'"><strong class="delPic">[删除]</strong><strong class="usePic">[使用]</strong></span></li>';
			$('#auth_form ul').prepend(con);
		}
		else
		{
			var els = $('#pic_'+id);
			els.find('.delPic').show();
			els.find('.usePic').show();
			els.find('.cancel').remove();
			els.find('.usingPic').remove();
			els.find('.mark').remove();
		}
		$('#upload_img').html('');
		$('#logo').val('');
		$(this).remove();
	});

	$('.delPic').live('click', function() {
		var id = $(this).parent().attr('data-id');
		var url = "?mid={$_INPUT['mid']}&a=dropImage&id="+id;
		$.getJSON(url, function(data) {
			if (data) {
				$('#pic_'+id).remove();
				if (!$.trim($('#picList').html())) {
					$('#selectMaterial').remove();
				}
			}
		});
	});

	$('.usePic').live('click', function() {
		$('#auth_form div.mark').remove();
		$('#auth_form .cancel').remove();
		$('#auth_form .usingPic').remove();
		$('#auth_form .delPic').show();
		$('#auth_form .usePic').show();
		var id = $(this).parent().attr('data-id');
		var sid = $('#logo').val();
		if (sid && sid != id)
		{
			var ids = [];
			$('#auth_form span').each(function() {
				ids.push($(this).attr('data-id'));
			});
			if ($.inArray(sid, ids) == -1)
			{
				var p_url = $('#upload_img img').attr('src');
				var p_con = '<li id="pic_'+sid+'">\
				<img id="img_'+sid+'" src="'+p_url+'" />\
				<span data-id="'+sid+'"><strong class="delPic">[删除]</strong><strong class="usePic">[使用]</strong></span></li>';
				$('#auth_form ul').prepend(p_con);
			}
		}
		var els = $('#pic_'+id);
		$('<div>', {
			'class' : 'mark',
		}).appendTo(els);
		$(this).parent().children().hide();
		var con = '<strong class="cancel">[撤销]</strong><em class="usingPic">已使用</em>';
		$(this).parent().append(con);
		var url = $('#img_'+id).attr('src');
		var img = '<img src="'+url+'" width="100" height="100" />';
		$('#upload_img').html(img);
		$('#logo').val(id);
		if ($('#delete_upload').length == 0) {
			var btn = '<span id="delete_upload" class="com_btn">删 除</span>';
			$('#upload_img').parent().append(btn);
		}
	});

	$('.cancel').live('click', function() {
		var id = $(this).parent().attr('data-id');
		var els = $('#pic_'+id);
		$(this).siblings('.delPic').show();
		$(this).siblings('.usePic').show();
		$(this).remove();
		els.find('.usingPic').remove();
		els.find('.mark').remove();
		$('#upload_img').html('');
		$('#logo').val('');
		$('#delete_upload').remove();
	});
});
</script>
<style type="text/css">
.com_btn {background:#5B5B5B; color:#FFF; border-radius:2px; display:inline-block; padding:4px 8px; cursor:pointer;}
#upload_img {display:inline-block; width:100px; height:100px; background:url("{$RESOURCE_URL}add-bg.png") no-repeat center center; float:left; margin-right:20px; border:1px solid #DEDEDE;}
#auth_form li {float:left; margin:0 17px 20px 0; position:relative;}
#auth_form li span {display:block;}
#auth_form li span strong {cursor:pointer; font-weight:normal;}
#auth_form strong.delPic,#auth_form strong.cancel {float:right;}
.mark {background:url("{$RESOURCE_URL}video/select-2x.png") no-repeat center center; position:absolute; left:0; top:0; z-index:9999; width:100px; height:100px;}
#showPicMaterial {display:none;}
</style>
		<!-- 新增分类面板 开始-->
 		 <div id="add_auth" class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">推送</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
 		 	</div>
		 </div>
 	    <!-- 新增分类面板结束-->
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return hg_form_check();">
		<h2>{$optext}样式</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title" style="width:72px;">样式名称：</span>
						<div class="input " style="width:345px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="zh_name" id="zh_name" size="14" style="width:330px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$formdata['zh_name']}"></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">样式类型：</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="type" id="catalog_type" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" {if $formdata['type']} readonly="readonly" {/if} value="{$formdata['type']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:block;">* {if $formdata['type']}不允许更改 {else}必填 {/if}</span>
					</div>
				</div>
			</li>
			
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title" style="width:72px;">样式HTML：</span>
						{code}$formdata['formhtml']=htmlentities($formdata['formhtml'],ENT_NOQUOTES);{/code}
						<textarea id="intro" name="formhtml">{$formdata['formhtml']}</textarea>
						<!-- <input type='hidden' name='html' value=1> -->
						<span class="error" id="intro_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
		</ul>
	<input type="hidden" name="zh_name_old" value="{$formdata['zh_name']}" />
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div>
{template:foot}