jQuery(function($){
    /*$(window).resize(function(){
        var height = $(this).height();
        $('.form-dioption, .form-cioption').each(function(){
            $(this).height(function(){
                var pt = parseInt($(this).css('padding-top'));
                var pb = parseInt($(this).css('padding-bottom'));
                var bt = parseInt($(this).css('border-top-width'));
                var bb = parseInt($(this).css('border-bottom-width'));
                var _height = height - $(this).offset().top - pt - pb - bt - bb;
                if($(this).hasClass('form-dioption')){
                    var submit = $('.form-dioption-submit');
                    var submitHeight = submit[0] ? parseInt(submit.outerHeight()) : 0;
                    _height -= submitHeight;
                    $(this).css('overflow', 'hidden');
                    var h2Height = $(this).find('h2').outerHeight(true);
                    $(this).find('.form-dioption-inner').height(function(){
                        return _height - h2Height - parseInt($(this).css('padding-top'));
                    });
                }
                return _height;
            });
        });
    });*/
    
    

    $('.form-nb').on({
        _move : function(event, lefts){
            $('.form-dioption-inner').stop().each(function(i, n){
                $(this).animate({
                    left : lefts[i]
                }, 300, function(){

                });
            });
        },
        _prev : function(){
            var lefts = [0, '100%'];
            $(this).triggerHandler('_move', [lefts]);
        },
        _next : function(){
            var lefts = ['-100%', 0];
            $(this).triggerHandler('_move', [lefts]);
        },
        click : function(){
            var state;
            $(this).data('state', !(state = $(this).data('state')));
            state = !state ? '_next' : '_prev';
            $(this).triggerHandler(state);
            $(this).html($(this).attr(state));
        }
    })
});

(function($){
    var inputHide = 'input-hide';
    var defaultOptions = {
        focus : 'focus',
        blur : 'blur',
        yizhi : false,
        documentCall : null
    };
    $.fn.inputHide = function(options){
        options = $.extend({}, defaultOptions, options);
        return this.each(function(){
            var focusEvent = options['focus'];
            var blurEvent = options['blur'];
            var documentCall = options['documentCall'];
            var yizhi = options['yizhi'];
            $(this).on(focusEvent, function(){

                $(document).data('current-focus', this);
                documentCall && $(document).on('current-focus-call', documentCall);

                var me = $(this),
                    val = me.attr('_value'),
                    _default = me.attr('_default');
               /* if(val == _default){
                    setTimeout(function(){
                        me.select();
                    }, 0);
                }else{
                    $(this).val(val);
                }*/
                me.removeClass(inputHide);
            }).on(blurEvent, function(){

                    $(document).removeData('current-focus');
                    documentCall && $(document).off('current-focus-call', documentCall);

                    var me = $(this),
                        val = me.val(),
                        _default = me.attr('_default'),
                        id = me.attr('id');
                    if(!val || val == _default){
                        me/*.val(_default)*/.attr('_value', _default).data('hasval', false);
                        if(yizhi){
                            me.addClass(inputHide);
                        }else{
                            me.removeClass(inputHide);
                        }
                    }else{
                        me.attr('_value', val).val(val).data('hasval', true);
                        me.addClass(inputHide);
                    }
                });
        });
    }
})(jQuery);

