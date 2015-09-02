jQuery(function($){
    /*var absolutesTimer = null;
    $(window).on('resize', function(){
        var me = $(this),
            height = me.height(),
            width = me.width();
        $('.form-left').height(height);

        $('.form-middle').width(function(){
            return width - parseInt($(this).css('left'));
        }).height(function(){
            var heightVal = height - parseInt($(this).css('top'))
            $(this).find('.form-upload').height(heightVal);
            return heightVal;
        });

        absolutesTimer && clearTimeout(absolutesTimer);
        absolutesTimer = setTimeout(function(){
            $('.form-imgs').triggerHandler('absolutes');
            $('.form-change-des').triggerHandler('mychange');
        }, 300);
    }).triggerHandler('resize');*/



    /*$(window).on('beforeunload', function(){
        var ids = $(document).data('ids') || [];
        if(ids.length){
            return '图片正在上传中或者有变动...';
        }
    });*/

    $(document).on({
        addnew : function(event, id){
            var ids = $(this).data('ids') || [];
            ids.push(id);
            $(this).data('ids', ids);
        },
        removenew : function(event, id){
            var ids = $(this).data('ids') || [];
            if(!$.isArray(id)){
                id = [id];
            }
            $.each(id, function(i, n){
                var index = $.inArray(parseInt(n), ids);
                if(index != -1){
                    ids.splice(index, 1);
                }
            });
            $(this).data('ids', ids);
        },
        emptynew : function(event){
            $(this).removeData('ids');
        }
    });

    $('#submit_ok, #submit').click(function(){
        $(document).triggerHandler('emptynew');

        var imgs = [];
        $('.form-img-each').each(function(i, n){
            imgs.push({
                pic_id : $(this).attr('picid'),
                des : $(this).find('.form-img-title').attr('title'),
                isfm : $(this).hasClass('isfm') ? 1 : 0,
                sort : (i + 1)
            });
        });
        $('#imgs').val(JSON.stringify(imgs));
        var picArea = $('textarea[name="pic_links"]'),
        	picLinks = picArea.val(),
        	picArr = picLinks.split("\n"),
        	picResult = $.unique(picArr).join('\n');
        picArea.val(picResult);
    });
    
    var select_water = $('.water-pic li').find('input[name="water_id"]:checked');
    if( select_water.length  ){
    	var name = select_water.attr('_name');
    	$('.add-water-pic').text( name );
    }
});

function bindEditTool(){
    $('.form-img-each img.suo').each(function(){
        var me = $(this);
        if(me.data('picEdit')) return;
        var imageId = 'tuji-image';
        if(me.attr('src')){
            var imgSrc = me.attr('src').replace(/\/160x/, '');
            me.data('picEdit', true);
        }else{
            return;
        }
        var picid = me.closest('.form-img-each').attr('picid');
        $(this).picEdit({
            imageId : imageId,
            imgSrc : imgSrc,
            saveAfter : function(){
                top.$('body').find('img.tmp-edit-top-img').remove();
                top.$('body').off('_picsave').on('_picsave', function(event, info){
                    try{
                        var topImg = $(this).find('#tuji-image');
                        var img = $(this).find('#formwin')[0].contentWindow.$('.form-img-each[picid="'+ topImg.attr('picid') +'"] img.suo');
                        img.attr('src', $.globalImgUrl(info, '160x', true));
                        $(this).find('img.tmp-edit-top-img').remove();
                    }catch(e){}
                }).append(me.clone().hide().attr('id', imageId).attr('picid', picid).addClass('tmp-edit-top-img'));
            }
        });
    });
}


function deleteImg(objs, noanimate){
    var ids = [];
    objs.each(function(){
        ids.push($(this).attr('picid'));
    });
    if(ids){
        $(document).triggerHandler('removenew', [ids]);
        ids = ids.join(',');
        if(!noanimate){
            var num = 0, len = objs.length;
            objs.removeClass('form-img-each-transition').each(function(){
                num++;
                $(this).hide(500, function(){
                    $(this).remove();
                    if(num == len){
                        $('.form-select-all').triggerHandler('mynum');
                        $('.form-option').triggerHandler('option');
                        $('.form-change-des').triggerHandler('mychange');
                        $('.form-batch-watermark').triggerHandler('mychange');
                        $('.form-imgs').triggerHandler('absolutes', [false, true]);
                        var fm = $('.form-img-each.isfmother');
            			fm[0] && fm.addClass('isfm').removeClass('isfmother');
                    }
                });
            });
        }
        $.post(
            "./run.php?mid=" + gMid + "&a=delete_pic&id="+ ids +"&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
            function(){

            }
        );
    }
}


jQuery(function($){
    $('.form-select-all')
        .on('click', 'label', function(){
            var state = $(this).find('input').prop('checked') ? true : false;
            $('.form-img-each')[state ? 'addClass' : 'removeClass']('selected');
            $('.form-change-des').triggerHandler('mychange');
            $('.form-batch-watermark').triggerHandler('mychange');
        })
        .on('mynum', function(){
            $(this).find('em').html('(' + $('.form-img-each').length + ')');
        })
        .on('mychecked', function(event, checked){
            $(this).find('input').prop('checked', checked);
        });
});

jQuery(function($){
    $('.form-change-des')
        .on('mychange', function(){
            var len = $('.form-img-each.selected').length;
            var oldState = $(this).data('can-option');
            $(this).data('can-option', len ? true : false);
            $(this).find('.form-button-box')[!len ? 'addClass' : 'removeClass']('form-button-cannot');
            $('.form-option-cancel')[len ? 'show' : 'hide']();
            $('.form-option-del').triggerHandler('state.animation');
        })
        .on('click', function(){
            if(!$(this).data('can-option')) return;
            if($(this).data('open')) return;
            $('.form-des-box').trigger('_show');
            $(this).data('open', true);
        });
    
    $('.link-btn').on( 'click', function(){
    	var box = $('.form-link-box');
        if($(this).data('open')){
        	box.trigger('_hide');
        }else{
            box.trigger('_show');
            $(this).data('open', true);
        }
    } );
    
});

