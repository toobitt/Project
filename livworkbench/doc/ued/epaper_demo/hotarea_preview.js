$(function(){
	$.widget('epaper.preview',{
		options : {},
		_init : function(){
			this._on({
				'mouseenter .hover-item' : '_showHot',
				'mouseleave .hover-item' : '_hideHot'
			});
			this._createHot();
		},
		_createHot : function(){
			var url = 'http://10.0.1.40/livworkbench/run.php?mid=571&a=get_page&type=edit_link&stack_id=18&period_id=10';
			$.getJSON(url,function(json){
				var hoverBox = $('.hover-box');
				var outerWidth = hoverBox.width(),
				outerHeight = hoverBox.height();
				$('.items').empty();
				var arrInfo = [];
				var data = json[0]['hot_area'];
				$.each(data,function(k,v){
					var info = {
							id : v['id'],
							top : Math.round( v['top']*outerHeight ) + 'px',
							left : Math.round( v['left']*outerWidth ) + 'px',
							width : Math.round( v['width']*outerWidth ) + 'px',
							height : Math.round( v['height']*outerHeight ) + 'px',
							title : v['title']
					};
					arrInfo.push(info);
				});
				$('#hot-item-tpl').tmpl(arrInfo).appendTo('.items');
			});
		},
		_showHot : function(event){
			var self = $(event.currentTarget),
				id = self.attr('_id'),
				all = this.element.find('.hover-item'),
				theSame = all.filter(function(){
					return $(this).attr('_id') == id;
				});
			if( theSame.length ){
				theSame.find('.title').hide();
				self.find('.title').show();
			}
			theSame.addClass('show');
		},
		_hideHot : function(){
			this.element.find('.hover-item').removeClass('show');
			this.element.find('.title').hide();
		}
	});
	$('.global-preview').preview();
});