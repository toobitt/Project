$(function(){
	(function($){
		var addressInfo = {
			addritem : '' +
				'<li style="cursor:pointer;">' + 
					'<a class="addritem overflow" href="###" data-type="${type}" attrid="${id}">${name}</a>' + 
				'</li>' +
				''
		}
		$.widget('address.address_list',{
			options : {
				getProviceUrl : './region.php?a=province',
				getCityUrl : './region.php?a=city',
				getAreaUrl : './region.php?a=area',
			},
			
			_create : function(){
				$.template('addrItem', addressInfo.addritem);
				var widget = this.element;
				this.address = {
					type : 'province',
					dlabel : function(){
						return widget.find('#display_'+ this.type +'_show');
					},
					dshow : function(){
						return widget.find('#'+ this.type +'_show');
					},
					dhidden : function(){
						return widget.find('#'+ this.type +'_id');
					}
				}
				this.type = ['province', 'city', 'area'];
				this.ajaxtype = ['area', 'province', 'city'];
			},
			
			_init : function(){
				var obj = this.element.find('li').children();
				this._on({
					'click .addritem' : '_clickData'
				});
				this._initAddr();
			},

			_initAddr : function(){
				var _this = this;
				$.getJSON(this.options.getProviceUrl, function( data ){
					_this._handlerData( data, 'province');
				});
				for(var i=1; i<this.type.length; i++){
					var type = this.ajaxtype[i];
					this.address.type = type;
					var boxid = this.address.dhidden().val();
					parseInt(boxid) && this._ajaxData( boxid, this.ajaxtype[i], 0 );
				}
			},

			_handlerData : function( data, type){
				this.address.type = type;
				var dshow = this.address.dshow(),
					dlabel = this.address.dlabel();
				var addrData = [],
					_this = this;
				$.each(data, function(key, value){
					_this._sortData(type, value);
					value.type = type;
					addrData.push( value );
				});
				dshow.find('li:not(:first-child)').detach();
				$.tmpl('addrItem', addrData).appendTo( dshow );
				(type == 'province') && _this._initData( data, type );
			},

			_sortData : function(type, value){
				switch(type){
					case 'province': {
						value.name = value.name;
						break;
					} 
					case 'city': {
						value.name = value.city;
						break;
					}
					case 'area': {
						value.name = value.area;
						break;
					}
				}
			},

			_getName : function( type, value ){
				switch(type){
					case 'province': {
						return value.name;
					} 
					case 'city': {
						return value.city;
					}
					case 'area': {
						return value.area;
					}
				}
			},

			_clickData : function( event ){
				var self = $(event.currentTarget);
				var id = self.attr('attrid'),
					type = self.data('type');
					text = $.trim(self.html());
				this._changeLabel(text, id, type);
				this._ajaxDataB(id, type);
			},

			_changeLabel : function(text, id, type){
				this.address.type = type;
				var dlabel = this.address.dlabel(),
					dhidden = this.address.dhidden(),
					dshow = this.address.dshow();
				dlabel.html(text);
				dshow.hide();
				dhidden.val(id);
			},

			_ajaxDataB : function( id, type ){
				if(type == 'area'){
					return false;
				}else{
					this._ajaxData( id, type, 1 );
				}
			},
			
			_ajaxData : function( id, type, noon ){
				var _this = this;
				var url, style, noon;
				if(type == 'province'){
					url = this.options.getCityUrl;
					style = 'city';
				}else{
					url = this.options.getAreaUrl;
					style = 'area';
					(noon == 1) && (noon = 2);
				}
				$.getJSON(url, {id : id}, function( data ){
					_this._handlerData(data, style);
					_this._initData(data, style, noon);
				});
			},
			
			_initData : function( data, type, style ){
				this.address.type = type;
				var dlabel = this.address.dlabel(),
					dhidden = this.address.dhidden(),
					dshow = this.address.dshow();
				var _this = this;
				var id = dhidden.val();
				if(id){
					$.each(data, function(key, value){
						if(value.id == id){
							var name = _this._getName(type, value);
							dlabel.html(name);
						}
					});
				}
				if(style == 1){
					this._clearArea('area', style);
					this.element.find('#display_city_show').html('所有市');
					this.element.find('#city_id').val(0);
				}else if(style == 2){
					this._clearArea('area', style)
				}
			},

			_clearArea : function( type, style ){
				this.address.type = type;
				var dlabel = this.address.dlabel(),
					dhidden = this.address.dhidden(),
					dshow = this.address.dshow();
				(style == 1) && dshow.find('li:not(:first-child)').detach();
				dlabel.html('所有区');
				dhidden.val(0);
			},
		});
	})($);
	var parent_address = $(parent.$('body').find('.address_box'));
	var address_info = parent_address.length ? parent_address : $('.address_box');
	address_info.length && address_info.address_list();
});
var gDragMode = false;