jQuery(function($){
	var linkbox = $('.form-link-box').on({
        '_show' : function(){
            $(this).slideDown(300);
            var me = $(this);
            setTimeout(function(){
                me.find('textarea').focus();
            }, 0);
        },
        '_hide' : function(){
            $(this).slideUp(300, function(){
                $('.link-btn').data('open', false);
            });
        }
    });
	linkbox.find('textarea').on('blur', function(){
		//linkbox.triggerHandler('_hide');
    });
});

jQuery(function($){
    $('.form-option-cancel').on('click', function(){
        $('.form-img-each.selected').removeClass('selected');
        $('.form-change-des').triggerHandler('mychange');
        $('.form-batch-watermark').triggerHandler('mychange');
        $('.form-select-all').triggerHandler('mychecked', [false]);
    });
});

jQuery(function($){
    $('.form-option-del')
        .on('style.animation', function(){
            if(!$(this).data('init')){
                var styles = '';
                $.each(['-webkit-', '-moz-', '-o-', ''], function(i, n){
                    styles += '@' + n + 'keyframes animation1{'
                        + '0% {'+ n +'transform:rotate(-5deg);'+ n +'animation-time-function:ease-out;}'
                        + '10% {'+ n +'transform:rotate(-3deg);'+ n +'animation-time-function:ease-in;}'
                        + '20% {'+ n +'transform:rotate(-1deg);'+ n +'animation-time-function:ease-in;}'
                        + '25% {'+ n +'transform:rotate(0deg);'+ n +'animation-time-function:ease-out;}'
                        + '30% {'+ n +'transform:rotate(2deg);'+ n +'animation-time-function:ease-in;}'
                        + '40% {'+ n +'transform:rotate(3deg);'+ n +'animation-time-function:ease-in;}'
                        + '50% {'+ n +'transform:rotate(4deg);'+ n +'animation-time-function:ease-out;}'
                        + '60% {'+ n +'transform:rotate(5deg);'+ n +'animation-time-function:ease-in;}'
                        + '70% {'+ n +'transform:rotate(3deg);'+ n +'animation-time-function:ease-in;}'
                        + '75% {'+ n +'transform:rotate(2deg);'+ n +'animation-time-function:ease-out;}'
                        + '80% {'+ n +'transform:rotate(-1deg);'+ n +'animation-time-function:ease-in;}'
                        + '90% {'+ n +'transform:rotate(-3deg);'+ n +'animation-time-function:ease-in;}'
                        + '100% {'+ n +'transform:rotate(-4deg);'+ n +'animation-time-function:ease-out;}'
                        + '}';

                    styles += '@' + n + 'keyframes animation2{'
                        + '0% {'+ n +'transform:rotate(-4deg);'+ n +'animation-time-function:ease;}'
                        + '10% {'+ n +'transform:rotate(-3deg);'+ n +'animation-time-function:ease-in;}'
                        + '20% {'+ n +'transform:rotate(-2deg);'+ n +'animation-time-function:ease;}'
                        + '25% {'+ n +'transform:rotate(0deg);'+ n +'animation-time-function:ease-out;}'
                        + '30% {'+ n +'transform:rotate(1deg);'+ n +'animation-time-function:ease;}'
                        + '40% {'+ n +'transform:rotate(2deg);'+ n +'animation-time-function:ease;}'
                        + '50% {'+ n +'transform:rotate(3deg);'+ n +'animation-time-function:ease-out;}'
                        + '60% {'+ n +'transform:rotate(3deg);'+ n +'animation-time-function:ease;}'
                        + '70% {'+ n +'transform:rotate(2deg);'+ n +'animation-time-function:ease;}'
                        + '75% {'+ n +'transform:rotate(0deg);'+ n +'animation-time-function:ease-out;}'
                        + '80% {'+ n +'transform:rotate(-1deg);'+ n +'animation-time-function:ease;}'
                        + '90% {'+ n +'transform:rotate(-3deg);'+ n +'animation-time-function:ease-in;}'
                        + '100% {'+ n +'transform:rotate(-5deg);'+ n +'animation-time-function:ease;}'
                        + '}';

                    styles += '@' + n + 'keyframes animation3{'
                        + '0% {'+ n +'transform:rotate(-3deg);'+ n +'animation-time-function:ease;}'
                        + '10% {'+ n +'transform:rotate(-2deg);'+ n +'animation-time-function:ease-in;}'
                        + '20% {'+ n +'transform:rotate(-1deg);'+ n +'animation-time-function:ease;}'
                        + '25% {'+ n +'transform:rotate(0deg);'+ n +'animation-time-function:ease-out;}'
                        + '30% {'+ n +'transform:rotate(1deg);'+ n +'animation-time-function:ease;}'
                        + '40% {'+ n +'transform:rotate(2deg);'+ n +'animation-time-function:ease;}'
                        + '50% {'+ n +'transform:rotate(3deg);'+ n +'animation-time-function:ease-out;}'
                        + '60% {'+ n +'transform:rotate(4deg);'+ n +'animation-time-function:ease;}'
                        + '70% {'+ n +'transform:rotate(3deg);'+ n +'animation-time-function:ease;}'
                        + '75% {'+ n +'transform:rotate(1deg);'+ n +'animation-time-function:ease-out;}'
                        + '80% {'+ n +'transform:rotate(-1deg);'+ n +'animation-time-function:ease;}'
                        + '90% {'+ n +'transform:rotate(-2deg);'+ n +'animation-time-function:ease-in;}'
                        + '100% {'+ n +'transform:rotate(-4deg);'+ n +'animation-time-function:ease;}'
                        + '}';
                });
                $('body').append('<style type="text/css">'+ styles +'</style>');

                $(this).data('init', true);
            }
        })
        .on('add.animation', function(event, which, need){
            need && $(this).triggerHandler('style.animation');
            which.css({
                'animation-name' : 'animation' + Math.floor((Math.random() * 3 + 1)),
                'animation-duration' : (Math.ceil(Math.random() * 3) + 3) / 10 + 's',
                'animation-iteration-count' : 'infinite'
            }).addClass('animation');
            which.closest('.form-img-each').find('.form-img-reddel').show();

            var fm = $('.form-img-each.isfm');
            fm[0] && fm.addClass('isfmother').removeClass('isfm');

        })
        .on('remove.animation', function(){
            $('.form-img-box.animation').css('animation', 'none').removeClass('animation');
            $('.form-img-reddel').hide();

            var fm = $('.form-img-each.isfmother');
            fm[0] && fm.addClass('isfm').removeClass('isfmother');
        })
        .on('state.animation', function(){
            var state = $(this).data('animation');
            var len = $('.form-img-each.selected').length;
            var stringState = 0;
            if(!len){
                if(state){
                    stringState = 1;
                }else{
                    stringState = 0;
                }
            }else{
                stringState = 2;
            }
            if(!$('.form-img-each').length){
                stringState = 0;
                state && $(this).data('animation', false);
            }
            switch(stringState){
                case 1:
                    $(this).find('.form-button-middle').html('取消删除');
                    break;
                case 2:
                    $(this).find('.form-button-middle').html('删除已选中');
                    break;
                default:
                    $(this).find('.form-button-middle').html('删除');
            }
        })
        .on('click', function(){
            var me = $(this);
            if(!$('.form-img-each.selected').length){
                me.triggerHandler('style.animation');
                if(me.data('animation')){
                    me.data('animation', false);
                    me.triggerHandler('remove.animation');
                }else{
                    setTimeout(function(){
                        $('.form-img-box').each(function(){
                            me.triggerHandler('add.animation', [$(this)]);
                        });
                    }, 0);
                    me.data('animation', true);
                }
            }else{
                if($(this).data('animation')){
                    $('.form-img-each.selected .form-img-reddel').trigger('click');
                }else{
                    me.triggerHandler('style.animation');
                    $('.form-img-each.selected .form-img-box').each(function(){
                        me.triggerHandler('add.animation', [$(this)]);
                    });
                    jConfirm('确定要删除吗？', '删除提示', function(result){
                        if(result){
                            deleteImg($('.form-img-each.selected'));
                        }else{
                            me.triggerHandler('remove.animation');
                        }
                    }).position(me);
                }
            }
            me.triggerHandler('state.animation');
        });
});

