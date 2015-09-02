/**
*
* 设置cookie
*/
function hg_set_cookie(name, value, expires)
{
	var today = new Date();
	today.setTime(today.getTime());
	if (expires) expires = expires * 86400000;
	var expires_date = new Date(today.getTime() + expires); 
	document.cookie = ((cookie_id) ? cookie_id : '') +
		name + '=' + hg_urlencode(value) +
		((expires) ? ';expires=' + expires_date.toGMTString() : '') +
		((cookie_path) ? ';path=' + cookie_path : '') +
		((cookie_domain) ? ';domain=' + cookie_domain : '');
}

/**
*
* 获取cookie值
*/
function hg_get_cookie(name)
{
	name = cookie_id + name;
	var start = document.cookie.indexOf(name + '=');
	var len = name.length;
	if (start == -1 || (!start && name != document.cookie.substring(0, len))) return null;
	len += start + 1;
	end = document.cookie.indexOf(';', len);
	if (end == -1) end = document.cookie.length;
	return unescape(document.cookie.substring(len, end));
} 
function hg_urlencode(text)
{
	text = escape(text.toString()).replace(/\+/g, '%2B');
	var matches = text.match(/(%([0-9A-F]{2}))/gi);
	if (matches)
	{
		for (var matchid = 0; matchid < matches.length; matchid++)
		{
			var code = matches[matchid].substring(1,3);
			if (parseInt(code, 16) >= 128) text = text.replace(matches[matchid], '%u00' + code);
		}
	}
	text = text.replace('%25', '%u0025');
	return text;  
}
/**
 * 包装函数, 创建延迟delay执行的函数fn,
 * exclusion控制是否互斥
 */
function hg_defer(fn, delay, exclusion, context) {
    var timerID;
    return function () {
    	var args = Array.prototype.slice.call(arguments);
        if (exclusion) {
            clearTimeout(timerID);
        }
        timerID = setTimeout(function () {
        	fn.apply(context, args);
        }, delay);
    };
}
/**
 * 包装函数让其只执行一次，并缓存其结果，下次调用直接返回缓存的结果
 * 适用于延迟初始化，和多次调用返回同样结果的函数
 */
function hg_once(func) {
	var load = false, ret;
	return function () {
		if (load) return ret;
		load = true;
		ret = func.apply(this, arguments);
		func = null;
		return ret;
	};
}
/**
 * 毫秒级别测试函数运行时间，
 * 第一个参数是函数
 */
function hg_test(func) {
	var wrapper = function () {
		var begin, end;
		begin = $.now();
		func.apply(this, arguments);
		end = $.now();
		console.log("开始：%d;\n结束：%d;\n用时：%d毫秒。", begin, end, end - begin); 
	};
	return wrapper;
}
function object2str(arr, deep)
{
	if ( typeof arr === "number" || ( !isNaN(+arr) && arr !== '' ) ) return arr;
	else if ( typeof arr === "string" ) return '"' + arr + '"';
	else if ( typeof arr === "function" ) return "一个函数";
	else if ( typeof arr !== "object" ) return String(arr);
	else if ( Object.prototype.toString.call(arr) == "[object RegExp]" ) return arr.toSource();
	
	var s, i, k, indent, symbol = '    ';
	deep = deep || 1;
	for (indent = '', i = 0; i < deep; i++) indent += symbol;
	s = "Array(\n";
	for (k in arr) {
		s += indent + object2str(k) + ' => ' + object2str(arr[k], deep + 1) + ',\n';
	}
	s += indent.replace(symbol, '') + ')';
	return s;
}
function print_r(arr, ret)
{
	var source = object2str(arr);
	return ret ? source : alert( source );
}
//用$做命名空间加一些方法
;(function() {
	var defaultOptions = {
		width: 20,
		height: 20
	};
	$.createImgSrc = function(data, options) {
		options = $.extend({}, defaultOptions, options);
		data = data || {};
		return [data.host, data.dir, options.width, 'x', options.height, '/', data.filepath, data.filename].join('');
	};
})();
function hg_safe_confirm(msg, name, cb, obj) {
	if (jConfirm) {
		jConfirm(msg, name + '提醒', function(yes) {
			yes && cb();
		}).position(obj);
	} else {
		window.confirm(msg) && cb();
	}
}
/*发送ajax请求*/
function hg_ajax_post(obj, name, need_confirm, callback, request_clew)
{
	var doPost = function() {
		hg_request_to(obj.href || obj, {}, "post", callback, request_clew);
	};
	if (need_confirm) {
		var msg = '您确认' + name + '此条记录吗？';
		hg_safe_confirm(msg, name, doPost, obj);
	} else {
		doPost();
	}
	return false;
}
/*批处理请求*/
function hg_ajax_batchpost(obj, op, name, need_confirm, primary_key, para, request_type ,callback, request_clew)
{
	var ids = $(obj).closest('form')
				.find('input:checked:not([name="checkall"])')
				.map(function() { return this.value; }).get().join(','),
		msg;
	if (!ids) {
		msg = '请选择要' + name + '的记录', name + '提醒';
		jAlert ? jAlert(msg, name + '提醒').position(obj) : alert(msg);
		return false;
	}
	var doRequest = function() {
		var data = {};
		data[primary_key || 'id'] = ids;
		hg_request_to(
			gBatchAction[op] + (para || ''), data, 'post', callback, request_clew
		);
	};
	
	if (need_confirm) {
		msg = '您确认批量' + name + '选中记录吗？';
		hg_safe_confirm(msg, name, doRequest, obj);
	} else {
		doRequest();
	}
	return false;
}

