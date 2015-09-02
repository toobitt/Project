$(function(){
	(function($){
		$.widget('mood.mood_set',{
			options : {
				tmp : '',
			},
			
			_create : function(){
			},

			_init : function(){
				this._on({
					'click .indexpic' : '_suoyin',
					'change input[name="Filedata"]' : '_uploadsuoyin',
					'click .content-image' : '_thumbnail',
					'change #photo-file' : '_uploadfile',
					'click .content-del' : '_del',
					'click .add-button' : '_add'
				});
				this._submit();
			},
			
			_suoyin : function(){
				this.element.find('input[name="Filedata"]').trigger('click');
			},
			
			_uploadsuoyin : function(event){
				var self = event.currentTarget,
					box = this.element.find('.indexpic'),
					file= self.files[0],
					wid = 160,
					hei = 160;
				this._preview(box , file , wid , hei );
			},
			
			_thumbnail : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('li');
				obj.find('#photo-file').trigger('click');
			},
			
			_uploadfile : function(event){
				var self = event.currentTarget,
					box = $(self).closest('li').find('.content-image'),
					file= self.files[0],
					wid = 50,
					hei = 50;
				this._preview(box , file , wid , hei );
				box.find('p').hide();
			},
			
			_preview : function(box , file , wid , hei ){
				box.hg_preview({
					box : box,
					file : file,
					width: wid,
					height: hei
				});
			},
			
			_del : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('li'),
					_this = this,
					val = obj.find('.content-title').val();
				if(!val){
					obj.remove();
					_this.element.find('.content-list').each(function(index){
						$(this).find('.content-index').text(index+1);
					});
					_this.element.find('.content-list').each(function(index){
						$(this).find('.#photo-file').attr('name' , 'Filedata_' + index);
					});
				}else{
					var method = function(){
						obj.remove();
						_this.element.find('.content-list').each(function(index){
							$(this).find('.content-index').text(index+1);
						});
						_this.element.find('.content-list').each(function(index){
							$(this).find('.#photo-file').attr('name' , 'Filedata_' + index);
						});
					}
					this._remind( '您确认删除此选项吗？', '删除提醒' , method );	
				}
			},
			
			_add : function(){
				var op = this.options,
					tmp = op.tmp ,
					num = parseInt(this.element.find('.content-index:last').text()),
					info={};
				if(!num){
					info.num = 1;
					info.reduce = 0;
				}else{
					info.num = num + 1;
					info.reduce = num;
				};
				tmp.tmpl(info).appendTo('.contents-list ul');
			},
			
			_remind : function( title , message , method ){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{}
				});
			},
			
			_submit : function(){
				var sform = this.element,
					_this = this;
				sform.submit(function(){
					var val = sform.find('input[name="name"]').val();
					var submit_btn = sform.find('input[type="submit"]');
					var tip = '',
						dleft = -120;
					if(!val){
						tip = '标题不能为空';
					}
					if( tip ){
						submit_btn.myTip({
							string : tip,
							dleft : dleft,
							color : '#7bb0e6'
						});
						return false;
					}
				});
			},
		});	
	})($);
	$('.m2o-form').mood_set({
		tmp : $('#options-tpl')
	});
});