function hg_remove_row(ids) {  
	var id = String(ids).replace(/\s/g, '').split(','),
		selector = $.map(id, function (v) { return ["#r" + v, "#r_" + v]; }).join();
	
	$(selector).remove();	
	if($('#edit_show').length) {
		hg_close_opration_info();
	}
}
/*内容获取错误效果*/
function hg_error_html(selector, num) {
	var el = num == 0 ? top.$(selector) : $(selector),
		values = ["-14px", "14px", "-10px", "10px", "-6px", "6px", "-2px", "2px", "0"],
	    durations = [5, 150, 80, 80, 40, 40, 20, 20, 10],
	    prop = "margin-left",
	    css;
	$.each(values, function (i, v) {
		(css = {})[prop] = v;
		el.animate(css, durations[i]);
	});
}
function trim(str)
{
	return $.trim(str);
}

hg_checkall = function(obj, checkedclass, defaultclass)
{
	if (!checkedclass)
	{
		checkedclass = 'cur';
	}

	if (!defaultclass)
	{
		defaultclass = '';
	}
	var liobj;
	var rowtag = $(obj).attr('rowtag');
	if (!rowtag || rowtag == '')
	{
		rowtag = 'TR';
	}
	$('input[name="' + obj.value + '[]"]').each(function() 
	{
		if($(this).attr("disabled"))
		{
			return;
		}
		else
		{
			$(this).attr( "checked", Boolean($(obj).attr("checked")) );
			liobj = hg_find_nodeparent(this, rowtag);
			$(liobj).attr("class", checkedclass);
		}
	});
	if ($(obj).attr("checked"))
	{
		$('input[name="' + obj.value + '[]"]').each(function() 
		{		
			if($(this).attr("disabled"))
			{
				return;
			}
			else
			{
				$(this).attr("checked", true);
				liobj = hg_find_nodeparent(this, rowtag);
				$(liobj).attr("class", checkedclass);
			}
		});
	}
	else
	{
		$('input[name="' + obj.value + '[]"]').each(function() 
		{
			if($(this).attr("disabled"))
			{
				return;
			}
			else
			{
				$(this).attr("checked", false);
				liobj = hg_find_nodeparent(this, rowtag);
				$(liobj).attr("class", defaultclass);
			}
		});
	}
	return false;
};

var gRowCls = '';
hg_row_interactive = function(obj, onout, classname, defaultclass, checkobj)
{
	if (!classname)
	{
		classname = 'hover';
	}
	if (!checkobj)
	{
		checkobj = 'infolist';
	}
	if (!defaultclass)
	{
		defaultclass = '';
	}
	if (!obj)
	{
		return;
	}
	if (onout == 'on')
	{
		gRowCls = $(obj).attr('class');
		if (!gRowCls)
		{
			gRowCls = '';
		}
		$(obj).attr('class', classname);
	}
	else if (onout == 'click')
	{
		if (gDragMode)
		{
			return false;
		}
		if (gRowCls != classname)
		{
			gRowCls = classname;
			$(obj).attr('class', classname);
			$(obj).find('input[name="' + checkobj + '[]"]').attr('checked', true);
		}
		else
		{
			gRowCls = defaultclass;
			$(obj).find('input[name="' + checkobj + '[]"]').attr('checked', false);
		}
	}
	else
	{
		$(obj).attr('class', gRowCls);
	}
};