jQuery(function($){
    $('#submit_ok, #submit').click(function(){
        $('#submit_type').val($(this).attr('_submit_type'));

        /*如果值为2则更改referto 跳转到继续添加页面*/
        if($('#submit_type').val() == 2)
        {
            $('#referto').val("./run.php?mid=" + gMid + "&a=form&infrm=1&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass);
        }
    });
});

jQuery(function($){
    $('.option-iframe-back').click(function(){
        if(top && top != self){
            top.$('#livwinarea').trigger('iclose');
        }
    });
});

jQuery(function($){
    $(document).on('keydown', function(event){
        if(event.keyCode == 13 && $(event.target).is('input')){
            event.preventDefault();
        }
    });
});

jQuery(function($){
	if (!$('.common-publish-button').size()) return;
	var pub = $('.common-form-pop');
	
    $('.common-publish-button').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();
      var self = $(event.currentTarget),
      	 type = self.attr('_type'),
      	 pop = null;
      if( type=='special' ){
    	  pop = $('#form_special');
      }else{
    	  pop = $('#form_publish');
      }
        if ( self.data('show') ) {
        	self.data('show', false);
       		pop.css({top: -450})
        } else {
        	self.data('show', true);
        	pop.css({top: 100});	
        }
    });
    pub.on('click', '.publish-box-close', function ( event ) { 
    	var pop = $(event.currentTarget).closest('.common-form-pop');
    	pop.css({top: -450});
    	if( pop.attr('_type') == 'publish' ){
    		$('.common-publish-button:first').data('show',false);
    	}
    	if( pop.attr('_type') == 'special' ){
    		$('.common-publish-button:last').data('show',false);
    	}
    });
    pub.each( function(){
    	var _this = this,
    		type = $(this).attr('_type');
    	var	common_button = null;
    	if( type=="publish" ){
    		common_button = $('.common-publish-button:first');
    	}else{
    		common_button = $('.common-publish-button:last');
    	}
    	var method = ( type == 'special' ) ? 'hg_special_publish' : 'hg_publish';
    	var hidden_name = ( type == 'special' ) ? '.publish-showname-hidden' : '.publish-name-hidden';
		$(this).find('.publish-box')[method]({
	    	change: function () {
	    		common_button.html(function(){
	        		var hidden = $(_this).find( hidden_name ).val();
	       			return hidden ? ($(this).attr('_prev') + '<span style="color:#000;">' + hidden + '</span>') : $(this).attr('_default');
	    		 });	
	    	},
	    	maxColumn: 3
	    });
		if( type=='special' ){
			var publish = $(this).find('.publish-box').data('publish');
			var id = $(this).data('id') + '';
			publish.reinit( id );
		}
    } );
});

jQuery(function(){
    var title = $('#title');
    title = title.length ? title : $('#vod-title');
    if(!title[0]) return;

    /*var func = function(event){
        var target = $(event.target);
        if(target.hasClass('form-dioption-title') || target.closest('.form-dioption-title').length){
            return;
        }
        $('.form-dioption-title').data('inclick', false);
        $('.form-title-option').hide();
        $('#title').triggerHandler('_blur');
        $(this).off('mousedown', func);
    };

    $('.form-dioption-title')
        .on('click', function(){
            if($(this).data('inclick')) return;
            $(this).data('inclick', true);
            $('.form-title-option').show();
            $('#title').focus();
        });

    $('#title')
        .autoResize({
            animate : false,
            extraSpace : 0
        })
        .on('focus', function(){
            $(document).off('mousedown', func).on('mousedown', func);
            $(this).triggerHandler('_focus');
            $('#title').css('border-color', '#77B7F9');
        })
        .inputHide({
            'focus' : '_focus',
            'blur' : '_blur',
            documentCall : function(){
                $('.form-dioption-title').data('inclick', false);
                $('.form-title-option').hide();
                $('#title').triggerHandler('_blur');
            }
        })
        .trigger('change');
        */

    title.on({
        focus : function(){
            var tcolor = $('#tcolor').val();
            var isbold = parseInt($('#isbold').val());
            var isitalic = parseInt($('#isitalic').val());
            var style = '';
            $.each({
                'font-weight' : isbold > 0 ? 'bold' : 'normal',
                'font-style' : isitalic > 0 ? 'italic' : 'normal',
                'color' : tcolor + ' !important',
                'border-bottom-color' : tcolor + ' !important'
            }, function(i, n){
                style += i + ':' + n + ';';
            });
            $(this).attr('style', style);

            isbold > 0 && $('.form-title-weight').addClass('selected');
            isitalic > 0 && $('.form-title-italic').addClass('selected');
        },

        blur : function(){
            $(this).attr('title', $(this).val());
        }
    });
    
    title.triggerHandler('focus');

    $('.form-title-weight, .form-title-italic').on({
        click : function(event){
            event.stopPropagation();
            var state = $(this).hasClass('selected') ? 0 : 1;
            $(this)[state ? 'addClass' : 'removeClass']('selected');
            $($(this).hasClass('form-title-weight') ? '#isbold' : '#isitalic').val(state);
            title.triggerHandler('focus');
        },

        mouseenter : function(){
            if(!$(this).hasClass('selected')){
                $(this).addClass('hover');
            }
        },

        mouseleave : function(){
            $(this).removeClass('hover');
        }
    });


});


