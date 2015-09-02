(function($){
	$.widget('subway.subwayInfo', {
		options : {
			getImageUrl : '',
			delImageUrl : '',
			getTypeUrl : '',
			addDefinedUrl : '',
			editSiteUrl : '',
			addImagetpl : '',
			getBusUrl : '',
			addImagetname : 'add-pic-tpl',
			addRoadtpl : '',
			addRoadtname : 'add-road-tpl',
			addExtendtpl : '',
			addExtendtname : 'add-extend-tpl',
			addGatetpl : '',
			addGatetname : 'add-gate-tpl',
			pullDowntpl : '',
			pullDowntname : 'add-pulldown-tpl',
			addServicetpl : '',
			addServicetname : 'add-service-tpl',
			addSiteboxtpl : '',
			addSiteboxtname : 'add-sitebox-tpl',
			addGateboxtpl : '',
			addGateboxtname : 'add-gatebox-tpl',
			addServiceboxtpl : '',
			addServiceboxtname : 'add-servicebox-tpl',
			addBustpl : '',
			addBustname : 'add-bus-tpl',
			ohms : null,
		},
		
		_create : function(){
			var op = this.options,
				widget = this.element;
			this.tab = widget.find('.way-tab');
			this.uploadLogo = widget.find('.images-file');
			this.pullDown = ['way', 'extend', 'service'];
			this.imgLoading = $('<img src="' + RESOURCE_URL + 'loading2.gif" class="loading2" style="width:50px; height:50px;"/>');
			$.template(op.addImagetname, op.addImagetpl);
			$.template(op.addRoadtname, op.addRoadtpl);
			$.template(op.addExtendtname, op.addExtendtpl);
			$.template(op.addGatetname, op.addGatetpl);
			$.template(op.pullDowntname, op.pullDowntpl);
			$.template(op.addServicetname, op.addServicetpl);
			$.template(op.addSiteboxtname , op.addSiteboxtpl);
			$.template(op.addGateboxtname , op.addGateboxtpl);
			$.template(op.addServiceboxtname , op.addServiceboxtpl);
			$.template(op.addBustname , op.addBustpl);
		},
		
		_init : function(){
			this._on({
				'click .way-tab li' : '_tabSelect',
				'blur .basic-info .site-name input' : '_getTitle',
				'click .cancel-button' : '_emptyInfo',
				'click .map-icon' : '_getMap',
				'click .add-img' : '_triggerUpload',
				'click .del-image' : '_delImage',
				'click .img-item' : '_selectItem',
				'click .del-way' : '_delBlock',
				'click .add-way' : '_addBlock',
				'click .way-list li a' : '_selectDown',
				'click .sure-defined' : '_saveDefined',
				'click .cancel-defined' : '_cancelDefined',
				'click .edit' : '_slideToggle',
				'click .del' : '_delLet',
				'click .del-pull' : '_delPull',
				'keypress .site-axis input' : 'judgeDigital',
				'click .search-btn' : '_getBus',
 			});
 			this.road = $.globalRoad;
			this._initUpload();
			this._getTmpltype();
			this.timeGet();
		},
		
		_initUpload : function(){
			var op = this.options,
				_this = this;
			var url = this.options.getImageUrl;
			this.uploadLogo.ajaxUpload({
				url : url,
				phpkey : 'Filedata',
				before : function(){
					_this.imgLoading.appendTo( _this.box );
				},
				after : function( json ){
					var data = json['data'];
					data && _this._UploadAfterData( data );
				}
			}); 
		},
		
		_UploadAfterData : function( data ){
			this.removeDom( this.box.find('.loading2') );
			var src = $.globalImgUrl(data, '141x101'), index;
			if(this.box.is('.extend-img')){
				var img = this.box.find('img');
				!img[0] && (img = $('<img />').appendTo( this.box ));
				this.box.find('img').attr('src', src);
				this.box.find('input[type="hidden"]').val( data.id );
			}else{
				var type = this.box.parent().is('.flat-list') ? false : true;
				index = parseInt(this.box.closest('.let-list').index()) + 1;
				var info =  {src : src, id : data.id, type : type, index : index}
				$.tmpl(this.options.addImagetname, info).insertBefore( this.box );
				this._sortPic();
			}
		},
		
		_sortPic : function(){
			 var sortList = this.element.find('.img-list'),
             	 url = 'run.php?mid='+ gMid +'&a=img_order',
             	 _this = this;
			 this.old_order_ids = this._getOrderId();
			 sortList.sortable({
	             cursor: 'move',
	             items: '.img-item' ,
	             placeholder: "sort-placeholder",
	             start: function (event, ui) {
	                 _this.startOrder = ui.item.attr('orderid');
	             },
	             stop: function (event, ui) {
	                 var self = ui.item,
	                     data = {};
	                 data.order_id = _this.old_order_ids;
	                 data.content_id = _this._getIds();
	                 _this._setOrderId(_this.old_order_ids);
	                 var stopOrder = ui.item.attr('orderid');
	                 if (_this.startOrder != stopOrder) {                            /*orderid没有改变则顺序没有发生改变，不发起请求*/
	                     $.globalAjax( sortList, function(){
	     					return $.getJSON(url, data, function( json ){
	     						 if (json.ErrorText || json.ErrorCode) {
		                             _this.myTip( self , json.ErrorText );
		                         } else {
		                             _this.myTip( self , '排序保存成功！' );
		                         }
	     					});
	     				});
	                 }
	             }
	         });
		},
		
		_getIds: function () {
            return this.element.find('.img-item').map(function () {
                return $(this).data('id');
            }).get().join(',');
        },

        _getOrderId: function () {
            return this.element.find('.img-item').map(function () {
                return $(this).attr('orderid');
            }).get().join(',');
        },

        _setOrderId: function (old_order_ids) {
        	old_order_ids  = old_order_ids.split(',');
            this.element.find('.img-item').each(function (i) {
                $(this).attr('orderid', old_order_ids[i])
            });
        },
		
		timeGet : function(){
			var _this = this;
			$('.main-wrap').on({
				'mousedown' : function(){
					var disOffset = {left : 0, top : 0};
					var $this = $(this);
		             _this.options.ohms.ohms('option', {
		                time : $this.is('input') ? $this.val() : $this.html(),
		                target : $this
		            }).ohms('show', disOffset);
		            return false;
				},
				 'set' : function(event, hms){
		         	var $this = $(this);
		         	var time = [hms.h, hms.m].join(':');
		         	if( $this.is('input') ){
		         		var box = $this.parent('span'),
		         			bool = $this.is('.start'),
		         			other = bool ? box.find('input.end') : box.find('input.start'),
		         			otherval = other.val();
	         			if( otherval ){
	         				if( bool && time >= otherval){
			         			_this.myTip( $this, '开始时间不能大于或等于结束时间' );
			         			return false;
			         		}
			         		if( !bool && time <= otherval ){
			         			_this.myTip( $this, '结束时间不能小于或等于开始时间' );
			         			return false;
			         		}
	         			}
		         		$this.val(time);
		         	}else{
		         		$this.html(time);
		         		$('#roadForm').find('input[name="' + $this.attr('title') + '_time"]').val( time );
		         		$this.removeClass('time-icon');
		         	}
	         	}
			}, '.way-time');
		},
		
		_getTmpltype : function(){	
			var _this = this;
			$.each([1, 2], function(k, v){
				_this._getTmpl( v );
			});
		},
		
		_getTmpl : function( v ){
			var _this = this;
			$.getJSON(this.options.getTypeUrl, { type : v }, function(data){
					if( data && data[0] ){
						var tmpl = data[0];
						(v == 1) && (_this.gate = data[0]);
						(v == 2) && (_this.service = data[0]);
						var box = _this.pullDown[v];
						tmpl.push({'title' : '自定义类型', 'id' :  0, 'sign' : 'userdefined'});
						$.each(tmpl, function(key, value){
							value.show = box + '_show';
							value.typeid = $('#' + box).find('input').attr('name');
						});
						$.tmpl(_this.options.pullDowntname, tmpl).appendTo( $('#' + box).find('ul').empty());
					}
				});
		},
		
		_tabSelect : function( event ){
			var self = $(event.currentTarget);
			var eq = self.index();
			this.chooseSelect( eq );
		},
		
		chooseSelect : function( eq ){
			var self = this.tab.find('li').eq( eq );
			if( eq > 0 && !this.element.attr('site_id')){
				this.myTip( self, '请先保存站点基本信息' );
				return false;
			}
			this.removeSiblings( self );
			var obj = this.element.find('.way-item').eq( eq );
			obj.show().siblings('.way-item').hide();
		},
		
		_getTitle : function( event ){
			var self = $(event.currentTarget);
			var _keys = this.element.attr('_keys');
			var val = self.val();
			this._trigger('pointShow', event, [_keys, val]);
		},
		
		_emptyInfo : function( event ){
			var self = $(event.currentTarget),
				box = self.closest('form'),
				_this = this,
				name = box.attr('name');
			var method = function(){
				_this._resortData(box, name);
			};
			if( box.closest('.way-content').attr('site_id') ){
				this.remind( '您确定恢复该页面的所有数据?', '提醒' , method, self );
			}else{
				this._cancelData(box);
			}
		},
		
		_resortData : function( box, name ){
			var data =this.data;
			switch( name ){
				case 'basicform' : {
					this.element.find('.basic-info').remove();
					data.site_info && this._getSiteAfter( data.site_info, data.site_time, true );
					var title = this.element.find('.basic-info').find('input[name="site_name"]').val(),
						_keys = this.element.attr('_keys'),
						dom = $('.way-map').find('.program-li[_keys=' + _keys + ']');
					dom.find('.theme-label').html( title ).attr('title', title);
					break; 
				}
				case 'operateform' : {
					this.element.find('.gate-info').remove();
					data.gate_info && this._getGateAfter( data.gate_info, true );
					break;
				}
				case 'serviceform' : {
					this.element.find('.service-info').remove();
					data.service_info && this._getServiceAfter( data.service_info, true );
					break;
				}
			}
		},
		
		_cancelData : function( box ){
			var _keys = this.element.attr('_keys'),
				dom = $('.way-map').find('.program-li[_keys=' + _keys + ']');
			dom.find('.theme-edit').click();
		},
		
		_getMap : function( event ){
			var box = $(event.currentTarget).closest('.m2o-item');
			var map = $('.map-box');
			if( box.closest('form').attr('name') == 'basicform' ){
				map.data('type', 'basicform');
			}else{
				var index = box.closest('.let-list').index();
				map.data('type', 'operateform_' + index );
			}
			var longitude = box.find('.site-titude').first().find('input').val();
			var latitude = box.find('.site-titude').last().find('input').val();
			map.removeClass('map-pop');
			map.find('input[name="site_longitude"]').val( longitude );
			map.find('input[name="site_latitude"]').val( latitude );
			getLng(longitude, latitude);
		},
		
		_triggerUpload : function( event ){
			var self = $(event.currentTarget);
			this.box = self;
			this.uploadLogo.click();
		},
		
		_delImage : function( event ){
			var obj = $(event.currentTarget).closest('li');
			this._judgeDelImg( obj, true );
		},
		
		_judgeDelImg : function( obj, type ){
			var imgid = obj.find('input[type="hidden"]').val(),
				_this = this;
			var method = function(){
				$.globalAjax( obj, function(){
					return $.getJSON(_this.options.delImageUrl, {id : imgid}, function( data ){
						if(data && data[0]){
							obj.remove();
						}
					});
				});
			};
			if( type ){
				this.remind( '您确定删除该图片?', '删除提醒' , method, obj );
			}else{
				method();
			}
		},
		
		remind : function( title, message, method, dom ){
			jConfirm( title, message , function(result){
				result && method();
			}).position( dom );
		},
		
		_selectItem : function( event ){
			var obj = $(event.currentTarget).closest('li');
			this.removeSiblings( obj );
		},
		
		_delBlock : function( event ){
			var self = $(event.currentTarget),tip,
				_this = this,
				box = self.closest('.m2o-item');
			var type = self.data('type');
			if( box.find('div[class$="-obj"]').find('input[type="hidden"]').val() > -1 ){
				if( type == 'way' ){
					tip = '所属线路';
				}else if( type == 'gate' ){
					tip = '扩展信息';
				}else{
					tip = '状态';
				}
				var method = function(){
					_this.removeDom( box );
				};
				this.remind( '您确定删除该' + tip +  '?', '删除提醒' , method, self );
			}else{
				this.removeDom( box );
			}
		},
		
		_addBlock : function( event ){
			var self = $(event.currentTarget),
				type = self.data('type');
			this._addPullDown( type, self );
		},
		
		_addPullDown : function( type, self ){
			switch( type ){
				case 'basic': {
					if($('#way').find('li').length < 2){
						this.myTip( self, '没有其余线路' );
						return false;
					}
					$.tmpl(this.options.addRoadtname).insertBefore( self );
					this._completeData( 'way' );
					this._trigger('getBeginningSite', null);
					break;
				}
				case 'extend' : {
					var info = {
						'sign' : ''
					};
					$.tmpl(this.options.addExtendtname, info).insertBefore( self );
					this._completeData( type, self );
					var box = self.closest('.let-list');
					var letindex = box.index();
					var formbox = ['input', 'textarea'];
					for(var i=0; i<formbox.length; i++){
						box.find('.extend').find( formbox[i] ).each(function(){
							if( $(this).attr('title') ){
								var name = $(this).attr('title');
								$(this).attr('name', letindex + name);
							}else{
								var name = $(this).attr('name'), tname;
								if( name ){
									if(name.indexOf('_') > 0){
										var arrayname = name.split('_');
										tname = arrayname[1];
									}else{
										tname = name;
									}
									$(this).attr('name', letindex + '_' + tname);
								}
							}
							
						});
					}
					break;
				}
				case 'gate' : {
					$.tmpl(this.options.addGatetname).insertBefore( self );
					break;
				}
				case 'service' : {
					$.tmpl(this.options.addServicetname).insertBefore( self );
					this._completeData( type );
					self.prev('.m2o-item').find('.color-picker').hg_colorpicker();
					break;
				}
			}
		},
		
		_completeData : function( type, dom ){
			var operateObj = dom ? dom.closest('.let-list') : this.element,
			operateLast = operateObj.find('.' + type + '-obj').last();
			var eq = operateObj.length;
			this._appendPull(type, operateLast, eq);
		},
		
		_appendPull : function( type, operateLast, eq ){
			 $( $('#' + type + '').html() ).clone( true ).appendTo( operateLast );
			 operateLast.find('ul').attr('id', type +'_show' + eq);
			 var idname = operateLast.find('input[type="hidden"]').attr('id');
			 operateLast.find('input[type="hidden"]').attr('name', idname + '[]');
			 var num = Math.floor(Math.random()*100);
			 operateLast.find('input[type="hidden"]').attr('id', num + '_' + idname)
			 operateLast.find('label.overflow').attr('id', 'display_' + type + '_show' + eq);
			 if( type == 'extend' || type == 'service' ){
				var len = $('#' + type + '').find('li').length;
				if( len < 3 ){
					operateLast.find('.way-list').hide();
					operateLast.find('.userdefined').show();
				}
			}
		},
		
		_getBus : function( event ){
			var self = $(event.currentTarget),
				_this = this,
				area = self.closest('.let-list');
				var index = area.index();
			var longitude = area.find('.site-titude').first().find('input[type="text"]').val();
			var latitude = area.find('.site-titude').last().find('input[type="text"]').val();
			if(longitude && longitude){
				$.getJSON(this.options.getBusUrl, {longitude : longitude, latitude : latitude}, function( data ){
					if( data && data[0] && data[0].length){
						var box = $('.bus-box');
						$.tmpl(_this.options.addBustname, data[0]).appendTo( box.find('ul').empty() );
						box.data('type', index).removeClass('pop-hide');
						
					}else{
						_this.myTip( self, '暂无可选择的站点' );
					}
				});
			}else{
				this.myTip( self, '请先填写经纬度' );
			}
		},
		
		_selectDown : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			var type = self.closest('li').attr('_sign');
			var box = self.closest('div[class$="-obj"]');
			if(self.closest('.m2o-item').find('.extend').length){
				var isbool = (type == 'bus') ? true : false;
				var dom = self.closest('.extend');
				this._trigger( 'JudgeShow', event, [[dom.find('.extend-name'),dom.find('.station_id')], isbool] );
			}
			
			if( box.is('.way-obj') ){
				var idx = box.closest('.m2o-way').index('.m2o-way');
				var attrid = self.attr('attrid');
				var bool = 0;
				this.roadid = this.roadid ? this.roadid : [];
				if( $.isArray(this.roadid) && this.roadid.length ){
					$.each(this.roadid, function(i, j){
						if( idx != j.key && attrid == j.roadid ){
							_this.myTip( self, '不能选择相同的所属线路' );
							bool = 1;
							return false;
						}
						if( idx == j.key ){
							j.roadid = attrid;
							bool = 2;
						}
					});
				}
				
				if( !bool ){
					this.roadid.push({key : idx, roadid : attrid});
				}else if( bool == 1 ){
					return false;
				}
			}
			
			if( type == 'userdefined' ){
				var extend = box.closest('.extend');
				extend.find('input').val('');
				extend.find('textarea').val('');
				extend.find('img').attr('src', '');
				extend.find('.select-input').css('background-color', '');
				this._OperateDefined( box, false );
			}else{
				box.find('input[type="hidden"]').val(self.attr('attrid'));
				self.closest('.m2o-item').find('.iterval-time').find('input').each(function(){
					var name = $(this).attr('name').split('_');
					$(this).attr('name', self.attr('attrid') + '_' + name[1]);
				});
				(self.attr('attrid') > -1) && this._relativeInfo( self );
				box.find('label[class="overflow"]').html( self.html() );
				box.find('ul').hide();
			}
		},
		
		_relativeInfo : function( dom ){
			var li = dom.closest('li'),
				box = dom.closest('div[class$="-obj"]'),
				_this = this;
				parent = box.closest('.m2o-item');
			var type = box.attr('class').split('-')[0],
				id = dom.attr('attrid');
			switch( type ){
				case 'way' : {
					var station = this.road[id];
					parent.find('.iterval-time').find('.start').html( station[0] );
					parent.find('.iterval-time').find('.end').html( station[1] );
					break;
				}
				case 'extend' : {
					var sign = li.attr('_sign');
					$.each(this.gate, function(key, value){
						if(value.id == id){
							_this._handleRelativeImg(parent, value);
							parent.find('.site-descrip').find('textarea').val( value.brief );
						}
					});
					break;
				}
				case 'service' : {
					var sign = li.attr('_sign');
					$.each(this.service, function(key, value){
						if(value.id == id){
							_this._handleRelativeImg(parent, value);
							parent.find('.site-descrip').find('textarea').val( value.brief );
							parent.find('.colorpicker-wrap').find('input').val(  value.color  )
								.css('background-color', value.color);
						}
					});
					break;
				}
			}
		},
		
		_handleRelativeImg : function(parent, value, type){
			var img = parent.find('.extend-img').find('img');
			if(value.indexpic && value.indexpic[0] && value.indexpic.length){
				var src = $.globalImgUrl(value.indexpic[0], '73x65');
				!img[0] && (img = $('<img />').appendTo( parent.find('.extend-img') ));
				img.attr('src', src);
				parent.find('.extend-img').find('input').val( value.indexpic[0].id );
			}else{
				if( img ){
					img.attr('src', '');
				}
			}
		},
		
		_saveDefined : function( event ){
			var self = $(event.currentTarget),
				box = self.closest('div[class$="-obj"]');
			var type_title = box.find('input[name*="type_title"]').val(),
				sign = box.find('input[name*="sign"]').val();
			if( !type_title ){
				this.myTip( self, '请先输入新的类型名称' );
				return false;
			}
			if( !sign ){
				this.myTip( self, '请先输入新的标识' );
				return false;
			}
			var info = {
				type_title : type_title,
				sign : sign,
				type : box.is('.extend-obj') ? 1 : 2
			}
			var current = box.find('ul').children().first();
			var _this = this;
			$.globalAjax( box, function(){
				return $.getJSON(_this.options.addDefinedUrl, info, function(data){
					if( data && data[0] ){
						var data = data[0];
						if( data.sign == 'bus' ){
							var extend = box.closest('.extend');
							_this._trigger( 'JudgeShow', event, [[extend.find('.extend-name'),extend.find('.station_id')], true] );
						}
						var area = _this.pullDown[data.type];
						var pullDown = {
							show : area + '_show',
							typeid : $('#' + area).find('input').attr('name'),
							sign : data.sign,
							id : data.id,
							title : data.title
						}
						var first = $('#' + area).find('ul').find('li').first();
						$.tmpl(_this.options.pullDowntname, pullDown).insertBefore( first );
						$.tmpl(_this.options.pullDowntname, pullDown).insertBefore( current );
						box.find('label.overflow').html( data.title );
						box.find('input[type="hidden"]').val( data.id );
						_this._OperateDefined( box, true );
					}
				});
			});
		},
		
		_cancelDefined : function( event ){
			var box = $(event.currentTarget).closest('div[class$="-obj"]');
			this._OperateDefined( box, true );
		},
		
		_OperateDefined : function( box, type ){
			box.find('.userdefined')[type ? 'hide' : 'show']();
			box.find('.way-list')[type ? 'show' : 'hide']();
			type && box.find('.userdefined').find('input[type="text"]').val('');
		},
		
		_slideToggle : function( event ){
			var self = $(event.currentTarget),
				box = self.closest('.let-list');
			var html = self.html();
			var dom = box.children().not('.m2o-item:first-child');
			if(html == '详情'){
				dom.show();
				self.html('缩小');
			}else{
				dom.hide();
				self.html('详情');
			}
		},
		
		_delLet : function( event ){
			var _this = this;
				box = $(event.currentTarget).closest('.let-list');
			if( box.find('input[name="new_gate_title[]"]').val() ){
				var method = function(){
					_this.removeDom( box );
				};
				this.remind( '您确定删除该出入口信息?', '删除提醒' , method, parent );
			}else{
				this.removeDom( box );
			}
			
		},
		
		removeDom : function( dom ){
			dom.remove();
		},
		
		_delPull : function( event ){
			var	self = $(event.currentTarget), 
				attrid = self.closest('li').find('a').attr('attrid')
				_this = this;
			var parent = self.closest('div[class$="-obj"]');
			var form = parent.closest('form').attr('name');
			var info = {
				'basicform' : ['线路', 'way'],
				'operateform' : ['扩展信息', 'extend'],
				'serviceform' : ['服务设施', 'service']
			}
			var method = function(){
				$.globalAjax( parent, function(){
					return $.getJSON(_this.options.addDefinedUrl, { type : 'delete', id : attrid }, function( data ){
						if( data && data[0] ){
							self.closest('li').remove();
							var dom = $('#' + info[form][1]).find('a[attrid=' + data[0]['id'] + ']');
							dom.closest('li').remove();
						}
					});
				});
			};
			this.remind( '您确定删除该' + info[form][0] + '?', '删除提醒' , method, parent );
			event.stopPropagation();
		},
		
		judgeDigital : function( e ){
			var self = $(e.currentTarget);
			if( !(window.event.keyCode>=48&&window.event.keyCode<=57) ){
				this.myTip(self, '该项只能输入数字');
				event.returnValue = false;
			}
		},
		
		
		
		getName : function( start, end ){
			if(this.element.find('.m2o-way').length){
				var way = this.element.find('.m2o-way').eq(0),
					iterval = way.find('.way-interval'),
					road = way.find('.way-road');
				iterval.find('em.start').html( start || '' );
				iterval.find('em.end').html( end || '');
				var id = $('#roadForm').data('id');
				if( this.road && this.road[id]){
					this.road[id] = [start, end];
				}
				setTimeout(function(){
					var name = $('#roadForm').find('input[name="title"]').val();
					if( !road.find('.pname').length ){
						var obj = road.find('.way-obj').hide();
						$('<p class="pname"></p>').insertAfter( obj );
					}
					road.find('.pname').html( name );
				}, 50);
			}
		},
		
		myTip : function( dom, str, left ){
			dom.myTip({
				string : str,
				delay: 2000,
				dtop : 15,
				dleft : left || -130,
				width : 'auto',
				padding: 10
			});
		},
		
		removeItem : function( _keys, type ){
			this.element.find( '.way-item' ).detach();
			this.element.attr('_keys', _keys)['slide' + (type ? 'Down' : 'Up')]();
		},
		
		removeSiblings : function( dom ){
			dom.addClass('selected').siblings().removeClass('selected');
		},
		
		getSiteinfo : function( site_id, _keys ){
			var sub_id = $('#roadForm').data('id'),
				dom = $('.way-map').find('.program-li[_keys=' + _keys + ']');
			var _this = this;
			$.globalAjax( dom, function(){
				return $.getJSON(_this.options.editSiteUrl, {site_id : site_id, sub_id : sub_id}, function( data ){
					if(data && data[0]){
						var data = data[0];
						_this.data = data;
						_this.roadid = [];
						_this.removeItem( _keys, true );
						_this.element.attr('site_id', site_id);
						_this.chooseSelect(0);
						data.site_info && _this._getSiteAfter( data.site_info, data.site_time );
						data.gate_info && _this._getGateAfter( data.gate_info );
						data.service_info && _this._getServiceAfter( data.service_info );
						_this.element.find('input[name="sub_id"]').val( sub_id );
						_this.element.find('input[name="site_id"]').val( site_id );
					}
					
					_this._initForm();
					_this._sortPic();
				});
			});
		},
		
		_getSiteAfter : function( data, site_time, type ){
			var _this = this;
			var isbool = this._judgetmplData(data, 'way', type);
			if( isbool ){
				return;
			}
			var dataArray = [];
			$.each(site_time, function(key, value){
				var info = {};
				if(value && value.start ){
					info.sstart = value.start.start || '';
					info.send = value.start.end || '';
				}
				if(value && value.end){
					info.estart = value.end.start || '';
					info.eend = value.end.end || '';
				}
				info.sitek = value.sub_id;
				info.sub_name = value.sub_name; 
				info.start_name = value.start_name;
				info.end_name = value.end_name;
				info.index = key;
				dataArray.push(info);
				});
			data.roadtpl = dataArray;
			var imgArray = [];
			if( data.indexpic && $.isArray(data.indexpic) && data.indexpic.length ){
				$.each(data.indexpic, function(key, value){
					imgArray.push({
						src : value ? $.globalImgUrl(value, '141x104') : '',
						type : true,
						id : value.id,
						order_id : value.order_id
					})
				});
			};
			data.imgtpl = imgArray;
			$.tmpl(this.options.addSiteboxtname, data).insertAfter( this.element.find('.way-tab') );
			_this._trigger('getBeginningSite', null);
			var dom = this.element.find('input[name="a"]');
			dom.val('update_site');
			if(this.element.find('.way-obj').length){
				this.element.find('.way-obj').each(function(i){
					_this._appendPull('way', $(this), i);
					$(this).find('input[type="hidden"]').val( dataArray[i].sitek );
					$(this).find('label[class="overflow"]').html( dataArray[i].sub_name );
					var parent = $(this).closest('.m2o-item');
					parent.find('.iterval-time').find('.start').html( dataArray[i].start_name );
					parent.find('.iterval-time').find('.end').html( dataArray[i].end_name );
					_this.roadid.push({
						key : i,
						roadid : dataArray[i].sitek
					});
				});
			}
		},
		
		_judgetmplData : function(data, type, style){
			var isbool = false,
				op = this.options;
			var param = {};
		 	if( $.isArray(data) && !data.length ){
				param.roadtpl = null;
				var info = {
					'way' : [op.addSiteboxtname, '.way-tab', '.basic-info'],
					'gate' : [op.addGateboxtname, '.basic-info', '.gate-info'],
					'service' : [op.addServiceboxtname, '.gate-info', '.service-info']
				}
				$.tmpl(info[type][0], param).insertAfter( this.element.find( info[type][1] ) );
				if( style ){
					this.element.find( info[type][2] ).show();
				}
				if( type == 'service' ){
					this.element.find('.color-picker').hg_colorpicker();
				}
				this._completeData( type );
				isbool = true;
			}
			return isbool;
		},
		
		_getGateAfter : function( data, type ){
			var _this = this;
			var info = {};
			var isbool = this._judgetmplData(data, 'gate', type);
			if( isbool ){
				return;
			}
			var expandArray = []
			$.each(data, function(key, value){
				if( value.expand && $.isArray(value.expand) ){
					$.each(value.expand, function(k, v){
						v.index = key;
						if( v.indexpic && $.isArray(v.indexpic) && v.indexpic.length ){
							v.src = $.globalImgUrl(v.indexpic[0], '73x65');
							v.id = v.indexpic[0].id;
						}
						expandArray.push(v);
					});
				}
				if( value.indexpic && $.isArray(value.indexpic) && value.indexpic.length ){
					$.each(value.indexpic, function(k, v){
						v.src = v ? $.globalImgUrl(v, '141x101') : '';
						v.index = key + 1;
					});
				}
			});
			info.roadtpl = data;
			$.tmpl(this.options.addGateboxtname, info).insertAfter( this.element.find('.basic-info') );
			if( type ){
				this.element.find('.gate-info').show();
			}
			if( this.element.find('.extend-obj').length){
				this.element.find('.extend-obj').each(function(i){
					var box = $(this).closest('.let-list');
					var index = box.index();
					_this._appendPull('extend', $(this), i);
					if( expandArray[i].type_name ){
						var name = $(this).find('input[type="hidden"]').attr('name');
						$(this).find('input[type="hidden"]').val( expandArray[i].type_id ).attr('name', index + '_' + name);
						$(this).find('label[class="overflow"]').html( expandArray[i].type_name );
					}
				});
			}
		},
		
		_getServiceAfter : function( data, type ){
			var _this = this;
			var info = {};
			var isbool = this._judgetmplData(data, 'service', type);
			if( isbool ){
				return;
			}
			$.each(data, function(key, value){
				if( value.indexpic && $.isArray(value.indexpic) && value.indexpic.length){
					value.src = $.globalImgUrl(value.indexpic[0], '73x65');
					value.id = value.indexpic[0].id;
				}
			});
			info.roadtpl = data;
			$.tmpl(this.options.addServiceboxtname, info).insertAfter( this.element.find('.gate-info') );
			if( type ){
				this.element.find('.service-info').show();
			}
			if( this.element.find('.service-obj').length ){
				this.element.find('.service-obj').each(function(i){
					_this._appendPull('service', $(this), i);
					$(this).find('input[type="hidden"]').val( data[i].type_id );
					$(this).find('label[class="overflow"]').html( data[i].type_name )
				});
			}
			this.element.find('.color-picker').hg_colorpicker();
		},
		
		
		slideTog : function( _keys ){
			var _this = this;
			this.keys = this.element.attr('_keys');
			this.element.attr('_keys', _keys).attr('site_id', '');
			this.element.find('input[name="site_id"]').val('');
			this.element.find( '.way-item' ).detach();
			var sitedata = {
				'roadtpl' : ''
			};
			var info = {
				'roadtpl' : {'sitek' : -1},
				'has_toilet' : 0,
			}
			$.tmpl(this.options.addSiteboxtname, info).appendTo( this.element );
			$.tmpl(this.options.addGateboxtname, sitedata).appendTo( this.element );
			$.tmpl(this.options.addServiceboxtname, sitedata).appendTo( this.element );
			_this._trigger('getBeginningSite', null);
			this._initForm();
			var info = ['way', 'service'];
			for(var i=0, len=info.length; i<len; i++ ){
				_this._completeData( info[i] );
			}
			var first = this.element.find('.way-road').first();
			var id = $('#roadForm').data('id');
			this.element.find('input[name="sub_id"]').val( id );
			first.find('label.overflow').html( $('.m2o-m-title').val() );
			this.getSitePull( first, id );
			this.element.find('.color-picker').hg_colorpicker();
			this.element.slideDown();
		},
		
		getSitePull : function( dom, id ){
			dom.find('input[name="line[]"]').val( id );
			dom.closest('.m2o-item').find('.iterval-time').find('input').each(function(){
				var name = $(this).attr('name').split('_');
				$(this).attr('name', id + '_' + name[1]);
			});
		},
		
		/*验证数字*/
		_verifyDigital : function( dom ){
			var bool = true;
			dom.each(function(){
				var val = $(this).find('input[type="text"]').val(),
					reg = /^\d+(\.\d+)?$/;
				if( val && !val.match(reg) ){
					bool = false;
				}
			});
			return bool;
		},
		
		vertifySpace : function( val ){
			var reg1 = /[(\)(\~)(\!)(\@)(\#)(\$)(\%)(\^)(\&)(\*)(\+)(\=)(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\<)(\>)(\?)(\，)(\。)(\？)]+/,
				reg2 = /^\s+$/;
			if( val.match( reg1 ) ){
				return '不能包含特殊字符';
			}
			if( val.match( reg2 ) ){
				return '不能全为空格';
			}
			return false;
		},
		
		_ajaxBefore : function( dom ){
			var name = dom.attr('name'),
				btn = dom.find('input[type="submit"]');
			var type = true, str;
			switch( name ){
				case 'basicform' : {
					var val = dom.find('input[name="site_name"]').val();
					if(!val){
						type = false;
						str = '请先填写站点名称';
					}else{
						var bool = this.vertifySpace( val );
						if( bool ){
							str = '站点名称' + bool;
							type = false;
						}
					}
					var axisbool = this._verifyDigital( dom.find('.site-axis') );
					var titudebool = this._verifyDigital( dom.find('.site-titude') );
					if( !axisbool ){
						str = 'xy轴只能填写数字';
						type = false;
					}
					if( !titudebool ){
						str = '经纬度只能填写数字';
						type = false;
					}
					if( !type ){
						this.myTip( btn, str, 130 );
					}
					
					break;
				}
				case 'operateform' : {
					var val = dom.find('input[name="new_gate_title[]"]').eq(0).val();
					if(!val){
						str = '请先填写出入口名称';
						type = false;
					}
					var titudebool = this._verifyDigital( dom.find('.site-titude') );
					if( !titudebool ){
						str = '经纬度只能填写数字';
						type = false;
					}
					if( !type ){
						this.myTip( btn, str, 130 );
					}
					break;
				}
				case 'serviceform' : {
					var val = dom.find('input[id="serivce_id"]').first().val();
					if(val == -1){
						this.myTip( btn, '请先选择服务设施状态', 130 );
						type = false;
					}
					break
				}
			}
			return type;
		},
		
		_initForm : function(){
			var form = this.element.find('form');
			var _this = this;
			form.each(function(i){
				var $this = $(this);
				$this.submit(function(){
					$this.ajaxSubmit({
						beforeSubmit:function(){
							var type = _this._ajaxBefore( $this );
							if( !type ){
								return false;
							}
						},
						dataType : 'json',
						success:function( data ){
							if(data['callback']){
								eval( data['callback'] );
								return;
							}else{
								if( data && $.isArray(data) ){
									_this._ajaxAfter( $this, data[0] );
								}
							}
						},
						error:function(){
							var btn = $this.find('input[type="submit"]');
							_this.myTip( btn, '保存线路站点失败', 130 );
						}
					});
					return false;
				});
			});
		},
		
		_ajaxAfter : function( dom, data ){
			var name = dom.attr('name'),
				_this = this;
			var btn = dom.find('input[type="submit"]');
			switch( name ){
				case 'basicform' : {
					var site_id = data.site_id,
					 	order_id = data.order_id ? data.order_id : '';
					_this.element.find('input[name="site_id"]').val( site_id );
					_this.element.attr('site_id', site_id);
					var keys = _this.element.attr('_keys'),
						engname = _this.element.find('input[name="site_egname"]').val();
					var options = {
						site_id : site_id,
						keys: keys,
						order_id : order_id,
						engname : engname
					}
					_this._trigger('successCallback', null, [options]);
					_this.myTip( btn, '保存基本信息成功', 130 );
					break;
				}
				case 'operateform' : {
					this._getTmpl(1);
					data && _this.myTip( btn, '保存出入口信息成功', 130 );
					break;
				}
				case 'serviceform' : {
					this._getTmpl(2);
					data && _this.myTip( btn, '保存服务设施成功', 130 );
					break;
				}
			}
		},
		
	});
})($)
