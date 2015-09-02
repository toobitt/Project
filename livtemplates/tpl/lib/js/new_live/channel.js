function hg_stream_operate(obj, type)
{
	var html = $('#tmp_stream_box').html();
	if (!html)
	{
		return;
	}
	
	if (type)
	{
		$('#stream_box').append(html);
		
	}
	else
	{
		$(obj).parent().remove();
		if($(obj).parent().find('input[name^="is_default"][checked]').val())
		{
			$('#stream_box div').first().find('input[name^="is_default"]').attr("checked",'checked');
		}
	}
	
	var stream_count = $('#stream_box div').length;
	
	if (stream_count > 1)
	{
		$('#stream_box div').first().find('span[name^="delete_submit[]"]').show();
	}
	else
	{
		$('#stream_box div').first().find('span[name^="delete_submit[]"]').hide();
	}
	$('#stream_box div').first().find('span[name^="is_default"]').val(stream_count);
	
	$('#stream_box div').each(function(i){
		$(this).find('span[name^="delete_submit[]"]').removeClass('display');
		$(this).find('input[name^="is_default"]').val(i);
	});
	
	hg_resize_nodeFrame();
}

function hg_is_delay(obj)
{
	if ($(obj).attr('checked') == 'checked')
	{
		$('#delay_box').show();
		$('input[name^="delay"]').removeAttr('disabled');
	}
	else
	{
		$('#delay_box').hide();
		$('input[name^="delay"]').attr('disabled', 'disabled');
	}
	hg_resize_nodeFrame();
}

var gChannelId = $('#channel_id').val();

/*
	设置音频台标
*/
function hg_set_logo_audio(obj)
{
	if ($(obj).attr('checked') == 'checked')
	{
		$('#logo_audio_box').show();
		$('input[name^="logo_audio"]').removeAttr('disabled');
	}
	else
	{
		$('#logo_audio_box').hide();
		$('input[name^="logo_audio"]').attr('disabled', 'disabled');
	}
	hg_resize_nodeFrame();
}
	
$(function(){
	return;
	$('#client').click(function(){
		hg_get_auth_info();
	});
});
function hg_get_stream_count()
{
	var server_id = $('#server_id').val();
	var url = "run.php?mid=" + gMid + "&a=get_stream_count&server_id=" + server_id + "&channel_id=" + gChannelId;
	hg_ajax_post(url,'','','get_stream_count_callback');
}

function get_stream_count_callback(obj)
{
	var obj = obj[0];
	var over_count = obj['over_count'];
	var type = obj['type'];
	if (over_count <= 0)
	{
		$('#sub').attr('disabled', 'disabled');
	}
	else
	{
		$('#sub').removeAttr('disabled');
	}
	
	if (type == 'tvie')
	{
		$('input[name="is_push"]').attr('disabled', 'disabled');
		$('input[name="is_control"]').attr('disabled', 'disabled');
		$('input[name="is_delay"]').attr('disabled', 'disabled');
		$('input[name="delay"]').attr('disabled', 'disabled');
	}
	else
	{
		if (!gChannelId)
		{
			$('input[name="is_push"]').removeAttr('disabled');
		}
		$('input[name="is_control"]').removeAttr('disabled');
		$('input[name="is_delay"]').removeAttr('disabled');
		$('input[name="delay"]').removeAttr('disabled');
	}
	
	$('#over_count').html('剩余 ' + over_count + ' 条');
}

function hg_get_auth_info()
{
	var offset = 0;
	var counts = 50;
	var length = $('#client_con').find('ul li').length;
	var total  = $('#client_con').find('ul li').attr('_total');
	
	if (length)
	{
		offset = length;
		if (length == total)
		{
			$('#client').html('');
			return;
		}
	}
	
	var url = './run.php?mid=' + gMid + '&a=get_auth_info&offset=' + offset + '&counts=' + counts;
	hg_ajax_post(url);
}

