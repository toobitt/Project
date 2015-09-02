function ReferInfoEvent(number, slide) {
    this.number = number;
    this.slide = slide;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.content = null;
    this.init();
}


jQuery.extend(ReferInfoEvent.prototype, {
	init: function() {
		var self = this,
			content;
		content = '<div id="edit-slide-refer-info' + this.number +'" class="edit-slide-html-each">' +
				'<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>引用素材属性</div>' +
				'<div class="edit-slide-content edit-slide-refer-info-content"></div>' +
				'</div>';
		this.slide.html( content );
		this.box = $('#edit-slide-refer-info' + this.number).parent();
		this.content = this.box.find('.edit-slide-content');
		this.waitingImg = '<img class="waiting-img" src="' + RESOURCE_URL + 'loading2.gif"/>';
		this.slide.addInitFunc( this.getUpdateBoxFunc() );
		this.box.on('click', '.open-refer-event', function() {
			var func = 'EditorRefer' + self.number;
			window[func]();
		});
		this.box.on("click", '.can-preview', function () {
			var swf = self.createSwf();
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
		});
	},
	//startTime={$formdata['start']}&duration={$formdata['duration']}&videoUrl={$formdata['video_url']}&videoId={$formdata['id']}&snap=false&aspect={$formdata['aspect']}&autoPlay=false&snapUrl={$formdata['snapUrl']}
	createSwf: function () {
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
	getUpdateBoxFunc: function() {
		var self = this, 
			index = 0;//用于为ajax请求分配id
		return function( data ) {
			if(data == self.data) {
				return;
			}
			self.data = data;
			var url, 
				me = self,
				ajaxId = ++index;

			me.content.empty().append(me.waitingImg);
			url = gUrl['referInfo'] + '&url=' + data;
			$.get(url, function(data) {
				if( ajaxId != index ) {
					return; //说明当前内容改变了,ajax请求已过期
				}
				self.info = data[0].flashvars;
				me.updateBoxWith(data);
			}, 'json');
		}
	},
	updateBoxWith: function(data) {
		//this.content.find( '.waiting-img' ).remove();
		var title, con;
		if (!data) {
			//jAlert('此素材已被删除!');
			data = [{
				type: 'error'
			}];
			window['globalSlideDeleteHtml' + this.number]('refer', this.data);
		} 
		var type = data[0].type;
		this.box.find('.edit-slide-title').replaceWith( this.getTitleFor(type) );
		this.content.html( this.getContentFor(type, data[0]) );
	},
	getTitleFor: function(type) {
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
		return '<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>' + titleLabel + '</div>';
	},
	getContentFor: function(type, data) {
		var content = '', buttonLabel = '', cname;
		switch(type) {
			case 'vod':
				buttonLabel = '替换这个视频';
				cname = 'can-preview';
				attrs = {
					title: '标　题:',
					time: '时　间:',
					keywords: '关键字:',
					sort_name: '分　类:',
					size: '大　小:',
					duration: '时　长:'
				};
				break;
			case 'tuji':
				buttonLabel = '替换这个图集';
				attrs = {
					title: '标　题:',
					time: '时　间:',
					keywords: '关键字:',
					sort_name: '分　类:'
				};
				break;
			case 'vote':
				buttonLabel = '替换这个投票';
				attrs = {
					title: '标　　题:',
					create_time: '创建时间:',
					start_time: '开始时间:',
					end_time: '结束时间:'
				};
				break;
			default:
				return '<p style="font-size:14px;text-align:center;color:red;">无发获取素材的信息！</p>';
		}
        var imgUrl = $.globalImgUrl(data.img);
		content = '<ul><li><div class="refer-info-img-box ' + cname + '"><div class="middle-wrap refer-info-img-box"><img src="' + imgUrl + '"/>' + (cname ? '点击预览' : '') + '</div></div></li>';
		for (var k in attrs) {
			content += '<li><label>' + attrs[k] + '</label><span>' + data[k] + '</span></li>';
		}
		content += '<li class="refer-info-item-last"><a class="button_6 open-refer-event">' + buttonLabel + '</a></li></ul>';
		return content;
	}
});