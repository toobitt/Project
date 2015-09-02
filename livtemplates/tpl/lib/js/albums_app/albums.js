jQuery(function(){

	var getColumns = function(wrap){
		wrap.hg_move_publish({
			php : 'run.php?mid='+gMid+'&a=get_cate',
			getUrl : 'run.php?mid='+gMid+'&a=get_cate',
		});
		var publish = wrap.data('publish');
		publish.reinit();
	}
	

	$('.move-publish').on('click',function(event){
		var self = $(event.currentTarget),
			obj = self.closest('li'),
			id = obj.attr('id'),
			wrap = $('.publish-box'),
			top = self.offset().top;
		top<220 ? $('.common-list-ajax-pub').css({'top': 100 ,'left':100}) :$('.common-list-ajax-pub').css({'top':top-350 ,'left':100});
		$('input[name="id"]').val(id);
		getColumns(wrap);
	});
});