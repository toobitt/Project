$(function(){
	(function($){
		$.widget('members.members_form',{
			options : {
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .icon' : '_icon',
					'change .file' : '_uploadfile',
					'change .date-picker' : '_changetime',
					'blur .value-verify' : '_verifyvalue'
				})
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
				$(".indexpic").removeClass("icons");
			},
			
			_changetime : function(event){
				var self = $(event.currentTarget),
					start_time = this.element.find('input[name="start_date"]').val(),
					end_time = this.element.find('input[name="end_date"]').val(),
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
			
			_verifyvalue : function(event){
				var self = $(event.currentTarget),
					val = self.val();
				if(isNaN( val )){
					var tip = "值只能为数字";
					this._myTip(self , tip);
					self.val('');
					return false;
				}
				if( val< 0 ){
					var tip = "值必须大于等于0";
					this._myTip(self , tip);
					self.val('');
					return false;
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
	$('.m2o-form').members_form();
});