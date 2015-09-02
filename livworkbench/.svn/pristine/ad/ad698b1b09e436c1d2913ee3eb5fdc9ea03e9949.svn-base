$(function(){
	var live_ajax = (function(){
		var urlBase = {
			appkey : 'appkey=d0WTCC30fX1FRwUD5XYtjKLTQtnE8Kwb',
			appid : 'appid=28',
			url : 'http://fapi.wifiwx.com/mobile/api/cmc/'
		};
		var config = {
			tabPage : '',
			tvlistPage : '',
			tvdetailPage : '',
			caselistPage : '',
			casedetailPage : ''
		};
		var _this = this;
		this.tabnav = $('.subnav');
		this.tabselect = $('.magic-line');
		this.tvlist = $('#thelist li');
		this.inner = $('.wrap-inner');
		this.wrapper = $('#wrapper');
		this.detailbox = $('.detail-list');
		this.tabbar = $('.tabbar');
		
		this.tvlist.on('click', '.live-flag', function( e ){
			var self = $(this);
			var op = _this.options;
			var channel_id = $(this).closest('li').data('id');
			var leftdom = [_this.inner, _this.wrapper, _this.tabbar]
			$.slideBox( 'left', leftdom );
			op.tvdetailPage && getAjax( 'program', op.programPage, '&channel_id=' + channel_id );
		});
		
		this.tabbar.on('click', '.playlist', function(e){
			var wrap = $('.playlist-wrap');
			wrap.toggleClass('show');
			if(wrap.hasClass('show')){
				wrap.find('.magic-line').css('opacity', 1);
			}
		});
		
		this.detailbox.on('click', '.list-item', function(){
			var $this = $(this),
				index = $this.index();
			if($this.hasClass('live')){
				$this.addClass('current').siblings().removeClass('current');
			}
			$('.playlist-wrap').removeClass('show');
		});
		
		var judgeAjax = function( options ){
			options.tabPage && getAjax( 'tab', options.tabPage );
		},
		getAjax = function( type, page, key ){
			//if(type == 'tvlist'){
				//var urlInfo ='http://fapi.wifiwx.com/mobile/api/wifiwx2.0/channel.php?appkey=4fJrS03Ergz9KE1ztJ5vNmrnmZgt0moU&appid=20&node_id=1';
			//}else{
				var urlInfo = getUrl( page, key );
			//}
			console.log( urlInfo );
			$.ajax({
				type : 'get',
				url : urlInfo,
				dataType : 'jsonp',
				jsonp : 'callback',
				success : function( data ){
					callBack(type, data);
				},
				error : function( data ){
					console.log(data);
				}
			});
		},
		callBack = function( type, data ){
			switch( type ){
				case 'tab': {
					getTabdata( data );
					break;
				}
				case 'tvdetail' : {
					getDetaildata( data[0] );
					break;
				}
				case 'tvlist' : {
					getListdata( data );
					break;
				}
				case 'program' : {
					getProgram( data );
					break;
				}
			}
		},
		
		showItem = function(){
			var self = $(this),
				index = self.index()
			node_id = self.attr('_id');
			$.tabSelect( index, self.closest('.subnav') );
			//options.tvlistPage && getAjax( 'tvlist', options.tvlistPage, 'node_id=' + node_id );
		},
		getTabdata = function( data ){
			var box = $('.live-list').find('.subnav');
			$('#tabLive-tpl').tmpl( data ).prependTo( box );
			box.find('.magic-line').css('opacity', 1);
			this.tabnav.find('.item').click( showItem );
			$.tabSelect(0, box);
			options.tvlistPage && getAjax( 'tvlist', options.tvlistPage, 'node_id=1' );
		},
		
		getListdata = function( data ){
			console.log(data);
		},
		
		getDetaildata = function( data ){
			if( data.cur_program ){
				data.cur_program.noon = 'cur';
				data.cur_program.start_time = '直播中';
				$('#tvdetailLive-tpl').tmpl( data.cur_program ).appendTo( this.detailbox );
			}
			if( data.next_program ){
				$('#tvdetailLive-tpl').tmpl( data.next_program ).appendTo( this.detailbox );
			}
			this.detailbox.on('click', '.list-item', chooseVedio);
		},
		
		getProgram = function( data ){
			if( data ){
				$('#tvdetailLive-tpl').tmpl( data ).appendTo( this.detailbox );
				this.detailbox.find('.list-item').eq(0).addClass('current');
				this.detailbox.find('.list-item').eq(data.length).addClass('current');
				var weekday = data[0]['weeks'];
				var index = weekday%7;
				$.tabSelect(index-1, $('.playlist-wrap'));
			}
		},
		
		chooseVedio = function(){
			var self = $(this),
				box = self.closest('.detail-list');
				self.addClass('current').siblings().removeClass('current');
				self.find('.live-time').html('正在播放');
		},
		
		getUrl = function( page, key ){
			return urlBase.url + page + '?' + urlBase.appkey + '&' + urlBase.appid + (key ? ('&' + key) : '');
		},
		ajaxOptions = function( options ){
			_this.options = options = $.extend({}, config, options);
			judgeAjax( options );
		};
		return {
			ajaxOptions : ajaxOptions,
		};
	})();
	live_ajax.ajaxOptions({
		tabPage : 'channel_node.php',
		tvlistPage : 'channel.php',
		tvdetailPage : 'channel_detail.php',
		programPage : 'program.php'
	});
	// $('.live').hg_slide({
		// slide : true
	// });
	
});
