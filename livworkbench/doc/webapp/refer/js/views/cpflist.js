define(function( require, exports, modules ){
	var utils = require('utils/utils'),
		tpl = require('utils/cpflist/template');
	require('toast');
	var options = {
		box : $('.refer-cpflist'),
		list : $('.refer-cpflist').find('.list-account ul'),
		btn : $('.btn-account'),
		account : $('.account-block'),
		form : $('.accountForm'),
		config : {
			name : '姓名',
			idcardNumber : '身份证号'
		},
		pattern : {
			idcardNumber : /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
			name : /^[\u4e00-\u9fa5]{1,}$/,
		}
	};
	
	utils.spinner.show();
	require('store');
	require('base64');
	options.base64 = new Base64(); 
	
	options.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	var cpflist = store.get('cpf.list', '');
	if( cpflist ){
		cpflist = JSON.parse(options.base64.decode( cpflist ));
		ajaxData( cpflist );
	}else{
		utils.getAjax('cpflist', null, function( data ){
			if( $.isArray( data ) && data[0] ){
				cpflist = data;
				ajaxData( data );
				store.set('cpf.list', options.base64.encode( JSON.stringify( data ) ));
			}else{
				var html = utils.render(tpl.tmpl, {
						tip : data && data.ErrorText || '暂无添加账户',
						hasdata : false
					});
				options.list.after( html );
				options.box.show();
				utils.spinner.close();
			}
		});
	}
	
	function ajaxData( data ){
		var html = utils.render(tpl.tmpl, {
			list : data,
			hasdata : true
		});
		options.list.append( html );
		options.box.show();
		bind();
		utils.spinner.close();
	}
	
	function bind(){
		options.list.on('click', '.outlink', function( event ){
			//event.preventDefault();
			var $this = $(this);
			utils.find(cpflist, 'userId', $this.data('id'), function( item ){
				store.set('cpf.cur', options.base64.encode( JSON.stringify( item ) ));
			});
		});
	}
	
	$('.btn-account').on('click', function(){
		var $this = $(this);
		if( $this.data('type') == 'add' ){
			options.account.show();
			$this.data('type', 'form');
			$this.html('确认添加');
		}else{
			addForm( $this );
		}
	});
	
	function addForm( dom ){
		var form = options.form.serializeArray();
		var error = doBefore( form );
		if( error ){
			options.toast.show( error );
			return false;
		}
		utils.spinner.show( dom );
		
		
		setTimeout(function(){
			var newValue = {
				fullName : 'Cary',
				number : 300,
				userId : 20,
				idcardNumber : "436284198942039944"
			};
			cpflist.push( newValue );
			store.set('cpf.list', options.base64.encode( JSON.stringify( cpflist ) ));
			utils.spinner.close();
			options.toast.show( '添加账户成功', function(){
				var html = utils.render(tpl.item, {
					value : newValue
				});
				options.list.append( html );
				options.account.hide();
				dom.data('type', 'add');
				dom.html('添加账户');
			});
		}, 2000);
	}
	
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

define('utils/cpflist/template', function( require, exports, modules ){
	var tpl = {
		tmpl : '' +
			'{{if hasdata}}' +
				'{{each list as value ii}}' +
					'<li class="hg-flex hg-flex-center">' +
						'<div class="title">{{value.fullName}}</div>' +
						'<div class="number hg-flex-one">月缴：{{value.number}}元</div>' +
						'{{if value.userId}}<a class="outlink" href="./cpfsearch.html?_ddtarget=push&id={{value.userId}}" data-id="{{value.userId}}"></a>{{/if}}' +
					'</li>' +
				'{{/each}}' +
			'{{else}}' +
				'<p class="nodata">{{tip}}</p>' +
			'{{/if}}' +
			'',
		item : '' + 
			'<li class="hg-flex hg-flex-center">' +
				'<div class="title">{{value.fullName}}</div>' +
				'<div class="number hg-flex-one">月缴：{{value.number}}元</div>' +
				'{{if value.userId}}<a class="outlink" href="./cpfsearch.html?_ddtarget=push&id={{value.userId}}" data-id="{{value.userId}}"></a>{{/if}}' +
			'</li>' +
			''
	}
	modules.exports = tpl;
});