function hg_bacthpub_show(obj)
{	
	var ids = $(obj).closest('form')
				.find('input:checked:not([name="checkall"])')
				.map(function() { return this.value; }).get().join(',');	
	if (!ids) {
		msg = '请选择要签发的记录', '签发提醒';
		jAlert ? jAlert(msg, '签发提醒').position(obj) : alert(msg);
		return false;
	}	
	App.trigger('batch:column_publish', ids);	
}
function hg_bacthspecial_show(obj)
{	
	var ids = $(obj).closest('form')
				.find('input:checked:not([name="checkall"])')
				.map(function() { return this.value; }).get().join(',');	
	if (!ids) {
		msg = '请选择要发布专题的记录', '专题发布提醒';
		jAlert ? jAlert(msg, '专题发布提醒').position(obj) : alert(msg);
		return false;
	}	
	App.trigger('batch:special_publish', ids);	
}
function hg_bacthblock_show(obj)
{	
	var ids = $(obj).closest('form')
				.find('input:checked:not([name="checkall"])')
				.map(function() { return this.value; }).get().join(',');	
	if (!ids) {
		msg = '请选择要发布区块的记录', '区块发布提醒';
		jAlert ? jAlert(msg, '区块发布提醒').position(obj) : alert(msg);
		return false;
	}	
	App.trigger('batch:block_publish', ids);	
}
function hg_bacthmove_show(obj,nodevar)
{
	var ids = $(obj).closest('form')
	.find('input:checked:not([name="checkall"])')
	.map(function() { return this.value; }).get().join(',');	
	if (!ids) {
		msg = '请选择要移动的记录', '移动提醒';
		jAlert ? jAlert(msg, '移动提醒').position(obj) : alert(msg);
		return false;
	}	
	App.trigger('batch:columns_publish', ids , nodevar);
}	




$(function() {
	
	/*$("#record-edit").on('click', 'a', function() {
		var text = $(this).text().trim();
		if(text!='移动') return ;
		var href = $(this).attr('href');
		$.get(href).done(function(data){
			$('#move_box').fadeIn();
			$('#move_box').html(data);
		});
		return false;	
	});*/
});
function hg_move_show(data)
{
	alert(data);
	$('#move_box').fadeIn();
	$('#move_box').html(data);
}
function hg_call_back(data, showmsg)
{
	var obj;
	try {
		obj = $.parseJSON(data);
		data = obj.msg || "请求成功";
		obj.callback && eval(obj.callback);
	} catch (e) {
		data = e.message + ' msg:' + object2str(e);
	}
	if (showmsg || (typeof showmsg == 'undefined')) hg_msg_show(data, 0);
}
function close_show_clew() 
{
	top && top.$("#liv_show_clew").hide();
}