/*jQuery(function($){
    return;
    var brief = $('#brief');
    brief.autoResize({
        animate : false,
        extraSpace : 0
    });
    brief.inputHide();
    brief.on({
        focus : function(){
            $(this).parent().css('max-height', 'none');
        },
        blur : function(){
            $(this).parent().css('max-height', '70px');
        }
    });
    brief.trigger('change').trigger('blur');

});*/

jQuery(function($){
	var brief = $('#brief-clone');
	brief.on({
        focus : function(){
            var text = $.trim($(this).text());
            var place = $(this).attr('placeholder');
            if(place == text){
                $(this).text('');
            }
            $(this).css({
                'overflow-y' : 'visible',
                height : 'auto'
            });
        },

        blur : function(){
            var text = $.trim($(this).text());
            var place = $(this).attr('placeholder');
            if(text == ''){
                $(this).text(place);
            }
            var lineHeight = $(this).css('line-height');
            if(('' + lineHeight).indexOf('px') == -1){
                lineHeight = parseInt(lineHeight) * parseInt($(this).css('font-size'));
            }else{
                lineHeight = parseInt(lineHeight);
            }
            $(this).css({
                'overflow-y' : 'hidden',
                height : lineHeight * 3  + 'px'
            });
            $('#' + $(this).attr('target')).val(text);
        }
    }).triggerHandler('blur');
});

jQuery(function($){
    var colors = [
        '#fff', '#000', '#eeece0', '#1c477c', '#4e80bf', '#c24f4a', '#99bd53', '#8162a5', '#46abc7', '#f99639',
        '#f2f2f2', '#7f7f7f', '#dcdac3', '#c4d8f1', '#dae6f2', '#f2ddda', '#ecf2dd', '#e5dfeb', '#dbeef5', '#fdead9',
        '#d8d8d8', '#595959', '#c5bd96', '#8cb2e3', '#b7cbe6', '#e6b9b6', '#d8e5ba', '#cdc0da', '#b7ddea', '#fdd7b3',
        '#bfbfbf', '#3f3f3f', '#948a4f', '#518bd5', '#95b3d9', '#da9492', '#c3d798', '#b2a2c9', '#8fcddc', '#fcc08c',
        '#a5a5a5', '#262626', '#494427', '#15355e', '#345f94', '#973630', '#759235', '#60487c', '#2d859d', '#e56c01',
        '#7f7f7f', '#0c0c0c', '#1d1b0f', '#0d223f', '#223f61', '#642422', '#4f6125', '#3e3051', '#1d5868', '#984800',
        '#c20000', '#fe0000', '#ffc100', '#ff0', '#8fd245', '#00b24c', '#00aef3', '#006ec3', '#011e62', '#712aa2'
    ];
    var html = '<div class="title-color-box"><div>';
    $.each( colors, function(i, color) {
        html += '<span style="background:' + color + '" data-color="' + color + '"></span>'
        if (i == 9) {
            html += '</div><div>';
        }
        if (i == 59) {
            html += '</div><div>';
        }
        if (i == 69) {
            html += '</div></div>'
        }
    });
    /*$('.form-title-color').append( html ).click(function(event) {
        $(this).children().toggle();
        event.stopPropagation();
    }).on('click', 'span', function() {
            changeTitleColor( $(this).css('background-color') );
        });
    function changeTitleColor( color ) {
        $('.form-title-color').css('background-color', color);
        $('#tcolor').val( color );
        $('#title').triggerHandler('focus');
    }
    changeTitleColor($('#tcolor').val() || '#000');*/

    $('.form-title-color').append(html).on({
        click : function(event){
            event.stopPropagation();
            $.proxy(function(){
                this[this.is(':visible') ? 'hide' : 'show']();
            }, $(this).children())();
        },

        _init : function(event, color){
            $(this).css('background-color', color);
            $('#tcolor').val(color);
            $('#title,#vod-title').triggerHandler('focus');
        }
    }).on({
        click : function(event){
            $(event.delegateTarget).triggerHandler('_init', [$(this).data('color') || $(this).css('background-color')]);
        }
    }, 'span').triggerHandler('_init', [$('#tcolor').val() || '#000']);
});