jQuery(function($){
    $('.form-option')
        .on('myshow', function(){
        	return;
            $(this).css({
                transition : 'all 0.4s',
                top : 0
            });
            $('.form-upload').css({
                transition : 'all 0.4s',
                'padding-top' : $(this).outerHeight() + 'px'
            });
        })
        .on('myhide', function(){
        	return;
            $(this).css({
                transition : 'all 0.4s',
                top : - $(this).outerHeight() + 'px'
            });
            $('.form-upload').css({
                transition : 'all 0.4s',
                'padding-top' : 0
            });
        })
        .on('option', function(){
            var state = $('.form-img-each').length;
            $(this).triggerHandler(!state ? 'myhide' : 'myshow');
        });
});

jQuery(function($){
    var defaultWh = {
        width : 160,
        height : 120
    };

    var forms = $('.form-imgs');
    forms.on('before', function(event, data, callback){
        var tpl = $('#img-tpl').val();
        tpl = tpl.replace(/{([a-zA-Z]*)}/g, function(all, match){
            return data[match] || '';
        });
        var add = $('.form-add');
        var img = $(tpl).insertAfter(add).find('.suo');
        if(!defaultWh.width){
            var box = img.parent();
            defaultWh.width = parseInt(box.css('width'), 10);
            defaultWh.height = parseInt(box.css('height'), 10);
        }
        img.preLoadImg({
            width : defaultWh.width,
            height : defaultWh.height,
            src : data.src + '?time=' + new Date().getTime(),
            loading : true,
            yizhi : true,
            callback : function(){
                $(this).removeAttr('_src');
                callback && callback.apply($(this));
            }
        });
        add.trigger('move');
        $('.form-select-all').triggerHandler('mynum');
        //$(this).triggerHandler('absolutes');
    });

    forms.on('after', function(event, data){
        var each = $(this).find('.form-img-each[index="'+ data['index'] +'"]');
        data = data.data;
        each.find('.suo').preLoadImg({
            width : defaultWh.width,
            height : defaultWh.height,
            src : data['pic_src'],
            callback : function(){
                $(this).closest('.form-img-each').removeAttr('index').attr('picid', data['pic_id']).attr('material_id',data['material_id']).find('.loading').remove();
                $('.form-option').triggerHandler('option');
            }
        });
        
        //图片描述
        each.find('.form-img-title').attr('title', data['description']).find('.form-img-title-content').text(data['description']); 

        //加上水印标识
        each.find('.watermark-btn').attr('_waterid', $('input[name="water_id"]').val() );
        
//        新增的图片编辑图片按钮
        var me = each.find('.suo');
        var imageId = 'tuji-image';
        var imgSrc = data['pic_src'].replace(/\/160x/, '');
        me.data('picEdit', true);
        var picid = me.closest('.form-img-each').attr('picid');
        me.picEdit({
        	imageId : imageId,
        	imgSrc : imgSrc,
        	saveAfter : function(){
        		top.$('body').find('img.tmp-edit-top-img').remove();
        		top.$('body').off('_picsave').on('_picsave', function(event, info){
        			try{
        				var topImg = $(this).find('#tuji-image');
        				var img = $(this).find('#formwin')[0].contentWindow.$('.form-img-each[picid="'+ topImg.attr('picid') +'"] img.suo');
        				me.attr('src', $.globalImgUrl(data, '160x', true));
        				$(this).find('img.tmp-edit-top-img').remove();
        			}catch(e){}
        		}).append(me.clone().hide().attr('id', imageId).attr('picid', picid).addClass('tmp-edit-top-img'));
        	}
        });
//         end
        
        
//        bindEditTool();
    });

    forms.on('setdes', function(event, des){
        $(this).find('.form-img-each.selected').each(function(){
            $(this).find('.form-img-title').attr('title', des).find('div').text(des);
            $(this).removeClass('selected');
        });
    });


    forms.on('click', '.form-img-oleft, .form-img-oright', function(event){
        var each = $(this).closest('.form-img-each');
        if(each.data('waiting')) return;
        each.data('waiting', true);
        var isLeft = $(this).hasClass('form-img-oleft');
        var suo = each.find('.suo');
        var suoWW = suo.width();
        var suoHH = suo.height();
        var clone = suo.clone();
        clone.appendTo(each.find('.form-img-center')).removeClass('suo').css({
            left : - (suoWW / 2) + 'px',
            top : - (suoHH / 2) + 'px'
        });
        var src = suo.hide().attr('src');
        $.preLoadImg({
            height : defaultWh.width,
            width : defaultWh.height,
            src : src,
            callback : function(callinfo){
                var ajax = function(){

                    $.post(
                        "./run.php?mid=" + gMid + "&a=revolveImg&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
                        {
                            pic_id : each.attr('picid'),
                            direction : isLeft ? 1 : 2
                        },
                        function(){
                            setTimeout(function(){
                                var index = src.indexOf('?');
                                src = src.substr(0, index != -1 ? index : src.length) + '?' + (parseInt(Math.random() * 100000));
                                suo.preLoadImg({
                                    width : defaultWh.width,
                                    height : defaultWh.height,
                                    src : src,
                                    loading : true,
                                    callback : function(){
                                        each.data('waiting', false);
                                        $(this).show();
                                        clone.remove();
                                    }
                                });
                            }, 500);
                        }
                    );

                };
                ajax();
                var transform = {
                    transform : 'rotate('+ (isLeft ? '-90' : '90') +'deg)',
                    transition : 'all 0.5s'
                };

                if(callinfo['type'] == 'width'){
                    transform['width'] = callinfo['val'] + 'px';
                    transform['top'] =  - ((suoHH * callinfo['val'] / suoWW) / 2) + 'px';
                    transform['left'] =  - (callinfo['val'] / 2) + 'px';
                }else{
                    transform['height'] = callinfo['val'] + 'px';
                    transform['left'] =  - ((suoWW * callinfo['val'] / suoHH) / 2) + 'px';
                    transform['top'] =  - (callinfo['val'] / 2) + 'px';
                }
                clone.css(transform);
            }
        });
    });

    forms.on('myselect', function(event, indexs){
        var eachs = $(this).find('.form-img-each').removeClass('selected');
        $.each(indexs, function(i, n){
            var each = eachs.eq(n);
            each.addClass('selected');
        });
    });

    forms.on('init', function(){
        var uploadBox = $('.form-upload');
        var maxww = uploadBox.width();
        if(!maxww){
            setTimeout(function(){
                $('.form-imgs').triggerHandler('init');
            }, 100);
            return;
        }
        uploadBox.css('min-height', $.proxy(function(){
            var winHeight = $(window).height() || $(window.top.window).height();
            return winHeight - $(this).offset().top - 10 + 'px';
        }, uploadBox)());
        //这里原来用的是$('#imgs').val(),提交时，该值会改动，所以在页面中重新写了一个dom，放初始数据
        var imgs = $.trim($('#imgs-data').val());
        if(imgs && imgs != 'null'){
            imgs = decodeURIComponent(imgs);
            imgs = $.parseJSON(imgs);
            if(!$.isArray(imgs)){
                $('.form-add').show();
                return;
            }
            imgs.sort(function(a, b){
                var val = a['order_id'] - b['order_id'];
                return val == 0 ? 0 : (val > 0 ? -1 : 1);
            });
            var num = 0, len = imgs.length;
            $.each(imgs, function(i, data){
                forms.trigger('before', [{
                    src : data['img_src'],
                    isfm : data['is_cover'] > 0 ? 'isfm' : '',
                    title : data['description'],
                    sort : data['order_id'],
                    materialid : data['material_id'],
                    index : 0,
                    waterid : data['water_id'],
                    ext : 'style="display:none;"'
                }, function(){
                    num++;
                    $(this).closest('.form-img-each').removeAttr('index').attr('picid', data['id']).find('.loading').remove();
                    if(num == len){
                        $('.form-option').triggerHandler('option');
                    }

                    bindEditTool();
                }]);
            });

            var allIndex = 1000;
            var transition = $(this).find('.form-img-each-transition').each(function(){
                $(this).css({
                    display : 'block',
                    left : 0,
                    top : 0,
                    opacity : 0,
                    'z-index' : allIndex--,
                    'transition-delay' : 0 + 's',
                    //'transition-delay' : parseInt(Math.random() * 10) / 10 + 's',
                    'transition-duration' : 0 + 's'
                    //'transition-duration' : parseInt(Math.random() * 10) / 10 + 's'
                });
            });
            $('.form-add').hide();

            if(transition.length > 0){
                var me = $(this);
                setTimeout(function(){
                    me.triggerHandler('absolutes', [true]);
                }, 0);
            }
        }//else{
            $('.form-add').show();
        //}
    });

    forms.on({
        mouseenter : function(){
            if($(document).data('move')) return;
            if($(this).find('.form-img-box').hasClass('animation')) return;
            if($(this).attr('index')){
                $(this).addClass('current-nooption');
            }else{
                $(this).addClass('current');
                $(this).find('.form-img-fm').removeAttr('style');
            }
        },
        mouseleave : function(){
            $(this).removeClass('current current-nooption');
        }
    }, '.form-img-each');

    forms.on('click', '.form-img-title', function(event){
        if($(this).closest('.form-img-each').find('.form-img-box').hasClass('animation')){
            return;
        }
        var title = $(this).attr('title');
        var me = $(this);
        $(this).find('.form-img-title-content').hide();
        var area = $('<textarea class="form-title-set" style="height:22px;line-height:22px;"></textarea>').appendTo(this);
        area.val(me.find('.form-img-title-content').text());
        area.autoResize({
            animate : false,
            extraSpace : 0
        });
        area.on('focus', function(){
            $(document).one('click', function(){
                $('.form-title-set').trigger('blur');
            });
        });
        area.on('blur', function(){
            var val = $(this).val();
            me.attr('title', val).find('.form-img-title-content').text(val).show();
            $(this).remove();
        });
        area.on('click', function(event){
            event.stopPropagation();
        });
        area.trigger('focus');
        event.stopPropagation();
    });

    forms.on('click', '.form-img-box', function(){

        $(this).closest('.form-img-each').toggleClass('selected');

        $('.form-change-des').triggerHandler('mychange');
        $('.form-batch-watermark').triggerHandler('mychange');

    });

    forms.on('click', '.form-img-odel', function(){
        var each = $(this).closest('.form-img-each');
        $('.form-option-del').triggerHandler('add.animation', [each.find('.form-img-box'), true]);
        jConfirm('确定要删除吗？', '删除提示', function(result){
            $('.form-option-del').triggerHandler('remove.animation');
            if(result){
                deleteImg(each);
            }
        }).position(this);
    });

    forms.on('click', '.form-img-fm', function(){
        var each = $(this).closest('.form-img-each');
        if(each.hasClass('isfm')){
            each.removeClass('isfm');
            $(this).hide();
        }else{
            $('.form-img-each.isfm').removeClass('isfm');
            each.addClass('isfm');
        }
    });

    forms.on('click', '.form-img-reddel', function(){
        var each = $(this).closest('.form-img-each');
        deleteImg(each);
    });

    forms.on('absolutes', function(event, transform, noRemoveSelected){
        var infos = {};
        var ww = 184, hh = 184;
        var marginRight = 60, marginTop = 35;
        var uploadBox = $(this).closest('.form-upload');
        var maxww = uploadBox.width();
        var initww = 0, inithh = 0;
        var startww = ww + marginRight, starthh = inithh;
        var transition = $(this).find('.form-img-each-transition');
        var i = 0, len = transition.length;
        transition.each(function(ii, nn){
            var css = {
                left : startww + 'px',
                top : starthh + 'px'
            };
            if(transform){
                //css['transform'] = 'rotate(360deg)';
                css['opacity'] = 1;
            }
            $(this).css(css);
            infos[ii] = {
                left : startww,
                top : starthh,
                right : startww + ww,
                bottom : starthh + hh,
                width : ww,
                height : hh
            };
            i++;
            if(i == len){
                return;
            }
            startww += ww + marginRight;
            if(startww + ww >= maxww){
                startww = initww;
                starthh += hh + marginTop;
            }
        });
        $(this).height(starthh + hh).data('infos', infos);
        $(this).data('firstInfo', {
            left : initww,
            top : inithh,
            right : ww + marginRight,
            bottom : hh + marginTop
        });
        if(!noRemoveSelected){
            $(this).find('.selected').removeClass('selected');
            $('.form-select-all').triggerHandler('checked', [false]);
        }
        if(transform){
            setTimeout(function(){
                transition.removeClass('form-img-each-transition').css({
                    transform : 'none',
                    'transition' : 'none'
                });
                transition.each(function(){
                    $(this).removeClass('form-img-each-transition');
                    var style = $(this).attr('style') || '';
                    var tmp = [];
                    $.each(style.split(';'), function(i, n){
                        var m = n.split(':');
                        var p = $.trim(m[0]);
                        if(p == 'left' || p == 'top' || p == 'z-index'){
                            tmp.push(n);
                        }
                    });
                    $(this).removeAttr('style').attr('style', tmp.join(';'));
                });
                setTimeout(function(){
                    transition.addClass('form-img-each-transition');
                    //$('.form-add').show();
                }, 10);
            }, 2000);
        }
    });
    /*水印*/
    $('.water-pic li').on('click' , function(event){
    	var name = $(this).data( 'value' );
    	$('.add-water-pic').text( name );
    	$('.edit-slide-close').trigger( 'click' );
    });
    
    $('.add-water-pic').on('click' , function(){
    	$('.water-pic').addClass('left');
    });
    $('.edit-slide-close').on('click' , function(){
    	$('.water-pic').removeClass('left');
    });
    
    var add = $('.form-add').on('move', function(){
        var first = $('.form-img-each:first');
        if(first[0]){
            $(this).insertBefore(first);
        }else{
            $(this).appendTo($(this).parent());
        }
    }).on('click', function(){
        $('.form-file').trigger('click');
    });

    $(".form-file").ajaxUpload({
        filter : function(data){
        	var water_id = $('.set-watermark-box').find('input[name="water_id"]').val();
            data.append('title', $('#title').val());
            data.append('water_id' ,water_id );
            data.append('type',$('input[name="a"]').val() );
        },
        before : function(data){
            forms.trigger('before', [{
                src : data.data.result,
                title : '',
                index : data.index
            }]);
        },
        after : function(data){
            forms.trigger('after', [{
                data : data.data,
                index : data.index
            }]);
            $(document).triggerHandler('addnew', [data.data['pic_id']]);
            $('.form-imgs').triggerHandler('absolutes');
            $.watermark.hide();
        }
    });

    $('.form-zip').on('click', function(){
        if(!$(this).data('uploading')){
            $('#form-zip').trigger('click');
        }
    });

    var zipOptions = {
        url : "./run.php?mid=" + gMid + "&a=upload_zip_img&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
        type : function(type){
            return !!type.match(/zip/);
        },
        phpkey : 'zipfile',
        filter : function(data){
//        	var water_id = $('.water-pic').find('input[name="water_id"]:checked').val();
        	var water_id = $('.form-add').find('input[name="water_id"]').val();
        	data.append('water_id' , water_id );
        	data.append('type',$('input[name="a"]').val());
        },
        before : function(data){
            $('.form-zip').html(function(){
                $(this).attr('_default', $(this).html());
                return '上传中...';
            }).data('uploading', true);
        },
        after : function(datas){
            if(datas.data){
                $.each(datas.data, function(i, data){
                    $('.form-imgs').triggerHandler('before', [{
                        src : data.pic_src,
                        title : '',
                        index : data.pic_id
                    }]);
                    $('.form-imgs').triggerHandler('after', [{
                        data : data,
                        index : data.pic_id
                    }]);

                    $(document).triggerHandler('addnew', [data['pic_id']]);
                });
                $('.form-imgs').triggerHandler('absolutes');
            }
            $(this).replaceWith($(this).clone());
            $('#form-zip').ajaxUpload(zipOptions);
            $('.form-zip').html(function(){
                $(this).removeData('uploading');
                return $(this).attr('_default');
            });
        }
    };

    $('#form-zip').ajaxUpload(zipOptions);

    var desbox = $('.form-des-box').on({
        '_show' : function(){
            $(this).slideDown(300);
            $(this).find('span').text(forms.find('.form-img-each.selected').length);
            var me = $(this);
            setTimeout(function(){
                me.find('textarea').focus();
            }, 0);
        },
        '_hide' : function(){
            $(this).slideUp(300, function(){
                $('.form-change-des').data('open', false);
            });
        }
    });
    desbox.find('textarea').on('blur', function(){
        $('.form-des-box').triggerHandler('_hide');
        var val = $.trim($(this).val());
        if(val){
            forms.triggerHandler('setdes', [val]);
            $('.form-change-des').triggerHandler('mychange');
            $('.form-batch-watermark').triggerHandler('mychange');
        }
    })/*.autoResize({
        animate : false,
        extraSpace : 0
    })*/;

});

