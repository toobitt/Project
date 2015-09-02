$(function(){
	(function($){
		$.widget('interact.tv_interact',{
			options : {
				ohms : null,
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .icon' : '_icon',
					'click .open-link-checkbox' : '_isOpenlink',
					'change .file' : '_uploadfile',
					'change .date-picker' : '_changetime',
					'blur .value-verify' : '_verifyvalue',
					'click #every_day' : '_checkSelect'
				});
				this.timeGet();
				this._initSubmit();
			},
			
			_initSubmit : function(){
				var _this = this;
				this.element.on('submit',function(){
					var isopenlink = $(this).find('.open-link-checkbox').prop('checked'),
						open_link_item = $(this).find('input[name="link_address"]');
					if( isopenlink ){
						var link_address_value = $.trim( open_link_item.val() );
						if( !link_address_value ){
							_this._myTip( open_link_item, '链接跳转地址不能为空' );
							return false;
						}
					}
				});
			},
			
			_checkSelect : function (event) {
                var self = $(event.currentTarget);
                var bool = self.is(':checked');
                if (bool) {
                    self.closest('#week_date').find('.n-h').not(self).attr("checked","checked");
                } else {
                    self.closest('#week_date').find('.n-h').not(self).removeAttr('checked');
                }
            },     
			
			_isOpenlink : function(event){
				var self = $(event.currentTarget),
					checked = self.prop('checked'),
					open_link_item = this.element.find('.open-link-item'),
					interact_info_box = this.element.find('.interact-info-box');
				self.val( checked ? 1 : 0 );
				interact_info_box[checked ? 'addClass' : 'removeClass']('hide');
				open_link_item[checked ? 'removeClass' : 'addClass']('hide');
			},
			
			_icon : function(event){
				var self = $(event.currentTarget);
				self.closest('.img-info').find('input[type="file"]').trigger('click');
			},
			
			_uploadfile : function(event){
				var self=event.currentTarget,
					file=self.files[0],
					box = $(self).closest('.img-info').find('.icon');
				box.find('.indexpic-suoyin ')[0] ? fg = true : fg = false;
				this._preview(box ,file , fg );
			},
			
			_changetime : function(event){
				var self = $(event.currentTarget),
					start_time = this.element.find('input[name="start_time"]').val(),
					end_time = this.element.find('input[name="end_time"]').val(),
					start_time = start_time.replace(/-/g,'/'),
					end_time = end_time.replace(/-/g,'/'),
					start_time = new Date(start_time),
					end_time = new Date(end_time),
					end_time = end_time.getTime(),
					start_time = start_time.getTime(),
					tip = '';
				if(start_time > end_time){
					tip = "初始时间不能大于结束时间";
					this._myTip(self , tip);
					self.val('');
					return false;
				}
			},
			
			timeGet : function(){
                var _this = this;
                this.element.on({
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
                        var time = [hms.h, hms.m, hms.s].join(':');
                        if( $this.is('input') ){
                            var box = $this.parent('span'),
                                bool = $this.is('.start'),
                                other = bool ? box.find('input.end') : box.find('input.start'),
                                otherval = other.val();
                            if( otherval ){
                                if( bool && (time >= otherval)){
                                    _this._myTip( $this, '开始时间不能大于或等于结束时间' );
                                    return false;
                                }
                                if( !bool && time <= otherval ){
                                    _this._myTip( $this, '结束时间不能小于或等于开始时间' );
                                    return false;
                                }
                            }
                            $this.val(time);
                        }
                    }
                }, '.way-time');
            },   
			
			_verifyvalue : function(event){
				var self = $(event.currentTarget),
					min = this.element.find('input[name="score_min"]').val(),
					max = this.element.find('input[name="score_max"]').val();
				if(isNaN(min) || isNaN(max)){
					var tip = "值只能为数字";
					this._myTip(self , tip);
					self.val('');
					return false;
				}
//				if(max<0 || min<0){
//					var tip = "值必须大于等于0";
//					this._myTip(self , tip);
//					self.val('');
//					return false;
//				}
				if(min && max){
					if(parseInt(max) < parseInt(min)){
						var tip = "最大值不能小于最小值";
						this._myTip(self , tip);
						self.val('');
						return false;
					}
				}
			},
			
			_myTip : function(self , tip ){
				self.myTip({
					string : tip,
					delay: 1000,
					width : 150,
					dtop : 0,
					dleft : 80,
				});
			},
			
			_preview : function(box, file , fg){
				box.hg_preview({
					box : box,
					file : file,
					flag : fg
				});
			},
		});
	})($);
	$('.m2o-form').tv_interact({
		ohms: $('#ohms-instance').ohms(),
	});
});