hg_find_nodeparent = function (obj, tagName)
{
	var parentTag = obj.tagName;
	var loop = 0;
	while (parentTag != tagName && loop < 10)
	{
		obj = obj.parentNode;
		parentTag = obj.tagName;
		loop++;
	}
	if (parentTag == tagName)
	{
		return obj;
	}
	else
	{
		return null;
	}
};

hg_get_checked_id = function(obj)
{
	var ids = new Array();
	$(obj).find('input[type="checkbox"]').each(
		function()
		{
			if ($(this).attr('checked') && $(this).attr('id') != 'checkall' && $(this).attr('name') != 'checkall')
			{
				ids.push($(this).attr('value'));
			}
		}
	);
	return ids.join(',');
};


function  hg_select_value(obj,flag,show,value_name,is_sub)
{
	if($(obj).attr('attrid') != $('#' + value_name).val())
	{
		$('#display_'+ show).text($(obj).text());
		$('.display_'+show).val($(obj).attr('attrid'));
		$('#' + value_name).val($(obj).attr('attrid'));
		$('#' + show).css('display','none');
		if(flag == 1)
		{
			if($(obj).attr('attrid') == 'other')
			{
			   $('#start_time_box' +value_name).css('display','block');
			   $('#end_time_box' +value_name ).css('display','block');
				$('#go_date' +value_name ).css('display','block');
			}
			else
			{
				$('#start_time' + value_name ).val('');
				$('#end_time' +value_name).val('');
				$('#start_time_box' +value_name).css('display','none');
				$('#end_time_box' +value_name).css('display','none');
				$('#go_date' +value_name).css('display','none');
			}
		}
		if(is_sub == 1)
		{
			$("#searchform").submit();
		}
		return true;
	}
	else
	{
		$('#' + show).css('display','none');
		return false;
	}
}

function hg_blur_value(is_sub){
	if(is_sub == 1)
		{
			$("#searchform").submit();
		}
	
}

function hg_open_column(target ){
	if( $(target).data('click') ) return;
	$(target).data('click',true);
	$('.common-form-pop').wrap( '<div class="common-form-wrap" />' );
	var wrap_box = $('.common-form-wrap'),
		pop_box = wrap_box.find('.common-form-pop'),
		title = wrap_box.find('.publish-result-title'),
		extend_item = wrap_box.find('.extend-item');
	wrap_box.find('.publish-box').hg_publish({maxColumn: 3});
	
	wrap_box.on('click','.publish-box-close',function(){
		restoreColumnBox();
	});
	
	wrap_box.on('click','.publish-box-save',function(){
		var column_id = wrap_box.find('input[name="column_id"]').val(),
			column_name = wrap_box.find('input[name="column_name"]').val(),
			search_form = $('#searchform'),
			pub_column_id = search_form.find('input[name="pub_column_id"]');
		pub_column_id.val( column_id  );
		search_form.find('input[name="pub_column_name"]').val( column_name );
		search_form.submit();
		wrap_box.find('.publish-box-close').trigger('click');
	});
	
	initColumnBox();
	
	function initColumnBox(){
		createMask();
		title.text('已选栏目：');
		extend_item.hide();
		pop_box.css('top','103px');
		setTimeout( function(){
			wrap_box.prepend( '<div class="publish-box-btn"><span class="publish-box-save">确定</span></div>' );
		}, 300 );
	}
	
	function restoreColumnBox(){
		target.mask && target.mask.remove();
		pop_box.css('top', '-450px');
		wrap_box.find('.publish-box-btn').remove();
		title.text('发布至：');
		extend_item.show();
		pop_box.unwrap();
		$(target).data('click',false);
	}
	
	function createMask(){
		var height = $('body').outerHeight(true);
    	target.mask = $('<div/>').css( {
    		position:'absolute',
    		width : '100%',
    		height : height + 'px',
    		background : 'black',
    		opacity : 0.1,
    		'z-index' : 10001
    	} ).prependTo( 'body' );
	}

}