function hg_show_clew(msg, unfadeout)
{
    return;
	var el = $("#liv_show_clew");
	
	if ( !el.length ) el = $('<div id="liv_show_clew" style="display:none;"></div>').appendTo("body");
	el.html('<span class="left"></span><span class="right"></span><span class="middle">' + msg + '</span>');
	el.css("margin-left", -el.outerWidth() / 2 + 'px');
	if (unfadeout == 1) {
		el.stop(false, true).show();
	} else if(unfadeout > 600) {
		el.stop(false, true).show().fadeOut(unfadeout); 
	} else if (unfadeout === false) {
		el.stop(false, true).show();
	} else {
		el.stop(false, true).show().fadeOut(2000);
	}
}
function hg_msg_show(data, unfadeout)
{
	top && top.hg_show_clew(data, unfadeout);
}
function hg_init_client_info()
{
	var clientW = document.documentElement.clientWidth;
	var offset = 0;
	var clientH = document.documentElement.clientHeight - offset;
	hg_set_cookie("client_info[w]", clientW);
	hg_set_cookie("client_info[h]", clientH);
	var wOffset;
	if (gMenuMode != 2)
	{
		wOffset = 65;
	}
	else
	{
		wOffset = 160;
	}
	/*if ($('#livwinarea').length > 0)
	{
		$('#livwinarea').height(clientH);
		$('#livwinarea').width(clientW - wOffset);
	}  */ 
	var childFrame = document.getElementById('mainwin').contentWindow;
    childFrame = childFrame.document.getElementById('nodeFrame');
    if (childFrame)
    {
		if ( childFrame.contentWindow.hg_resize_nodeFrame ) {
			childFrame.contentWindow.hg_resize_nodeFrame();
		}
    }
    
    /*设置上传的flash按钮的位置*/
    if(top.livUpload.currentFlagId)
    {
    	top.livUpload.OpenPosition();
    }
 
};


hg_find_nodeFrame_wrapper = hg_once(function () {
	return top == self ? null : top == parent ? 
			parent.$("#livnodewin") : 
			top.$("#mainwin")[0].contentWindow.$("#livnodewin");
});
/**
 * wh = $(window).height;
 * vh = '为了使页面所有显示的元素可见所需要的最小高度';
 * $(document).height() = Max(wh, vh);
 * $('html').height() = Min(vh, '文档流的高度');
 * 条件：内嵌的iframe其wh不等于视口高度(可变)且不随vh的改变而变;
 * 
 * 目标: 以vh的值调整wh，使wh-vh的值尽可能小
 * 
 * 显示某个元素时，vh变大，所以让wh = $(document);
 * 隐藏某个元素时，vh变小，所以让wh = $('html');
 */
hg_resize_nodeFrame = function (hideModel, firstload)
{
	var wrapper = hg_find_nodeFrame_wrapper();
	if (!wrapper) return;
	wrapper.css({
		"height": hideModel ? $("html").height() : $(document).height(),
		"min-height": "580px"
	});
	
	/*if (parent.$('#livnodewin').length > 0)
	{
		var clientW = parent.document.documentElement.clientWidth;
		if (firstload && firstload == 1)
		{
			clientW = clientW - 17;
		}
		var height = document.documentElement.scrollHeight;
		if (height < 550)
		{
			height = 550;
		}
		parent.$('#livnodewin').height(height);
        var dis = 152;
        if(parent.$('#livnodewin').attr('_isnotnode')){
            dis -= 3;
        }
		parent.$('#livnodewin').width(clientW - dis);
	}*/
};
$.fn.deferHover = function() {
	return this.each(function() {
		var el = $(this);
		var show_hide = hg_defer(function(show) {
			show ? el.find('.defer-hover-target').show() : el.find('.defer-hover-target').hide();
		}, 300, true);
		el.removeAttr('onmouseover').removeAttr('onmouseout');
		el.hover(function() {
			show_hide(true);
		}, function() {
			show_hide();
		});
	});
};

