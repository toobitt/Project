$(function(){
	
	var MC = $('body');
	
	MC.on('click' , '.btn-submit' , function( event ){
		var target = $( event.currentTarget ),
			user_name = MC.find('input[name="username"]').val(),
			pwd = MC.find('input[name="password"]').val();
		var tip = '';
		if( !pwd ){
			tip = '密码不能为空';
		}
		if( !user_name ){
			tip = '用户名不能为空';
		}
		if( tip ){
			$.tip( MC.find('.login-box') , tip );
			return;
		}
		var param = {
				username : $.trim( user_name ),
				password : $.trim( pwd )
		};
		
		var url = 'login.php?a=dologin';
		$.doajax( target , url , param , function( data ){
			var data = JSON.parse( data );
			if( !data.error ){
				$.tip( MC.find('.login-box') , data.message );
				return;
			}else{
				$.tip( MC.find('.login-box') , '登录成功' );
				setTimeout(function(){
					window.location.href="list_web.html";
				},1000);
			}
		});
	})
})