function clearPubResult(){
	var pub_box = $('.publish-box');
		pub_boxObj = pub_box.data('publish');
	if( pub_box.length && pub_boxObj ){
		pub_boxObj.removeResult();
	}
}



function type_serach( self ,method , key ){
	var value = $( self ).closest( '.range-search' ).find( 'input[type="text"]').val(),
		url = './run.php?mid=' + gMid + '&a=' + method + '&' + key + '=' + value,
		box = $(self).closest( 'ul' );
	$.get( url, function( data ){
		box.find('li').remove();
		box.append( data );
	} );
}

(function () {
var show_hide = hg_defer(function(show, id, el) {
	if (!show) {
		
	} else {
		$('#'+id).show();
		$(el).css('z-index', 100001);
	}
}, 300, true);
hg_search_show = function(flag,id,extra, el)
{
	if(extra)
	{
		if(eval(extra))
		{
			return false;
		}	
	}
	switch(flag)
	{
		case 0:
			$('#'+id).hide();
			$(el).css('z-index', 10000);
			break;
		case 1:
			$('#'+id).show();
			$(el).css('z-index', 100001);
			break;
		default:
			break;
	}
}
})();

function text_value_onfocus(obj,text){
	$(obj).removeClass("t_c_b");
	if($(obj).val()==text)
	{
		$(obj).val("");
	}
}
function text_value_onblur(obj,text){
	if($(obj).val()=="")
	{
		$(obj).addClass("t_c_b");
		$(obj).val(text);
	}
}
function textarea_value_onfocus(obj,text){
	$(obj).removeClass("t_c_b");
	if($(obj).text()==text)
	{
		$(obj).text("");
	}
}
function textarea_value_onblur(obj,text){
	if($(obj).text()=="")
	{
		$(obj).addClass("t_c_b");
		$(obj).text(text);
	}
	else
	{
		$(obj).removeClass("t_c_b");
	}
}


function dateToUnix(str)
{
	str = str.replace(/(^\s*)|(\s*$)/g, "");
	var new_str = str.replace(/:/g,'-');
	new_str = new_str.replace(/ /g,'-');
	var arr = new_str.split('-');

	var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
	return (datum.getTime()/1000);
}

function unixToDate(timestamp,jstimestamp)
{
	timestamp = timestamp*1000;
	jstimestamp = jstimestamp ? jstimestamp :"Y-m-d H:i:s";
	var d = new Date(timestamp);
	jstimestamp = jstimestamp.replace("Y", d.getFullYear());
	jstimestamp = jstimestamp.replace("m", ((d.getMonth()+1)<10?"0"+(d.getMonth()+1) : (d.getMonth()+1)));
	jstimestamp = jstimestamp.replace("d", (d.getDate()<10 ? "0"+d.getDate() : d.getDate()));
	jstimestamp = jstimestamp.replace("H", (d.getHours()<10 ? "0"+d.getHours() : d.getHours()));
	jstimestamp = jstimestamp.replace("i", (d.getMinutes()<10 ? "0"+d.getMinutes() : d.getMinutes()));
	jstimestamp = jstimestamp.replace("s", (d.getSeconds()<10 ? "0"+d.getSeconds() : d.getSeconds()));
	return jstimestamp;
}


function hg_rand_num(leng)
{
	var i, n;
	leng = leng ? leng : 5;
	var salt = '';
	for(i=0 ; i< leng ; i++)
	{
		n = Math.floor(Math.random()*10);
		if(!n && !i)
		{
			n = 3;
		}
		salt += n.toString();
	}
	return salt;
}



/*
 * 列表发布框的显示和隐藏
 */