$.fn.hg_autocomplete = function( option ){
	return this.each( function(){
		var $this = $(this),
			defaultOption = { url : './getUser.php', param : 'name' },
			options = $.extend( defaultOption, option );

        var cache = {
            _cache : {},
            get : function(key){
                return this._cache[key];
            },
            set : function(key, val){
                key && (this._cache[key] = val);
            }
        };

        var autoClass = $.fn.hg_autocomplete.autoClass = $.fn.hg_autocomplete.autoClass || (function(){
            function _autoClass($dom){
                this.$dom = $dom;
                this.init();
            }

            $.extend(_autoClass.prototype, {
                init : function(){
                    this.$dom.autocomplete({source : []});
                },
                callback : function(value, members){
                    this.$dom.autocomplete('option', 'source', members);
                    this.$dom.autocomplete('search' , value);
                }
            });

            return _autoClass;
        })();
        var autoComplete = new autoClass($this);

        $this.on('keyup', function( event ){
            if(event.keyCode >= 37 && event.keyCode <= 40){
                return;
            }
            var $this = $(this);
            var timer = $this.data('timer');
            timer && clearTimeout(timer);
            $this.data('timer', setTimeout(function(){
                var	value = $.trim($this.val());
                if(value){
                    var members = cache.get(value);
                    if(members){
                        autoComplete.callback(value, members);
                    }else{
                        var url = options['url'] + '?' + options['param'] + '=' + value;
                        var hash = +new Date() + '' + Math.ceil(Math.random() * 1000);
                        $this.data('ajaxhash', hash);
                        $.getJSON(url ,function(data){
                            if(hash != $this.data('ajaxhash')) return;
                            var members = [];
                            $.each(data, function(key, value){
                                members.push(value['user_name']);
                            });
                            cache.set(value, members);
                            autoComplete.callback(value, members);
                        });
                    }
                }

            }, 300));
		});
	});
};

$.fn.autocompleteResult = function( option ){
	var defaultOption = { event: 'autocompleteselect', issubmit : true },
		options = $.extend( defaultOption, option  );
	return this.each( function(){
		$(this).hg_autocomplete();
		$(this).on( options['event'], function( event,ui ){
			$(this).val( ui.item.label );
			options['issubmit'] && $(this).closest( 'form' ).submit();
		});
	} );
};

hg_repos_top_menu = function( iframe )
{
	var p$ = ( iframe == 'mainwin' ) ? $ : parent.$,
		_target = iframe ? iframe : 'nodeFrame';

	var search = p$('#hg_info_list_search'),
		box = p$('<div class="key-search"></div>'),
		key;
	if (window != top || $('#info_list_search').size()) {	
		search.html( $('#info_list_search').hide().html() || '' ).find('form').attr('target', _target);
		key = search.find('.text-search,.right_2').find('input:text');
		key_search=search.find('.text-search,.right_2').find('input:submit');
		key.val() && box.addClass('key-search-open'),
		pub_column_box = search.find('.pub_column_search');
		if ( key.size() ) {
			search.find('.serach-btn').remove();
			search.prepend('<span class="serach-btn"></span>');
			box.append(key).append(key_search).prependTo(search.find('form'));
		}
		search.find('.serach-btn').click(function () {
			var btn = p$(this), open, animate;
			
			open = btn.data('open');
			btn.data('open', !open);
			box[open ? 'removeClass' : 'addClass']('key-search-open');
		});
		var date_picker = search.find('.date-picker');
		if( date_picker.length ){
			date_picker.hg_datepicker();
		}
		if( pub_column_box.length ){
			var column_list = pub_column_box.find('li'),
				length = column_list.length,
				show_name = '',
				first_name = column_list.eq(0).text();
			if(  length== 1 ){
				column_list.remove();
			}
			show_name = length > 1 ? ( first_name + '('+ length + ')' ): first_name;
			pub_column_box.find('#display_pub_column_show').text( show_name );
		}
		
		if( search.find('.site_list').length ){
			search.find('.site_list').site_list();
		}
	}
	var autoitem = search.find( '.autocomplete' );
	if( autoitem.length ){
		autoitem.autocompleteResult();
	}
	
	p$('.colonm.down_list').deferHover();
	
	if (parent.$('#_nav_menu').length > 0)
	{
		if ($('#_nav_menu').length > 0)
		{
			var html = $('#_nav_menu').html();
			var pnav = parent.$('#_nav_menu').html(html);
			pnav.find('a').each(function(){
				if(!$(this).attr('target')){
					$(this).attr('target', 'nodeFrame');
				}
				var click = '';
				if(click = $(this).attr('onclick')){
					$(this).attr('onclick', 'nodeFrame.' + click);
				}
			});
			return;
		}
	}
}
create_color_for_weight = function(weight) {
    weight = 100 - weight;
    return "rgb(" + ([255, weight * 2, weight].join()) + ")";
};
/**
* type = 0 更新最后级
* type = 1 追加最后一级
*/
hg_rebuild_nav = function(obj, type)
{
	if (!type)
	{
		type = 0;
	}
	if ($(obj).html())
	{
		var html = $(obj).html();
	}
	else
	{
		var html = '<a href="run.php?mid=' + obj[0] + '" target="nodeFrame">' + obj[1] + '</a>';
	}
	if (type == 0)
	{
		$('#hg_cur_nav_last').html(html);
	}
}

