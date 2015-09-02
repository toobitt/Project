seajs.config({
	base: baseUrl + "/static/js/",
    alias: {
        '$':'module/jquery/jquery.js',
        'jquery' : 'module/jquery/jquery.js',
        'jqueryui' : 'module/jquery-ui.js',
        'bootstrap' : 'module/bootstrap/bootstrap.js',
        'tip' : 'module/jquery/jquery.tip.js',
        'tmpl' : 'module/jquery/jquery.tmpl.min.js',
        'msg' : 'module/sco/sco.message.js',
        'chart' : 'chart/Chart.js',
        'ajax_new' : 'module/ajaxload_new.js',
        'highchart' : 'highchart/highcharts.js',
        'exporting' : 'highchart/exporting.js',
        'ajaxUpload' : 'uploadify/ajax_upload.js',
        'cookie' : 'module/jquery/jquery.cookie.js',
        'colorpicker' : 'colorpicker/colorpicker.min.js',
        'colorConfig' : 'colorpicker/hg_colorpicker.js',
        'switch' : 'module/bootstrap/bootstrap-switch.min.js',
        'jform' : 'module/jquery.form.js'
    },
    preload: ["jquery"]
})
