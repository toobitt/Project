$(function(){
	(function($){
		$.widget('vod.vod_list' , {
			options : {
				
			},
			
			_create : function(){
				this.ids = ''
			},
			
			_init : function(){
				this._on({
					'click .export-file' : '_show',
					'click .export-file-close' : '_close',
					'click .export-btn' : '_export'
				})
			},
			
			_show : function( event ){
				var self = $(event.currentTarget),
					type = self.attr('_type'),
					position = self.offset(),
					item = this.element.find('.export-file-box');
				    this.item = self;
				if( type == 1 ){
					this.ids = this.element.find('.common-list-data').map(function(){
						if( $(this).find('input[type="checkbox"]').prop('checked') ){
							return $(this).attr('_id');
						}
					}).get().join(',')
				}else{
					this.ids = self.closest('li').attr('_id');
				}
				if(!this.ids){
					var tip = '请先选择导出纪录';
					this._myTip( self , tip );
					return false;
				}else{
					position.top < 378 ? item.css('top' , 78 + 'px') : item.css('top' , position.top-300 + 'px');
				}
			},
			
			_close : function(){
				this.element.find('.export-file-box').css('top' , -500 + 'px');
			},
			
			_export : function( event ){
				var self = $(event.currentTarget),
					box = this.element.find('.export-file-box'),
					url = './run.php?mid='+ gMid +'&a=xmlExport',
					item = this.element.find('.file-list li'),
					fileid = item.map(function(){
						if( $(this).find('input[type="radio"]').prop('checked') ){
							return $(this).attr('_id');
						}
					}),
					is_file = this.element.find('input[name="is_need_file"]').prop('checked') ? 1 : 0,
					data = {
						vod_id : this.ids,
						xml_id : fileid[0],
						need_file : is_file
					},
					_this = this;
				this.element.find('.exporting').show();
				$.globalAjax(box, function(){
			        return $.getJSON(url,data,function(json){
			        	var tip = '成功导出';
						_this._myTip(self , tip);
						if(_this.item.attr('_type') == 0){
							_this.item.removeClass('un_export').addClass('is_export').text('已导出');
						}else{
							_this.element.find('.common-list-data ').each(function(){
								var checked = $(this).find('input[type="checkbox"]').prop('checked'),
									obj = $(this).find('.export-file');
								if( checked ){
									return obj.removeClass('un_export').addClass('is_export').text('已导出');
								}
							})
						}
						_this.element.find('.exporting').hide();
						setTimeout(function(){
							_this.element.find('.export-file-close').trigger('click');
						},'2000');	
			        });
			    });
			},

			_myTip : function( self , tip ){
				self.myTip({
					string : tip,
					delay: 1000,
					dtop : 0,
					dleft : 80,
					color : '#1bbc9b'
				});
			},

		});
	})($);
	$('.common-list-content').vod_list();
});