<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{js:area}
{js:common/ajax_upload}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return hg_form_check();">
		<h2>{$optext}船次</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">发船日期</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="departDate" id="departDate" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['departDate']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:block;">*不填写则默认今日,否则按格式:20131111或者2013-11-11填写</span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">船次</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="busCode" id="busCode" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['busCode']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">发船时间</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="departTime" id="departTime" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['departTime']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">上船站名称</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="departStation" id="departStation" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['departStation']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">到达站名称</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="arriveStation" id="arriveStation" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['arriveStation']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">终点站名称</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="terminalStation" id="terminalStation" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['terminalStation']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:block;">*不填写默认为到达站</span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">途时</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="takeTime" id="takeTime" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['takeTime']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">客座数</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="seats" id="seats" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['seats']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">船辆等级</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="busLevel" id="busLevel" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['busLevel']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:block;">*不填写默认为:客船</span>
					</div>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">余票</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="remainTickets" id="remainTickets" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['remainTickets']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
					<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">始发站名称</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="startStation" id="startStation" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['startStation']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:block;">*不填写默认为上船站</span>
					</div>
				</div>
			</li>
					<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">全票价</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="fullPrice" id="fullPrice" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['fullPrice']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
					<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">半票价</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="halfPrice" id="halfPrice" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['halfPrice']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:block;">*不填写默认为全票价1/2</span>
					</div>
				</div>
			</li>
								<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">校验信息</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="verifyMessage" id="verifyMessage" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['verifyMessage']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
								<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">里程</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="mileages" id="mileages" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$formdata['mileages']}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			{if $formdata['id']}  
								<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">到达时间</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								{$formdata['arriveTime']}</span> 
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			{/if}
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}&station={$formdata['departStation']}&date={$formdata['departDate']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<!-- 
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div> -->
{template:foot}

<script>
$(function($){
	(function($){
		$.widget('logo.upload_img',{
			options : {
				'add-photo' : '.add-photo',
				'logoid' : '.logoid',
				'photo-item' : '.photo-item',
				'image-file' : '.image-file',
				'add-pic-tpl' : '#add-pic-tpl',
			},
	        _create : function(){
	        	this.uploadFile=this.element.find(this.options['image-file']);
	        },
			
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['add-photo'] ] = '_addlogo';
				this._on(handlers);
				var url = "./run.php?mid=" + gMid + "&a=upload_img";

				this.uploadFile.ajaxUpload({
					url : url,
					phpkey : 'pic',
					after : function( json ){
						_this._uploadIndexAfter(json);
					}
				});
			},
			
			_uploadIndexAfter : function( json ){
				var op = this.options,
					data = json['data'];
				var info = {};
				info.imginfoid = data.id;
				info.img_info = data.img_info;
				var img = $( op['photo-item'] ).find('img');
				var src = info.img_info,
					id = info.imginfoid;
				img.attr('src',src);
				$( op['logoid'] ).val(id);
			},
			_addlogo : function(){
				var op = this.options;
				$( op['image-file'] ).click();
			}
	});
})($);
$('.col_choose').upload_img();
});
</script>