function get_auth_info_callback(html)
{
	$('#client_box').show();
	var length = $('#client_con').find('ul li').length;
	var total  = $('#client_con').find('ul li').attr('_total');
	
	
	if (length && total)
	{
		if (length == total)
		{
			return;
		}
	}
	
	$('#client_con ul').append(html);
	
	$('#client').html('更多客户端');
	$('#client_con ul li a').each(function(){
		if (!$(this).attr('_flag'))
		{
			$(this).click(function(){
				var _appid = $(this).attr('_appid');
				var _appname = $(this).attr('_appname');
				hg_set_client_logo(_appid, _appname);
			});
		}
		
		$(this).attr('_flag', '1');
	});
	hg_resize_nodeFrame();
}

function hg_set_client_logo(_appid, _appname)
{
	var flag = 0;

	$('#client_logo div').each(function(){
		if ($(this).find('input[name^="_appid"]').val() == _appid)
		{
			flag = 1;
		}
	});
	if (flag)
	{
		$('#client_alert').html('').fadeIn(3000);
		$('#client_alert').html(_appname + '已添加').fadeOut(3000);
		
		return false;
	}
	var html = '<div class="client_logo_box">';
		html+= '<span class="client_name">' + _appname + '：</span>';
		html+= '<span class="file_input s" style="float:left;">选择文件</span>';
		html+= '<input name="client_logo[' + _appid + ']" type="file" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />';
		html+= '<input type="hidden" name="_appid[' + _appid + ']" value="' + _appid + '" />';
		html+= '<input type="hidden" name="_appname[' + _appid + ']" value="' + _appname + '" />';
		html+= '<span class="s_right" onclick="hg_unset_client_logo(this);" style="display: inline;"></span>';
		html+= '</div>';
		
	$('#client_logo').append(html);
	hg_resize_nodeFrame();
}

function hg_unset_client_logo(obj)
{
	$(obj).parent().remove();
	hg_resize_nodeFrame();
}