jQuery(function($){
    var forms = $('.form-imgs');
    var active, move, moveInit, sx, sy, lx, ly, ileft, itop, selected, selfs, mask, timer, infos, firstInfo, sorts;
    function init(){
        active = null;
        move = false;
        moveInit = 0;
        sx = 0;
        sy = 0;
        lx = 0;
        ly = 0;
        ileft = 0;
        itop = 0;
        selected = null;
        selfs = null;
        mask = null;
        timer = null;
        infos = [];
        firstInfo = 0;
        sorts = [];
    }
    init();

    $(document).on('mousedown', '.form-img-box', function(event){
        event.preventDefault();
        $(document).triggerHandler('hidefocus');
        if(event.which > 1){
            return;
        }
        sx = event.pageX;
        sy = event.pageY;
        active = $(this).closest('.form-img-each');
        move = true;
    }).on('mousemove', function(event){
        if(!move) return;
        if(!moveInit){
            $(this).data('move', true);
            $('.form-img-each-transition').each(function(){
                sorts.push($(this).attr('picid'));
            });
            active.addClass('selected');

            mask = createMask();
            var picid = active.attr('picid');
            var scrollTop = forms.scrollTop();
            selfs = $('.form-img-each.selected').each(function(){
                var clone = $(this).clone();
                clone.hide();
                $(this).addClass('form-img-each-self');
                var offset = $(this).offset();
                if($(this).attr('picid') == picid){
                    ileft = offset.left;
                    itop = offset.top;
                    lx = event.pageX - offset.left;
                    ly = event.pageY - offset.top;
                    clone.removeClass('current');
                }
                clone.appendTo('body').find('.form-img-title').hide();
                clone.addClass('form-img-each-place').css({

                    position : 'absolute',
                    left : offset.left + 'px',
                    top : offset.top - scrollTop + 'px',
                    'z-index' : 99999
                });
            });
            selected = $('.form-img-each-place');
            active = $('.form-img-each-place[picid="'+ picid +'"]');
            forms.trigger('absolutes');
            getInfo();
            moveInit++;
            return;
        }

        var nx = event.pageX,
            ny = event.pageY,
            okx = ileft + (nx - sx),
            oky = itop + (ny - sy),
            zIndex = 100000;
        active.removeClass('form-img-each-transition').css({
            left : okx + 'px',
            top : oky + 'px',
            'z-index' : zIndex
        });

        if(moveInit == 1){
            $('.form-img-each-self').append(createImgMask());
            selected.show();
            moveInit++;
            setTimeout(function(){
                selected && selected.removeClass('form-img-each-transition');
            }, 1000);
        }else{
            selected.not(active).each(function(i, n){
                i++;
                $(this).css({
                    left : okx + i * 5 + 'px',
                    top : oky + i * 5 + 'px',
                    'z-index' : zIndex - i
                });
            });
        }

        var formOffset = forms.offset();
        var disx = nx - formOffset.left - lx;
        var disy = ny - formOffset.top - ly;

        if(timer){
            clearTimeout(timer);
        }
        timer = setTimeout(function(){
            var findOK = false, isSelf = false;
            if(firstInfo && disx > firstInfo.left && disx < firstInfo.right && disy > firstInfo.top && disy < firstInfo.bottom){
                $('.form-img-each-self').insertAfter($('.form-add')[0]);
                forms.trigger('absolutes');
                getInfo();
                return false;
            }
            $.each(infos, function(i, n){
                if(disx > n.left && disx < n.right && disy > n.top && disy < n.bottom){
                    var which = forms.find('.form-img-each').eq(i);
                    if(which.hasClass('form-img-each-self')){
                        isSelf = true;
                        return false;
                    }
                    findOK = true;
                    var where = '';
                    if(disx < n.left + parseInt(n.width / 3, 10)){
                        where = 'insertBefore';
                    }else{
                        where = 'insertAfter';
                    }
                    $('.form-img-each-self')[where](which[0]);
                    forms.trigger('absolutes');
                    getInfo();
                    return false;
                }
            });
            if(!isSelf && !findOK){
                $.each(sorts, function(i, n){
                    forms.find('.form-img-each-transition[picid="'+ n +'"]').appendTo(forms);
                });
                forms.trigger('absolutes');
                getInfo();
            }
        }, 100);

    }).on('mouseup', function(event){
        if(!move) return;
        if(selected){
            $(this).data('move', false);
            mask && mask.hide();
            selected.addClass('form-img-each-transition').each(function(){
                var picid = $(this).attr('picid');
                var myself = forms.find('.form-img-each-self[picid="'+ picid +'"]');
                var offset = myself.offset();
                $(this).css({
                    left : offset.left + 'px',
                    top : offset.top + 'px'
                });
            });
            setTimeout(function(){
                $('.form-img-each-place').remove();
                $('.form-img-each-mask').remove();
                $.each(['selected', 'current', 'form-img-each-self'], function(i, n){
                    $('.form-img-each.' + n).removeClass(n);
                });

                $('.form-change-des').triggerHandler('mychange');
                $('.form-batch-watermark').triggerHandler('mychange');
            }, 250);
        }
        init();
    });

    function createMask(){
        var mask = $('#move-mask');
        if(!mask[0]){
            mask = $('<div id="move-mask"></div>').appendTo('body').css({
                position : 'absolute',
                left : 0,
                top : 0,
                background : '#fff',
                opacity : 0,
                'z-index' : 100000
            });
        }
        var d = $(document),
            dw = d.width(),
            dh = d.height();
        mask.css({
            width : dw + 'px',
            height : dh + 'px',
            display : 'block'
        });
        return mask;
    }

    function createImgMask(){
        return '<div class="form-img-each-mask"></div>';
    }

    function getInfo(){
        infos = forms.data('infos');
        firstInfo = forms.data('firstInfo');
    }
});