jQuery(function($){
    var start = $('.keywords-start').click(function(){
        var me = $(this);
        appendKeywordInput(me, function(result){
            if(result){
                me.hide();
                add.trigger('_move');
            }else{
                me.show();
                me.closest('.form-dioption-item').css('padding-bottom', '15px');
            }

        });
        me.hide();
    });

    var add = $('.keywords-add').click(function(){
        var me = $(this);
        appendKeywordInput(me, function(result){
            if(result){
                me.trigger('_move');
            }else{
                me.show();
            }
        });
        me.hide();
    }).on('_move', function(){
            $(this).appendTo($(this).parent()).show();
            $(this).closest('.form-dioption-item').css('padding-bottom', '10px');
    }).hide();

    function appendKeywordInput(obj, callback){
        obj.after('<span class="each-keyword-input"><input type="text" /></span>').next().find('input').focus().on('blur', function(){
            var val = $.trim($(this).val());
            if(!val || val == appendKeywordInput.defaultValue){
                callback(false);
            }else{
                obj.after(appendKeywordItem($(this).val()));
                box.trigger('values');
                callback(true);
            }
            $(this).parent().remove();
        });
    }

    function appendKeywordItem(val){
        return '<span class="each-keyword-item"><span class="each-keyword-item-span">' + val +'</span><span class="keywords-del"></span></span>';
    }
    appendKeywordInput.defaultValue = $('#keywords-box').attr('_value');

    var box = $('#keywords-box').on('mouseenter', '.each-keyword-item', function(){
        if($(this).hasClass('editing')) return;
        !$(this).hasClass('hover') && $(this).addClass('hover');
    }).on('mouseleave', '.each-keyword-item', function(){
            if($(this).hasClass('editing')) return;
            $(this).hasClass('hover') && $(this).removeClass('hover');
        }).on('click', '.each-keyword-item-span', function(event){
            var val = $(this).text();
            $(this).closest('.each-keyword-item').addClass('editing');
            $('<input type="text" style="width:50px;" value="'+ val +'" _defaultValue="'+ val +'"/><span class="keywords-del"></span>').appendTo($(this).parent().empty()).on('blur', function(){
                var val = $.trim($(this).val()), _dval = $(this).attr('_defaultValue');
                if(!val){
                    val = _dval;
                }
                $(this).closest('.each-keyword-item').html('<span class="each-keyword-item-span">' + val + '</span><span class="keywords-del"></span>').removeClass('editing hover');
                box.trigger('values');
            }).click(function(event){
                    event.stopPropagation();
                }).focus();
        }).on('click', '.keywords-del', function(){
            var item = $(this).closest('.each-keyword-item');
            item.remove();
            if(!$('.each-keyword-item').length){
                start.show();
                add.hide();
                $('#keywords-box').closest('.form-dioption-item').css('padding-bottom', '15px');
            }
            box.trigger('values');
        }).on('values', function(){
            var words = [];
            $(this).find('.each-keyword-item').each(function(){
                words.push($(this).text());
            });
            $('#keywords').val(words.join(','));
        }).on('append', function(event, val, only){
            if(!$('.each-keyword-item').length){
                start.hide();
            }
            if($.type(val) != 'array'){
                val = [val];
            }
            var me = $(this);
            $.each(val, function(i, n){
                me.append(appendKeywordItem(n));
            });
            add.trigger('_move');
            $(this).trigger('values');
        }).on('init', function(){
            var val = $.trim($('#keywords').val());
            if(val){
                val = val.split(',');
                $(this).trigger('append', [val]);
            }
        }).trigger('init');
});