(function() {
	
var tpl_logo = 
		'<div class="client_logo_item" id="client_logo_item{{appid}}" data-appid="{{appid}}"> \
			<p><img width="40" height="40" src={{src}} /></p> \
			<p>{{appname}}</p> \
			<span class="client_logo_delete">x</span> \
			<input type="hidden" name="_appid[{{appid}}]" value="{{appid}}" /> \
			<input type="hidden" name="_appname[{{appid}}]" value="{{appname}}" /> \
		</div>';

var tpl_client = 
		'<div class="overflow" data-appid="{{appid}}" data-custom_name="{{custom_name}}"> \
			<span class="client_name">{{custom_name}}</span> \
			<span class="client_setting">设置台标</span>\
		</div>';
		
function replaceTpl(tpl, data) {
	return tpl.replace(/{{(.+?)}}/g, function(all, match) {
		return data[match] || '';
	});
}

var LogoList = {
	init: function(options) {
		this.options = options || {};
		
		this.alreadyLogos = options.logos || {};
		this.info = [];
		this.offset = 0;
		this.count = 50;
		
		this._ensureElement();
		this.bindEvents();
	},
	_ensureElement: function() {
		this.$el = $(this.options.el || '<div></div>');
		this._resetFileElement();
	},
	_resetFileElement: function() {
		this.fileElement = $('<input type="file" />').appendTo('body').hide();
		this.fileElement.on('change', $.proxy(this._change, this));
	},
	refreshLogo: function(logoInfo) {
		var appid = logoInfo.appid;
		if ( $("#client_logo_item" + appid).size() ) {
			this.updateLogo(logoInfo);
		} else {
			this.addLogo(logoInfo);
		}
		this.alreadyLogos[logoInfo.appid] = true;
		this.renderClientList();
		this.adjustPointer();
		$("#client_logo_item" + appid).find('input:file').remove();
		$("#client_logo_item" + appid)
			.append( this.fileElement.attr('name', 'client_logo[' + logoInfo.appid + ']') );
		this._resetFileElement();
	},
	updateLogo: function(logoInfo) {
		$("#client_logo_item" + logoInfo.appid).find('img').attr('src', logoInfo.src);
	},
	addLogo: function(logoInfo) {
		var html = $(this.template_logo(logoInfo));
		this.$('.client_logo_item_add').before(html);
		html.addClass('yellow');
		setTimeout(function() {
			html.removeClass('yellow');
		}, 1000);
	},
	removeLogo: function(appid) {
		$('#client_logo_item' + appid).remove();
		this.alreadyLogos[appid] = false;
		this.renderClientList();
		this.adjustPointer();
	},
	template_logo: function(logoInfo) {
		return replaceTpl(tpl_logo, logoInfo);
	},
	loadClientList: function() {
		if (this.loading) return;
		this.loading = true;
		$.getJSON('run.php', {
			mid: gMid,
			a: 'get_auth_info',
			counts: this.count,
			offset: this.offset
		}).done($.proxy(function(data) {
			data = data[0];
			this.hasLoadedOnce = true;
			this.total = data.total;
			this.info = this.info.concat(data.info);
			this.offset += 10;
			this.renderClientList();
		}, this)).always($.proxy(function() {
			this.loading = false;
		}, this));
	},
	adjustPointer: function() {
		var left = this.$('.client_logo_item_add').position().left;
		this.$('.client_all_list_pointer').css('left', left + this.$('.client_logo_item_add').outerWidth() / 2);
	},
	open: function() {
		this.showing = true;
		this.animating = true;
		this.adjustPointer();
		this.$('.client_all_list').slideDown($.proxy(function() {
			this.animating = false;
		}, this));
		if (this.hasLoadedOnce) {
			
		} else {
			this.loadClientList();
		}
	},
	close: function() {
		this.showing = false;
		this.animating = true;
		this.$('.client_all_list').slideUp($.proxy(function() {
			this.animating = false;
		}, this));
	},
	toggleClientList: function() {
		if (this.animating) return;
		if (this.showing) {
			this.close();
		} else {
			this.open();
		}
	},
	fileSelect: function(e) {
		this.addingLogoData = {
			appid: $(e.currentTarget).data('appid'),
			appname:  $(e.currentTarget).data('custom_name')
		};
		this.fileElement.trigger('click');
	},
	handleDelete: function(e) {
		var appid = $(e.currentTarget).closest('.client_logo_item').data('appid');
		this.removeLogo(appid);
		return false;
	},
	_change: function() {
		var file = this.fileElement[0].files[0];
		
		if (!file) return;
		if( !file.type.match(/image.*/) ) {
			return;
        }
        
        var _this = this;
		var reader = new FileReader;
		reader.onload = function() {
			_this.addingLogoData.src = event.target.result;
			_this.refreshLogo(_this.addingLogoData);
			_this.addingLogoData = null;
			
		};
		reader.readAsDataURL(file);
	},
	bindEvents: function() {
		this.$el
		.on('click', '.client_logo_item_add', $.proxy(this.toggleClientList, this))
		.on('click', '.client_all_list_con > div,.client_logo_item:not(.client_logo_item_add)', $.proxy(this.fileSelect, this))
		.on('click', '.client_logo_delete', $.proxy(this.handleDelete, this))
	},
	$: function() {
		return this.$el.find.apply(this.$el, arguments);
	},
	renderClientList: function() {
		var data = this.info;
		var html = '';
		data.forEach(function(info) {
			if (this.alreadyLogos[info.appid]) return;
			html += replaceTpl(tpl_client, info);
		}, this);
		this.$('.client_all_list_con').html(html);
		
		hg_resize_nodeFrame();
		return this;
	},
};
	
function onReady() {
	LogoList.init({ el: $('#client_logo'), logos: globalData.client_logo });
}	

$(onReady);
	
})();


/*信号流排序*/
function hg_stream_name_order(id)
{
	$('#'+id).sortable({
			revert: true,
			cursor: 'move',
			containment: 'document',
			scrollSpeed: 100,
			tolerance: 'intersect' ,
			axis: 'y',
			start: function(event, ui) {gDragMode = true;},
			change: function(event, ui) {},
			update: function(event, ui) {},
			stop: function(event, ui) {/*alert('stop');*/gDragMode = false;}			
	});
}