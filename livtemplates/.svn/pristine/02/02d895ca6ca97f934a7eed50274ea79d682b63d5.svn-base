(function($){
	$.widget('subway.subwaySlider', {
		options : {
			currentKey : [],
			order : [],
			delSiteUrl : '',
			dragOrderUrl : '',
			addSitetpl : '',
			addSitetname : 'add-site-tpl',
			addSlidertpl : '',
			addSlidertname : 'add-slider-tpl',
			addSorttpl : '',
			addSorttname : 'add-sort-tpl'
		},
		
		_create : function(){
			this.list = this.element.find('.way-list');
			$.template(this.options.addSitetname, this.options.addSitetpl);
			$.template(this.options.addSlidertname, this.options.addSlidertpl);
			$.template(this.options.addSorttname, this.options.addSorttpl);
		},
		
		_init : function(){
			var _this = this;
			this._on({
				'click .program-li' : '_editSite',
				'click .theme-edit' : '_delSite',
			});
			this.siteData = $.isArray($.globalSite) ? $.globalSite : [];
			this._initCache();
			this._initEvent();
			this.element.find('.slider').each(function(i){
				_this._initSlider($(this), i);
				_this.JudgeShow( $(this).find('a'), false );
			});
		},
		
		_initCache : function(){
			var _this = this;
			var len = this.siteData.length;
			if( len > 16 ){
				var k = Math.floor((len - 16)/8);
				if( k ){
					for(var i = 0; i <= k; i++){
						var point = (i%2) ? 'right' : 'left';
						$.tmpl(this.options.addSlidertname, {index : (i + 2), point : point}).appendTo( this.element );
					}
				}else{
					$.tmpl(this.options.addSlidertname, {index : (k + 2), point : 'left'}).appendTo( this.element );
				}
			}
			if( len ){
				_this._drawSite();
			}
			$('.site_num').find('em').html( len || 0 );
			this.getBeginningSite();
		},
		
		_drawSite : function(){
			var _this = this;
			$.each(this.siteData, function(i, v){
				v.payer = Math.floor(i/8);
				v.top = v.payer * 60 - 30;
				v.slider = _this.sliderToLeft( i, v.payer );
				v._keys = v._keys || _this.randNum(4);
				v.Sslider = ( v.payer % 2 ) ? (v.payer + 1) * 840 - v.slider : v.payer * 840 + v.slider;
				if( v.subname && v.subname.length > 1 ){
					v.tname = v.subname.join(',');
				}
			});
			$.tmpl(this.options.addSitetname, this.siteData).appendTo( this.list.empty() );
		},
		
		_initEvent : function(){
			var _this = this;
			$('.list_sort').click(function(){
				var self = $(this), bool = true,
					id = $('#roadForm').data('id');
				var box = $('.site-box');
				var dataArray = _this._ergodicCache('id', false);
				$.each(_this.siteData, function(k, v){
					if( !v.id ){
						bool = false;
						return false;
					}
				});
				var method = function(){
					$.tmpl(_this.options.addSorttname, _this.siteData).appendTo( box.find('ul').empty() );
					_this._sortable( box.find('ul') );
					_this.orderid = _this.getOrderid();
					_this.siteid = _this.getSiteid();
					box.data('id', id).removeClass('pop-hide');
					$('.cover-layer').fadeIn();
				};
				if( !bool ){
					_this.remind( '你还有站点未保存，确定要执行此操作', '提醒', method, self );
				}else{
					method();
				}
			});
			
			$('.sort-save').click(function(){
				var self = $(this),
					sub_id =  $('.site-box').data('id');
				$('.site-box').find('li').each(function(i){
					$(this).attr('order_id', _this.orderid[i]);
				});
				var orderid = _this.getOrderid().join(','),
					siteid = _this.getSiteid().join(',');
				$.globalAjax( self, function(){
					return $.getJSON(_this.options.dragOrderUrl, {content_id : siteid, order_id : orderid, sub_id : sub_id}, function( data ){
						if(data['callback']){
							eval( data['callback'] );
							return;
						}else{
							if( data && data[0] && data[0]['id']){
								_this._trigger('sliderTip', null, [self, '排序保存成功']);
								setTimeout(function(){
									_this.sortBack( siteid );
									if( !$('.way-content').attr('site_id') ){
										$('.way-content').slideUp();
									}
									$('.sort-close').click();
								},3000);
							}else{
								_this._trigger('sliderTip', null, [self, '排序保存失败']);
								
							}
						}
					});
				});
			});
			
			$('.sort-close').click(function(){
				var box = $('.site-box');
				box.addClass('pop-hide');
				$('.cover-layer').fadeOut();
			});
		},
		
		sortBack : function( siteid ){
			var site_ids = siteid.split(','),
				_this = this;
			var len = this.siteData.length;
			var orderArr = []
			$.each(site_ids, function(k, v){
				for( var i=0; i<len; i++ ){
					if( _this.siteData[i].id == v ){
						orderArr[k] = _this.siteData[i];
					}				
				}
			});
			this.siteData = orderArr;
			this._drawSite();
			this.getBeginningSite();
			$('.m2o-save').click();
		},
		
		getOrderid : function(){
			return $('.site-box').find('li').map(function(){
				return $(this).attr('order_id');
			}).get();
		},
		
		getSiteid : function(){
			return $('.site-box').find('li').map(function(){
				return $(this).attr('site_id');
			}).get();
		},
		
		_sortable : function( dom ){
			dom.sortable({
				revert: false,
		        cursor: "move",
		        axis : 'y',
		        tolerance : 'pointer',
		        placeholder: "ui-state-highlight"
			});
		},
		
		getBeginningSite : function(){
			var siteData = this.siteData;
			var len = siteData.length;
			var starttitle = len ? siteData[0].title : '',
				startegname = len ? siteData[0].egname : '',
				startid = len ? siteData[0].id : '';
			if( len > 1 ){
				var endtitle = siteData[len-1].id ? siteData[len-1].title : siteData[len-2].title,
				endegname = siteData[len-1].id ? siteData[len-1].egname : siteData[len-2].egname,
				endid = siteData[len-1].id ? siteData[len-1].id  : siteData[len-2].id;
			}
			var form = $('#roadForm');
			form.find('input.start').val( starttitle );
			form.find('input.end').val( endtitle );
			form.find('input[name="start_egname"]').val( startegname );
			form.find('input[name="end_egname"]').val( endegname );
			form.find('input[name="site_count"]').val( len );
			form.find('input[name="start"]').val( startid );
			form.find('input[name="end"]').val( endid );
			var acontent = [], aorder = [];
			$.each(siteData, function(k, v){
				acontent.push(v.id);
				aorder.push(v.order_id);
			});
			var content_id = acontent.join(',');
				order_id = aorder.join(',');
			form.find('input[name="content_id"]').val( content_id );
			form.find('input[name="order_id"]').val( order_id );
			this._trigger('getBeginning', null, [starttitle, endtitle]);
		},
		
		_editSite : function( event ){
			var self = $(event.currentTarget),
				_this = this,
				site_id = self.attr('site_id'),
				_keys = self.attr('_keys'),
				left = self.attr('_slider');
			if( site_id ){
				var dataArray = this._ergodicCache('id', false, true);
				if( !dataArray[0] ){
					this._judgeData(dataArray[1]._keys, false, dataArray[1].slider);
					this.element.find('.program-li[_keys=' + dataArray[1]._keys + ']').remove();
				}
				this._trigger('removeSiblings', event, [self]);
				this._trigger('editSiteinfo', event, [site_id, _keys]);
			}else{
				this._trigger('sliderTip', event, [self, '请先保存该站点再编辑']);
			}
			this.element.find('.slider').find('a').hide();
		},
		
		
		pointShow : function( _keys, val ){
			var parent = this.element.find('.program-li[_keys=' + _keys + ']'),
				dslider = this.element.find('.slider');
			this.JudgeShow( dslider.find('a'), false );
			var type = true;
			var site_id = $('.way-content').attr('site_id');
			if( val ){
				type = this.JudgeTitle( val, parent );
				parent.find('.program-con').show();
				if( type ){
					parent.find('.theme-label').show().html( val ).attr('title', val);
				}
			}else if( !val && !site_id ){
				parent.remove();
				this._judgeData(_keys, false, null);
				$('.way-content').attr('site_id', '').attr('_keys', '');
			}
		},
		
		JudgeTitle : function( val, dom ){
			if( val != dom.find('.theme-label').html() ){
				var type = this._ergodicCache('title', true, val);
				type && this._trigger('sliderTip', null, [dom, '站点名称不能相同!']);
				return !type;
			}
		},
		
		_setLeft : function ( type ){
			var _this = this;
			var len = this.siteData.length;
			if( type && (len > 16) && (len%8 == 1) ){
				var k = Math.floor((len - 16)/8);
				if( !this.element.find('.slider[id="' + (k+2) + '_way-slider"]').length ){
					var point = (k%2) ? 'right' : 'left';
					$.tmpl(this.options.addSlidertname, {index : (k + 2), point : point}).appendTo( this.element );
					var dom = this.element.find('.slider').last(),
						index = this.element.find('.slider').length -1;
					this._initSlider(dom, index);
					this.JudgeShow( dom.find('a'), false );
				}
			}
			$.each(this.siteData, function(k, v){
				var obj = _this.element.find('.program-li[_keys=' + v._keys + ']');
				v.payer = Math.floor(k/8);
				v.top = v.payer * 60 - 30;
				v.slider = _this.sliderToLeft( k, v.payer );
				v.Sslider = ( v.payer % 2 ) ? (v.payer + 1) * 840 - v.slider : v.payer * 840 + v.slider;
				obj.attr('_slider', v.slider).css({'left': v.slider + 'px' , 'top' : v.top + 'px'});
			});
		},
		
		/*遍历全局数据，对比或判断*/
		/* key为要处理的value中的字段, type为true时表示比较,false时判断;
		 * data存在时表示对比的数据
		 */
		_ergodicCache : function(key, type, data){	
			var siteData = this.siteData;	
			var reply = false;
			if( siteData.length ){
				$.each(siteData, function(k, v){
					if( type && (v[key] == data)){
						reply = true;
						return false;
					}
					if( !type && v[key]){
						reply = [true, v];
					}else if( !type && !v[key]){
						reply = [false, v]
						if( data ){
							return false;
						}
					}
				});	
			}
			return reply;
		},
		
		JudgeShow : function( dom, type, reverse ){
			var len = dom.length;
			if( len > 1 ){
				$.each(dom, function(){
					$(this)[type ? 'show' : 'hide']();
				});
			}else{
				dom[type ? 'show' : 'hide']();
				reverse && reverse[type ? 'hide' : 'show']();
			}
		},
		
		sliderToLeft : function(key, payer){
			var left;
			tleft = Math.round((key - 8 * payer) * 120);
			left = (payer % 2) ? 840 - tleft : tleft;
			return left;
		},
		
		/*处理全局数据，增加或删除*/
		/*_keys为唯一标示符, type为true时增加数据, type为false时删除数据*/
		_judgeData : function( _keys, type, slider, noon ){
			var siteData = this.siteData, top, bool = false; len;
			var len = siteData.length;
			if( type ){
				top = noon * 60 - 30;
				if( len > 1 ){
					for(var i=0; i< len-1; i++){
						siteData[i].Sslider = ( siteData[i].payer % 2 ) ? (siteData[i].payer + 1) * 840 - siteData[i].slider : siteData[i].payer * 840 + siteData[i].slider;
						siteData[i+1].Sslider = ( siteData[i+1].payer % 2 ) ? (siteData[i+1].payer + 1) * 840 - siteData[i+1].slider : siteData[i+1].payer * 840 + siteData[i+1].slider;
						Sslider = (noon % 2) ? (noon + 1)*840 - slider : noon * 840 + slider;
						if(siteData[i].Sslider < Sslider && siteData[i+1].Sslider > Sslider){
							siteData.splice(i+1, 0, {slider : slider, _keys : _keys, top: top, payer : noon});
							len = len + 1;
							bool = true;
							return false;
						}
					}
				}
				if( !bool ){
					siteData.push({slider : slider, _keys : _keys, top: top, payer : noon});
				}
			}else{
				for(var i=0; i< len; i++){
					if(siteData[i]._keys == _keys){
						siteData.splice(i, 1);
						len = len -1;
						return false;
					}
				}
			}
			this.siteData = siteData;
		},
		
		remind : function( title, message, method, dom ){
			jConfirm( title, message , function(result){
				result && method();
			}).position( dom );
		},
		
		_delSite : function( event ){
			var parent = $(event.currentTarget).closest('.program-li'),
				_this = this;
			var site_id = parent.attr('site_id'),
				_keys = parent.attr('_keys'),
				_slider = parent.attr('_slider'),
				sub_id = $('#roadForm').data('id');
			if( site_id ){
				var method = function(){
					$.globalAjax( parent, function(){
						return $.getJSON(_this.options.delSiteUrl, {site_id : site_id, sub_id : sub_id}, function( data ){
							if(data['callback']){
								eval( data['callback'] );
								return;
							}else{
								if( data && data[0]){
									_this._deleteSiteBack( parent, _keys, _slider, site_id );
								}
							}
						});
					});
				};
				_this.remind( '您确定删除该站点吗', '删除提醒', method, parent );
			}else{
				this._deleteSiteBack( parent, _keys, _slider );
			}
			event.stopPropagation();
		},
		
		_deleteSiteBack : function( dom, _keys, _slider, site_id ){
			dom.remove();
			_this = this;
			if( $('.way-content').attr('_keys') == _keys ){
				$('.way-content').attr('site_id', '').slideUp();
			}
			if( site_id ){
				this._judgeData(_keys, false, _slider);
				$('.site_num').find('em').html( _this.siteData.length || 0 );
				this.getBeginningSite();
				this._setLeft( true );
				setTimeout(function(){
					$('.m2o-save').click();
				}, 300);
			}
		},
		
		_initSlider : function(e, noon){
			var _this = this;
			e.slider({
				orientation: "horizontal",
				value:0,
				min: 0,
				max: 840,
				step: 1,
				disabled:false,
				noon : noon,
				create : function( event, ui ){
					var slider_shadow = '<div class="slider_shadow"></div>';
					$(this).append(slider_shadow);
				},
				start : function( event, ui ){
					e.find('a').show();
				},
				slide: function(event, ui) {
					_this.createForm(ui.value, $(this).slider("option", "noon"));
				},
			}).on({
				'setSlider' : function( event, data ){
					e.slider("option", "value",data.slider);
					e.slider("option", "noon",data.noon);
				},
				'mousemove' : function( event ){
					var position = { x: event.pageX, y: event.pageY };
					var offset = $(this).offset();
					var cha = parseInt(position.x - offset.left);
					var slider_value = cha;
					var this_slider = '#'+$(this).attr('id');
					if(!$(this_slider).find('.site-move').length){
						var way_dom = '<span class="site-move"></span>';
						$(this).find('a').before(way_dom);
					}
					if(cha <= e.slider("option", "min"))
					{
						slider_value = e.slider("option", "min");
					}
					if(cha >= e.slider("option", "max"))
					{
						slider_value = e.slider("option", "max");
					}
					var pos = slider_value;
					$(this_slider + ' .site-move').css({'left':pos}).show();
					
				},
				'mouseout' : function( event ){
					var this_slider = '#'+$(this).attr('id');
					$(this_slider + ' .site-move').hide();
				}
			});
		},
		
		createForm : function( slider, noon ){
			var tslider = this.element.find('.slider').eq( noon );
			var _this = this, 
				isbool = true;
			if( !$('#roadForm').data('id') ){
				if( !$('.m2o-m-title').val() ){
					this._trigger('sliderTip', null, [$('.m2o-m-title'), '请先填写线路名称']);
					isbool = false;
					this.JudgeShow( tslider.find('a'), false );		//滑动点消失
					return false;
				}
				this._trigger('saveRoadForm', null);
			}else{
				this._saveLastInfo();
				this._trigger('tabTrigger', null, [0]);
			}
			if( $('.way-map').find('.program-li').length ){
				$('.way-map').find('.program-li').each(function(){
					if( !$(this).find('.theme-label').html() ){
						$(this).remove();
						_this._judgeData( $(this).attr('_keys'), false, $(this).attr('slider') );
					}
				})
			}
			if( isbool ){
				setTimeout(function(){
					_this.appendProgram( slider, noon );
				}, 800);
			}
		},
		
		appendProgram : function( slider, noon ){
			var tslider = this.element.find('.slider').eq( noon );
			var sslider = tslider.siblings();
			var parent = tslider.closest('.way-map');
			var _keys = this.randNum(4);
			parent.find('.program-li').removeClass('selected');
			var top = noon * 60 - 30;
			var html = '<div class="program-li" _slider="' + slider + '" _keys="' + _keys + '"style="z-index:999;left:' + slider + 'px; top:' + top + 'px;"><span style="display:none;" class="program-con"></span><span class="theme-label" style="display:none;"></span><span class="theme-edit">删除</span></div>';
			parent.find('.way-list').append(html);
			this._judgeData( _keys, true, slider, noon );
			this._tabShow( _keys );
			this.JudgeShow( tslider.find('a'), true, sslider.find('a') );		//滑动点出现
		},
		
		_saveLastInfo : function(){
			var content = $('.way-content');
			var siteData = this.siteData;
			if( siteData.length ){
				var _lastKeys = siteData[siteData.length-1]._keys;
				if( _lastKeys ){
					$('.way-content').find('input[type="submit"]').eq(0).click();
					if($('.operateform').find('input[name="new_gate_title[]"]').eq(0).val()){
						$('.operateform').find('input[type="submit"]').click();
					}
					if($('.serviceform').find('input[name="serivce_id[]"]').eq(0).val() > -1){
						$('.serviceform').find('input[type="submit"]').click();
					}
				}
			}
		},
		
		_tabShow : function( _keys ){
			this._trigger('tabShow', null, [_keys]);
		},
		
		callbackData : function( options ){
			var obj = $('.way-map').find('.program-li[_keys=' + options.keys + ']');
			obj.attr('site_id', options.site_id);
			var _this = this;
			$.each(this.siteData, function(key, value){
				if(value._keys == options.keys){
					value.id = options.site_id;
					value.title = obj.find('.theme-label').html();
					value.egname = options.engname;
					value.order_id = options.order_id ? options.order_id : value.order_id;
				}
			});
			$('.site_num').find('em').html( _this.siteData.length || 0 );
			this.getBeginningSite();
			this._setLeft( true );
			setTimeout(function(){
				$('.m2o-save').click();
			}, 300);
			var dom = $('.basicform').find('input[name="a"]');
			dom.val('update_site');
		},
		
		randNum:function(len)
		{
			var salt = '';
			for (var i = 0; i < len; i++)
			{
				var tmp = parseInt(Math.ceil(Math.random()*10));
				if(!tmp)
				{
					tmp = '2';
				}
				salt += tmp;
			}
			return salt;
		}
		
	});
})($);