jQuery(function($){
    (function($) {
        var sp = $('#sort-box').find('.sort-box-inner'),
            label = $('#sort-box p.sort-label');
        if(sp[0]){
            sp.hgSortPicker({
            	site_id : label.attr('_site') || '',
                nodevar: label.attr('_multi'),
                width: 191,
                change: function(id, name) {
                    label[0].firstChild.nodeValue = name;
                    label.prev().show();
                    $('#sort_id').val(id);
                    label.trigger('click');
                },
                getId: function() {
                    return $('#sort_id').val();
                },
                baseUrl: label.attr('baseUrl') || undefined
            });
            sp.hide();
        }else{
        	
        }
        label.toggle(function() {
            sortBian();
            sp.slideDown(500, function () { hg_resize_nodeFrame(); });
        }, function() {
            sortBian();
            sp.slideUp(500);
        });
        $('#sort-box').click(function(e) {
            if (e.target == this || e.target == $(this).find('label:first')[0] ) {
                label.trigger('click');
            }
        });
        function sortBian() {
            $('#sort-box').toggleClass('sort-box-with-show');
        }
        function sortBian() {
            $('#sort-box').toggleClass('sort-box-with-show');
        }
    })($);

    $('#pub').click(function() {
        var top;
        if (parseInt($('#vodpub').css('top')) < 0) {
            top = '300px';
        } else {
            top = '-500px';
        }
        $('#vodpub').animate({
            'top': top
        })
    });

    $(document).on('click', function(event){
        if($(event.target).closest('#sort-box').length) return;
        var box = $('#sort-box');
        if(box[0] && box.hasClass('sort-box-with-show')){
            box.find('p.sort-label').triggerHandler('click');
        }
    });
});

jQuery(function($){
    var sortBox = $('#sort-box'),
     	id = $('#id,input[name="id"]').val();
    if(!sortBox[0] || window.top == window.self || id > 0) return;
    var sortParent = $(window.top.document).find('#mainwin').contents().find('#hg_node_node li.cur:last');
    if(!sortParent[0]) return;
    var sortItem = sortParent.find('.l');
    var sortIdMatch = sortItem.attr('href').match(/_id=(\d+)/);
    if(!sortIdMatch[1]) return;
    var sortId = sortIdMatch[1];
    var sortName = sortItem.text();
    $('.sort-label', sortBox).html(function(){
        return sortName + $(this).find('img').clone().wrap('<div/>').parent().html();
    }).prev().show();
    $('#sort_id').val(sortId);
});