(function () {
	var pub = $('#vodpub'),
		cont = $('#vodpub_body'),
		which = null;
	$(function () {
		pub = $('#vodpub');
		cont = $('#vodpub_body');
	});
	function fillHtml(html, id) {
		if (id == which) return;
		which = id;
		cont.html(html);
		pub.find('.common-list-pub-title div p').text(
			$('#r_'+ id).find('.common-list-biaoti').text()
		);
		display(id);
	}
	function display(id) {
		var _r, _r2;
		var t = $('#r_'+ id).offset().top;
		t = ( (t >= 200) ? (t - 200) : 0);
		if ( t + (_r = pub.outerHeight()) > (_r2 = $(document).height()) ) {
			t = _r2 - _r - 50;
		}
		pub.css('top', t + 'px');
		pub.css('margin-left', pub.outerWidth() / -2);
	}
	function disappear(id) {
		pub.css('top', '');
		which = null;
	}
	$.extend(window, {
		hg_show_pubhtml: fillHtml,
		hg_vodpub_hide: disappear
	});
})();


;(function($){

    $(function($){
        if(top != parent){
            parent.$('#nodeFrame').triggerHandler('_load');
        }else if(top != self){
            top.$('#mainwin, #formwin').filter(function(){
                return this.contentWindow == self;
            }).triggerHandler('_load');
        }
    });

    $(window).on({
        'load' :  function() {
            $('img[_src]:not([src])').attr('src', function(){
                return $(this).attr('_src') || '默认图';
            });
        },

        'focusin' :  function(event) {
            $.focusWindow = event.target === window ? true : false;
        },

        'focusout' : function(event){
            $.focusWindow = event.target !== window ? true : false;
        }
    }).trigger('focus');

    $(document).on({
        keydown : function(event){
            var keycode = event.keyCode;
            if(keycode == 8){
                if($.focusWindow && (event.target == document.body || event.target == document)){
                    event.preventDefault();
                }
            }
        }
    });


    $(function($){
        if(top == parent){
            //return;
        }
        var match = location.href.match(/k(?:ey)?=([^&]*)/);
        if(match && match[1] && $.trim(match[1])){
            var key = decodeURI(match[1]);
            if(/^\+*$/.test(key)) return;
            key = key.replace(/^\++/, '').replace(/\++$/, '');
            var reg = new RegExp(key, 'ig');
            $('.m2o-common-title').each(function(){
            	var _title = $(this).html(); 
                _title = _title.replace(reg, '<b style="color:red;font-style:normal;font-weight:normal;">' + key + '</b>');
                $(this).html(_title);
            });
        }
    });

})(jQuery);