var gTasks = {}, gTasksId = 0;
hg_window_destruct = function ()
{
	var str = '';
	var hasTask = false;
	for (var tid in gTasks)
	{
		str = str + tid + ' : '  + gTasks[tid].name + '\n';
		hasTask = true;
	}
	
	if (hasTask)
	{
		return "当前系统有以下任务正在执行\n\n" + str;
	}
	
}
//页面载入时初始化flash位置
$(function() { 
	if(top.livUpload.SWF)
    {
    	//列表页为视频页面时什么都不做
    	var ifr = top.livUpload.findNodeFrame();
    	if (  ifr && (ifr.gMid == top.$.globalData.get('vod_mid') )  ) {
    		// do nothing
    	} else {
    		//top.livUpload.initPosition();
    	}
    }
});

hg_add2Task = function (task)
{
	var tid = gTasksId;
	var item = {Tid : tid, name : task.name };
	gTasks[tid] = item;
	gTasksId++;
	return tid;
}

hg_taskCompleted = function (Tid)
{
	delete gTasks[Tid];
	return true;
}
/*function hg_getvideoinfo(maxid,trans_ids,vod_leixing,vod_sort_id, conditions)
{
	var frame = document.getElementById("mainwin");
	var mpara = '';
	var transpara = '';
	if (maxid && maxid != -1)
	{
		mpara = '&since_id=' + maxid;
		if(vod_leixing != -1)
		{
			mpara += '&vod_leixing=' + vod_leixing;
		}
		
		if(vod_sort_id != -1)
		{
			mpara += '&vod_sort_id=' + vod_sort_id;
		}
	}
	
	transpara = '&transids=' + trans_ids;
	
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#vodlist').attr('id'))
		{
			var mid = frame.gMid;
			var html = '<scr' + 'ipt id="request_videoinfo" type="text/javascript" src="run.php?mid='+mid+'&a=getinfo&ajax=1' + mpara + transpara + '"></scr' + 'ipt>';
			frame.$('head').append(html);
		}
	}
	else
	{
		var html = '<scr' + 'ipt id="request_videoinfo" type="text/javascript" src="run.php?mid='+gMid+'&a=getinfo&ajax=1' + mpara + '&' + $.param(conditions) + transpara + '"></scr' + 'ipt>';
		
		$('head').append(html);
	}
}*/

function hg_add_list(html)
{
	var frame = document.getElementById("mainwin");
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#vodlist').attr('id'))
		{
			frame.$('#vodlist').prepend(html);
			frame.correctPosition();
			hg_resize_nodeFrame();
		}
	
	}
	else
	{
		$('#vodlist').prepend(html);
		correctPosition();
		hg_resize_nodeFrame();
	}
}

