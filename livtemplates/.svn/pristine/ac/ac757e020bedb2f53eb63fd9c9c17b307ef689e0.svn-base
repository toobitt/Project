$(function(){
	var myPublish = {
		config : function( setting ){
			myPublish.config = {
				$btn : $('.publish-button'),
				$box : $('.common-form-pop'),
				$close : $('.publish-box-close'),
				$hiddenid : $('.publish-name-hidden'),
				$area : $('.publish-box'),
				$time : $('.extend-item')
			};
			myPublish.param = {
				change: function(){
					return myPublish.callback();
				},
				getUrl :  function(){
					return 'fetch_column.php?siteid=1&fid=0';
				},
				maxColumn: 3,
			};
			$.extend(myPublish.param, setting);
			myPublish.init();
			myPublish.setup();
		},
		
		init : function(){
			myPublish.config.$area.hg_publish(myPublish.param);
			myPublish.config.$time.hide();
		},
		
		setup : function(){
			myPublish.config.$btn.click(myPublish.togglePop);
			myPublish.config.$close.click(myPublish.closePop);
		},
	
		closePop : function(){
			myPublish.config.$btn.data('show', false);
			myPublish.config.$box.css({top: -450, left:6, margin:0});
		},
		
		togglePop : function(){
			var $this = $(this),
				$box = myPublish.config.$box;
			if($this.data('show')){
				$this.data('show', false);
				$box.css({top:-450, left:6, margin:0});
			}else{
				$this.data('show', true);
				$box.css({top:116, left:6, margin:0});
			}
		},
		
		callback : function(){
			myPublish.config.$btn.html(myPublish.callcontent)
		},
		
		callcontent : function(){
			var config = myPublish.config;
			var hidden = config.$box.find( config.$hiddenid ).val(),
				$btn = config.$btn;
			if(config.$box.data('init')){
				return hidden ? ($btn.attr('_prev') + '：<span style="color:#000;">' + hidden + '</span>') : $btn.attr('_default');
			}else{
				config.$box.data('init', true);
				return;
			}
		}
	}
	myPublish.config();
});
