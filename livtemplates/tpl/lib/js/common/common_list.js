window.App = window.App || $({});
(function($) {

    function makeProxy( name ) {
        return function() {
            ( this._JQ || ( this._JQ = $( this ) ) )[name].apply( this._JQ, arguments );
        };
    }

    $.eventEmitter = {
        emit: makeProxy( "trigger" ),
        once: makeProxy( "one" ),
        on: makeProxy( "on" ),
        off: makeProxy( "off" )
    };

}(jQuery));


/*魔法棒，控制列的显示和隐藏*/
(function($){
	/*
	 * 创建延迟delay执行的函数fn,
	 * exclusion控制是否互斥
	 * */
	function defer(fn, delay, exclusion) {
        var timerID;
        return function () {
        	var args = Array.prototype.slice.call(arguments);
            if (exclusion) {
                clearTimeout(timerID);
            }
            timerID = setTimeout(function () {
            	fn.apply(null, args);
            }, delay);
        };
    }

    /*
    需要下面两个JS
    {js:domcached/jquery.json-2.2.min}
    {js:domcached/domcached-0.1-jquery}
    调用方式
    $.commonListCache('**-list');
    */

    $.commonListCache = hg_once(function(topNameSpace){
    	/*关闭这个功能*/
    	$('#open-close-box').hide();
    	return;
    	
    	/*把topNameSpace改为使用mid，mid唯一*/
    	topNameSpace = gMid || topNameSpace;
        var cachestate = {
            getcache : function(){
                return $.DOMCached.get("key", topNameSpace);
            },
            setcache : function(value){
                $.DOMCached.set("key", value, false, topNameSpace);
            }
        };
        
        var box = $('#open-close-box'),
        	opacityHide;	/*一个互斥延迟执行的函数*/
        
        opacityHide = defer(function (leave) {
    		leave && box.addClass('opacity-hide');
    	}, 5000, true);
        
        box.hover(function () {
        	box.removeClass('opacity-hide');
        	opacityHide();
        }, function () {
        	opacityHide(true);
        });
        
     	var widthMap = {};
     	box.find('li').each(function () {
     		var which = $(this).attr('which'),
     			width = $('.common-list-head .' + which).width();
     		widthMap[which] = width;
     	});
     	
     	box.find('input').on({
     		translateX: function (e, x, notransition) {
     			$(this).data('translateX', $(this).data('translateX') + x).trigger('transform', [notransition]);
     		},
     		transform: function (e, notransition) {
     			$('.' + $(this).closest('li').attr('which'))
	     			.css({
	     				transition: notransition ? '' : 'all 0.5s',
	     				transform: 'translateX(' + 
	     					$(this).data('translateX') + 'px) rotateY(' + 
	     					$(this).data('rotateY') + 'deg)'
	     			});
     		},
     		rotateY: function (e, y, notransition) {
     			var li = $(this).closest('li');
  
     			$(this).data('rotateY', y).trigger('transform', [notransition]);
     			li.prevAll().find('input')
     				.trigger('translateX', [widthMap[li.attr('which')] * (y == 0 ? -1 : 1), notransition]);
     		}
     	}).data('translateX', 0).data('rotateY', 0);
        box.on('click', 'input', function(event, nocache, notransition){
            var which = $(this).closest('li').attr('which');
            var hide = !$(this).prop('checked');
            
            $(this).trigger('rotateY', [hide ? '90' : '0', notransition]);
            if(nocache){
                return;
            }
            var cache = [];
            $('#open-close-box input:not(:checked)').each(function(){
                cache.push($(this).closest('li').attr('which'));
            });
            cachestate.setcache(cache.join(','));
        });
        
        function init() {
        	/*已隐藏的列*/
	        var hideColumns = cachestate.getcache() || '';
	        
	        hideColumns = hideColumns.split(',');
            box.find('input').each(function() {
                var which = $(this).closest('li').attr('which');
                if($.inArray(which, hideColumns) != -1) {
                    $(this).prop('checked', false).trigger('click', [true, true]).prop('checked', false);
                }
            });
            
            opacityHide(true);
        }
        init();
    });
})(jQuery);

/**
 * 列表页打开编辑页是新建iframe
 */
jQuery(function ($) {
	var load = false;
	$.initOptionIframe = function(option){
		if ( load ) return;
		load = true;
        option = option || {};
        var url = option['url'];
        var attr = option['attr'];
        $(document).on('click', '.common-list .option-iframe', function(event) {
            var wltion = false;
            if(top && top != self){
                var src = $(this).attr('href');
                if(src && src.indexOf('?') === 0){
                    src = location.href.replace(location.search, '') + src;
                }else if(!src){
                    var id = $(this).attr(attr);
                    src = url + id;
                }
                var iframe = top.$('#livwinarea');
                if(iframe[0]){
                    iframe.trigger('iopen', [{
                        src : src,
                        gMid: gMid
                    }]);
                    App && App.trigger('optionIframeOpen');
                    return false;
                }else{
                    wltion = true;
                }
            }else{
                wltion = true;
            }
            if(wltion){
                top.location.href = $(this).attr('href');
            }
            event.preventDefault();
        }).on('click', '.common-list .option-iframe a', function (event) {
        	event.preventDefault();
        }).on('click', '.gDragMode .common-list-biaoti a', function (event) {
        	event.preventDefault();
        });
    }
    $.initOptionIframe();
    var wrapHtml = '<div class="common-list-item"><div class="common-list-cell"></div></div>';
    window.replaceLi = function (id, html) {
    	var el, needWrap;
    	html = $(html);
    	if ( html.attr('need-wrap') == 'true' ) {
    		html.removeAttr('need-wrap');
    		el = html.find('>div');
    		needWrap = el.children();
			if ( !needWrap.size() ) {
				el.wrapInner( wrapHtml );
			} else {
				needWrap.wrap( wrapHtml );
			}
    	}
 		$( "#r_" + id ).replaceWith( html );
    }
});