jQuery(function($) {
    var v = $('#quanzhong'),
        m = $('#weight'),
        getLabelBy = (function() {
            var label = [0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90];
            return function(level) {
                return label[level];
            }
        })();
    v.on('click', '.quanzhong-option .down', function() {
        v.trigger('update', [ +(m.val()) - 1 ] );
    }).on('click', '.quanzhong-option .up', function(e) {
            e.preventDefault();
            v.trigger('update', [ +(m.val()) + 1 ] );
        }).on('update', function(e, level) {
            if (level < 0 || level > 12) {
                return;
            }
            v.find('.quanzhong').text( getLabelBy(level) ).parent().attr('class', 'quanzhong-box' + level);
            m.val(level);
        }).on('updateView', function(e, level) {
            if (level < 0 || level > 12) {
                return;
            }
            v.find('.quanzhong').text( getLabelBy(level) ).parent().attr('class', 'quanzhong-box' + level);
        });
    $('#quanzhong-map').find('area').each( function() {
        var i = 1,
            changeLevel = function() {
                v.trigger( 'update', [$(this).data('level')] );
            },
            enter = function() {
                v.trigger( 'updateView', [$(this).data('level')] );
            },
            leave = function() {
                v.trigger( 'update', [ m.val() ] );
            };
        $('.quanzhong-masking').parent().hover( $.noop(), leave );
        return function() {
            $(this)
                .data('level', i++)
                .click( changeLevel )
                .hover( enter, $.noop() );
        };
    }() );
});
/*权重选择*/
$(function ($) {
	var select = $('#weight_box');
	if (!select.size()) return;
	function update(weight) {
		var val = select.find('input').val();
		val = parseInt(val);
		if (weight != null) {
			val = weight;
		}
		if ( 0 <= val && val <= 100 ) {
			select.find('input').val(val);
			$('#weight').val(val);
			$("#weightPicker").find('.common-quanzhong-label').text(val);
			$('#weightPicker').find('.common-quanzhong').css('background', create_color_for_weight(val));
		}
	}
	select.find('input').val( $('#weight').val() );
	select.on('click', function(e) { e.originalEvent['pass?'] = true; });
	select.on('click', 'li', function() { update($(this).data('weight')); select.hide(); });
	$('body').on('click', function(e) {
      if (e.originalEvent && !e.originalEvent['pass?']) {
      	update();
        return select.hide();
      }
    });
	$("#weightPicker")
		.on("click", ".common-quanzhong-box", function (e) {
			select.toggle();
			e.originalEvent['pass?'] = true;
			if ( select.is(":visible") ) {
				$("#listWeightSlider").slider('value', $('#weight').val() );
				select.css('left', $("#weightPicker").offset().left);
				$("#weightPicker").css("z-index", 100000);
			} else {
				$("#weightPicker").css("z-index", 100);
			}
		});
	$("#listWeightSlider").slider({
		animate: true,
		max: 100,
		min: 0,
		slide: function(e, ui) {
			update(ui.value);
		}
	});
	
	var _render = function() {
    	if ( !top.$.globalData ) return;
    	var configWeight = top.$.globalData.get('quanzhong');
    	if (configWeight) {
    		var el = $.tmpl( $('#weight_box_tpl').html(), {mydata: configWeight}, {} );
    		$('.weight-select').append(el);
    	}
    }
    _render();
});


jQuery(function($){
    if(location.href.indexOf('backpublish=1') != -1){
        $('.option-iframe-back').html('返回发布内容');
    }
});

jQuery(function($){
    if(location.href.indexOf('fromsource=1') != -1){
    	var index = location.href.indexOf('backurl='),
    		decode_url = location.href.slice( index ),
    		encode_url = decodeURIComponent( decode_url );
    	var realurl = encode_url.replace(/backurl=./,'.'),
    		close_btn = $('.option-iframe-back'),
    		back_btn = '<a href=' + realurl+ ' class="m2o-back" style="cursor:pointer;background:#6ea5e8;color:#fff;width:34px;font-size:12px;line-height:34px;text-align:center;border-radius:2px;float:right;"">返回</a>';
    	$( back_btn ).insertBefore( close_btn );
    	close_btn.hide();
    }
});

/*
 * 标题与摘要字数统计
*/
jQuery( function($){
	var wordcount_Target = $('.need-word-count');
	wordcount_Target.length && wordcount_Target.wordCount();
} );

