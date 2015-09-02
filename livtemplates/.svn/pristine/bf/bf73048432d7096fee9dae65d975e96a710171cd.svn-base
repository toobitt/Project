(function(){
	$.widget('hospital.doctor_form', {
		options : {
			teltpl : '',
			hasImage : 'has-images',
			events : [
				// {
					// title : '专家号',
					// start : '2014-12-15T08:30:00',
					// end : '2014-12-15T12:00:00',
					// allDay : false
				// },
				// {
					// title : '专家号',
					// start : '2014-12-16T09:30:00',
					// allDay : false
				// },
				// {
					// title : '专家号',
					// start : '2014-12-17',
					// allDay : false
				// }
			]
		},
		
		_create : function(){
			var op = this.options,
				widget = this.element;
			this.imgLoading = $('<img src="' + RESOURCE_URL + 'loading2.gif" class="loading2" style="width:50px; height:50px;"/>');
			this.calendar = widget.find('.fullcalendar-wrapper');
			this.time = {
				'am' : '上午',
				'pm' : '下午',
				'night' : '晚上'
			}
		},
		
		_init : function(){
			this._on({
				'click .save-button' : '_submit',
				'click .img-add' : '_addImg',
				'change .images-file' : '_uploadImg',
				'click .m2o-close' : '_close'
			})
			this._switch();	
			//this._initForm();
			this._initCalendar();
			this._filter();
		},
		
		//滑动选择
		_switch : function(){
			var _this = this;
			$('.common-switch').each(function(){
				var $this = $(this),
					obj = $this.parent();
				var id = _this.widget().data('id'),
					val;
				$this.hasClass('common-switch-on') ? val = 100 : val = 0;
				$this.hg_switch({
					'value' : val,
					'callback' : function( event, value ){
						var is_on = 0;
						( value > 50 ) ? is_on = 1 : is_on = 0;
						_this._onOff(id, obj, is_on);
					}
				});
			});
		},
		
		_onOff : function( id, obj, is_on ){
			obj.find('input[type="hidden"]').val( is_on );
		},
		
		_initCalendar : function(){
			var _this = this,
				op = this.options,
				events = op.events;
			this.calendar.hg_fullCalendar( {
				header: {
					left: 'prev,next today',
					center: 'title',
					right: ''
				},
				
				dayNamesShort : ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
				monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
				monthNamesShort: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
				titleFormat : {
					month : 'yyyy年MMMM',
					week: "yyyy年MMMd日[ yyyy]{ '-'[ MMM] d日 }",
				},
				columnFormat : {
					week: 'M/d ddd'
				},
				buttonText : {
					'today' : '今天',
					'month' : '月',
					'week' : '周'
				},
				
				dayClick : function(date, allDay, jsEvent, view){
					return;
					var target = $( jsEvent.currentTarget ),
						daytime = _this.formatDate( date );
					_this._dayclick( daytime, allDay, target );
				},
				
				aspectRatio : 1.6,
				
				weekMode : 'liquid',
				
				eventSources : [{
					events : events,
					color : '#5b6fce',
					textColor : '#fff'
				}]
			} );
		},
		
		_dayclick : function( date, allDay, target){
			var param = target.data('index') || {
					date : date,
					allDay : allDay,
			};
			this._initorder( param, target );
			return;
			this._render({
				allDay : allDay,
				date : date,
				title : '上午 专家：¥50',
			});
		},
		
		_initorder : function( param, target ){
			var _this = this,
				addorder = $('#add-order');
			if( !addorder.is(':hospital-doctorpop')  ){
				addorder.doctorpop( $.popdoctorConfig );
			}
			var callback = function( eventObj ){
				_this.filterData( eventObj, target );
				
			};
			addorder.doctorpop('option', 'callback', $.proxy(callback, this));
			addorder.doctorpop('refresh', param );
		},
		
		filterData : function( eventObj, target ){
			var _this = this;
			if( $.isArray( eventObj ) && eventObj[0] ){
				var indexData = {};
				var filter = $.map(eventObj, function( vv ){
					if ( vv.name == 'time' ){
						(indexData.time || (indexData.time = [])).push( vv.value );
						return vv;
					}else{
						indexData[vv.name] = vv.value
					}
				});
				target.data('index', indexData );
				$.each(filter, function(nn, vv){
					if( vv.value == 'all' ){
						return;
					}
					_this._render( {
						title : _this.time[ vv.value ] + '：' + indexData['export'] + ' ¥' + indexData['cost'],
						allDay : indexData['allDay'],
						start : indexData['date'],
					} );
				});
			}
				
		},
		
		_render : function( eventObj ){
			this.calendar.fullCalendar('renderEvent', eventObj, true);
		},
		
		_submit : function( event ){
			var _this = this,
				btn = $(event.currentTarget),
				loadSubmit = $.globalLoad( btn );
			var tip = _this._before();
			if( tip ){
				loadSubmit();
				_this._myTip(btn, tip);
				return false;
			}
			this.element.trigger('submit');
			loadSubmit();
		},
		
		_close : function(){
			location.href = document.referrer;
		},
		
		_initForm : function(){
			var btn = this.element.find('.save-button'),
				_this = this, tip, loadSubmit;
			this.element.submit(function(){
				$(this).ajaxSubmit({
					beforeSubmit : function(){
						tip = _this._before();
						if( tip ){
							_this._myTip(btn, tip);
							return false;
						}
						loadSubmit = $.globalLoad( btn );
					},
					dataType : 'json',
					success : function( data ){
						loadSubmit();
						if( data && data.error ){
							_this._myTip(btn, data.msg);
							return;
						}
						_this._myTip( btn, btn.val() + '成功');
						setTimeout(function(  ){
							_this._close();
						}, 2000)
					}
				});
				return false;
			});
		},
		
		//增加图片
		_addImg : function( event ){
			$(event.currentTarget).next('.images-file').click();
		},
		
		_uploadImg : function( event ){
			var _this = this,
				self = event.currentTarget,
				imgbox = $( self ).prev();
			var file=self.files[0];
		        reader = new FileReader();
		   reader.onload=function( e ){
				var imgData = e.target.result,
					img = imgbox.find('img');
				!img[0] && (img = $('<img />').appendTo( imgbox.prev() ));
	            img.attr('src', imgData);
	            imgbox.addClass( _this.options.hasImage );
			}  
			reader.readAsDataURL( file );    
		},
		
		_before : function(){
			var widget = this.element,
				param = {
					empty_name : '请填写医生名字',
					error_doctorid : '医生id为必须为有效数字'
				},
				tip = '';
			var title = $.trim(widget.find('.m2o-m-title').val()),
				doctorid = widget.find('input[name="doctor_id"]').val();
			$.each({
				empty_name : title,
				error_doctorid : doctorid
			}, function(ii, nn){
				if( !nn ){
					tip = param[ ii ];
					return false;
				}
				if( ii == 'error_doctorid' ){
					var reg_id = /^[1-9]\d*$/;
					var match = reg_id.test( doctorid );
					if( !match ){
						tip = param[ ii ];
					}
				}
			});
			return tip;
		},
		
		_filter : function(){
			var events = this.events = {};
			$.each( this.options.schedules, function( kk, vv ){
				events[vv.schedule_id] = vv;
			});
		},
		
		_remind : function( title, message, callback, dom ){
			jConfirm( title, message , function(result){
				result && callback();
			}).position( dom );
		},
		
		_myTip : function( dom, str, left ){
			dom.myTip({
				string : str,
				delay: 2000,
				dtop : 5,
				dleft : left || 130,
				width : 'auto',
				padding: 10
			});
		},
		
		formatDate : function( date ){
			var date = $.fullCalendar.formatDate( date,'yyyy-MM-dd' );
			return date;
		}
	});
})();
