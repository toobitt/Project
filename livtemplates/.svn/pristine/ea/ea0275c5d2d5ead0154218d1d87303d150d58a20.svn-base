/*
 * author zhangzhen
 * date 2014-11-13
 * 全局视频设置弹窗组件
 * 组件名称 hoge_vodsetting
 * example $('div').hoge_vodsetting();
 * @param  可配置参数
 * 	{	ajax_url : './run.php?mid=' + gMid + '&a=get_transcode_config',  //取配置信息接口
* 		config : {														//配置项信息字典
					server_id : {title : '转码服务器', default_option : '空闲'},
					water_id : {title : '水印', default_option : '无', system_option : '系统预设水印'},
					mosaic_id : {title : '马赛克', default_option : '无'},
					vod_config_id : {title : '转码配置', default_option : '无'}
				}
 * }
 * 公用方法
 * show $('div').hoge_vodsetting('show')
 * close $('div').hoge_vodsetting('close')
 * */
(function($){
	var settingInfo = {
		template : '' +
					'<div class="global-vodsetting-box">' +
						'<div class="set-area-title">设置</div>' +
						'<ul class="set-area-nav">' +
							'{{each nav_list}}' +
							'<li class="${$value.key}" data-key="${$value.key}">${$value.title}(<span class="select-item">${$value.default_option}</span>)</li>' +
							'{{/each}}' +
						'</ul>' +
						'<div class="set-area-content">' + 
						'</div>' +
					'</div>' +
					'',
		setting_tpl : '' +
					'<div class="set-item ${key} clear" data-key="${key}">' +
						'{{if key != "water_id"}}'+
						'<ul>' +
							'{{if value}}' +
								'{{each value}}' +
								'<li data-id="${$value.id}" data-name="${$value.name}" data-set="true" {{if $data.key=="server_id"}}title="转码中:${$value.transcode_on};等待中:${$value.transcode_wait}"{{/if}}>' +
									'<span>${$value.name}</span>' +
									'<span class="flag"></span>' +
								'</li>' +
								'{{/each}}' +
							'{{/if}}' +
						'</ul>' +
						'{{else}}' +
						'<div class="watermark">' +
							'<div class="title">水印列表</div>' +
							'<ul>' +
								'{{if value}}' +
									'{{each value}}' +
									'<li data-id="${$value.id}" {{if $value.id == "system"}}class="select"{{/if}} data-name="${$value.name}" data-set="true">' +
										  '<img src="${$value.water_pic}"/>' +
										  '<span>${$value.name}</span>' +
										  '<span class="flag"></span>' +
									'</li>' +
									'{{/each}}' +
								'{{/if}}' +
							'</ul>' +
						'</div>' +
						'<div class="water-position set-item clear show" data-key="water_pos">' +
							'<div class="title">水印位置</div>' +
							'<ul>' +
							'<li data-id="0,0" data-name="左上">左上</li>' +
							'<li data-id="1,0" data-name="中上">中上</li>' +
							'<li data-id="2,0" data-name="右上">右上</li>' +
							'<li data-id="0,1" data-name="左中">左中</li>' +
							'<li data-id="1,1" data-name="中中">中中</li>' +
							'<li data-id="2,1" data-name="右中">右中</li>' +
							'<li data-id="0,2" data-name="左下">左下</li>' +
							'<li data-id="1,2" data-name="中下">中下</li>' +
							'<li data-id="2,2" data-name="右下">右下</li>' +
							'</ul>' +
							'<input type="hidden" class="fast-set-hidden"  name="water_pos" value=""/>' +
						'</div>' +
						'<input type="hidden" class="fast-set-hidden"  name="no_water" value="{{if !$item.is_defaultwater}}1{{/if}}"/>' +
						'{{/if}}' +
						'<input type="hidden" class="fast-set-hidden" name="${key}" value="" />' +
					'</div>' +
					'',
		css : '' + 
			'.global-vodsetting-box{padding-right:10px;}' +
			'.global-vodsetting-box .set-area-title{float:left;width:60px;height:36px;line-height:36px;font-size:16px;text-align:center;}' +
			'.global-vodsetting-box .set-area-nav{overflow:hidden;height:30px;border:1px solid #ccc;background:#efefef;color:#000;}' +
			'.global-vodsetting-box .set-area-nav li{float:left;height:30px;line-height:30px;padding:0 10px;border-right:1px solid #ccc;cursor:pointer;}' +
			'.global-vodsetting-box .set-area-nav li.select{background:#5295e6;color:#fff;}' +
			'.global-vodsetting-box .set-area-content{margin-left:60px;background:#fff;padding-bottom:20px;}' +
			'.global-vodsetting-box .set-item{display:none;border:1px solid #ccc;border-top:0;padding:10px;}' +
			'.global-vodsetting-box .set-item .title{font-weight:bold;padding:5px 0 10px;}' +
			'.global-vodsetting-box .set-area-content .show{display:block;}' +
			'.global-vodsetting-box .set-area-content li{cursor:pointer;}' +
			'.global-vodsetting-box .set-area-content li{float:left;padding: 5px 10px;background:#eee;color:#000;margin-right:10px;position:relative;}' +
			'.global-vodsetting-box .set-area-content li.select .flag{background:url(' + RESOURCE_URL + 'select.png) no-repeat;position: absolute;width: 18px;height: 18px;top: -7px;left: -5px;background-size:18px 18px;}' +
			'.global-vodsetting-box .water-area .title{font-weight:bold;padding:5px 0 10px;}' +
			'.global-vodsetting-box .watermark ul{height: auto;max-height:160px;padding-top: 5px;overflow-x: hidden;line-height: 15px;padding-left:10px;}' +
			'.global-vodsetting-box .watermark li{float:left;width: 74px;height: 60px;text-align:center;margin:5px 0 20px 15px;position:relative;background:transparent;padding:0;}' +
			'.global-vodsetting-box .watermark li img{width: 55px;height: 45px;margin-bottom:5px;}' +
			'.global-vodsetting-box .watermark li span{font-size: 12px;overflow: hidden;text-align:center;display:block;}' +
			'.global-vodsetting-box li.select .flag{left:5px;}' +
			'.global-vodsetting-box .water-position{width:200px;border:none;}' +
			'.global-vodsetting-box .water-position ul{margin-left:25px;margin-top:10px;}' +
			'.global-vodsetting-box .water-position li{float:left;padding: 5px 10px;margin-bottom:10px;background:#eee;color:#000;margin-right:10px;position:relative;}' +
			'.global-vodsetting-box .water-position li.select{background:#5295e6;color:#fff;}' +
			'',
		cssInit : false
	};
	
	$.widget('hoge.vodsetting_widget', {
		options : {
			ajax_url : './run.php?mid=' + gMid + '&a=get_transcode_config',
			cache_key : 'global_vodsetting_key'
		},
		
		_create : function(){
			$.template('setting_main_tpl',settingInfo.template);
			$.template('setting_item_tpl',settingInfo.setting_tpl);
			this._initCss();
		},
		
		_init : function(){
			this._on( {
				'click .set-area-nav li' : '_tab',
				'click .set-area-content li' : '_click',
			} );
			this._ajax();
		},
		
		_tab : function( event ){
			var self = $( event.currentTarget ),
				key = self.data('key'),
				current_item = this.element.find( '.set-item[data-key="' + key + '"]' );
			self.addClass( 'select' ).siblings().removeClass( 'select' );
			current_item.addClass( 'show' ).siblings().removeClass( 'show' );
		},
		
		_click : function( event ){
			var self = $( event.currentTarget ),
				item = self.closest( '.set-item' ),
				key = item.data('key');
			this._clickResult( {
				self : self,
				item : item,
				key : key
			} );
		},
		
		_clickResult : function( param ){
			var config = this.options.config,
				self = param.self,
				item = param.item,
				key = param.key,
				hidden_value = '',
				name = config[key] ? config[key]['default_option'] : '';
			var input_hidden_dom = item.find( 'input[name="' + key + '"]' ),
				current_nav_item = this.element.find('.set-area-nav li.select').find('.select-item');
			if( key == 'water_id' ){
				var input_nowater_hidden = item.find('input[name="no_water"]');
			}
			self.toggleClass( 'select' ).siblings().removeClass('select');
			if( self.hasClass('select') ){
				hidden_value = self.data( 'id' ),
				name = self.data( 'name' );
				if( key == 'water_id' ){
					input_nowater_hidden.val('');
				}
			}else{
				if( key == 'water_id' ){
					input_nowater_hidden.val('1');
				}
			}
			self.data( 'set' ) && current_nav_item.text( name );
			input_hidden_dom.val( hidden_value );
			localStorage.setItem( key, hidden_value + '|' + name );
		},
		
		_addCss : function(css){
            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
        },
        
        _initCss : function(){
        	if( !settingInfo.cssInit && settingInfo.css ){
        		this._addCss( settingInfo.css );
        	}
        },
		
		_initSetting : function( data ){
			if( $.isArray( data ) ){
				var data = this._handleData( data[0] ),
					is_defaultwater = data['defaultwater'].length ? true : false;
				this.element.html('');
				$.tmpl( 'setting_main_tpl', data  ).appendTo( this.element );
				var setting_content_area = this.element.find('.set-area-content');
				$.tmpl( 'setting_item_tpl', data.item_list, {
					is_defaultwater : is_defaultwater
				} ).appendTo( setting_content_area );
				this._initlocalStorage();
			}
		},
		
		_initlocalStorage : function(){
			var _this = this;
			this.element.find('.set-item').each( function(){
				var key = $(this).data( 'key' );
				var localData = localStorage.getItem( key );
				if( localData ){
					localData = localData.split( '|' );
					if( localData.length ){
						$(this).find('li').filter( function(){
							var id = $(this).data('id');
							return ( id == localData[0] );
						} ).trigger('click');
						_this.element.find( '.set-area-nav li.' +key ).find('.select-item').text( localData[1] );
					}
				}
			} );
		},
		
		_handleData : function( data ){
			var config = this.options.config,
				nav_list = [],
				item_list = [],
				other_config = data['other'],
				default_water = other_config.defaultwater;
			$.each( data, function( key, value ){
				if( key == 'other' ) return;
				var nav_obj = {},
					item_obj = {};
				nav_obj.key = key;
				nav_obj.title = config[key]['title'];
				nav_obj.default_option = config[key]['default_option'];
				item_obj.key = key;
				item_obj.value = value;
				if( key == 'water_id' && default_water.length ){
					nav_obj.default_option = config[key]['system_option'];
					item_obj.value.push( { id : 'system', name : config[key]['system_option'], water_pic : default_water[0]['water_default'] } );
				}
				nav_list.push( nav_obj );
				item_list.push( item_obj );
			} );
			return { nav_list : nav_list, item_list : item_list, defaultwater : default_water };
		},
		
		_ajax : function(){
			var _this = this,
				url = this.options.ajax_url,
				cache_data = this.getCache();
			if( cache_data ){
				this._initSetting( cache_data );
				return;
			}
			$.getJSON( url, function( data ){
				_this._initSetting( data );
				if( $.isArray( data ) && data.length ){
					_this.setCache( data );
				}
			} );
		},
		
		setCache : function( data ){
			if( data ){
				top.$.globalData.set(this.options.cache_key,data);
			}
		},
		
		getCache : function(){
			return top.$.globalData.get(this.options.cache_key);
		}
		
		
	});
	
	$.widget( 'hoge.hoge_vodsetting', {
		options : {
			css : '',
			ajax_url : './run.php?mid=' + gMid + '&a=get_transcode_config',
			config : {
				server_id : {title : '转码服务器', default_option : '空闲'},
				water_id : {title : '水印', default_option : '无', system_option : '系统预设水印'},
				mosaic_id : {title : '马赛克', default_option : '无'},
				vod_config_id : {title : '转码配置', default_option : '无'}
			}
		},
		_create : function(){
			this.element.html( '配置信息初始化中...' );
		},
		_init : function(){
			if( this.element.data('init') ){
				this.show();
				return;
			}
			this.element.vodsetting_widget( this.options );
			this.element.data('init',true);
		},
		show : function(){
			var css = this.options.css;
			this.element.show();
			css && this.element.css( css );
		},
		close : function(){
			this.element.hide();
		}
	} );
	
})($);
