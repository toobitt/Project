(function(){
	var app = new Framework7(),
		$$ = Dom7;
	app.showIndicator();
	var param = {
		box : $$('.content-greeting'),
		form : $$('.popup-form'),
		formname : '#greeting-form',
		listurl : getUrl('list'),
		formurl : getUrl('form'),
		posturl : getUrl('post'),
		appkey : 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7',
		appid : '55'
	}
	var greeting_list = (function(){
		var ajaxGreeting = {
			ajaxList : function(){
				param.listurl += ( '?appkey=' + param.appkey + '&appid=' + param.appid );
				app.get(param.listurl, null, true, function( data ){
					app.hideIndicator();
					var data = JSON.parse( data );
					data.concat( data );
					if( $$.isArray( data ) && data[0] ){
						for ( var prop in data ) {
							if( data[prop]['indexpic'] && data[prop]['indexpic']['filename'] ){
								data[prop]['pic'] = app.myuitls.createImgsrc( data[prop]['indexpic'] );
							}
						}
					}
					var html = app.myuitls.render( '#list', {
						items : data
					} );
					param.box.append( html );
					bindEvent( 'list' );
				});
			},
			ajaxForm : function( id ){
				param.formurl += ( '?appkey=' + param.appkey + '&appid=' + param.appid + '&id=' + id );
				app.get(param.formurl, null, true, function( data ){
					var data = JSON.parse( data );
					if( $$.isArray( data ) && data[0] ){
						data = data[0];
						var form = data['form'];
						for ( var prop in form ) {
							if( form[prop]['mode_type'] ){
								form[prop][ form[prop]['mode_type'] ] = true;
							}
						}
						data['action'] = getUrl('post') + ( '?appkey=' + param.appkey + '&appid=' + param.appid );
						data['id'] = id
						html = app.myuitls.render( '#modal', data );
						param.form.find('.list-block').remove();
						param.form.append( html );
						bindEvent( 'form' );
					}
				});
			},
			PostForm : function( dom ){
				var url = param.posturl + ( '?appkey=' + param.appkey + '&appid=' + param.appid ),
					formData = app.formToJSON( param.formname );
				if( dom[0].disabled ){
					return;
				}
				dom[0].disabled = true;
				var liLimited = false;
				for( var prop in formData ){
					if( !formData[prop] ){
						liLimited = true;
					}
				}
				if( liLimited ){
					app.myuitls.showAlert(app, {
						title : '请填写完整信息',
						delay : 1500
					});
					dom[0].disabled = false;
					return;
				}
				app.showIndicator();
				$$.ajax({
					url : url,
					method : 'POST',
					data : formData,
					complete : function( xhr ){
						dom[0].disabled = false;
						app.hideIndicator();
					},
					success : function(responseData, status, xhr){
						if( status == 200 ){
							console.log( responseData );
							app.closeModal('.popup-form');
						}
					},
					error : function( xhr ){
						dom[0].disabled = false;
						app.hideIndicator();
					}
				});
				var id = param.form.attr('attr'),
					currentItem = param.box.find('.item[_id="' + id + '"]');
				var info = {
						content : '',
						content_url : '',
						pic : currentItem.find('.img-box img').attr('src')
					};
				console.log( app.device.android );
			}
		}
		
		function bindEvent( type ){
			if( type == 'list' ){
				param.box.on('click', 'li', function( e ){
					var id = $$(this).attr('_id');
					ajaxGreeting.ajaxForm( id );
					app.popup('.popup-form');
					param.form.attr('attr', id);
				});
			}else if(  type == 'form'  ){
				param.form.on('click', '.form-to-json', function( e ){
					var self = $$(this);
					ajaxGreeting.PostForm( self );
				});
			}
		}
		
		
		return ajaxGreeting.ajaxList;
	})()
	window.greeting_list = greeting_list;
})();
