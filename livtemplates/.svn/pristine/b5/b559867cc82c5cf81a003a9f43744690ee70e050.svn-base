jQuery(function($){
	var wrap = $('.m2o-form'),
		_this = this;
	var ohms = wrap.find('#ohms-instance').ohms();
	var control = {
			init : function(){
				wrap
				.on('click' , '.limit-checkbox' , $.proxy(this.toggle , this))
				.on('click' , '.suoyin-box' , $.proxy(this.indexpic , this))
				.on('click' , '.tab-btns li' , $.proxy(this.tabToggle , this))
				.on('click' , '.info-tab .del-btn' , $.proxy(this.delPic , this))
				.on('click' , '.info-tab .add-btn' , $.proxy(this.addPic , this))
				.on('click' , '.info-tab .set-fm' , $.proxy(this.setIndex , this))
				.on('click' , '.awards-tab .add-btn' , $.proxy(this.addItem , this))
				.on('click' , '.awards-tab .del-btn' , $.proxy(this.delItem , this))
				.on('click' , '.awards-tab .pic-box' , $.proxy(this.awardIndex , this))
				.on('click' , '.lottery_show li' , $.proxy(this.showGame , this))
				.on('change' , '.time-select' , $.proxy(this.datePicker , this))
				.on('click' , '.info-tab .lottery-bg-btn' , $.proxy(this.setLotteryBg , this))
				.on('click' , '.save-as' , $.proxy(this.saveAs , this));
				this.initInputFile();  																	/*图片上传*/
				this.initSwitch();																		/*轮滑按钮*/
				this.submit();
				this.timeGet();
				this.weekEvent();
			},
			
			weekEvent : function(){
				wrap.on('click','#every_day',function(event){
					var self = $(event.currentTarget);
	                var bool = self.is(':checked');
	                if (bool) {
	                    self.closest('#week_date').find('.n-h').not(self).attr("checked","checked");
	                } else {
	                    self.closest('#week_date').find('.n-h').not(self).removeAttr('checked');
	                }
				});
				wrap.on('click','.each-week',function(event){
					var self = $(event.currentTarget);
					var bool = self.is(':checked'),
						every_day = wrap.find('#every_day');
					if(!bool){
						every_day.removeAttr('checked');
					}else{
						var len = wrap.find('.each-week:checked').length;
						if( len == 7 ){
							every_day.attr("checked","checked");
						}
					}
				});
				wrap.on('click','.cycle-input',function(){
					var self = $(this),
						value = self.val(),
						week_box = wrap.find('#week_date'),
						month_box = wrap.find('#month_date');
					week_box[value == 'week' ? 'show' : 'hide']();
					month_box[value == 'month' ? 'show' : 'hide']();
					
				});
			},
			
			timeGet : function(){
                var _this = this;
                wrap.on({
                    'mousedown' : function(){
                        var disOffset = {left : 0, top : 0};
                        var $this = $(this);
                         ohms.ohms('option', {
                            time : $this.is('input') ? $this.val() : $this.html(),
                            target : $this
                        }).ohms('show', disOffset);
                        return false;
                    },
                    'set' : function(event, hms){
                        var $this = $(this);
                        var time = [hms.h, hms.m, hms.s].join(':');
                        if( $this.is('input') ){
                            var box = $this.parent('span'),
                                bool = $this.is('.start'),
                                other = bool ? box.find('input.end') : box.find('input.start'),
                                otherval = other.val();
                            if( otherval ){
                                if( bool && (time >= otherval)){
                                    _this.myTip( $this, '开始时间不能大于或等于结束时间' );
                                    return false;
                                }
                                if( !bool && time <= otherval ){
                                    _this.myTip( $this, '结束时间不能小于或等于开始时间' );
                                    return false;
                                }
                            }
                            $this.val(time);
                        }
                    }
                }, '.way-time');
            },   

			initInputFile : function(){
                var _this = this;
                wrap.find('input[type="file"]').ajaxUpload({
                	url : './run.php?mid=' + gMid + '&a=upload', 
                    phpkey : 'Filedata',
                    before : function( info ){
						_this._loading();
						return false;
					},
                    after : function( json ){
                        _this.ajaxUploadAfter(json);
                    }
                });
	        },
	        
	        initSwitch : function(){
	        	var _this = this;
	        	wrap.find('.common-switch').each(function(){
	    			var $this = $(this),
						obj = $this.parent(),
						tname = 'common-switch-on';
	    			$this.hasClass( tname ) ? val = 100 : val = 0;
	    			$this.hg_switch({
	    				'value' : val,
	    				'callback' : function( event, value ){
	    					$this.hasClass( tname ) ? status = 1 : status = 0;
	    					_this.onOff( $this , status );
	    				}
	    			});
	        	});
	        },
	        
	        onOff : function( self , status ){
	        	var input = self.closest('.m2o-switch').find('input[type="hidden"]');
	        	input.val( status );
	        },
	        
	        _loading : function(){
	        	this.target.addClass('loading');
			},
	        
	        ajaxUploadAfter : function(json){
                if( this.type == 0){
                    this.uploadIndexAfter( json.data );
                    this.uploadPicAfter( json.data );
                }else if( this.type == 1){
                	this.uploadPicAfter( json.data );
                }else if( this.type == 2){
                	this.uploadAwardAfter( json.data );
                }else if( this.type == 3 ){
                	this.uploadLotteryBgAfter( json.data );
                }
                this.target.removeClass('loading');
            },
            
            uploadIndexAfter : function( json ){
            	var src = this.createImgsrc( json ),
            		suoyinflag = wrap.find('.suoyin-flag'),
            		hasClass = suoyinflag.hasClass('current');
            	wrap.find('.suoyin-box').find('img').attr('src', src );
            	wrap.find('input[name="indexpic_id"]').val(json.id);
                wrap.find('.suoyin-flag').addClass('current');
                if(!hasClass){
					suoyinflag.addClass('current');
				}
            },
            
            uploadPicAfter : function( json ){
            	var item = wrap.find('.info-tab .pic-add-btn');
            	var info = {};
            	info.src= this.createImgsrc( json );
            	info.id = json.id;
            	$('#add-basic-pic-tpl').tmpl( info ).insertBefore( item );
            },
            
            uploadAwardAfter : function( json ){
            	var src = this.createImgsrc( json , 98 , 64 );
            	this.target.find('img').attr('src' , src );
            	this.target.find('input[name="award_indexpic[]"]').val( json.id );
            },
            
            datePicker : function( event ){
            	var self = $( event.currentTarget ),
	            	startTime = $('input[name="start_time"]').val(),
		    		endTime = $('input[name="end_time"]').val(),
		    		startTime = startTime.replace(/-/g,'/'),
		    		endTime = endTime.replace(/-/g,'/'),
					startTime = new Date(startTime),
					endTime = new Date(endTime),
					endTime = endTime.getTime(),
					startTime = startTime.getTime();
		    	if( startTime && endTime ){
		    		if(startTime > endTime ){
		    			this.myTip( self ,'初始时间不能大于结束时间' , '200');
		    			self.val('');
		    		}
		    	}
            },
            
            
			toggle : function( event ){
				var self = $( event.currentTarget ),
					checked = self.prop('checked'),
					obj = self.closest('.m2o-item').find('.limit-hour');
				checked ? obj.show() : obj.hide();
			},
			
			indexpic : function( event ){
				var self = $( event.currentTarget );
				self.siblings('.indexpic-file').click();
				this.target = self;
				this.type = 0;
			},
			
			tabToggle : function( event ){
				var target = $( event.currentTarget );
				target.addClass('current').siblings().removeClass('current');
				wrap.find('.tab-item').filter(function(){
					return $(this).attr('_index') == target.attr('_index')
				}).addClass('current').siblings().removeClass('current');
				if( target.attr('_index')==4 ){
					if (!window.BMap){
						$('<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4&callback=initializeMap"><\/script>').appendTo('body');
					}else{
						window.initializeMap();
					}					/*由于tab切换 地图所在一开始是隐藏的 所以刚进页面实例化地图会使得箭头定位不会居中 故当切换至区域模块时  实例化地图*/
				}
			},
			
			delPic : function( event ){
				var target = $( event.currentTarget ),
					item = target.closest('.pic-box'),
					id = item.attr('_id'),
					indexpic = wrap.find('input[name="indexpic_id"]'),
					indexpic_id = indexpic.val();
//					url =  './run.php?mid=' + gMid + '&a=del_mater';	
//				 $.globalAjax( item, function(){
//					return $.getJSON( url, {id : id}, function(data){
//						item.remove();
//	 				});
// 				});
				var del = wrap.find('input[name="del_id"]'),
					old_del_id = del.val(),
					del_ids =( old_del_id && ( old_del_id + ',' ) ) + id;
				del.val( del_ids );
				item.remove();
				if( id == indexpic_id){								/*删除索引*/
					indexpic.val('');
					wrap.find('.suoyin-box').find('img').attr('src' , '' );
					wrap.find('.suoyin-flag').removeClass('current');
				}
			},
			
			addPic : function( event ){
				var target = $( event.currentTarget );
				target.siblings('[type="file"]').click();
				this.target = target;
				this.type = 1;
			},
			
			setIndex : function( event ){
				var target = $( event.currentTarget ),
					item = target.closest('.pic-box'),
					src = item.find('img').attr('src'),
					id = item.attr('_id'),
					suoyinflag = wrap.find('.suoyin-flag'),
					hasClass = suoyinflag.hasClass('current');
				wrap.find('.suoyin-box').find('img').attr('src' , src );
				wrap.find('input[name="indexpic_id"]').val( id );
				if(!hasClass){
					suoyinflag.addClass('current');
				}
			},
			
			delItem : function( event ){
				var target = $( event.currentTarget );
				jConfirm('确定要删除么？','删除提示',function( result ){
					if( result ){
						var parent = target.closest('li'),
							wrap = parent.closest('.content-list');
						parent.slideUp(function(){
							parent.remove();
							wrap.find('.content-list-list').each(function(k, v){
								$(this).find('.index').text( k+1 );
							});
						});
					}
				}).position( target );
			},
			
			addItem : function( event ){
				var target =$( event.currentTarget ),
					pos = target.siblings('.content-list');
				var data = {
						index : pos.find('.content-list-list').length+1
					};
				target.hasClass('add-awards') ? $('#add-awards-tpl').tmpl(data).appendTo( pos ) : $('#add-no-awards-tpl').tmpl(data).appendTo( pos );
			},
			
			awardIndex : function( event ){
				var target = $( event.currentTarget );
				target.next('input[type="file"]').click();
				this.target = target;
				this.type = 2;
				this.initInputFile();  /*重新实例化*/
			},
			
			showGame : function( event ){
				var self = $( event.currentTarget ),
					type = self.find('a').attr('attrid'),
					lottery_frame = wrap.find('#lottery-iframe'),
					src = lottery_frame.attr('src') + '&type=' + type;
				lottery_frame.attr('src' , src );
			},
			
			createImgsrc :function( data, wid , hei ){						//图片src创建
				if( wid && hei ){
					var options = $.extend( {}, {width:wid,height:hei}, options ),
					data = data || {},
					src = [data.host, data.dir, options.width, 'x', options.height, '/', data.filepath, data.filename].join('');
				}else{
					var data = data || {},
					src = [data.host, data.dir, data.filepath, data.filename].join('');
					return src;
				}
				return src;
			},
			
			submit : function(){
				var _this = this,
					subButton = wrap.find('.m2o-save');
				wrap.submit(function(){
					var isTimeLimit = wrap.find('input[name="time_limit"]').val();
					if( isTimeLimit ){
						var sTime = wrap.find('input[name="start_time"]').val(),
							eTime = wrap.find('input[name="end_time"]').val();
						if( !(sTime && eTime) ){
							_this.myTip(subButton ,  '抽奖初始时间和结束时间不能为空' , '200');
							return false;
						}
					}
					_this.handleTimevalue();
				})
			},
			
			handleTimevalue : function(){
				var cycle_type = wrap.find('.cycle-input:checked').val(),
					cycle_value = '',
					cycle_value_hidden = wrap.find('input[name="cycle_value"]');
				if( cycle_type == 'week' ){
					var checked_week = wrap.find('.each-week:checked');
					if(checked_week.length){
						var week_values = checked_week.map(function(){
							return $(this).val();
						}).get().join();
						cycle_value = week_values;
					}
				}
				if( cycle_type == 'month' ){
					cycle_value = $.trim( wrap.find('.month-value').val() );
				}
				
				wrap.find('input[name="cycle_value"]').val( cycle_value );
				wrap.find('.cycle-value-box').find('input').attr('disabled','disabled');
				
			},
			
			myTip : function(self , tip , wid){
                self.myTip({
                    string : tip,
                    delay: 1000,
                    width : wid || 200,
                    dleft : 20,
                });
            },
            
            setLotteryBg : function(event){
            	var self = $(event.currentTarget),
            		input_file = self.next('input[type="file"]');
            	this.lotteryHidden = self.find('input[type="hidden"]');
            	this.target = self;
				this.type = 3;
            	input_file.click();
            },
            
            uploadLotteryBgAfter : function( data ){
            	this.lotteryHidden.val( data.id );
            	var src = this.createImgsrc( data );
            	window.lottery_bg_src = src;
            	this.refreshFrame();
            },
            
            saveAs : function(){
            	wrap.find('input[name="a"]').val('create');
            	wrap.submit();
            },
            
            refreshFrame : function(){
            	var frame = wrap.find('#lottery-iframe');
            	if( frame.length ){
            		frame[0].contentWindow.location.reload();
            	}
            }
	};
	control.init();
});