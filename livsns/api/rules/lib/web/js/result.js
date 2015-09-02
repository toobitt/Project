define(function( require, exports, modules ){
	var utils = require('dist/utils'),
		tpl = require('utils/cpflist/template');
	require('store');
	var options = {
		panel : $('.refer-cpfresult').find('.panel'),
		nav : $('.refer-cpfresult').find('.tab-block ul')
	};
	require('base64');
	options.base64 = new Base64(); 
	
	utils.spinner.show();
	var year = options.nav.find('li.active').data('year');
	var curdata = store.get('cpf.cur', '');
	if( curdata ){
		curdata = JSON.parse(options.base64.decode( curdata ));
		console.log( curdata );
	}
	
	doAjax({ year : year });
	options.nav.on('click', 'li', function(){
		var $this = $(this);
		if( $this.hasClass('active') ){
			return false;
		}
		utils.spinner.show();
		var year = $this.data('year');
		$this.addClass('active').siblings().removeClass('active');
		options.panel.find('.panel-content').remove();
		doAjax({year : year});
	});
	
	function doAjax( param ){
		utils.getAjax('cpfresult', param, function( data ){
			var param = {};
			if( $.isArray( data ) && data[0] ){
				param.list = data;
				param.hasdata = true;
			}else{
				param.tip = data && data.ErrorText || '暂无公积金数据';
				param.hasdata = false;
			}
			var html = utils.render(tpl.panel, param);
			options.panel.append( html );
			utils.spinner.close();
		});
	}
});

define('utils/cpflist/template', function( require, exports, modules ){
	var tpl = {
		panel : '<div class="panel-content">' +
			'{{if hasdata}}' +
				'{{each list as value ii}}' +
				'<div class="panel-item hg-flex hg-flex-center">' +
					'<div class="date wid60">{{value.date}}</div>' +
					'<div class="project hg-flex-one">' +
						'<span class="type">{{value.type}}{{value.year}}</span>' +
						'<span class="company">{{value.company}}</span>' +
					'</div>' +
					'<div class="num wid60">{{value.num}}</div>' +
					'<div class="account primary wid100">{{value.account}}</div>' +
				'</div>' +
				'{{/each}}' +
			'{{else}}' +
				'<p class="nodata">{{tip}}</p>' +
			'{{/if}}' +
			'</div>',
	};
	modules.exports = tpl;
});