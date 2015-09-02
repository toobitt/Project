$(function(){
	(function($){
		var plugInfo = {
			template : ''+
//						'<div class="share-wrap">' +
							'<div class="share-box">' +
								'<header>' +
									'<span>分享</span>' +
									'<span class="share-title">{{= title}}</span>' +
									'<a class="close"></a>' +
								'</header>' +
								'<div class="btns-area">' +
									'<a class="add-account">添加新账号</a>' +
								'</div>' +
								'<div class="share-info">' +
									'<p class="word-count"><span class="flag">还可以输入</span><span class="num">140</span>个字</p>' +
									'<textarea class="word"></textarea>' +
									'<div class="media">' +
										'<ul class="clear">' +
											'<li>' +
												'<a>' +
													'<img src="http://img.dev.hogesoft.com:233/material/vote/img/78x52/2013/10/20131022141509Pqwx.jpg">' +
												'</a>' +
											'</li>' +
										'</ul>' +
									'</div>' +
									'<div class="handlers">' +
										'<a class="share-btn">立即分享</a>' +
									'</div>' +
								'</div>' +
							'</div>' +
//						'</div>' +
						'',
			css : '' +
				'.share-wrap{z-index:99999;top:-1000px;position:absolute;-webkit-transition:top 0.5s ease 0s;-moz-transition:top 0.5s ease 0s;-o-transition:top 0.5s ease 0s;transition:top 0.5s ease 0s;left:50%;margin-left:-350px;}' +
				'.box-show{top:65px;}' +
				'.share-box{background:#6ea5e8;padding:0 10px 10px 10px;width:700px;}' +
				'.share-box header{color:white;height:45px;line-height:45px;}' +
				'.share-box header span{font-size:20px;}' +
				'.share-box header .share-title{font-size:14px;}' +
				'.share-box .close{height:26px;width:26px;float:right;margin-top:9px;background:url('+ RESOURCE_URL +'share/close.png) no-repeat center #f3f3f3;border-radius:2px;}' +
				'.share-box .btns-area{background:white;padding:15px;}' +
				'.share-box .btns-area a{display:inline-block;height:30px;line-height:32px;padding-right:10px;border:1px solid #DFDFDF;color:#656565;font-size:14px;text-indent:28px;background:url('+ RESOURCE_URL +'share/add.png) no-repeat 10px center #efefef;cursor:pointer;}' +
				'.share-box .share-info{background:#eee;border:1px solid #DFDFDF;}' +
				'.share-box .word-count{height:36px;text-align:right;padding:10px 18px;}' +
				'.share-box .word-count .num{font-family:Constantia, Georgia;font-size:24px;}' +
				'.share-box textarea{width:650px;border:1px solid #ddd;height:100px;padding:10px;margin:0 13px;}' +
				'.share-box textarea:hover,.share-box textarea:focus{box-shadow:none;}' +
				'.share-box .media{margin:10px 0 8px 15px;}' +
				'.share-box .media li{float:left;width:78px;height:52px;box-shadow:0 0 0 1px #dedede;margin-right:8px;}' +
				'.share-box .media li:hover,.share-box .media li.selected{box-shadow:0 0 0 2px #6ea5e8;}' +
				'.share-box .handlers{padding:10px 15px 15px;}' +
				'.share-box .handlers a{display:inline-block;cursor:pointer;height:32px;line-height:32px;}' +
				'.share-box .share-btn{background:#6ea5e8;color:white;padding:0 10px;border-radius:2px;font-size:14px;}' +
				''
		};
		$.widget('m2o.share',{
			options : {
				maxLen : 140,
			},
			_init : function(){
				$( plugInfo.template ).appendTo(this.element);
				this._addCss( plugInfo.css );
				this._on({
					'click .close' : '_close',
					'click .share-btn' : '_share',
					'keyup .word' : '_calculate',
					'click .media li' : '_selectMedia'
				});
				this.element.addClass('box-show');
			},
			_addCss : function(css){
	            $('<style />').attr('style', 'text/css').appendTo('head').html(css);
	        },
			_close : function(){
				this.element.removeClass('box-show');
			},
			_share : function( event ){
				var self = $(event.currentTarget);
				$.globalAjax(self,function(){
		    		var	url = './run.php?mid=58&a=share';
		    		return $.getJSON(url,function( json ){
		    			alert(' ');
		    		});
		    	});
			},
			_calculate : function( event ){
				var self = $(event.currentTarget),
					words = self.val(),
					count = words.length,
					available = this.options.maxLen,
					flag = this.element.find('.flag'),
					num = this.element.find('.num');
				var chi = words.replace(/[A-z]|[0-9]/g,''),
					eng = words.replace(/[^A-z|0-9]/g,'');
				var chiLen = chi.length,
					engLen = Math.ceil(eng.length / 2);
				available -= (chiLen + engLen);
				num.text(available);
				if( available < 0 ){
					var over = chiLen + engLen - this.options.maxLen;
					flag.text('已经超过');
					num.css('color','#E44443').text(over);
				}else{
					flag.text('还可以输入');
				}
				
			},
			_selectMedia : function( event ){
				var self = $(event.currentTarget);
				self.toggleClass('selected');
			},
			refresh : function(){
				this.element.addClass('box-show');
			},
		});
		$.share = function( option ){
	    	var className = option.className,
	    		data = option.data,
	    		refresh = option.refresh || 'refresh';
	    	var el = $( '.' + className );
			if( el.length ){
				el.share( refresh, data );
				return;
			}
			$('<div class="share-wrap"></div>').appendTo('body').share( option );
	    };
	})($)
})