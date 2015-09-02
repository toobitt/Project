( function(){
	
	var myApp = new Framework7();
	
	function Main(){
		this.$panelLeft = $('.panel-left-box');
		this.$views = $('.main-body');
		this.$nav = this.$views.find('.navbar');
		this.$panelLeftBtn = this.$nav.find('.open-panel-btn');
		this.$gobackBtn = this.$nav.find('.go-back-btn');
		this.$closeBtn = this.$nav.find('.close-btn');
		this.$iframe = this.$views.find('#main-frame');
		this.init();
		this.initOptions();
	}
	
	$.extend( Main.prototype, {
		
		constructor : Main,
				
		initOptions : function(){
			var _this = this;
			this.options = {
				'bar' : _this.$panelLeftBtn,
				'back' : _this.$gobackBtn,
				'close' : _this.$closeBtn
			};
		},
		
		init : function(){
			//this.initFrameHeight();
			this.bindEvent();
			this.$panelLeft.find('.panel-left-menu:first').trigger('click');
		},
		
		bindEvent : function(){
			this.$panelLeft.on('click', '.panel-left-menu', $.proxy(this.switchMenu, this));
			this.$gobackBtn.on('click', $.proxy(this.goBack, this));
			this.$closeBtn.on('click', $.proxy(this.closePhoto, this));
		},
		
		switchMenu : function( event ){
			var self = $( event.currentTarget ),
				src = self.find('a').attr('href'),
				name = self.find('.name').text();
			//if( self.hasClass('active') ) return;
			myApp.showIndicator();
			this.setIframe( src );
			this.changeTitle( name );
		},
		
		goBack : function(){
			var contentViews = this.$iframe[0].contentWindow.$('body'),
				navbar = contentViews.find('.navbar-on-center'),
				goback_btn = navbar.find('a.back');
			goback_btn.trigger('click');
			var num = navbar.data('num');
			if( typeof num == 'number' && num > 2 ){
				this.switchNavStatus( 'back' );
			}else{
				this.switchNavStatus( 'bar' );
			}
		},
		
		closePhoto : function(){
			var contentViews = this.$iframe[0].contentWindow.$('body');
				close_btn = contentViews.find('a.photo-browser-close-link');
			close_btn.trigger('click');
			this.switchNavStatus( 'back' );
		},
		
		initFrameHeight : function(){
			var window_h = $(window).height(),
				nav_h = this.$nav.height();
			this.$iframe.height( window_h - nav_h  );
		},
		
		setIframe : function( src ){
			this.$iframe.attr('src',src);
			setTimeout(function() {
	            myApp.hideIndicator();
	        }, 1000);
		},
		
		changeTitle : function( name, type ){
			this.$nav.find('.center').text( name );
		},
		
		switchNavStatus : function( type ){
			var type = type ? type : 'bar',
				btn = this.options[type];
			btn.removeClass('hide');
			btn.siblings().addClass('hide');
		},
		
		geolocation: function (onSuccess,options) {
			this.onSuccess = onSuccess;
			this.op = options;
		    navigator.geolocation.getCurrentPosition(onSuccess, this.geolocationError.bind( this ), options );
		},
		
		geolocationError : function( error ){
			var _this = this;
			myApp.confirm('提示信息:' + error.message + ',是否重新定位？', '定位失败提醒', function () {
		        _this.geolocation( _this.onSuccess, _this.op );
		    });
		},
		
		locationTip: function (longitude, latitude) {
		    longitude && myApp.alert('获取当前位置成功','定位提醒');
		}
		
	} );
	
	window.mainStrap = new Main();
	
} )($);
