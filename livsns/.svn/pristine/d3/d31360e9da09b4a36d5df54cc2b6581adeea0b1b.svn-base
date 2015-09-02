(function(){
	var h5app = $.Hg_h5app({
			triggerBtn : ['share-app'],
		}),
		toast = $.h5_toast({
			content : '请填写完整信息',
			delay : 1500
		});
	
	
	var contentMore = $('.content-more'),
		block = $('.greeting-list'),
		downloadurl = 'http://xz.zhihuiyancheng.com/';
	var count = block.data('count'),
		search = getParam();
	if( search.share == 1){
		block.find('.link').addClass('download').attr('href', downloadurl);
		if( block.find('.email_con').length ){
			block.find('.email_con').addClass('download').attr('href', downloadurl);
		}
	}
	
	block.on('click', '.download', function( event ){
		event.preventDefault();
		toast.show('请安装客户端后再操作~');
		var $this = $(this);
		setTimeout(function(){
			location.href = $this.attr('href');
		}, 1500);
	});
	
	judge( count );
	contentMore.on('click', function(){
		spinner.show( contentMore );
		setTimeout(function(){
			judge( count );
			spinner.close( contentMore );
		}, 500);
	});
	
	function judge( count ){
		var target = block.find('.greeting-drawing').filter(function(){
			return !$(this).hasClass('show');
		});
		if( target.length ){
			target.each(function( i ){
				if( i < count ){
					$(this).addClass('show')
				}
			});
			if( target.length < count ){
				contentMore.hide();
			}
		}else{
			toast.show('没有更多作品了~');
			setTimeout(function(){
				contentMore.hide();
			}, 1500);
		}
	}
	
	function getParam(){
		var search = location.search.substring(1),
			pairs = search.split('&');
		var args = {}, param = {};
		for(var i=0; i< pairs.length; i++){
			var pos = pairs[i].indexOf('=');
			if( pos == -1 ) continue;
			var arg = pairs[i].substring(0, pos);
			args[arg] = pairs[i].substring(pos+1);
		}
		return args;
	};
})();