jQuery(function($){
    var forms = $('.form-imgs');
    var sx, sy, move, mask, moveInit;
    function init(){
        sx = sy = 0;
        move = false;
        mask = null;
        moveInit = false;
    }
    init();
    $(document).on('mousedown', function(event){
        var target = $(event.target);
        if(target.closest('.form-img-each').length || !target.closest('.form-imgs').length){
            return;
        }
        event.preventDefault();
        $(document).triggerHandler('hidefocus');
        if(event.which > 1){
            return;
        }
        if(!inUpload(event.target)){
            return;
        }
        sx = event.pageX;
        sy = event.pageY;
        move = true;
    }).on('mousemove', function(event){
        if(!move) return;
        if(!moveInit){
            $(this).data('move', true);
            moveInit = true;
        }
        var nx = event.pageX;
        var ny = event.pageY;
        var four = createMask({
            x : sx,
            y : sy
        }, {
            x : nx,
            y : ny
        });
        var formsOffset = forms.offset();
        four['left'] = four['left'] - formsOffset.left;
        four['top'] = four['top'] - formsOffset.top;
        var position = getInfo();
        var select = [];
        $.each(position, function(i, n){
            if(!(n['bottom'] < four['top'] || n['right'] < four['left'] || n['top'] > four['top'] + four['height'] || n['left'] > four['left'] + four['width'])){
                select.push(i);
            }
        });
        select = $.unique(select);
        if(select){
            forms.trigger('myselect', [select]);
        }
    }).on('mouseup', function(){
        if(!move) return;
        if(mask){
            mask.hide();
        }
        $(this).data('move', false);
        init();

        $('.form-change-des').triggerHandler('mychange');
        $('.form-batch-watermark').triggerHandler('mychange');
    });

    function createMask(point1, point2){
        mask = $('#select-mask');
        if(!mask[0]){
            mask = $('<div/>').attr({
                id : 'select-mask'
            }).appendTo('body').css({
                border : '1px solid yellow',
                background : '#fff',
                opacity : 0.3,
                position : 'absolute',
                'z-index' : 100001
            });
        }
        var width = Math.abs(point2.x - point1.x) - 2;
        var height = Math.abs(point2.y - point1.y) - 2;
        var left = point2.x > point1.x ? point1.x : point2.x;
        var top = point2.y > point1.y ? point1.y : point2.y;
        mask.css({
            width : width + 'px',
            height : height + 'px',
            left : left,
            top : top,
            display : 'block'
        });
        return {
            left : left,
            top : top,
            height : height,
            width : width
        };
    }

    function inEach(target){
        return target && $(target).closest('.form-img-each, .form-add').length;
    }

    function inUpload(target){
        return !inEach(target) && $(target).closest('.form-imgs').length;
    }

    function getInfo(){
        return forms.data('infos');
    }
});

