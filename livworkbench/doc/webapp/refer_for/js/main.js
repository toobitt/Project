seajs.config({
	base: "./js",
	debug : 2,
    alias: {
        '$':'module/zepto.min',
        'zepto' :'module/zepto.min',
        'spin' : 'module/spin.min',
        'toast' : 'utils/toast.min',
        'store' : 'module/store.min',
        'base64' : 'module/base64',
        'print' : 'utils/print.min',
        'h5Client' : 'utils/h5Client.min'
    },
    preload : ['zepto'],
    map: [
	    // ['.js', '.js?' + new Date().getTime() ]
	  ]
});
