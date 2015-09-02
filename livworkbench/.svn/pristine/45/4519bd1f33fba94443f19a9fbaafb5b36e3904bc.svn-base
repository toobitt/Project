;(function($){
	var referInfo = {
		template : ''+
			'<ul>'+
				'<li class="refer-info-item refer-info-prev">'+
					'<div class="refer-info-img-box {{= cname}}">'+
						'<div class="middle-wrap refer-info-img-box">'+
							'<img src="{{= imgUrl}}"/>{{if cname}}<p class="prev-btn">点击预览</p>{{/if}}'+
						'</div>'+
					'</div>'+
				'</li>'+
				'<li class="refer-info-item-last">'+
					'<a class="button_6 open-refer-event">{{= buttonLabel}}</a>'+
				'</li>'+
			'</ul>'+
			'',
		css : ''+
			'.refer-info-item{border-bottom:1px solid #E7E7E7;padding:10px 7px;margin:0 10px;}'+
			'.refer-info-item .name{color:#9f9f9f;display:inline-block;width:60px;text-align:right;margin-right:5px;}'+
			'.refer-info-prev{text-align:center;}'+
			'.refer-info-item-last{text-align: center;padding:10px 0;}'+
			'.can-preview{cursor:pointer;}'+
			'',
		cssInited : false
	};
	$.widget('ueditor.referinfo', $.ueditor.baseWidget, {
		options : {
			title : '引用素材属性'
		},
		_create : function(){
			this._super();
		},
		_init : function(){
			this._super();
			this._on({
				'click .can-preview' : '_showSwf',
				'click .open-refer-event' : '_referChange'
			});
		},
		_referChange : function(){
			$.editorPlugin.get(this.editor, 'refer').refer('show');
		},
		_showSwf : function(){
			var swf = this._createSwf();
			$("<div>" + swf + "</div>").dialog({
				modal: true,
				dialogClass: 'swf-dialog',
				resizable: false,
				width: 430,
				height: 400,
				close: function () {
					$(this).dialog('destroy');
				}
			});
		},
		_createSwf : function(){
			var flashvars = "startTime=0&duration=227467&videoUrl=http://vfile1.dev.hogesoft.com/500x48/2013/02/1359683684581621.ssm/manifest.f4m&videoId=1821&snap=false&aspect=4:3&autoPlay=true&snapUrl=http://vapi1.dev.hogesoft.com:233/snap.php";
			
			flashvars = $.map(this.info, function (v, k) { return k + '=' + v; }).join('&') + '&autoPlay=true';
			return '<object type="application/x-shockwave-flash" data="' + RESOURCE_URL + 'swf/vodPlayer.swf?11122713" width="400" height="330">' +
			'<param name="movie" value="' + RESOURCE_URL + 'swf/vodPlayer.swf?11122713">' +
			'<param name="allowscriptaccess" value="always">' +
			'<param name="allowFullScreen" value="true">' +
			'<param name="wmode" value="transparent">' +
			'<param name="flashvars" value="' + flashvars + '">'+
	  		'</object>';
		},
		_getReferInfo : function(){
			var url = this.options.config['materialInfoUrl'],
				src = $(this.img).attr('src'),
				_this = this;
			$.getJSON( url, {'url':src}, function(data){
				if( !data ){
					data = [{
						type: 'error'
					}];
				}
				_this.info = data[0].flashvars;
				var type = data[0].type;
				var title = _this._getTitle( type );
				_this.title.text( title );
				_this._getContent(type, data[0]);
			});
		},
		_getTitle : function( type ){
			var titleLabel = '';
			switch(type) {
				case 'vod':
					titleLabel = '引用视频属性';
					break;
				case 'tuji':
					titleLabel = '引用图集属性';
					break;
				case 'vote':
					titleLabel = '引用投票属性';
					break;
				default:
					titleLabel = '出错了';
					break;
			}
			return titleLabel;
		},
		_getContent : function( type, json ){
			var data = {};
			switch(type) {
				case 'vod':
					data = {
						buttonLabel : '替换这个视频',
						cname : 'can-preview',
						attrs : {
							title: '标　题',
							time: '时　间',
							keywords: '关键字',
							sort_name: '分　类',
							size: '大　小',
							duration: '时　长'
						}
					};
					break;
				case 'tuji':
					data = {
						buttonLabel : '替换这个图集',
						attrs : {
							title: '标　题',
							time: '时　间',
							keywords: '关键字',
							sort_name: '分　类'
						}
					};
					break;
				case 'vote':
					data = {
						buttonLabel : '替换这个投票',
						attrs : {
							title: '标　题',
							create_time: '创建时间',
							start_time: '开始时间',
							end_time: '结束时间'
						}
					};
					break;
				default:
//					return '<p style="font-size:14px;text-align:center;color:red;">无法获取素材的信息！</p>';
			}
			data.imgUrl = json.img ? $.globalImgUrl(json.img,'160x120') : '';
			this._template( 'refer_info_tpl', referInfo, this.body.empty(), data );
			var content= '';
			for (var k in data.attrs) {
				content += '<li class="refer-info-item"><span class="name">' + data.attrs[k] + '：</span><span class="info">' + json[k] + '</span></li>';
			}
			$(content).insertBefore( this.element.find('.refer-info-item-last') );
		},
		
		_empty : function(){
            this.body.empty();
        },
		
		refresh : function( img ){
			this._empty();
			this.img = img;
			this._getReferInfo();
			if( !this.element.hasClass('pop-show') ){
            	this.show();
            }
		}
	});
})(jQuery);