jQuery(function($){
	var form = $('#content_form');
	form.tooltip({
        items : '.form-img-title',
        content : this.items,
        track : true,
        close : function(){
        	form.attr('title','');
        }
    });
	
});

/*添加水印*/
$(function(){
	$('.form-upload').on('click','.watermark-btn',function(event){
		var target = $(this);
		var material_id = target.closest('.form-img-each').attr('material_id')
		watermarkDefault(target,material_id);
	});
	$('.form-add').on('click','.the-add',function(event){
    	var target = $(event.currentTarget);
    	var material_id = '';
    	watermarkDefault(target,material_id);
		event.stopPropagation();
    });
	function watermarkDefault(target,material_id){
		var selectItems = $('.form-img-each.selected');
		if( selectItems.length ){
			selectItems.each(function(){
				$(this).find('.form-img-box').click();
			});
		}
		$.watermark.waterBox.attr('material_id',material_id);
		$('.watermark-btn').removeClass('current');
		target.addClass('current');
		
		var waterid = parseInt( target.attr('_waterid') ) || 0;
		$.watermark.waterBox.attr('_waterid', waterid == 0 ? 0 : material_id);
		$.watermark.showWatermarkBox(target);
	}
	$('.form-batch-watermark')
		.on('mychange',function(){
			$.watermark.hide();
			var len = $('.form-img-each.selected').length;
			var aim = $(this).find('.form-button-box');
			len ? aim.removeClass('form-button-cannot') : aim.addClass('form-button-cannot');
			var mid = $('.form-img-each.selected').map(function(){
				return $(this).attr('material_id')
			}).get().join();
			$.watermark.waterBox.attr('material_id',mid);
			//记录所有选中项中，有水印的li的id
			var items = $('.form-img-each.selected');
			var wIdArr = [];
			$( items ).each(function(){
				var self = $(this);
				if( parseInt(self.find('.watermark-btn').attr('_waterid')) > 0 ){
					wIdArr.push( self.attr('material_id') );
				}
			});
			$.watermark.waterBox.attr('_waterid', wIdArr.length ? wIdArr.join() : 0);
		})
		.on('click',function(){
			if( $(this).find('.form-button-box').hasClass('form-button-cannot') )
				return;
			$.watermark.showWatermarkBox($(this));
		});
	function Water(){
		var _this = this;
		this.waterBox = $('.set-watermark-box');
		this.waterBox.on('click','li',function(){
			$(this).toggleClass('selected').siblings().removeClass('selected');
			var flag = $(this).hasClass('selected');
			_this.waterBox.find('input[name="water_id"]').val( flag ? $(this).attr('_id') : 0 );
			var currentBtn = $('.watermark-btn.current'),
				val = flag ? '水印(' + $(this).data('value') + ')' : '添加水印';
			currentBtn.text(val).attr( 'title',val);
			if( currentBtn.hasClass('the-add') ){
				currentBtn.siblings('input').val( flag ? $(this).attr('_id') : 0);
			}
		});
		this.waterBox.on('click','.submit-watermark',function(){
			var self = $(this);
			if( _this.waterBox.hasClass('needajax') ){
				var material_id = _this.waterBox.attr('material_id');
				var url = './run.php?mid='+gMid+'&a=change_material_water',
					data = {
						water_id : _this.waterBox.find('input[name="water_id"]').val(),
						material_id : material_id,
						id : $('input[name="id"]').val(),
						tuji_sort_id : $('input[name="tuji_sort_id"]').val(),
						ajax : 1,
						type : $('input[name="a"]').val()
				};
				var load = $.globalLoad( self );
				$.getJSON(url, data, function(json){
					load();
					if( json.callback ){
						eval( json['callback'] );
						return;
					}
					var ids = material_id.split(',');
					for( var i=0,len=ids.length;i<len;i++ ){
						var item = $('.form-img-each').filter(function(){
							return $(this).attr('material_id') == ids[i]
						});
						var src = item.find('.suo').attr('src');
						var time = '?time='+ new Date().getTime();
						item.find('.suo').attr('src',src+time);
						item.find('.watermark-btn').attr('_waterid', data['water_id']);
					}
					_this.hide();
				});
			}else{
				_this.waterBox.hide();
			}
		});
		this.waterBox.on('click','.cancel-watermark',function(){
			_this.hide();
		});
		this.waterBox.on('click','.del-watermark',function(){
			var self = $(this);
			if( _this.waterBox.hasClass('needajax') ){
				var material_id = _this.waterBox.attr('material_id');
				var url = './run.php?mid='+gMid+'&a=change_material_water',
					data = {
						water_id : 0,
						material_id : material_id,
						id : $('input[name="id"]').val(),
						tuji_sort_id : $('input[name="tuji_sort_id"]').val(),
						ajax : 1,
						type : $('input[name="a"]').val()
				};
				var load = $.globalLoad(self);
				$.getJSON(url, data, function(json){
					load();
					if( json.callback ){
						eval( json['callback'] );
						return;
					}
					var ids = material_id.split(',');
					for( var i=0,len=ids.length;i<len;i++ ){
						var item = $('.form-img-each').filter(function(){
							return $(this).attr('material_id') == ids[i]
						});
						var src = item.find('.suo').attr('src');
						var time = '?time='+ new Date().getTime();
						item.find('.suo').attr('src',src+time);
						item.find('.watermark-btn').attr('_waterid', 0);
					}
					_this.hide();
				});
			}else{
				_this.waterBox.hide();
			}
		});
	};
	$.extend(Water.prototype,{
		showWatermarkBox : function( target ){
			this.waterBox.find('.del-watermark')[ this.waterBox.attr('_waterid') == 0 ? 'hide' : 'show' ]();
			var offset = this.calWatermarkPos(target);
			target.hasClass('the-add') ? this.waterBox.removeClass('needajax') : this.waterBox.addClass('needajax');
			this.waterBox.show().offset(offset);
		},
		calWatermarkPos : function(target){
			var p = target.offset();
			var arrow = this.waterBox.find('.arrow');
			var sHeight = this.waterBox.outerHeight(),
				sWidth = this.waterBox.outerWidth();
			var tHeigth = target.outerHeight(),
				tWidth = target.outerWidth();
			var pLeft = p.left,
				pTop = p.top;
			var dHeight = $(document).height(),
				dWidth = $(document).width();
			var top = tHeigth + pTop + 15,
				left = tWidth + pLeft - sWidth/3;
			arrow.removeClass('down').css('left',(sWidth/3-tHeigth)+'px');
			arrow.css('top','-13px');
			if( top+sHeight > dHeight ){
				arrow.addClass('down').css('top',(sHeight-2)+'px');
				top = pTop - sHeight - 15;
			}
			if( left+sWidth > dWidth ){
				arrow.css('left',(sWidth/3*2+tHeigth)+'px');
				left = pLeft - sWidth + sWidth/3;
			}
			var offset = {
					left : left,
					top : top
			};
			return offset;
		},
		hide : function(){
			this.waterBox.hide();
		},
	});
	$.watermark = new Water();
});
$(window).on('load', function(){
    $('.form-imgs').triggerHandler('init');
    //$('.form-change-des').triggerHandler('mychange');
});