function hg_create_collect(mid,id)
{
	 var url = "./run.php?mid="+mid+"&a=create_collect&id="+id;
	 hg_ajax_post(url);
}

function hg_batchremove(obj, op, name, need_confirm, primary_key, para, request_type,collect_id)
{
	var tmp = obj;
	obj = hg_find_nodeparent(obj, 'FORM');
	var ids = hg_get_checked_id(obj);

	if(typeof jAlert != 'undefined'){
		if(!ids){
			jAlert('请选择要' + name + '的记录', name + '提醒').position(tmp);
			return false;
		}

		var wrapcallback = function(){
			primary_key = primary_key || 'id';
			url = "./run.php?mid="+gMid+"&a="+op+"&collect_id="+collect_id;
			para && (url += para);
			var data = {};
			data[primary_key] = ids;
			if(request_type == 'ajax'){
				hg_request_to(url, data);
			}else{
				location.href = url + '&id=' +data.id;
			}
		};

		if(need_confirm){
			jConfirm('您确认批量' + name + '选中记录吗？', name + '提醒', function(result){
				if(!result) return false;
				wrapcallback();
			}).position(tmp);
		}else{
			wrapcallback();
		}
	}else{
		if(!ids)
		{
			alert('请选择要' + name + '的记录');
			return false;
		}
		if (need_confirm && !window.confirm('您确认批量' + name + '选中记录吗？'))
		{
			return false;
		}
		if (!primary_key)
		{
			primary_key = 'id';
		}
		
		url = "./run.php?mid="+gMid+"&a="+op+"&collect_id="+collect_id;

		if (para)
		{
			url = url + para;
		}

		var data = {};
		data[primary_key] = ids;
		if (request_type == 'ajax')
		{
			hg_request_to(url, data);
		}
		else
		{
			document.location.href = url + '&id=' +data.id;
			
		}
	}
	return false;
}


function  hg_get_size()
{
	var left = parseInt($(window).width())/2 - 331;
	var top =  parseInt($(window).height())/2 - 275 + $(window).scrollTop();
	
	$("#player_container_o").css({"left":left+"px","top":top+"px"});
}

function hg_close_video()
{
	 $("#player").remove();
	 $("#player_container_o").removeClass("player_style_o");
	 $("#player_container_c").removeClass("player_style_c");
	 $("#close_player").css("display","none");
	 $("#player_container_c").html("<div id='player'></div>");
}

function hg_fold(obj)
{
    var status = $(obj).attr("status");
    var id = $(obj).attr("id");
    if(status == "0")
    {
  	  $("#m_"+id).show();
  	  $(obj).attr("status","1");
    }
    else
    {
  	  $("#m_"+id).hide();
  	  $(obj).attr("status","0");
    }
	hg_resize_nodeFrame();
 }

function hg_chang_pic(obj,pic)
{
	$(obj).attr("src",RESOURCE_URL+pic);
}

function  hg_back_pic(obj,pic)
{
	$(obj).attr("src",RESOURCE_URL+pic);
}


function hg_single_video(obj,type)/*type是发布的类型(手机还是网站)*/
{
	var frame = document.getElementById("mainwin");
	var para = '';
	if(type)
	{
		para = '&pubinfo='+type;
	}
	
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#vodlist').attr('id'))
		{
			var mid = frame.gMid;
			var url = "./run.php?mid="+mid+"&a=form_addlist&vodid="+obj.vodid+"&row_id="+obj.id+para;
			hg_ajax_post(url);
		}
	}
	else
	{
		var url = "./run.php?mid="+gMid+"&a=form_addlist&vodid="+obj.vodid+"&row_id="+obj.id+para;
		hg_ajax_post(url);
	}
	
}



/*寻找页面元素,按id寻找
 * ①如果当前层级有该元素,就返回当前层级的元素
 * ②如果当前层级没有该元素,就寻找最里层的子级元素
 * 返回jquery对象
 * 
 * */
