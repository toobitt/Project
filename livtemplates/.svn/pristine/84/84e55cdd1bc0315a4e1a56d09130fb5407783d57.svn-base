$(function(){
	(function($){
		$.widget('epaper.form_common',{
			options : {
				'stack-item' : '.stack-item',
				'del-stack' : '.del-stack'
			},
			_init : function(){
				var handler = {},
					op = this.options;
				handler['mouseenter' + op['stack-item']] = '_detect';
				handler['mouseleave' + op['stack-item']] = '_removeDel';
				handler['click' + op['del-stack']] = '_delStack';
				this._on(handler);
			},
			_detect : function( event ){
				var self = $(event.currentTarget),
					next = self.next('.stack-item');
				if( self.hasClass('active') ){
					if( !next.length ){
						$('<span />').appendTo(self).attr('class','del-stack').text('x');
					}
				}
			},
			_removeDel : function(){
				$('.del-stack').remove();
			},
			_delStack : function( event ){
				var self = $(event.currentTarget),
					parent = self.closest('li'),
					id = parent.attr('_id'),
					period_id = $('#info').attr('_periodid'),
					url = './run.php?mid=' + gMid + '&a=del_stack';
				var posStack = $('.each-list').filter(function(){
					return ( $(this).attr('_belong') == parent.attr('_flag') )
				});
				var paper = posStack.find('li');
				if(paper.length){
					jAlert('请先删除该叠下的版','提示');
				}else{
					$.get(url,{period_id:period_id, stack_id:id},function(){
						parent.remove();
						posStack.remove();
					});
				}
			event.stopPropagation();
			},
		});
	})($)
})