;(function($){
    $.globalImgUrl = function(info, wh, f5){
        wh = wh ? wh + '/' : '';
        f5 = f5 ? '?' + parseInt(Math.random() * 100000) : '';
        if(info['path']){
            return info['path'] + wh + info['dir'] + info['filename'] + f5;
        }
        return info['host'] + info['dir'] + wh + info['filepath'] + info['filename'] + f5;
    }

    $.fn.delay = function(time, type){
        time = $.fx ? $.fx.speeds[time] || time : time;
        type = type || 'fx';
        return this.queue(type, function(){
            var elem = this;
            setTimeout(function(){
                $.dequeue(elem, type);
            }, time);
        });
    }
    
    $.widget('global.hg_switch',{
		options:{
			'switch-slide'  : '.switch-slide',
			'switch-item' : '.switch-item',
			'active' : 'common-switch-on',
			'on' : '',
			'off' : '',
			'value' :0
		},
		_create:function(){
		},
		_init:function(){
			this.myswitch = this.element.find(this.options['switch-slide']);
			var handlers={};
			handlers['click '+this.options['switch-item']]='_click';
			this._on(handlers);
			this._initSwitch();
		},
		_initSwitch:function(){
			var _this=this;
			var op=this.options;
			this.myswitch.slider({
				value: op['value'],
				slide:function(event,ui){
					var val = ui.value;
					_this._changeStatus(val);
				}
			}).on('slidestop',function(event,ui,val){
				    if( typeof val != 'undefined'){
				    	var val=val;
				    }else{
						var val=ui.value;					    	
				    }
					_this.myswitch.slider('value', val >= 50 ? 100 : 0);
					_this._changeStatus(val);
					_this._trigger('callback',null,[val]);   //回调函数
			});
		},
		_click:function(event){
			var val=$(event.currentTarget).data('number');
			this.myswitch.trigger('slidestop',[null,val]);
		},
		_changeStatus:function(val){
			var active=this.options['active'];
			if( val >= 50 ){
				!this.element.hasClass(active) && this.element.addClass(active);
			}else{
				this.element.hasClass(active) && this.element.removeClass(active);
			}
        },
        refresh:function(option){
        	$.extend(this.options,option);
        	this.myswitch.slider('value', this.options['value'] >= 50 ? 100 : 0);
			this._changeStatus(this.options['value']);
        }
	});
	
	/*重写getScript 默认缓存js*/
	$.getScript = function( url, callback, option ){
		var options = $.extend( {
			    dataType: "script",
			    cache: true,
			    url: url
		  	}, option || {});
		 return $.ajax(options).done( function( script, textStatus ){
		 	$.isFunction( callback ) && callback();
		 } );
	};
    
    $.fn.hg_getIds = function( option ){
		var defaultOption = {
			selected : 'selected',
			item : 'li'
		};
		var options = $.extend( defaultOption , option );
		var	items = $(this).find( options['item'] ).filter(function(){
				return $(this).hasClass( options['selected'] );
			});
		var	ids = items.map(function(){
			return $(this).attr('_id');
		}).get().join(',');
		return ids;
	};
    
    $.includeUEditor = function(init, m2oOptions){
        !(init && $.isFunction(init)) && (init = $.noop);
        
        if(window.isReady){
            init();
            return;
        }
        if( window.UEDITOR_HOME_URL ){
        	var waitEditorLib = setInterval( function(){
        		if( window.isReady ){
        			init();
        			clearInterval( waitEditorLib );
        		}
        	}, 1000 );
        	return;
        }
        var URL = window.UEDITOR_HOME_URL = "./res/ueditor/";
        var js = ['all.min', 'config'];
        $.m2oDeferred(js, URL + 'ueditor.', function(){
        	var plugins = m2oOptions.plugins || null;	
            if(plugins){
                var homeUrl = URL + 'third-party/m2o/';
                var baseUrl = homeUrl + 'baseWidget.js';
                $.getScript(baseUrl, function(){
                    var js = [];
                    if(plugins == 'all'){
                        js = ['plugin.all.min'];
                    }else{
                    	js = plugins;
                    }
                    $.m2oDeferred(js, homeUrl, init);
                });
            }else{
            	init();
            }
            window.isReady = true;
        });
    };

    $.m2oEditor = {
        get : function(id, opts){
            opts = $.extend({}, opts, {
                imagePopup : false,
            });
            return UE.getEditor(id, opts);
        },

        del : function(id){
            return UE.delEditor(id);
        }
    };


    $.m2oDeferred = function(js, homeUrl, init){
        !(init && $.isFunction(init)) && (init = $.noop);
        var ready = 0;
        var readyM = js.length;
        if(!readyM){
            init();
            return;
        }
        var dtd = new $.Deferred();
        $.when(dtd.promise()).then(
            function(){
                init();
            },
            $.noop,
            function(){
                ready++;
                if(ready == readyM){
                    this.resolve();
                }
            }
        );
        $.each(js, function(i, n){
            $.getScript(homeUrl + n + '.js', function(){
                dtd.notify();
            });
        });
    };



})(jQuery);