function  hg_findFrameElements(id)
{
	if($('#'+id).attr('id'))
	{
		return $('#'+id);
	}
	
	var frame = document.getElementById("mainwin");
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#'+id).attr('id'))
		{
			return frame.$('#'+id);
		}
		else
		{
			return false;
		}
	}
}

/*找到nodeframe*/
function hg_findNodeFrame()
{
	var frame = document.getElementById("mainwin");
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		return frame;
	}
	else
	{
		return false;
	}
}

/*添加一行图集列表的回调函数*/
function hg_put_new_tujilist(html)
{
	var frame = hg_findNodeFrame();
	if(frame)
	{
		frame.$('#tujilist').prepend(html);
	}
	else
	{
		$('#tujilist').prepend(html);
	}
}



$(document).ready(function()
{
	var gCurPicIndex = 0;
	var gDialog;
	hg_showpage = function(link)
	{
		hg_showwindialog();
		return false;
	};

	hg_showwindialog = function()
	{
		if($("#livwindialog").html() != null)	
		{
			gDialog = new AlertBox("livwindialog"),locks = false;
			function lockup(e){ e.preventDefault(); };
			function lockout(e){ e.stopPropagation(); };
			$("#livwindialogClose").click(function(){ 
				gDialog.close(); 
				$("#livwindialogbody").html('');
				if(livUpload.SWF)
				{
					livUpload.initPosition();
					livUpload.currentFlagId  = livUpload.moreFlagId;
				}
                
			});
			locks = true;
			var clientW = document.documentElement.clientWidth;
			var top = document.documentElement.scrollTop;
			$("#livwindialog").css('top', (top + 60) + 'px');
			$("#livwindialog").css('left', (clientW / 2 - 250) + 'px');
			gDialog.show();
		}
	};

	hg_clear = function()
	{
	};
	
	window.onclick = hg_clear;
	hg_ajax_batchpost_select = function (obj, op, name, need_confirm, primary_key)
	{
		if ($(obj).val() == -1)
		{
			return;
		}
		var para = '&' + op + '=' + $(obj).val();
		hg_ajax_batchpost(obj, op, name, need_confirm, primary_key, para);
	}
	
	
	hg_ajax_post_select = function(obj, url,  name, need_confirm)
	{
		var state = $(obj).val();
		if (state == -1)
		{
			return;
		}
		var para = $(obj).attr('name');
		para = para.split('__');
		url = url + '&' + para[0] + '=' + state + '&' + para[1];
		hg_ajax_post(url, name, need_confirm);
	}
	

	hg_change_text = function (id)
	{
		alert(id);
	}

	hg_show_template = function (html)
	{
		if (top)
		{
			top.hg_addDialogHtml(html);
			top.hg_showwindialog();
		}
		else
		{
			hg_addDialogHtml(html);
			hg_showwindialog();
		}
	}

	hg_show_error = function(html)
	{
		if (top)
		{
			top.hg_addDialogHtml(html);
			top.hg_showwindialog();
		}
		else
		{
			hg_addDialogHtml(html);
			hg_showwindialog();
		}
	}


	hg_addDialogHtml = function (html)
	{
		$("#livwindialogbody").html(html);
	}
	hg_selected_pic = function(index, obj, src)
	{
		$('#hg_pic_' + gCurPicIndex).removeClass('cur');
		gCurPicIndex = index;
		$('#hg_pic_' + gCurPicIndex).addClass('cur');
		if (src)
		{
			$('#' + obj).val(src);
		}
	}
	
	hg_ajax_submit = function (formname,beforeSubmit,success,callback)
	{	
		var url = $('#' + formname).attr('action');
		url = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'ajax=1';
		if (beforeSubmit)
		{
			beforeSubmit = beforeSubmit + "('" + formname + "')";
		}
		else
		{
			beforeSubmit = '';
		}
		
		var options = {
     	 	url: url,
     	 	dataType: 'html',
      		success: function(data) {
         	// 'data' is an object representing the the evaluated json data
	         	//print_r(data);	
			if(callback)
			{
				var fn = callback + '(' + data + ')';
				eval(fn);
			}
			
			hg_call_back(data);
      		
		    },
      		beforeSubmit : function (formname)
      		{
      			eval(beforeSubmit);
      		}
		};
		
		hg_msg_show('正在发送请求......', 1);	
		$('#' + formname).ajaxSubmit(options);
		return false;
	}
	hg_dialog_close = function ()
	{
		top.gDialog.close();
	}
	//推荐内容后callback事件
	hg_recommend_call = function (id)
	{
		hg_dialog_close();
	}
	//权限设置后的callback事件
	hg_prms_setting_call = function ()
	{
		hg_dialog_close();
	}
	hg_check_recomend = function (formname)
	{
		var form;
		eval("form = document." + formname);
		if(form.hg_columnid.value == 0)
		{
			hg_msg_show('请选择要推荐到的栏目', 1);
			return false;
		}
		if(form.hg_title.value == '')
		{
			hg_msg_show('请设置推荐内容的标题', 1);
			return false;
		}
	}
	
	hg_set_dom_html = function (html, dom)
	{
		$("#" + dom).html(html);
	}
	if ($("#checkall").length > 0)
	{
		$("#checkall").bind('click',function(){hg_checkall(this)});//绑定全选事件
	}
	
	
});
$(function(){
	/*实例化日期选择器*/
	var date_picker = $('.date-picker');
	if( date_picker.length ){
		date_picker.hg_datepicker();
	}
});


