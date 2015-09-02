/* 商品表单页 */
$(function(){
	var loading = $('#loading');
	(function($){
		$.widget('product.aside',{
			options : {
				url : '',
				ctrl : null
			},
			_init : function(){
				this._on({
					'click .indexpic' : '_uploadIndex',
				});
				this._initWidget();
			},
			_initWidget : function(){
				var _this = this;
				this.indexFile = this.element.find('input[type="file"]');
				this.indexFile.ajaxUpload({
					url : _this.options.url,
					before : function(){
						_this.options.ctrl.loading( $('.indexpic') );
					},
					after : function( json ){
						var parent = $('.indexpic');
						_this.options.ctrl.removeLoading( parent );
						var src = $.createImgSrc(json['data'],{width:160,height:160});
						parent.find('img').attr('src',src);
						parent.find('#indexpic_id').val(json['data']['id']);
					},
				});
			},
			_uploadIndex : function(){
				this.indexFile.click();
			}
		});
		$.widget('product.section',{
			options : {
				picUrl : '',
				vodUrl : '',
				picTpl : '',
				vodTpl : '',
				ctrl : null
			},
			_init : function(){
				this._on({
					'click .add-btn' : '_uploadMedia',
					'click .play' : '_playVideo',
					'click .vedio-back-close' : '_hideVideo',
					'click .del' : '_del',
					'blur .date-picker' : '_compareTime'
				});
				this.cache = {
						start_time : '',
						end_time : ''
				};
				this._initWidget();
				this._initSwitch();
			},
			_initWidget : function(){
				var _this = this;
				this.picFile = this.element.find('input[name="Filedata"]');
				this.vodFile = this.element.find('input[name="videofile"]');
				this.picFile.ajaxUpload({
					url : _this.options.picUrl,
					before : function(){
						_this.options.ctrl.loading( $('.add-pic') );
					},
					after : function( json ){
						var parent = $('.add-pic'),
							ctrl = _this.options.ctrl;
						ctrl.removeLoading( parent );
						var json = json['data'];
						var data = {
								'id' : json['id'],
								'src' : $.createImgSrc(json,{width:130,height:90})
						};
						ctrl.tmpl('pic-item', _this.options.picTpl, data, '.pic-list ' );
					},
				});
				this.vodFile.ajaxUpload({
					url : _this.options.vodUrl,
					type : 'vod',
					before : function(){
						_this.options.ctrl.loading( $('.add-vod') );
						$('.btn-mask').css('z-index',10);
					},
					after : function( json ){
						var parent = $('.add-vod'),
							ctrl = _this.options.ctrl;
						ctrl.removeLoading( parent );
						var data = json['data'];
						var info = {
								id : data.id,
								src : $.createImgSrc(data.img,{width:130,height:90}),
								material_id : data['material_id']
						}
						ctrl.tmpl('vod-item', _this.options.vodTpl, info, '.vod-list ' );
						$('.btn-mask').css('z-index',-1);
					},
				});
			},
			_initSwitch : function(){
				var switchs = this.element.find('.common-switch'),
					_this = this;
				switchs.each(function(){
					var val = $(this).hasClass( 'common-switch-on' ) ? 100 : 0;
					var currentSwitch = $(this);
					$(this).hg_switch({
						'value' : val,
						'callback' : function( event, value ){
							var is_on = 0;
							( value > 50 ) ? is_on = 1 : is_on = 0;
							currentSwitch.closest('.product-item').find('input[type="hidden"]').val(is_on);
						}
					});
				});
			},
			_uploadMedia : function( event ){
				var input = $(event.currentTarget).siblings('input');
				input.click();
			},
			_playVideo : function( event ){
				var	id = $(event.currentTarget).closest('li').attr('_id'),
					url = './run.php?mid='+gMid+'&a=get_video_url&vid='+id,
					_this = this;
				var top = $(event.currentTarget).closest('li').offset().top-100;
				$.getJSON(url, function( json ){
					var box = $('.player-box');
					box.addClass('show');
					_this.element.css('overflow','visible');
					box.empty();
					$('#vedio-tpl').tmpl(json).appendTo('.player-box');
					$('.player-box').find('.flash-box').css('margin-top',top);
					$('.player-box').find('.vedio-back-close').css('top',top);
				});
			},
			_hideVideo : function(){
				$('.player-box').removeClass('show').empty();
				this.element.css('overflow','hidden')
			},
			_del : function( event ){
				var self = $(event.currentTarget),
					parent = self.closest('li'),
					url = '';
				url = './run.php?mid='+gMid+'&a=del_mater&id=' + parent.attr( parent.attr('_mid') ? '_mid' : '_id' );
//				if( parent.attr('_mid') ){
//					url = './run.php?mid='+gMid+'&a=del_mater&id=' + parent.attr('_mid');
//				}else{
//					url = './run.php?mid='+gMid+'&a=del_mater&id=' + parent.attr('_id');
//				}
				$.globalAjax(parent, function(){
	                return $.getJSON(url, {ajax:1}, function(json){
	                	parent.remove();
	                });
	            });
			},
			_getTime : function( event ){
				var self = $(event.currentTarget),
					name = self.attr('name'),
					val = self.val();
				if( name == 'start_time' ){
					this.cache['start_time'] = val;
				}else{
					this.cache['end_time'] = val;
				}
			},
			_compareTime : function( event ){
				var self = $(event.currentTarget),
					start = $('input[name="start_time"]'),
					end = $('input[name="end_time"]');
				setTimeout(function(){
					var startVal = start.val().replace(/[^0-9]/g,''),
						endVal = end.val().replace(/[^0-9]/g,'');
					if( !start.val() || !end.val() )
						return;
					if( endVal-startVal <= 0 ){
						if( self.attr('name') == 'start_time' ){
							start.myTip({
								string : '开始时间应小于结束时间',
								delay : 2000,
								dtop : 30,
								width : 260
							});
						}else{
							end.myTip({
								string : '结束时间应大于开始时间',
								delay : 2000,
								dtop : 30,
								width : 260
							});
						}
					}
				},500);
			}
		});
		
		
		$.widget('product.ctrl',{
			options : {
				aside : $('aside'),
				section : $('section')
			},
			_init : function(){
				this._initWidget();
				this._on({
					'click .save-button' : '_submit',
					'click .btn-mask' : '_prevent'
				});
			},
			_initWidget : function(){
				var product_id = $('#product-id').val();
				var picUrl = '',
					vodUrl = '',
					indexUrl = '';
				if( product_id ){
					picUrl = './run.php?mid='+gMid+'&a=upload_pic&id=' + product_id;
					vodUrl = './run.php?mid='+gMid+'&a=upload_video&id=' + product_id;
					indexUrl = './run.php?mid='+gMid+'&a=upload_pic&indexpic=1&id=' + product_id;
				}else{
					picUrl = './run.php?mid='+gMid+'&a=upload_pic';
					vodUrl = './run.php?mid='+gMid+'&a=upload_video';
					indexUrl = './run.php?mid='+gMid+'&a=upload_pic&indexpic=1';
				}
				this.options.aside.aside({
					url : indexUrl,
					ctrl : this
				});
				this.options.section.section({
					picUrl : picUrl,
					vodUrl : vodUrl,
					picTpl : $('#pic-item-tpl').html(),
					vodTpl : $('#vod-item-tpl').html(),
					ctrl : this
				});
			},
			tmpl : function(tname, html, json, pos){
				$.template(tname, html);
				$.tmpl(tname, json).appendTo( pos );
			},
			loading : function( parent ){
				parent.css('position','relative');
				loading.appendTo(parent).show();
			},
			removeLoading : function( parent ){
				parent.find( loading[0] ).remove();
			},
			_prevent : function(event){
				$(event.currentTarget).myTip({
					string : '视频正在上传，请稍候...',
					delay : 2000,
					width : 150,
					color : '#1bbc9b'
				});
			},
			_submit : function(){
				//1.判断是否有索引图
				var indexpic = this.options.aside.find('.indexpic');
				if( !indexpic.find('img').attr('src') ){
					indexpic.myTip({
						string : '上传一张索引图吧~',
						delay : 2000
					});
					return;
				}
				//2.检查必填选项
				var count = $('.must-count'),
					price = $('.must-price'),
					title = $('#title');
				title.val() ? 
						( count.val() ? 
								( price.val() ? $('#save-btn').click() : this._saveTip('优惠价格',price ) )
									: this._saveTip('数量',count )
							)
							: this._saveTip('标题',title )
			},
			_saveTip : function(tip, input){
				input.myTip({
					string : '请填写'+tip,
					color : 'red'
				});
			}
		});
		$('body').ctrl();
	})($);
	
});