/**
 * 权重
 */
jQuery(function($) {
	var timer;
	var UPDATE_HZ = 2000,	
		originRecords = {}, /*这个记录中的信息和后台数据库中的一致*/
		records = {},	/*待更新的记录*/
		url = './run.php?mid=' + gMid + '&a=update_weight',	
		LevelLabel = [0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90];	
	function updateWeight () {
		if ( $.isEmptyObject( records ) ) return;	/*为空，不需要更新权重*/	
	
		var recordsCopy = $.extend( {}, records );
		records = {};/*重置*/
		$.ajax({
			url: url,
			type: 'POST',
			data: { data: JSON.stringify(recordsCopy) },
			dataType: 'json',
			success: function () {
				$.extend( originRecords, recordsCopy );/*刷新origin_records，使其与服务器同步*/
				/*清理records中的数据，去除不需要被更新的记录*/
				for( var i in records ) {
					if ( records[i] == originRecords[i] ) {
						delete records[i];
					}
				}
			},
			timeout: UPDATE_HZ / 2,
			error: function () {
				/*把更新失败的记录重新添加进待更新记录中*/
				records = $.extend( recordsCopy, records );
			}
		});
	}
	function updateWeightView ( isUp ) {
		var o = this.parent().parent(),
			level = +o.attr( '_level' ),
			newLevel = isUp ? level + 1 : level - 1; 
		if ( newLevel < 0 || newLevel > 12 ) return;	
		
		var cname = 'common-quanzhong-box' + newLevel,
			id = o.closest('.common-list-data').attr('id').substring(2);
		o.attr( 'class', cname ).attr( '_level',newLevel ).find( '.common-quanzhong' ).text( LevelLabel[newLevel] ); /*更新权重的视图*/
		
		//如果还没加入origin_records中则加入
		if ( typeof originRecords[id] == 'undefined' ) originRecords[id] = level;
		//如果更改了记录，则将记录存入records中,否则从records中删除
		if ( newLevel != originRecords[id] ) {
			timer && clearTimeout( timer );
			timer = setTimeout( updateWeight, UPDATE_HZ );
			records[id] = newLevel;
		} else {
			typeof records[id] == 'undefined' || delete records[id]; 
		}
	}
	$(document)
	.on( 'click', '.common-quanzhong-box .common-quanzhong-down', function () {
		updateWeightView.call( $(this), false );
	})
	.on( 'click', '.common-quanzhong-box .common-quanzhong-up', function () {
		updateWeightView.call( $(this), true );
	});
	$(window).on( 'unload', function () {
		updateWeight();
	});
});

/**
 * 权重选择0.0.2
 */
jQuery(function ($) {
	var url = './run.php?mid=' + gMid + '&a=update_weight';
	
	function changeValueTo(box, newLevel, name) {
		var el = box.find(">div");
		if ( newLevel == el.attr("_level") ) return;
		el.attr({
			"class": 'common-quanzhong-box' + newLevel,
			"_level": newLevel
		}).find(".common-quanzhong").text( name );
		
		var data = {};
		data[ box.closest(".common-list-data").attr("_id") ] = newLevel;
		$.post(url, {"data": JSON.stringify(data)});
	}
	
	$(".common-list")
		.on("click", ".common-quanzhong-box", function () {
			var select = $(this).find(".common-quanzhong-select");
			if ( select.is(":hidden") ) {
				var offset = $(this).offset(),
					limit = $(document).height(),
					myHeight = 24,
					selectHeight = 180,
				    arrow=select.find(".arrow");
				if (offset.top + myHeight + selectHeight > limit) {
					select.addClass("topModel");
					arrow.addClass("arrowdown");
				} else {
					select.removeClass("topModel");
					arrow.removeClass("arrowdown");
				}
				select.show();
				/*确保在此次事件分发中，click冒泡到document时，document还没绑定下面的处理函数*/
				setTimeout(function () { $(document).one("click", function () { select.hide(); }); }, 1);
			}
		})
		.on("click", ".common-quanzhong-select .li", function () {
			var value = $(this).data("val"), name = $(this).text();
			changeValueTo( $(this).closest(".common-quanzhong-box"), value, name );
		});
});

/**
 * 选中、全选
 */
jQuery(function($) {
	var checkedClass = 'selected';
	var ul = $(".common-list-data").parent()
		batCheckbox = $('.common-list-bottom input:checkbox');
	
	ul.on('click', '.common-list-data input:checkbox', function() {
		$(this).closest('.common-list-data').toggleClass(checkedClass);
	});
	batCheckbox.on('click', function() {
		var isChecked = batCheckbox.prop('checked');
		ul
			.find(".common-list-data input:checkbox").prop('checked', isChecked)
			.closest('.common-list-data')[ (isChecked ? 'add' : 'remove') + 'Class' ](checkedClass);
	});
});
function tip(str){
    var div = $('#div');
    if(!div.get(0)){
        div = $('<div id="div"></div>').appendTo('body').css({
            position : 'absolute',
            top : 0,
            right : '100px',
            width : '200px',
            'max-height' : '300px',
            overflow : 'auto',
            border : '1px solid red',
            background : '#000',
            color : '#fff'
        });
    }
    div.html(div.html() + str +'<br />');
}