$(function(){
	$('body').on( 'click', '.jiaozheng', function( event ){
		var url = $(this).attr( '_href' );
		$.get( url, function( data ){
			if( data[0] ){
				var nodeFrame = $('#nodeFrame'),
					src = nodeFrame.attr( 'src' );
				nodeFrame.attr( 'src', src );
			}
		} );
		
		return false;
	} );
});


/*
 * 列表页站点列表组件
 */
;(function($){
		$.widget('site.site_list',{
			options : {
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'mouseenter .site-nav' : '_over',
					'click .search-img' : '_search',
					'keyup input[name="search"]' : '_change'
				});
				this._switchable();
				this.element.find('.arrow').show();
			},
			
			_switchable : function(){
			    var len = this.element.find('.list-item').length;
			    if( len > 1 ){
			    	this.element.find('.switch-slider-box').switchable({
				        triggers: false,
				    	panels: '.list-item',
				    	effect: 'scrollRight',
				    	easing: 'cubic-bezier(.455, .03, .515, .955)',
				    	end2end: true,
				    	autoplay: false,
				    	next : '.next',
				    	prev : '.prev',
				    });
			    }
			    this._onOffarrow( len > 1 ? true : false );
			},
			
			/*实例化数据  插入数据*/
			_initinfo : function( data ){
				var selectdata = this._slice( data );
				var box = this.element.find('.switch-slider-box'),
					info = {};
				info.option = selectdata;
				box.empty();
				selectdata.length ? this.element.find('#select-tpl').tmpl( info ).appendTo( box ) : box.append('<div class="no-data">没有找到相关数据！</div>');
				this._switchable();
			},
			
			_onOffarrow : function( bool ){
				this.element.find('.arrow')[ bool ? 'show' : 'hide' ]();
			},
			
			_over : function(){
				this.element.find('.site-box').show();
			},
			
			_change : function( event ){
				this._search( event );
			},
			
			_search : function( event ){
				var self = $(event.currentTarget),
					val = $.trim(this.element.find('input[name="search"]').val());
				this._getsearchlist( val );
			},
			
			/*关键字搜索*/
			_getsearchlist : function( data ){
				var searchdata = [];
				$.each( datalist , function(key , value ){
					if( !data ){
						var result = [ key , value ];
						searchdata.push( result );
					}else{
						if( value.indexOf( data ) > -1  ){
							var result = [ key , value ];
							searchdata.push( result );
						}
					}
				});
				this._initinfo( searchdata );
			},
			
			/*截断数据 分为50条数据一组的数组*/
			_slice : function( data ){
				var result = [];
				for(var i=0,len=data.length;i<len;i+= 50 ){
				   result.push(data.slice(i,i+50));
				}
				return result;
			}
		});
})($);