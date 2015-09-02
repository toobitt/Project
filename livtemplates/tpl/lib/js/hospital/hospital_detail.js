(function(){
	$.widget('hospital.depart', {
		options : {
			departtpl : '',
			departtname : 'depart-tpl',
			departlitpl : '',
			departlitname : 'departli-tpl',
			url : '',
			stretch : 'stretch-name',
			count : 20
		},
		
		_create : function(){
			var op = this.options,
				widget = this.element;
			$.template(op.departtname, op.departtpl);
			$.template(op.departlitname, op.departlitpl);
			this.imgLoading = $('<img src="' + RESOURCE_URL + 'loading2.gif" class="loading2" style="width:50px; height:50px;"/>');
			this.box = $('.depart-box');
			this.sec_indexdepart = {};
			this.first_indexdepart = {};
			this.seconddepart = {};
		},
		
		_init : function(){
			this._on({
				'click li.first-depart' : '_stretch',
				'click li.second-depart' : '_doctor',
				'click .add-depart' : '_adddepart',
				'click .edit' : '_editdepart',
				'click .view-all' : '_viewAll'
			});
			this._root();	
		},
		
		_root : function(){
			var hospital_id = this.element.attr('hospital_id');
			this._ajax( this.element, 'first', {
				hospital_id : hospital_id
			} );
		},
		
		_ajax : function( parent, level, param ){
			var _this = this;
			$.globalAjax( parent, function(){
				return $.getJSON( _this.options.url, param, function( json ){
					if( json && json['callback'] ){
						eval( json['callback'] );
						return;
					}
					if( $.isArray(json ) && json[0] ){
						var count = json[0]['count'] || 0,
							data = json[0]['data'] || '';
						if( count == 0){
							_this._nodepart( level );
						}
						_this._setcache( data, param.fid );
						_this._count( count );
						_this._ajaxBack( data, level);
					}else{
						_this._nodepart( level );
					}
				} );
			});
		},
		
		_ajaxBack : function( json, level){
			if( level == 'first' ){
				this.box.find('.departsort-first').remove();
			}
			this.box.find('.departsort-second').remove();
			var hassort = $.isArray( json ) && json[0] ? json.length : false;
			$.tmpl( this.options.departtname, {
				list : json,
				hassort : hassort,
				level : level,
				count : this.options.count,
			}) .appendTo( this.box );
			if( level == 'first' ){
				this.box.find('.first-depart').eq(0).trigger('click');
				$( '#first' ).paginate({itemsPerPage: this.options.count});
			}else{
				$( '#second' ).paginate({itemsPerPage: this.options.count});
			}
		},
		
		_nodepart : function( level ){
			var _this = this;
			$.tmpl( _this.options.departtname, {
				list : [],
				hassort : false,
				level : level,
				count : _this.options.count,
			}) .appendTo( _this.box );
			_this.box.find('.list-depart').show();
			_this._initdoctor( null );
		},
		
		_count : function( count ){
			this.element.find('.title-depart .num').html( count );
		},
		
		_setcache : function(json, id){
			var _this = this;
			if( !id ){
				this.firstdepart = json;
				
				$.each(json, function(ii, nn){		//一级科室数据
					_this.first_indexdepart[nn.department_id] = nn;
				});
			}else{
				this.seconddepart[id] = json;	//一级fid
				
				$.each(json, function(ii, nn){
					_this.sec_indexdepart[nn.department_id] = nn;
				});
			}
		},
		
		_getcache : function( id ){		
			if( id && this.seconddepart[id] ){
				return this.seconddepart[id];
			}
			return this.seconddepart;
		},
		
		_stretch : function( event ){
			var item = $(event.currentTarget),
				hospital_id = this.element.attr('hospital_id');
			if( item.hasClass('nodepart') ){
				return;
			}
			if( item.hasClass('current') ){
				return;
				item.removeClass('current');
				item.find('ul').hide();
			}else{
				item.addClass('current').siblings().removeClass('current');
				var depart = item.attr('_depart'),
					fid = item.attr('_fid');
				var id = depart.split('_')[0],
					name = depart.split('_')[1];
				if( item.data('ajax') ){
					var data = this._getcache( id );
					this._ajaxBack( data, 'second' );
				}else{
					item.data('ajax', true);
					this._ajax( item, 'second', {
						fid : id,
						hospital_id : hospital_id
					} );
				}
				this._initdoctor( id, fid, name );
			}
		},
		
		_viewAll : function(){		//查看所有信息
			this.box.find('.departsort-second').remove();
			this.box.find('.first-depart.current').removeClass('current');
			this._initdoctor( 'all' );
		},
		
		_doctor : function( event ){
			event.stopPropagation();
			var item = $(event.currentTarget);
			if( $( event.target ).is('.edit') || item.hasClass('nodepart')){
				return false;
			}
			var depart = item.attr('_depart'),
				fid = item.attr('_fid');
			var id = depart.split('_')[0],
				name = depart.split('_')[1];
			if( !id ){
				return false;
			}
			item.addClass('current').siblings().removeClass('current');
			this._initdoctor( id, fid, name );
		},
		
		_initdoctor : function( id, fid, name ){
			var doctorbox = $.MC.doctor;
			if( !doctorbox.is(':hospital-doctor')  ){
				doctorbox.doctor( $.doctorConfig );
			}
			doctorbox.doctor('refresh', id, fid, name);
		},
		
		_initdepart : function( currentdepart, level ){
			var _this = this,
				departbox = $.MC.popdepart;
			if( !departbox.is(':hospital-popbox')  ){
				 departbox.popbox( $.popdepartConfig );
			}
			var callback = function( method, level, param ){
				_this._refresh();
			};
			departbox.popbox('option', 'callback', $.proxy(callback, this));
			departbox.popbox('option', 'firstdepart', this.firstdepart);
			departbox.popbox('refresh', currentdepart, level);
		},
		
		_adddepart : function(){
			var hospital_id = this.element.attr('hospital_id');
			this._initdepart( {
				hospital_id : hospital_id
			}, 'first' );
		},
		
		_editdepart : function( event ){
			var item = $( event.currentTarget ).closest('li'),
				id = item.attr('_selfid');
			//var depart = level == 'first'? this.first_indexdepart[id] : this.sec_indexdepart[id];
			this._getdetail( item, id );
		},
		
		_getdetail : function( item, id ){
			var _this = this,
				level = item.hasClass('second-depart') ? 'second' : 'first';;
			$.globalAjax( item, function(){
				return $.getJSON( _this.options.edit_url, {id : id}, function( json ){
					if( json && json['callback'] ){
						eval( json['callback'] );
						return;
					}
					if( $.isArray(json ) && json[0] ){
						var data = json[0];
						if( $.isArray( data.pic_info ) && data.pic_info[0] ){
							$.each( data.pic_info, function(kk, vv){
								if( vv.filename ){
									vv.img = $.globalImgUrl(vv, '120x120');
								}else{
									vv.img = '';
								}
							})
						}
						_this._initdepart( data, level );
					}
				} );
			});
		},
		
		_remind : function( title, message, callback, dom ){
			jConfirm( title, message , function(result){
				result && callback();
			}).position( dom );
		},
		
		_refresh : function(){
			var hospital_id = this.element.attr('hospital_id');
			this._ajax( this.element, 'first', {
				hospital_id : hospital_id
			} );
		},
		
		destroy : function(){
			$.Widget.prototype.destroy.call( this );
		}
	});
	
	$.widget('hospital.doctor', {
		options : {
			url : '',
			doctortpl : '',
			doctortname : 'doctor-tpl',
		},
		
		_create : function(){
			$.template(this.options.doctortname, this.options.doctortpl);
			this.departdoctor = {};
			this.box = this.element.find('.m2o-list');
			this.btn = this.element.find('.add-doctor');
			this.form = this.element.find('#searchform');
		},
		
		_init : function(){
			this._on({
				'click .m2o-each' : '_click',
				'click .add-doctor' : '_add',
				'click .search' : '_search',
				'click .del-btn' : '_cancel',	
				'click .select' : '_select',
				'click .selectbox-wrapper li' : '_change',
				'click .del' : '_del'		
			});
			this._initSelect();
			this._initForm();
		},
		
		_initForm : function(){
			var _this = this;
			this.form.submit(function(){
				$(this).ajaxSubmit({
					beforeSubmit : function(){
						//submit[0].disabled = true;
					},
					dataType : 'json',
					success : function( json ){
						if( $.isArray( json ) && json[0] ){
							var data = json[0]['data'] || '';
							_this._ajaxBack( json[0] );
						}
					},
					complete : function(){
						//submit[0].disabled = false;
					}
				});
				return false;
			});
		},
		
		_search : function( event ){
			var self = $(event.currentTarget),
				item = self.closest('.type-item');
			if( item.hasClass('type-search') ){
				if( !self.next('input[name="k"]').val() ){
					item.removeClass('type-search');
				}
				this.form.trigger('submit');
			}else{
				item.addClass('type-search');
			}
		},
		
		//取消搜索框内容
		_cancel : function( event ){
			var self = $(event.currentTarget);
			self.prev('input[name="k"]').val('');
		},
		
		_select : function( event ){
			var self = $(event.currentTarget),
				item = self.closest('.type-item');
			item.toggleClass('type-select');
			item.toggleClass('overflow-hide');
		},
		
		_change : function(){
			this.form.trigger('submit');
		},
		
		_initSelect : function(){
			$('.select-item select').selectbox();
		},
		
		_ajax : function( page, count ){
			var _this = this,
				op = _this.options,
				param = {
					page : page || 1,
					count : count || 20
				};
			(_this.btn.attr('_depart') !== 'all') && (param.department_id = _this.btn.attr('_depart'))
			if( op.extend == 'reservation' ){
				param.hospital_id = _this.element.attr('hospital_id');
			}else{
				param.fid = _this.btn.attr('_fid');
			}
			
			$.globalAjax(this.box, function(){
				return $.getJSON( _this.options.url, param, function( json ){
					if( $.isArray( json ) && json[0] ){
						var count = json[0]['count'] || 0,
							data = json[0]['data'] || '';
						//_this._setcache( data, param.department_id );
						_this._count( count );
						_this._ajaxBack( json[0] );
					}
				} );
			});
		},

		_setcache : function( json, id ){
			this.departdoctor[id] = json;	
		},

		_getcache : function( id ){
			if( id && this.departdoctor[id] ){
				return this.departdoctor[id];
			}
			return this.departdoctor;
		},

		_ajaxBack : function( json ){
			if( !json ){
				this._nodoctor();
				return;
			}
			this._initpage( json );
			var hospital_id = this.element.attr('_id'),
				hospital_ids = this.element.attr('hospital_id'),
				department_name = this.btn.attr('_depart_name'),
				data = json['data'];
			if( $.isArray( data ) && data[0] ){
				$.each(data, function(kk, nn){
					if( nn && nn.indexpic && nn.indexpic.filename ){
						nn.img = $.globalImgUrl(nn.indexpic, '30x30');
					}else{
						nn.img = '';
					}
					nn.index = kk;
					nn.search = $.param({
						hospital_id : hospital_id,
						hospital_ids : hospital_ids,
						department_id : nn.department_id,
						id : nn.id,
						department_name : department_name,
					});
				});
				$.tmpl( this.options.doctortname, data ).appendTo( this.box.empty() );
			}else{
				this._nodoctor();
			}
			
		},

		_nodoctor : function(){
			var extend = this.options.extend,
				str = (extend == 'reservation' ? '预约单' : '医生');
			$( '<div class="m2o-each nodoctor"><p class="common-list-empty">暂无' + str + '数据！</p></div>' ).appendTo( this.box.empty() );
		},

		_initpage : function( option ){
			var _this = this,
				pagebox = this.element.find('.page_size');
			if( !pagebox.is(':hoge-page') ){
				option['page'] = function( event, page, page_num ){
					_this._page( page, page_num );
				};
				option.show_all = false;
				pagebox.page( option );
				return;
			}
			pagebox.page( 'refresh', option );
		},

		_page : function( page, page_num ){
			this._ajax( page, page_num );
		},

		_count : function( count ){		//医生数目
			this.element.find('.title-doctor .num').html( count );
		},
		
		_setbtn : function( id, fid, name ){		//按钮状态及链接
			var btn = this.btn;
			btn[( parseInt( fid ) ? 'remove' : 'add') + 'Class']('btn-disable');
			btn.attr({
				'_depart' : id,
				'_depart_name' : name,
				'_fid' : fid
			});
			
			var form = this.form;
			form.find('input[name="department_id"]').val( id );
			form.find('input[name="fid"]').val( fid );
			var hospital_id = this.element.attr('hospital_id');
			form.find('input[name="hospital_id"]').val( hospital_id );
		},

		refresh : function( id, fid, name ){
			if( !id ){
				this._ajaxBack();
			}
			var depardoctor = this._getcache( id );
			this._setbtn( id, fid, name );
			if( $.isArray( depardoctor ) && depardoctor[0] ){
				this._ajaxBack( depardoctor );
			}else{
				this._ajax( 1 );
			}
		},
		
		_click : function( event ){
			if( $(event.target).is('a') ){
				return;
			}
			var self = $(event.currentTarget);
			if( self.hasClass('nodoctor') ){
				return;
			}
			self.addClass('current').siblings().removeClass('current');
		},
		
		_del : function( event ){			//删除医生
			var self = $(event.currentTarget),
				item = self.closest('.m2o-each');
			var _this = this,
				id = item.attr('_id');
			var callback = function(){
				$.globalAjax(item, function(){
					return $.getJSON( _this.options.delurl, {id : id}, function( json ){
						if( json && json['callback'] ){
							eval( json['callback'] );
							return;
						}
						if( $.isArray(json ) && json[0] && json[0] == 'success' ){
							item.remove();
						}else{
							_this.myTip(self, '删除失败', -50);
						}
					} );
				});
			}
			this._remind('你确定要删除该医生信息', '删除提示', callback, self);
		},
		
		_add : function( event ){
			var self = $(event.currentTarget);
			if( self.hasClass('btn-disable') ){
				return false;
			}
			var search = {
				hospital_id : this.element.attr('_id'),
				hospital_ids : this.element.attr('hospital_id'),
				department_id : self.attr('_depart'),
				department_name : self.attr('_depart_name')
			}
			var href = this.options.doctorurl + '&' + $.param( search );
			self.attr('href', href);
		},
		
		_remind : function( title, message, callback, dom ){
			jConfirm( title, message , function(result){
				result && callback();
			}).position( dom );
		},
		
		myTip : function( dom, str, left ){
			dom.myTip({
				string : str,
				delay: 2000,
				dtop : 5,
				dleft : left || 130,
				width : 'auto',
				padding: 10
			});
		},
		destroy : function(){
			$.Widget.prototype.destroy.call( this );
		},
		
	});
})();