;(function($){
    $.template('my-tip', '<div class="my-tip-box"><div class="my-tip-inner m2o-transition">{{= tip}}</div></div>');
    $.fn.myTip = function(options){
        options = $.extend({
            string : '提示',
            cname : 'on',
            delay : 1000,
            dtop : 0,
            dleft : 0,
            color : '',
            padding : '',
            width : '',
            animate : true,
            animate_css : { top : '-30px' },
            callback : $.noop
        }, options);

        return this.each(function(){
            var tip = $.tmpl('my-tip', {tip : options['string']}).appendTo('body');
            var inner = tip.find('.my-tip-inner');
            var on = options['cname'];
            var delay = options['delay'];
            var dleft = options['dleft'];
            var dtop = options['dtop'];
            var color = options['color'];
            var width = options['width'];
            var z_index = options['z-index'];
            var padding = options['padding'];
            var callback = options['callback'];
            var $this = $(this);
            if(color){
                inner.css('background-color', color);
            }
            if(width){
            	width = isNaN(width) ? width : (width + 'px');
                inner.css('width', width );
            }
            if(padding){
            	inner.css({'padding-left' : padding + 'px' , 'padding-right' : padding + 'px'});
            }
            var position = $this.offset();
            var width = $this.outerWidth(true);
            var height = $this.outerHeight(true);
            tip.css({
                left : position.left + width / 2 + dleft + 'px',
                top : position.top + dtop + 'px',
                'z-index' : z_index
            });
            setTimeout(function(){
            	options['animate'] && inner.css( options['animate_css'] );
            	inner.addClass(on);
            }, 1);
            setTimeout(function(){
            	options['animate'] && inner.css( {top : 0} );
                inner.removeClass(on);
                callback();
                setTimeout(function(){
                	tip.remove();
                }, 500);
            }, delay || 1300);
        });
    }
})(jQuery);

;(function($){
	$.fn.wordCount = function( options ){
		var options = $.extend( {
			top : '-10px',	//上间距
			left : '-52px',	//左间距
			bg_color : '#6ba4eb',
			color : 'white'
		}, options );
		return this.each( function(){
			options.left = $(this).data('left') || options.left;
			options.top = $(this).data('top') || options.top;
			var wd = $(this).width();
			var tip_box = $('<div class="wordcount-tip"/>').css( {
				'position' : 'absolute',
				'color' : options.color,
				'font-size' : '12px',
				'left' : wd+ 'px',
				'text-align' : 'center',
				'min-width' : '50px',
				'z-index' : 100,
				'margin-left' : options.left,
				'margin-top' : options.top,
				'background' : options.bg_color,
				'padding' : '2px 3px',
				'border-radius' : '2px'
			} ).insertBefore( this );
			var parent_box = tip_box.parent(),
				position = parent_box.css('position');
			( position != 'relative' ) && parent_box.css( {position : 'relative'} );
			var	initCount = function( self ){
				var	word = $.trim( self.val() || self.text() ),
					placeholder = $.trim( self.attr('placeholder') || '' ),
					length = ( word && word != placeholder ) ? word.length : 0;
				length ? tip_box.text( length + '字' ).show() : tip_box.text('').hide();
			};
			
			initCount( $(this) );
			$(this).on( 'keyup', function(){
				initCount( $(this) );
			} );
		} );
	}
})(jQuery);