/*
 * 关键字提取
*/
jQuery( function($){
	var keyword_box = $('#keywords-box'),
		require_tiqu = keyword_box.data('require-tiqu');
	if( require_tiqu || !require_tiqu ){	//暂没有权限判断
		var tiqu_btn = $('<div class="keywords-tiqu" />').insertAfter( keyword_box ),
			keywords_hover = $( '.keywords-hover-tip' );
		keywords_hover.length || ( keywords_hover = $('<div class="keywords-hover-tip"/>').appendTo('body') );
		tiqu_btn.on( {
			click : function(){
				var self = $( this );
				if( self.hasClass( 'on' ) ) return;
				var content = Tools.getContent();
				if( !content ){
					Tools.errorTip( self, '标题或文章没有内容!' );
					return;
				}
				var keywords = Tools.getKeywords();
				self.tiquKeywords( {
					content : content,
					keywords : keywords,
					change : function( word ){
						var target = this;
						var param = Tools.getParam( word );
						if( target.hasClass( 'on' ) ){
							var ishas = param.ishas;
							if( !ishas ){
								keyword_box.trigger('append', [word] )
							}
						}else{
							var target = param.target;
							if( target.length ){
								target.find('.keywords-del').trigger('click');
							}
							
						}
					},
					close : function(){
						self.removeClass( 'on' );
					}
				} );
				self.addClass( 'on' );
				keywords_hover.hide();
			},
			hover : function( event ){
				if( $( this ).hasClass( 'on' ) ) return;
				var	hover_tip = keyword_box.data('title'),
					offset = $(this).offset(),
					wd = $(this).outerWidth(true);
				keywords_hover.css( { left : offset.left + wd + 'px', top : offset.top + 'px'} ).html( hover_tip || '提取标题或文章内容为关键字' );
				if( event.type == 'mouseenter' ){
					keywords_hover.show();
				}else{
					keywords_hover.hide();
				}
			}
		} );
	}
	
	var Tools = {
			errorTip : function(me,msg, rotate){
				if( msg ){
					me.myTip( {
						string : msg,
						color : 'red',
						dtop : 20,
						dleft : 100,
						width : 130
					} );
				}
				rotate = rotate || [8, -8, 5, -5, 2, -2, 'end'];
		        if($.inArray('end', rotate) == -1){
		            rotate.push('end');
		        }
		        $.each(rotate, function(i, n){
		            if(n == 'end'){
		                me.queue('mt', function(next){
		                    $(this).removeAttr('style');
		                });
		            }else{
		                me.queue('mt', function(next){
		                    $(this).css('transform', 'rotate(' + n + 'deg)');
		                    next();
		                }).delay(50, 'mt');
		            }
		        });
		        me.dequeue('mt');
			},
			getContent : function( obj ){
				var content = '';
				if( obj && obj.length ){
					obj.each( function(){
						var text = $.trim( $(this).text() ) + ',';
						content += text;
					} );
				}else{
					var title = $('input[name="title"],#title'),
						descr = $('textarea#comment,textarea#brief'),
						editor = $.myueditor;
					if( title.length ){
						var text = $.trim( title.val() );
						content += text;
					}
					if( descr.length ){
						var text = $.trim( descr.val() );
						content += text;
					}
					if( editor ){
						var text = $.trim( editor.getContentTxt() );
						content += text;
					}
				}
				return content;
			},
			getParam : function( word ){
				var items = keyword_box.find( '.each-keyword-item' ),
					ishas = false,
					target = null,
					param = {};
				if( items.length ){
					items.each( function(){
						var text = $(this).find('.each-keyword-item-span').text();
						if( text == word ){
							ishas = true;
							target = $(this);
							return false;
						}
					} );
				}
				param.ishas = ishas;
				param.target = target;
				return param;
			},
			getKeywords : function(){
				var keywords = [],
					items = keyword_box.find( '.each-keyword-item' );
				items.each( function(){
					var text = $(this).find('.each-keyword-item-span').text();
					keywords.push( text );
				} );
				return keywords;
			}
	};
	
	
	
} );

/*
 * 回到顶部
*/
jQuery( function($){
	$('body').toTop();
});

/*
$(function ($) {
	if (window.parent !== self) {
		window.parent.openEditModel();
		$(window).on("unload", function () { window.parent.quitEditModel(); });
	}
});*/