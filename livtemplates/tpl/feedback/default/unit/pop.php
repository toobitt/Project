<style>
.pop-head{height:38px;padding:5px 0;font-size:24px;color:#fff;font-size:24px;}
.pop-head .pop-title{float:left;}
.pop-head .pop-close{display:block;float:right;width:26px;height:26px;border-radius:2px;background:url({$RESOURCE_URL}datasource/close4.png) no-repeat center,-webkit-linear-gradient(#f3f3f3,#dedede);cursor:pointer;}
.pop-body{background:#fff;padding:20px;}
.m2o-flex{display:-webkit-box;display:-moz-box;display:box;width:100%;}
.m2o-flex-center{-webkit-box-align:center;-moz-box-align:center;box-align:center;}
.m2o-flex-one{-webkit-box-flex:1;-moz-box-flex:1;box-flex:1;}

.mypop{position:absolute;width:600px;top:-1000px;left:50%;margin-left:-300px;padding:10px;background:#6ba4eb;z-index:2;transition:top .4s;}
.mypop.show{top:0;}
.mypop .pop-body{max-height:480px; overflow-y:auto; overflow-x:hidden; }
.mypop .layout-left{margin-right:20px;}
.mypop .indexpic-box{position:relative;width:120px;height:120px;line-height:120px;text-align:center;background:url({$RESOURCE_URL}tv_interact/suoyin-default.png) no-repeat center;border:1px solid #ccc;cursor:pointer;}
.mypop .indexpic-box.has-img{background-image:none;}
.mypop .indexpic-box img{max-width:100%;max-height:100%; vertical-align: middle; }
.mypop .index-flag{background:url({$RESOURCE_URL}tv_interact/suoyintu.png);position:absolute;left:-2px;top:-2px;width:16px;height:46px;}
.mypop .index-flag.current{background-image:url({$RESOURCE_URL}tv_interact/suoyintu-current.png);}
.mypop label{display: inline-block;font-size:14px;width:64px;line-height:34px;text-align:right;padding-right:10px;}
.mypop .form-group{margin-bottom: 15px;}
.mypop .form-control{display:block;height:34px;color:#555;border:1px solid #ccc;border-radius:2px;width:100%;line-height:1.5; box-sizing:border-box; }
.mypop textarea.form-control{height:62px;}
.mypop .form-control.edui-default{height:auto; border-width:0; }
.mypop .breif.brief-editor{display:none; }
.mypop .form-time input{display:inline-block; width:154px;}
.mypop .form-time input:first-child{margin-right:8px; }
.mypop .form-time input:last-child{margin-left:8px; }
.mypop .handle-btn{display:inline-block;background:#6ba4eb;color:#fff;height:34px;width:120px;border-radius:2px;line-height:36px;text-align:center;font-size:16px;cursor:pointer;margin:10px 7px 0 0;}
.mypop .generate-btn{background:#1abc9c;}
</style>
<script type="text/x-jquery-tmpl" id="mypop-tpl">
<div class="layout m2o-flex">
	<div class="layout-left">
		<div class="indexpic-box {{if data.indexpic && data.indexpic.host}}has-img{{/if}}">
			<img src="{{if data.indexpic && data.indexpic.host}}{{= $.createImgSrc( data.indexpic, {width:120, height:120} )}}{{/if}}" class="index-pic">
			<span class="index-flag {{if data.indexpic && data.indexpic.host}}current{{/if}}"></span>
		</div>
		<input class="index-file" name="indexpic" type="file" accept="image/*" style="display:none;"/>
		<label class="control-label">{{if data.url}}<a href="{{= data.url}}" target="_blank">查看链接{{/if}}</a></label>
	</div>
	<div class="layout-right m2o-flex-one">
		<div class="form-group m2o-flex">
			<label class="control-label">标题</label>
			<div class="m2o-flex-one">
				<input class="form-control" name="title" value="{{= data.title}}"/>
			</div>
		</div>
		
		<div class="form-group m2o-flex">
			<label class="control-label">套系</label>
			<div class="m2o-flex-one">
				<input class="form-control" name="style" value="{{= data.style}}"/>
			</div>
		</div>
		
		<div class="form-group m2o-flex">
			<label class="control-label">模板</label>
			<div class="m2o-flex-one">
				<input class="form-control" name="template" value="{{= data.template}}"/>
			</div>
		</div>
		
		<!--<div class="form-group form-time m2o-flex">
			<label class="control-label">时间</label>
			<div class="m2o-flex-one">
				<input class="form-control date-picker" _time="true" name="start_time" value="{{= data.start_time}}"/>-<input class="form-control date-picker" _time="true" name="end_time" value="{{= data.end_time}}"/>
			</div>
		</div>-->
		
		<div class="form-group m2o-flex">
			<label class="control-label">抽奖ID</label>
			<div class="m2o-flex-one">
				<input class="form-control" name="lottery_id" value="{{= data.lottery_id}}"/>
			</div>
		</div>
	</div>
</div>
<div class="group-box">
	<div class="form-group m2o-flex">
		<label class="control-label">描述</label>
		<div class="m2o-flex-one">
			<textarea class="form-control brief-editor" name="brief">{{= data.brief}}</textarea>
		</div>
	</div>
	
	<div class="form-group m2o-flex">
		<label class="control-label">活动规则</label>
		<div class="m2o-flex-one">
			<textarea class="form-control brief-editor" name="more_info">{{= data.more_info}}</textarea>
		</div>
	</div>
	<div class="form-group m2o-flex">
		<label class="control-label">一句话</label>
		<div class="m2o-flex-one">
			<textarea class="form-control brief-editor" name="more_brief">{{= data.more_brief}}</textarea>
		</div>
	</div>
	<div class="form-group m2o-flex">
		<label class="control-label">一幅照</label>
		<div class="m2o-flex-one">
			<div class="indexpic-box {{if data.more_picture && data.more_picture.host}}has-img{{/if}}">
				<img src="{{if data.more_picture && data.more_picture.host}}{{= $.createImgSrc( data.more_picture, {width:120, height:120} )}}{{/if}}" class="index-pic">
			</div>
			<input class="index-file" name="more_picture" type="file" accept="image/*" style="display:none;"/>
		</div>
	</div>
	<div class="form-group m2o-flex">
		<label class="control-label"></label>
		<div class="m2o-flex-one">
			<div class="handle-btn submit-btn">保存</div>
			<div class="handle-btn generate-btn">生成页面</div>
		</div>
	</div>
	<input type="hidden" name="a" value="updateCatagory">
	<input type="hidden" name="id" value="{{= id}}">
</div>
</script>

<div class="mypop">
	<div class="pop-head">
		<div class="pop-title">补充分类数据</div>
		<div class="pop-close"></div>
	</div>
	<div class="pop-body">
		<form class="pop-inner">

		</form>
	</div>
</div>

<script>
$(function(){
	var currentId = {code}echo json_encode( $_INPUT['_id'] ){/code},
		triggerBth = $( top.frames['mainwin'].document.body ).find('.show-pop');
	$.widget('feedback.pop', {
		options : {
			Url : './run.php?mid=' + gMid
		},
		_create : function(){
			this.form = this.element.find('form');
		},
		_init : function(){
			this._on({
				'click .pop-close' : 'close',
				'click .indexpic-box' : '_triggerFile',
				'change .index-file' : '_changefile',
				'click .submit-btn' : '_submit',
				'click .generate-btn' : '_generate'
			});
		},
		_generate : function(e){
			var target = $(e.currentTarget),
				_this = this,
				param = {
					a : 'get_catagory',
					id : currentId,
				};
			$.globalAjax(target, function(){
				return $.getJSON(_this.options.Url, param, function( json ){
					var json = typeof json == 'string' ? JSON.parse(json) : json,
						isError = json.ErrorCode || json.ErrorText;
					target.myTip({
						string : isError ? json.ErrorCode : '保存成功',
						color : isError ? 'red' : ''
					});
				});
			});
		},
		_changefile:function( e ){
			var _this=this,
			    self = e.currentTarget,
				file = self.files[0];
			var box = $( self ).prev('.indexpic-box');
			var reader = new FileReader();
			reader.onload=function(e){
				box.find('img').attr('src', e.target.result);
				box.find('.index-flag').length && box.find('.index-flag').addClass('current');
			}
			reader.readAsDataURL(file);
		},
		_triggerFile : function( event ){
			var self = $(event.currentTarget);
			self.next('.index-file').click();
		},
		_submit : function( e ){
			var _this = this,
				trigger = $(e.currentTarget),
				load = $.globalLoad( trigger );
			this.form.ajaxSubmit({
				url : _this.options.Url,
				type : 'POST',
				success : function( json ){
					load();
					var json = typeof json == 'string' ? JSON.parse(json) : json,
						isError = json.ErrorCode || json.ErrorText;
					trigger.myTip({
						string : isError ? json.ErrorCode : '保存成功',
						color : isError ? 'red' : ''
					});
					var time = setTimeout(function(){
						_this.close();
					}, 2000);
				},
			});
		},
		ajaxData : function( callback ){
			var _this = this,
				params = {
						a : 'getCatInfo',
						id : currentId,
					};
			$.globalAjax($('.m2o-list'), function(){
				return $.getJSON(_this.options.Url, params, function( json ){
					var data = json[0];
					$('#mypop-tpl').tmpl({
						id : currentId,
						data : data,
					}).appendTo( _this.form.empty() );
					_this._initDom();
					_this._includeUEditor();
					_this.show();
				});
			});
		},
		
		_includeUEditor : function(){
			var _this = this;
			this.element.find('.brief-editor').each(function(){
				var id = $(this).attr('id'),
					name = $(this).attr('name');
				$.includeUEditor( _this._initEditor( id, name ), {
					plugins: null
				} );
			});
		},
		
		_initEditor : function( editorId, name ){
			return function(){
				$.m2oEditor.get( editorId, {
					initialFrameWidth : 470,
					initialFrameHeight : 62,
					editorContentName : name,
				} );
			}
		},
		
		_initDom : function(){
			this.form.find('.date-picker').hg_datepicker();
			
			this.form.find('.brief-editor').each(function(){
				var id = Math.ceil( Math.random() * 1000 );
				$(this).attr('id', id);
			});
		},
		show : function(){
			this.element.addClass('show');
			
		},
		close : function(){
			this.element.removeClass('show');
		},
	});
	var pop = $('.mypop').pop();
	triggerBth.click(function(){
		pop.pop('ajaxData');
	});
});
</script>