;(function(){
	
	//提取关键字模板字符串
	var keywordsInfo = {
			template : '' + 
						'<div class="keyword-box">' +
							'<div class="keyword-box-close">X</div>' +
							'<div class="keyword-box-content">' + 
								'<ul></ul>' +
							'</div>' +
							'<div class="keyword-box-arrow"></div>' +
						'</div>' +
						'',
			item_tpl : '' + 
						'<li data-name="${word}">' + 
							'<span class="item">${word}</span>' +
						'</li>' +
						''
			/*css : '' + 
					'.keyword-widget{position:absolute;border:1px solid #619cd2;padding:10px;}'+
					'',
			css_init : false*/
	};
	
	//提取关键字组件
	$.widget( 'hoge.keywords',{
		options : {
			url : '',
			content : '',
			num : '',
			change : $.noop,	//click关键字项回调
			close : $.noop		//关闭关键字弹窗回调
		},
		
		_create : function(){
			this._template( 'keyword-box', keywordsInfo.template, this.element );
			this.element.addClass( 'keyword-widget' );
			this.keyword_box = this.element.find('.keyword-box');
			this.keyword_list = this.keyword_box.find( 'ul' );
			this.keyword_tip = this.element.find('.keyword-tip');
		},
		
		_template : function( name, tpl, container, data ){
			$.template( name, tpl );
			var dom = $.tmpl( name, data ).appendTo( container );
			/*if( !keywordsInfo.cssInited && keywordsInfo.css ){
				keywordsInfo.cssInited = true;
                this._addCss( keywordsInfo.css );
            }*/
			return dom;
		},
		
		_init : function(){
			this._on( {
				'click li' : '_setKeyword',
				'click .keyword-box-close' : '_close'
			} );
			this._ajax();
		},
		
		_addCss : function(css){
            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
        },
		
		_setKeyword : function( event ){
			var self = $( event.currentTarget ),
				name = self.data( 'name' );
			self.toggleClass( 'on' );
			this.options.change.call( self, name );
		},
		
		_close : function( ){
			this._hide();
			this._reset();
			this.options.close.call();
		},
		
		refresh : function( option ){
			$.extend( this.options, option );
			this._ajax();
		},
		
		_ajax : function(){
			var _this = this,
				op = this.options,
				url = op.url,
				content = op.content,
				num = op.num;
			if( $.trim( content ) ){
				$.post( url, { content : content, num : num }, function( data ){
					if( $.isArray( data ) ){
						var data = data[0];
						_this.keyword_list.empty();
						_this._template( 'keyword-tpl', keywordsInfo.item_tpl, _this.keyword_list, data );
						_this._position();
						_this._setOn();
					}else{
						_this.keyword_list.text( data['errmsg'] );
						_this._position();
					}
				}, 'json' );
			}
		},
		
		_setOn :function(){
			var keywords = this.options.keywords,
				items = this.keyword_list.find( 'li' );
			if( items.length ){
				if( keywords.length ){
					$.each( keywords, function( key, value ){
						items.each( function(){
							var word = $(this).data('name');
							if( word == value ){
								$(this).addClass('on');
							}
						} );
					} )
				}
			}
		},
		
		_position : function(){
			var offset = this.options.offset,
				top = offset.top,
				left = offset.left,
				height = this.element.outerHeight( true );
			this.element.css( {
				left : left + 'px',
				top : top + 'px',
				'margin-top' : '-' + height/2 + 'px'
			} );
			this._show();
		},
		
		_show : function(){
			this.element.show();
		},
		
		_hide : function(){
			this.element.hide();
		},
		
		_reset : function(){
			this.element.find('ul').empty();
		}
		
		
	} );
	
	//提取关键字插件
	$.fn.tiquKeywords = function( option ){
		var options = $.extend( {
			url : 'xs.php?a=get_keywords',	//提取接口
			content : '',	//提取的原内容
			num : 30,
			keywords : [],
			change : $.noop,
			close : $.noop
		}, option );
		options.offset = this.offset();
		options.offset.left += this.outerWidth( true );
		return this.each( function(){
			var widget = $( this ).data('widget');
			if( widget ){
				widget.keywords( 'refresh', options );
			}else{
				var offset = $( this ).offset(),
					top = offset.top,
					left = offset.left,
					wd = $(this).outerWidth(true);
				var widget = $('<div />').appendTo( 'body' );
				widget.keywords( options );
				$( this ).data( 'widget', widget );
			}
		} );
	};
	
	
})(jQuery);


/*回到顶部*/
;(function($){
	var topInfo = {
		top_tpl : '' + 
			'<div id="upBtn" class="m2o-to-top">' + 
				'<a class="to-top-btn">顶部</a>' +
			'</div>' +
			''
	}
    $.template('Totop', topInfo.top_tpl);
    $.fn.toTop = function(options){
        options = $.extend({
        	scrollTop : 200,
        	speed : 300,
        	scrollObj : window
        }, options);
        return this.each(function(){
	    	var $this = $(this);
	     	var $topDom = $.tmpl(topInfo.top_tpl).appendTo( this );
	        var $backTotop = function(){
	        	var myScroll = $(this).scrollTop();
	         	if( myScroll >= options.scrollTop ){
		         	$topDom.show();
		         }else{
		         	$topDom.hide();
		         }
	        }
	        $( options.scrollObj ).on('scroll', $backTotop);
       		$backTotop();
	        $topDom.on( 'click','.to-top-btn', function(){
	        	$('html, body').animate({ scrollTop:0 }, options.speed);
				return false;
			});
        });
    }
})(jQuery);

