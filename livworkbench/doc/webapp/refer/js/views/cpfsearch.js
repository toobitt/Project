define(function( require, exports, modules ){
	var utils = require('utils/utils');
	require('toast');
	require('store');
	var options = {
		form : $('.searchForm'),
		search : $('.btn-area').find('.search'),
		config : {
			idcardNumber : '身份证号',
			password : '查询密码',
			code : '验证码',
		},
		pattern : {
			idcardNumber : /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
			password : /^\d{6}$/,
			code : /^\d{4}$/
		}
	};
	
	var id = utils.getParam('id');
	
	require('base64');
	options.base64 = new Base64(); 
	
	var curdata = store.get('cpf.cur', '');
	if( curdata ){
		curdata = JSON.parse( options.base64.decode( curdata ) );
		options.form.find('input[name="idcardNumber"]').val( curdata.idcardNumber );
	}

	options.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	options.search.on('click', function(){
		var $this = $(event.currentTarget),
			form = options.form.serializeArray();
		
		var error = doBefore( form );
		if( error ){
			options.toast.show( error );
			return false;
		}
		$.each(form, function( _, vv ){
			curdata[vv.name] = vv.value;
		});
		
		store.set('cpf.cur', options.base64.encode( JSON.stringify( curdata ) ));
		
		location.href = './cpfresult.html?_ddtarget=push&id=' + id;
	});
	
	function doBefore( form ){
		var errorTip = '';
		$.each(form, function( _, vv ){
			if( !vv.value ){
				errorTip = '请输入' + options.config[ vv.name ];
				return false;
			}
			if( options.pattern[vv.name] && !options.pattern[vv.name].test( vv.value ) ){
				errorTip = '请正确填写' + options.config[ vv.name ];
				return false;
			}
		});
		return errorTip;
	}
});