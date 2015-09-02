$(function(){
	var MC = $('body');
	var control = {
					
		init : function(){
			this.getUserInfo();
			var hrefData = $.getHrefinfo(),
				_this = this;
			var param = {
				id : hrefData.id,
				bundle_id : hrefData.bundle_id,
				a : 'detail'
			};
			$.doajax( null , 'rules.php', param, function( data ){
				var data = JSON.parse( data );
				if( data.message){
	    				$.tip( data.message );
	    				window.location.href="login_web.html";
	    				return;
	    			}
				 _this.initTrak( data.trak_list[0] , data.data_info );
				 MC.find('.cloud-section').append( data.content );
				 MC.find('.suoyin-box img').attr('src' , data.data_info.indexpic)
			});
			MC
			.on('click , touchstart' , '.list-aside-detail' , $.proxy(this.showLeft , this))
			.on('click , touchstart' , '.go-artical' , $.proxy(this.hideLeft , this))
		},
		
		getUserInfo : function(){
	    	   var url = 'rules.php';
	    	   $.doajax( null , url , {a : 'getUserInfo'} , function( json ){
	    		   var data = JSON.parse( json );
	    		   if( data.message){
	    				$.tip( data.message );
	    				window.location.href="login_web.html";
	    				return;
	    			}
	    		   var userInfo =  data[0];
	    		   if( userInfo ){
	    			   MC.find('.user-name').text( userInfo.user_name ).attr('title' , userInfo.user_name );
	    		   }
	    	   });
	    },
		
		showLeft : function(){
			MC.find('.cloud-aside').addClass('show');
			MC.find('.cloud-section').hide();
		},
		
		hideLeft : function(){
			MC.find('.cloud-aside').removeClass('show');
			MC.find('.cloud-section').show();
		},
		
		initTrak : function( list , info ){
			var html ='';
			$.each( list , function( k , v ){
				var user = v.user_name ? v.user_name : '',
					time = v.create_time ? v.create_time : '暂未处理',
					current = v.status ? 'current' : '',
					txt = (v.level == 1 ) ? '签发' : '审核';
				html += '<li class="sys-flex '+ current +'">'+
							 '<div class="video-list-item sys-flex-one">'+
								'<span>文稿第'+ v.level +'轮'+ user + txt + '</span>'+
								'<span>'+ time +'</span>'+
							 '</div>'+
						 '</li>';
			});
			MC.find('.video-list-content ul').html( html );
				
		}
	}
	control.init();
});
