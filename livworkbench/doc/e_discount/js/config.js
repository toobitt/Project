define('config', function( require, exports, modules ){
	require('utils/print');
	var base = 'http://10.0.1.40/livsns/api/mobile/data/exzk/',
		baseOptions = {
		'discount_list' : 'index_list.php',		//今日推荐
		'team_detail' : 'productdetail.php',
		'eval_star' : 'pingjia.php',
		'foryou' : 'tuijian.php',
		'submit_order' : 'submit_order.php',
		'my_order' : 'myorder.php',
		'my_coupon' : 'my_coupon.php',
		'user' : 'user.php',
		'order_detail' : 'order_detail.php',
		'refund' : 'refund.php',
		'new_detail' : 'newproductdetail.php',
		'new_orderdetail' : 'new_orderdetail.php',
		'submit_comment' : 'submit_comment.php',
		'coupon_pj' : 'coupon_pj.php',
		'order_cancel' : 'order_cancel.php'
	}

	var getUrl = function( tpl ){			//url*连接
		return base + baseOptions[ tpl ];
	}
	
	modules.exports = getUrl;
});

seajs.config({
	base: "./js",
	debug : 2,
    alias: {
        '$':'sea-modules/zepto/zepto.min',
        'zepto' :'sea-modules/zepto/zepto.min',
        'framework7' : 'module/framework7.custom.min',
        'spin' : 'module/spin.min',
        'lazyload' : 'module/lazyload'
    },
    preload : ['zepto'],
    map: [
	   // ['.js', '.js?' + new Date().getTime() ]
	  ]
});
