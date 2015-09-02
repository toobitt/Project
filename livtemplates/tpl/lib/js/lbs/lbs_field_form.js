$(function(){
	(function($){
		$.widget('lbs.lbs_set',{
			options : {
				hasTmpl: '',
				noTmpl : '',
			},
			
			_create : function(){
				this.val =  this.element.find('.data-type').filter(function(){
					return $(this).prop('checked');
				}).closest('li').find('span').text();
			},
			
			_init : function(){
				this._on({
					'click .data-type' : '_type',
					'click .add-option' : '_addoption',
				});
				this._submit();
			},
			
			_type : function(event){
				var self = $(event.currentTarget),
					txt = $.trim(self.attr('_val')),
					input = this.element.find('.default-option input'),
					info={},
					_this = this,
					op = this.options;
				info.txt = txt;
				if(input && txt== _this.val){
					input.remove();
					$.each($.globaldefault,function(k , v){
						info.value = v;
						$(op.hasTmpl).tmpl(info).appendTo('.option-contain');
					})
				}else{
					input.remove();
					$(op.hasTmpl).tmpl(info).appendTo('.option-contain');
					this.element.find('.default-option').css('display','-webkit-box');
				}
			},
			
			_addoption : function(event){
				var self = $(event.currentTarget),
					obj =self.closest('.default-option').find('.option-contain'),
					op = this.options;
				obj.append($(op.noTmpl).html());
			},
			
			_submit : function(){
				var	sform = this.element,
					_this = this;
				sform.submit(function(){
					var val = $.trim(sform.find('input[name="zh_name"]').val()),
						txt = $.trim(sform.find('input[name="field"]').val()),
						num = /^[0-9]*$/;
					if(!val){
						alert("请填写信息名称");
						return false;
					}
					if(txt && num.test(txt)){
						alert("标识不能全部是数字");
						return false;
					}
				});
			},
		});
	})($);
	$('.ad_form').lbs_set({
		hasTmpl : $('#option-tpl'),
		noTmpl : $('#add-option-tpl')
	});
});
